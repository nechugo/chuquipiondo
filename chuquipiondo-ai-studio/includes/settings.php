<?php
/**
 * Settings registration for CHUQUIPIONDO AI Studio.
 *
 * Centralizes the AI provider, image and SEO options. Stored as options
 * (not theme_mods) so they persist across theme changes.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the AI settings group + fields.
 *
 * @return void
 */
function chuquipiondo_ai_register_settings() {
	$option_keys = array_keys( chuquipiondo_ai_defaults() );
	foreach ( $option_keys as $key ) {
		register_setting(
			'chuquipiondo_ai_group',
			$key,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'chuquipiondo_ai_sanitize_option',
				'default'           => '',
			)
		);
	}
}
add_action( 'admin_init', 'chuquipiondo_ai_register_settings' );

/**
 * Sanitize an AI option. API keys are stored as-is (they must remain usable).
 *
 * @param mixed $value Raw value.
 * @return mixed
 */
function chuquipiondo_ai_sanitize_option( $value ) {
	if ( is_array( $value ) ) {
		return array_map( 'sanitize_text_field', $value );
	}
	return sanitize_text_field( $value );
}

/**
 * Render the settings page.
 *
 * @return void
 */
function chuquipiondo_ai_settings_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$providers = chuquipiondo_ai_providers();
	$active    = chuquipiondo_ai_get_option( 'ai_provider', 'mistral' );
	?>
	<div class="wrap chuquipiondo-ai-admin">
		<h1><?php esc_html_e( 'CHUQUIPIONDO AI Studio - Ajustes', 'chuquipiondo-ai' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Configura el motor de IA, las imagenes por defecto (500px alto x 900px ancho) y las opciones de SEO/publicacion.', 'chuquipiondo-ai' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'chuquipiondo_ai_group' ); ?>

			<h2 class="title"><?php esc_html_e( '1. Motor de IA', 'chuquipiondo-ai' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="ai_provider"><?php esc_html_e( 'Proveedor', 'chuquipiondo-ai' ); ?></label></th>
					<td>
						<select name="ai_provider" id="ai_provider" class="regular-text">
							<?php foreach ( $providers as $slug => $cfg ) : ?>
								<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $active, $slug ); ?>><?php echo esc_html( $cfg['label'] ); ?></option>
							<?php endforeach; ?>
						</select>
						<p class="description"><?php esc_html_e( 'Recomendado: Mistral AI. Tambien OpenAI y Anthropic. "Local" usa plantillas sin API.', 'chuquipiondo-ai' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_api_key"><?php esc_html_e( 'API Key', 'chuquipiondo-ai' ); ?></label></th>
					<td>
						<input type="password" name="ai_api_key" id="ai_api_key" class="regular-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_api_key', '' ) ); ?>" autocomplete="new-password" />
						<p class="description"><?php esc_html_e( 'Tu clave se guarda en la base de datos. Usa HTTPS. Nunca se muestra completa en la UI.', 'chuquipiondo-ai' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_model"><?php esc_html_e( 'Modelo', 'chuquipiondo-ai' ); ?></label></th>
					<td>
						<input type="text" name="ai_model" id="ai_model" class="regular-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_model', '' ) ); ?>" />
						<p class="description"><?php esc_html_e( 'Ej.: mistral-large-latest, gpt-4o, claude-3-5-sonnet-latest.', 'chuquipiondo-ai' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_temperature"><?php esc_html_e( 'Temperatura', 'chuquipiondo-ai' ); ?></label></th>
					<td>
						<input type="number" step="0.1" min="0" max="2" name="ai_temperature" id="ai_temperature" class="small-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_temperature', '0.7' ) ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_max_tokens"><?php esc_html_e( 'Max tokens', 'chuquipiondo-ai' ); ?></label></th>
					<td>
						<input type="number" min="128" max="32000" name="ai_max_tokens" id="ai_max_tokens" class="small-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_max_tokens', '4096' ) ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_language"><?php esc_html_e( 'Idioma de salida', 'chuquipiondo-ai' ); ?></label></th>
					<td>
						<input type="text" name="ai_language" id="ai_language" class="small-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_language', 'es' ) ); ?>" />
						<span class="description"><?php esc_html_e( 'es, en, fr, ...', 'chuquipiondo-ai' ); ?></span>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( '2. Imagenes por defecto (500px alto x 900px ancho)', 'chuquipiondo-ai' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="ai_image_width"><?php esc_html_e( 'Ancho (px)', 'chuquipiondo-ai' ); ?></label></th>
					<td><input type="number" min="1" max="5000" name="ai_image_width" id="ai_image_width" class="small-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_image_width', '900' ) ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_image_height"><?php esc_html_e( 'Alto (px)', 'chuquipiondo-ai' ); ?></label></th>
					<td><input type="number" min="1" max="5000" name="ai_image_height" id="ai_image_height" class="small-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_image_height', '500' ) ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Redimensionar automaticamente', 'chuquipiondo-ai' ); ?></th>
					<td>
						<label><input type="checkbox" name="ai_image_auto_resize" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_image_auto_resize' ) ); ?> /> <?php esc_html_e( 'Forzar 500x900 en cada imagen gestionada', 'chuquipiondo-ai' ); ?></label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_image_provider"><?php esc_html_e( 'Origen de imagenes IA', 'chuquipiondo-ai' ); ?></label></th>
					<td>
						<select name="ai_image_provider" id="ai_image_provider">
							<option value="local" <?php selected( chuquipiondo_ai_get_option( 'ai_image_provider', 'local' ), 'local' ); ?>><?php esc_html_e( 'Pollinations (gratis, sin key)', 'chuquipiondo-ai' ); ?></option>
							<option value="openai-dalle" <?php selected( chuquipiondo_ai_get_option( 'ai_image_provider', 'local' ), 'openai-dalle' ); ?>><?php esc_html_e( 'OpenAI DALL-E 3', 'chuquipiondo-ai' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_max_generated_images"><?php esc_html_e( 'Max imagenes por articulo', 'chuquipiondo-ai' ); ?></label></th>
					<td><input type="number" min="1" max="20" name="ai_max_generated_images" id="ai_max_generated_images" class="small-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_max_generated_images', '6' ) ); ?>" /></td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( '3. Acceso a contenido', 'chuquipiondo-ai' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Acceso a Entradas', 'chuquipiondo-ai' ); ?></th>
					<td><label><input type="checkbox" name="ai_scope_posts" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_scope_posts' ) ); ?> /> <?php esc_html_e( 'Permitir leer y editar Entradas', 'chuquipiondo-ai' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Acceso a Paginas', 'chuquipiondo-ai' ); ?></th>
					<td><label><input type="checkbox" name="ai_scope_pages" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_scope_pages' ) ); ?> /> <?php esc_html_e( 'Permitir leer y editar Paginas', 'chuquipiondo-ai' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Codigo HTML/PHP/JS', 'chuquipiondo-ai' ); ?></th>
					<td><label><input type="checkbox" name="ai_allowed_html" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_allowed_html' ) ); ?> /> <?php esc_html_e( 'Permitir pegar bloques de codigo (HTML, PHP, JS)', 'chuquipiondo-ai' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_default_post_status"><?php esc_html_e( 'Estado al publicar', 'chuquipiondo-ai' ); ?></label></th>
					<td>
						<select name="ai_default_post_status" id="ai_default_post_status">
							<option value="draft" <?php selected( chuquipiondo_ai_get_option( 'ai_default_post_status', 'draft' ), 'draft' ); ?>><?php esc_html_e( 'Borrador', 'chuquipiondo-ai' ); ?></option>
							<option value="pending" <?php selected( chuquipiondo_ai_get_option( 'ai_default_post_status', 'draft' ), 'pending' ); ?>><?php esc_html_e( 'Pendiente de revision', 'chuquipiondo-ai' ); ?></option>
							<option value="publish" <?php selected( chuquipiondo_ai_get_option( 'ai_default_post_status', 'draft' ), 'publish' ); ?>><?php esc_html_e( 'Publicado', 'chuquipiondo-ai' ); ?></option>
						</select>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( '4. SEO y publicacion', 'chuquipiondo-ai' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="ai_seo_meta_desc_len"><?php esc_html_e( 'Longitud meta descripcion', 'chuquipiondo-ai' ); ?></label></th>
					<td><input type="number" min="80" max="320" name="ai_seo_meta_desc_len" id="ai_seo_meta_desc_len" class="small-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_seo_meta_desc_len', '160' ) ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="ai_seo_keywords_count"><?php esc_html_e( 'Numero de keywords/etiquetas', 'chuquipiondo-ai' ); ?></label></th>
					<td><input type="number" min="3" max="30" name="ai_seo_keywords_count" id="ai_seo_keywords_count" class="small-text" value="<?php echo esc_attr( chuquipiondo_ai_get_option( 'ai_seo_keywords_count', '8' ) ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Generar', 'chuquipiondo-ai' ); ?></th>
					<td>
						<label><input type="checkbox" name="ai_seo_generate_tags" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_seo_generate_tags' ) ); ?> /> <?php esc_html_e( 'Etiquetas', 'chuquipiondo-ai' ); ?></label><br>
						<label><input type="checkbox" name="ai_seo_generate_excerpt" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_seo_generate_excerpt' ) ); ?> /> <?php esc_html_e( 'Extracto', 'chuquipiondo-ai' ); ?></label><br>
						<label><input type="checkbox" name="ai_seo_generate_slug" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_seo_generate_slug' ) ); ?> /> <?php esc_html_e( 'Slug optimizado', 'chuquipiondo-ai' ); ?></label><br>
						<label><input type="checkbox" name="ai_seo_add_schema" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_seo_add_schema' ) ); ?> /> <?php esc_html_e( 'Schema.org JSON-LD', 'chuquipiondo-ai' ); ?></label><br>
						<label><input type="checkbox" name="ai_auto_featured_image" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_auto_featured_image' ) ); ?> /> <?php esc_html_e( 'Imagen destacada automatica', 'chuquipiondo-ai' ); ?></label>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( '5. Compatibilidad de tema', 'chuquipiondo-ai' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Hooks seguros (multi-tema)', 'chuquipiondo-ai' ); ?></th>
					<td><label><input type="checkbox" name="ai_compat_safe_hooks" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_compat_safe_hooks' ) ); ?> /> <?php esc_html_e( 'Usar solo hooks agnosticos del tema (evita conflictos)', 'chuquipiondo-ai' ); ?></label></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Astra (especial)', 'chuquipiondo-ai' ); ?></th>
					<td><label><input type="checkbox" name="ai_compat_astra" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_compat_astra' ) ); ?> /> <?php esc_html_e( 'Activar ajustes especificos para Astra', 'chuquipiondo-ai' ); ?></label>
					<p class="description"><?php echo chuquipiondo_ai_is_astra_active() ? esc_html__( 'Astra detectado como tema activo.', 'chuquipiondo-ai' ) : esc_html__( 'Astra no es el tema activo (se aplicara igualmente cuando lo sea).', 'chuquipiondo-ai' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Depuracion', 'chuquipiondo-ai' ); ?></th>
					<td><label><input type="checkbox" name="ai_log_requests" value="1" <?php checked( chuquipiondo_ai_is_enabled( 'ai_log_requests' ) ); ?> /> <?php esc_html_e( 'Registrar llamadas a la IA (key enmascarada)', 'chuquipiondo-ai' ); ?></label></td>
				</tr>
			</table>

			<?php submit_button( __( 'Guardar ajustes', 'chuquipiondo-ai' ) ); ?>
		</form>
	</div>
	<?php
}
