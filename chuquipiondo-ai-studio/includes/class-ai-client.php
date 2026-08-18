<?php
/**
 * AI client: unified chat-completion client for Mistral / OpenAI / Anthropic
 * plus a no-API local template fallback.
 *
 * The client normalizes the three OpenAI-compatible-ish providers into a
 * single `chat()` method that returns a plain string answer, and exposes
 * task-oriented helpers (`improve_text`, `generate_paragraphs`, `seo_meta`,
 * `generate_code`, `image_alt`, etc.) consumed by the REST endpoints.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Chuquipiondo_AI_Client
 */
final class Chuquipiondo_AI_Client {

	/**
	 * Cached provider config.
	 *
	 * @var array|null
	 */
	private $provider = null;

	/**
	 * Build the provider config from current settings.
	 *
	 * @return array
	 */
	private function provider() {
		if ( null !== $this->provider ) {
			return $this->provider;
		}
		$providers  = chuquipiondo_ai_providers();
		$key        = chuquipiondo_ai_get_option( 'ai_provider', 'mistral' );
		if ( ! isset( $providers[ $key ] ) ) {
			$key = 'mistral';
		}
		$cfg        = $providers[ $key ];
		$cfg['key'] = $cfg['id'] = $key;
		$cfg['api_key']  = (string) chuquipiondo_ai_get_option( 'ai_api_key', '' );
		$cfg['model']    = (string) chuquipiondo_ai_get_option( 'ai_model', isset( $cfg['models'][0] ) ? $cfg['models'][0] : '' );
		$cfg['timeout']  = (int) chuquipiondo_ai_get_int_option( 'ai_timeout', 10, 120 );
		$cfg['temp']     = (float) chuquipiondo_ai_get_option( 'ai_temperature', '0.7' );
		$cfg['max_tok']  = (int) chuquipiondo_ai_get_int_option( 'ai_max_tokens', 256, 32000 );
		$cfg['lang']     = (string) chuquipiondo_ai_get_option( 'ai_language', 'es' );
		$this->provider  = $cfg;
		return $cfg;
	}

	/**
	 * Test the connection with a minimal request.
	 *
	 * @return array {success: bool, message: string, provider: string}
	 */
	public function test() {
		$cfg = $this->provider();
		if ( 'local' === $cfg['id'] ) {
			return array(
				'success'  => true,
				'provider' => $cfg['id'],
				'message'  => __( 'Modo local activo (sin API). Las tareas usan plantillas internas.', 'chuquipiondo-ai' ),
			);
		}
		if ( '' === $cfg['api_key'] ) {
			return array(
				'success'  => false,
				'provider' => $cfg['id'],
				'message'  => __( 'Falta la API key. Configurala en Ajustes.', 'chuquipiondo-ai' ),
			);
		}
		$answer = $this->chat( array( array( 'role' => 'user', 'content' => 'Di "OK" en una palabra.' ) ), 32 );
		if ( is_wp_error( $answer ) ) {
			return array(
				'success'  => false,
				'provider' => $cfg['id'],
				'message'  => $answer->get_error_message(),
			);
		}
		return array(
			'success'  => true,
			'provider' => $cfg['id'],
			'message'  => sprintf( /* translators: provider + answer */ __( 'Conexion OK con %1$s. Respuesta: %2$s', 'chuquipiondo-ai' ), $cfg['id'], trim( (string) $answer ) ),
		);
	}

	/**
	 * Low-level chat completion. Returns the assistant text or WP_Error.
	 *
	 * @param array $messages  Array of [role, content] pairs.
	 * @param int   $max_tokens Optional override.
	 * @return string|WP_Error
	 */
	public function chat( array $messages, $max_tokens = 0 ) {
		$cfg = $this->provider();

		if ( 'local' === $cfg['id'] ) {
			return $this->local_answer( $messages );
		}

		$body = array(
			'model'       => $cfg['model'],
			'messages'     => $messages,
			'temperature' => $cfg['temp'],
			'max_tokens'  => $max_tokens > 0 ? $max_tokens : $cfg['max_tok'],
		);

		$headers = array(
			'Content-Type'  => 'application/json',
			'Accept'        => 'application/json',
			'Authorization' => 'Bearer ' . $cfg['api_key'],
		);

		// Anthropic uses a different header scheme + top-level messages shape.
		if ( 'anthropic' === $cfg['id'] ) {
			$headers['x-api-key']         = $cfg['api_key'];
			$headers['anthropic-version'] = '2023-06-01';
			unset( $headers['Authorization'] );
			$body['max_tokens'] = $max_tokens > 0 ? $max_tokens : min( $cfg['max_tok'], 4096 );
		}

		$payload = wp_json_encode( $body );
		$response = wp_remote_post(
			$cfg['endpoint'],
			array(
				'timeout'    => $cfg['timeout'],
				'headers'    => $headers,
				'body'       => $payload,
				'sslverify'  => true,
			)
		);

		$this->maybe_log( $cfg, $messages, $response );

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'ai_http', $response->get_error_message() );
		}
		$code = wp_remote_retrieve_response_code( $response );
		$raw  = wp_remote_retrieve_body( $response );
		$data = json_decode( $raw, true );

		if ( $code < 200 || $code >= 300 ) {
			$err = isset( $data['error']['message'] ) ? $data['error']['message'] :
				( isset( $data['error'] ) && is_string( $data['error'] ) ? $data['error'] :
				sprintf( /* translators: HTTP code */ __( 'Error HTTP %d al llamar a la IA.', 'chuquipiondo-ai' ), $code ) );
			return new WP_Error( 'ai_http_' . $code, $err );
		}

		return $this->extract_text( $cfg['id'], $data );
	}

	/**
	 * Extract assistant text from the raw provider response.
	 *
	 * @param string $provider Provider id.
	 * @param array  $data     Decoded JSON.
	 * @return string|WP_Error
	 */
	private function extract_text( $provider, $data ) {
		if ( ! is_array( $data ) ) {
			return new WP_Error( 'ai_parse', __( 'Respuesta no valida de la IA.', 'chuquipiondo-ai' ) );
		}
		if ( 'anthropic' === $provider ) {
			if ( isset( $data['content'][0]['text'] ) ) {
				return (string) $data['content'][0]['text'];
			}
			return new WP_Error( 'ai_parse', __( 'Respuesta Anthropic vacia.', 'chuquipiondo-ai' ) );
		}
		// OpenAI / Mistral compatible.
		if ( isset( $data['choices'][0]['message']['content'] ) ) {
			return (string) $data['choices'][0]['message']['content'];
		}
		return new WP_Error( 'ai_parse', __( 'Respuesta de la IA sin contenido.', 'chuquipiondo-ai' ) );
	}

	/**
	 * Local template fallback (no API). Returns a deterministic stub.
	 *
	 * @param array $messages Messages.
	 * @return string
	 */
	private function local_answer( array $messages ) {
		$last = '';
		foreach ( array_reverse( $messages ) as $m ) {
			if ( 'user' === $m['role'] ) {
				$last = (string) $m['content'];
				break;
			}
		}
		$w = 900;
		$h = 500;
		$placeholder = 'https://placehold.co/' . $w . 'x' . $h . '?text=CHUQUIPIONDO+AI';
		$html = '<p>' . esc_html( wp_trim_words( $last, 40 ) ) . '</p>';
		$html .= '<figure class="wp-block-image"><img src="' . esc_url( $placeholder ) . '" alt="' . esc_attr__( 'Imagen generada por IA', 'chuquipiondo-ai' ) . '" width="' . $w . '" height="' . $h . '" /></figure>';
		return $html;
	}

	/**
	 * Optionally log the request (API key masked) for debugging.
	 *
	 * @param array             $cfg      Provider config.
	 * @param array            $messages Messages.
	 * @param array|WP_Error   $response Response.
	 * @return void
	 */
	private function maybe_log( $cfg, $messages, $response ) {
		if ( ! chuquipiondo_ai_is_enabled( 'ai_log_requests' ) ) {
			return;
		}
		$log = array(
			'time'     => current_time( 'mysql' ),
			'provider' => $cfg['id'],
			'key'      => chuquipiondo_ai_mask_secret( $cfg['api_key'] ),
			'model'    => $cfg['model'],
			'code'     => is_wp_error( $response ) ? 'ERR' : wp_remote_retrieve_response_code( $response ),
		);
		$existing = chuquipiondo_ai_get_array_option( '_chuquipiondo_ai_log' );
		$existing[] = $log;
		$existing = array_slice( $existing, -50 );
		update_option( '_chuquipiondo_ai_log', $existing, false );
	}

	/**
	 * Run a high-level task. Returns a structured array ready for REST.
	 *
	 * @param string $task    Task slug.
	 * @param mixed  $context Context (post content, text, etc.).
	 * @param string $prompt  Extra user prompt.
	 * @param array  $params  Extra params.
	 * @return array|WP_Error
	 */
	public function run_task( $task, $context, $prompt = '', array $params = array() ) {
		$cfg   = $this->provider();
		$lang  = $cfg['lang'];

		$sys = __( 'Eres un asistente experto en redaccion editorial, SEO y WordPress. Respondes en el idioma solicitado y en HTML valido para el editor de bloques (sin markdown).', 'chuquipiondo-ai' );

		switch ( $task ) {
			case 'improve_text':
				$messages = array(
					array( 'role' => 'system', 'content' => $sys ),
					array( 'role' => 'user', 'content' => "Mejora la redaccion, ortografia, fluidez y persuasion del siguiente texto manteniendo el significado. Idioma: {$lang}.\n\nTEXTO:\n" . (string) $context . "\n\nInstruccion extra: " . (string) $prompt ),
				);
				$answer = $this->chat( $messages );
				break;

			case 'generate_paragraphs':
				$n = isset( $params['count'] ) ? absint( $params['count'] ) : 3;
				$messages = array(
					array( 'role' => 'system', 'content' => $sys ),
					array( 'role' => 'user', 'content' => "Escribe {$n} parrafos coherentes en HTML (<p>...</p>) sobre: " . (string) $context . ". Idioma: {$lang}.\n\nExtra: " . (string) $prompt ),
				);
				$answer = $this->chat( $messages );
				break;

			case 'generate_code':
				$language = isset( $params['language'] ) ? sanitize_key( $params['language'] ) : 'html';
				$messages = array(
					array( 'role' => 'system', 'content' => __( 'Eres experto en codigo para WordPress. Devuelves solo el bloque de codigo pedido, sin explicacion adicional ni markdown fences, listo para pegar.', 'chuquipiondo-ai' ) ),
					array( 'role' => 'user', 'content' => "Genera codigo {$language} para: " . (string) $context . ".\n\nDetalle: " . (string) $prompt ),
				);
				$answer = $this->chat( $messages );
				break;

			case 'seo_meta':
				$len = (int) chuquipiondo_ai_get_int_option( 'ai_seo_meta_desc_len', 120, 320 );
				$messages = array(
					array( 'role' => 'system', 'content' => $sys ),
					array( 'role' => 'user', 'content' => "Para el siguiente contenido, genera una meta descripcion SEO de maximo {$len} caracteres (devuelve solo la descripcion) y luego, en una nueva linea con prefijo 'KEYWORDS:', hasta " . chuquipiondo_ai_get_int_option( 'ai_seo_keywords_count', 5, 20 ) . " palabras clave separadas por coma. Idioma: {$lang}.\n\nCONTENIDO:\n" . (string) $context ),
				);
				$answer = $this->chat( $messages );
				break;

			case 'image_alt':
				$messages = array(
					array( 'role' => 'system', 'content' => $sys ),
					array( 'role' => 'user', 'content' => "Genera un texto ALT descriptivo, accesible y optimizado para SEO (maximo 125 caracteres) para una imagen del articulo titulado: " . (string) $context . ". Idioma: {$lang}. Devuelve solo el ALT." ),
				);
				$answer = $this->chat( $messages, 64 );
				break;

			case 'title_ideas':
				$messages = array(
					array( 'role' => 'system', 'content' => $sys ),
					array( 'role' => 'user', 'content' => "Da 5 titulos atractivos y optimizados para SEO sobre: " . (string) $context . ". Idioma: {$lang}. Uno por linea." ),
				);
				$answer = $this->chat( $messages, 256 );
				break;

			case 'full_article':
				$word_target = isset( $params['words'] ) ? absint( $params['words'] ) : 800;
				$img_count   = isset( $params['images'] ) ? absint( $params['images'] ) : 3;
				$messages = array(
					array( 'role' => 'system', 'content' => $sys . ' Usa encabezados H2/H3, listas cuando aporten, e inserta marcadores de imagen con el formato exacto <!--AI_IMAGE:descripcion--> entre parrafos.' ),
					array( 'role' => 'user', 'content' => "Escribe un articulo de blog de unos {$word_target} palabras sobre: " . (string) $context . ". Inserta exactamente {$img_count} marcadores <!--AI_IMAGE:descripcion breve de la imagen--> distribuidos en el cuerpo. Idioma: {$lang}. Devuelve solo el HTML del articulo.\n\nTono/extra: " . (string) $prompt ),
				);
				$answer = $this->chat( $messages );
				break;

			default:
				return new WP_Error( 'ai_unknown_task', __( 'Tarea de IA no reconocida.', 'chuquipiondo-ai' ) );
		}

		if ( is_wp_error( $answer ) ) {
			return $answer;
		}
		return array(
			'task'    => $task,
			'content' => (string) $answer,
		);
	}
}
