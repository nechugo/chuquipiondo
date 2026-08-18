<?php
/**
 * Main CHUQUIPIONDO AI Studio class (singleton).
 *
 * Coordinates the plugin lifecycle: registers the admin menu, enqueues
 * assets only on our screens, exposes the REST/AJAX endpoints and wires
 * the AI client, content, image and publish services.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Chuquipiondo_AI
 */
final class Chuquipiondo_AI {

	/**
	 * Single instance.
	 *
	 * @var Chuquipiondo_AI|null
	 */
	private static $instance = null;

	/**
	 * AI client instance.
	 *
	 * @var Chuquipiondo_AI_Client|null
	 */
	public $client = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return Chuquipiondo_AI
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	 * Wire up hooks. Admin menu, assets and AJAX endpoints are registered
	 * lazily by their respective include files; here we only bootstrap the
	 * AI client and REST endpoints.
	 *
	 * @return void
	 */
	private function init() {
		$this->client = new Chuquipiondo_AI_Client();

		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

		// First-run notice.
		add_action( 'admin_notices', array( $this, 'first_run_notice' ) );

		// Theme-compat integration (Astra aware, never conflicts).
		add_action( 'after_setup_theme', array( $this, 'compat_integration' ), 20 );
	}

	/**
	 * Register REST API routes under the chuquipiondo-ai/v1 namespace.
	 *
	 * All routes require `edit_posts` capability and a nonce check.
	 *
	 * @return void
	 */
	public function register_rest_routes() {
		$namespace = 'chuquipiondo-ai/v1';

		register_rest_route(
			$namespace,
			'/posts',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_list_posts' ),
				'permission_callback' => array( $this, 'rest_can_edit' ),
				'args'                => array(
					'post_type' => array( 'default' => 'post', 'sanitize_callback' => 'sanitize_key' ),
					's'         => array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ),
					'paged'     => array( 'default' => 1, 'sanitize_callback' => 'absint' ),
				),
			)
		);

		register_rest_route(
			$namespace,
			'/post/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'rest_get_post' ),
				'permission_callback' => array( $this, 'rest_can_edit' ),
			)
		);

		register_rest_route(
			$namespace,
			'/update',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'rest_update_post' ),
				'permission_callback' => array( $this, 'rest_can_edit' ),
			)
		);

		register_rest_route(
			$namespace,
			'/analyze-images',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'rest_analyze_images' ),
				'permission_callback' => array( $this, 'rest_can_edit' ),
			)
		);

		register_rest_route(
			$namespace,
			'/generate',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'rest_generate' ),
				'permission_callback' => array( $this, 'rest_can_edit' ),
			)
		);

		register_rest_route(
			$namespace,
			'/publish',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'rest_publish' ),
				'permission_callback' => array( $this, 'rest_can_publish' ),
			)
		);

		register_rest_route(
			$namespace,
			'/test',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'rest_test_connection' ),
				'permission_callback' => array( $this, 'rest_can_edit' ),
			)
		);
	}

	/**
	 * REST permission callback: user must be able to edit posts.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return bool|WP_Error
	 */
	public function rest_can_edit( $request ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new WP_Error( 'chuquipiondo_ai_forbidden', __( 'No tienes permisos para usar la IA.', 'chuquipiondo-ai' ), array( 'status' => 403 ) );
		}
		$nonce = $request->get_header( 'x_wp_nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'chuquipiondo_ai_rest' ) ) {
			return new WP_Error( 'chuquipiondo_ai_nonce', __( 'Nonce invalido.', 'chuquipiondo-ai' ), array( 'status' => 403 ) );
		}
		return true;
	}

	/**
	 * REST permission callback: user must be able to publish posts.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return bool|WP_Error
	 */
	public function rest_can_publish( $request ) {
		$can = $this->rest_can_edit( $request );
		if ( true !== $can ) {
			return $can;
		}
		if ( ! current_user_can( 'publish_posts' ) ) {
			return new WP_Error( 'chuquipiondo_ai_no_publish', __( 'No tienes permisos para publicar.', 'chuquipiondo-ai' ), array( 'status' => 403 ) );
		}
		return true;
	}

	/**
	 * List posts/pages for the AI editor.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function rest_list_posts( $request ) {
		$post_type = $request->get_param( 'post_type' );
		$scope     = chuquipiondo_ai_get_array_option( 'ai_scope_posts' ) ? array() : array();
		$allowed   = array();
		if ( chuquipiondo_ai_is_enabled( 'ai_scope_posts' ) ) {
			$allowed[] = 'post';
		}
		if ( chuquipiondo_ai_is_enabled( 'ai_scope_pages' ) ) {
			$allowed[] = 'page';
		}
		if ( empty( $allowed ) ) {
			$allowed = array( 'post' );
		}
		if ( ! in_array( $post_type, $allowed, true ) ) {
			$post_type = $allowed[0];
		}

		$query = new WP_Query(
			array(
				'post_type'      => $post_type,
				'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
				's'              => $request->get_param( 's' ),
				'paged'          => $request->get_param( 'paged' ),
				'posts_per_page' => 20,
				'orderby'        => 'modified',
				'order'          => 'DESC',
			)
		);

		$items = array();
		foreach ( $query->posts as $post ) {
			$thumb = get_the_post_thumbnail_url( $post->ID, 'medium' );
			$items[] = array(
				'id'           => (int) $post->ID,
				'title'        => get_the_title( $post ),
				'type'         => $post->post_type,
				'status'       => $post->post_status,
				'date'         => mysql2date( 'Y-m-d H:i', $post->post_modified ),
				'thumbnail'    => $thumb ? $thumb : '',
				'edit_url'     => get_edit_post_link( $post->ID, 'raw' ),
				'image_count'  => chuquipiondo_ai_count_images_in_content( $post->post_content ),
			);
		}

		return rest_ensure_response(
			array(
				'items'    => $items,
				'total'     => (int) $query->found_posts,
				'pages'     => (int) $query->max_num_pages,
				'post_type' => $post_type,
			)
		);
	}

	/**
	 * Get a single post for editing.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function rest_get_post( $request ) {
		$id = absint( $request->get_param( 'id' ) );
		$post = get_post( $id );
		if ( ! $post ) {
			return new WP_Error( 'not_found', __( 'Entrada/Pagina no encontrada.', 'chuquipiondo-ai' ), array( 'status' => 404 ) );
		}
		return rest_ensure_response(
			array(
				'id'          => (int) $post->ID,
				'title'       => $post->post_title,
				'content'     => $post->post_content,
				'excerpt'     => $post->post_excerpt,
				'slug'        => $post->post_name,
				'status'      => $post->post_status,
				'type'        => $post->post_type,
				'author'      => (int) $post->post_author,
				'date'        => $post->post_date,
				'tags'        => chuquipiondo_ai_get_post_terms( $post->ID, 'post_tag' ),
				'categories'  => chuquipiondo_ai_get_post_terms( $post->ID, 'category' ),
				'featured'    => (int) get_post_thumbnail_id( $post->ID ),
				'meta_desc'   => chuquipiondo_ai_get_meta_description( $post->ID ),
				'images'      => chuquipiondo_ai_analyze_post_images( $post ),
			)
		);
	}

	/**
	 * Update a post (title, content, excerpt, meta, images, code blocks).
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function rest_update_post( $request ) {
		$id     = absint( $request->get_param( 'id' ) );
		$post   = get_post( $id );
		if ( ! $post || ! current_user_can( 'edit_post', $id ) ) {
			return new WP_Error( 'no_access', __( 'No puedes editar este contenido.', 'chuquipiondo-ai' ), array( 'status' => 403 ) );
		}

		$data = array( 'ID' => $id );
		if ( null !== $request->get_param( 'title' ) ) {
			$data['post_title'] = wp_strip_all_tags( $request->get_param( 'title' ) );
		}
		if ( null !== $request->get_param( 'content' ) ) {
			// Content is HTML allowed; AI may include HTML/PHP/JS code blocks per requirement 1.
			$data['post_content'] = chuquipiondo_ai_sanitize_content( $request->get_param( 'content' ) );
		}
		if ( null !== $request->get_param( 'excerpt' ) ) {
			$data['post_excerpt'] = sanitize_textarea_field( $request->get_param( 'excerpt' ) );
		}
		if ( null !== $request->get_param( 'slug' ) ) {
			$data['post_name'] = sanitize_title( $request->get_param( 'slug' ) );
		}
		if ( null !== $request->get_param( 'status' ) ) {
			$status = sanitize_key( $request->get_param( 'status' ) );
			if ( in_array( $status, array( 'draft', 'pending', 'publish', 'private' ), true ) ) {
				$data['post_status'] = $status;
			}
		}

		$result = wp_update_post( $data, true );
		if ( is_wp_error( $result ) ) {
			return $result;
		}

		// Meta description (SEO).
		$meta = $request->get_param( 'meta_desc' );
		if ( null !== $meta ) {
			chuquipiondo_ai_set_meta_description( $id, sanitize_textarea_field( $meta ) );
		}

		// Tags / categories.
		$tags = $request->get_param( 'tags' );
		if ( null !== $tags ) {
			wp_set_post_tags( $id, array_map( 'sanitize_text_field', (array) $tags ), false );
		}
		$cats = $request->get_param( 'categories' );
		if ( null !== $cats && is_array( $cats ) ) {
			wp_set_post_categories( $id, array_map( 'absint', $cats ) );
		}

		// Featured image handling.
		$featured = $request->get_param( 'featured' );
		if ( null !== $featured ) {
			set_post_thumbnail( $id, absint( $featured ) );
		}

		return rest_ensure_response(
			array(
				'success' => true,
				'id'      => $id,
				'images'  => chuquipiondo_ai_analyze_post_images( get_post( $id ) ),
			)
		);
	}

	/**
	 * Analyze images inside a post (autodetect count after the first image).
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function rest_analyze_images( $request ) {
		$id     = absint( $request->get_param( 'id' ) );
		$post   = get_post( $id );
		if ( ! $post ) {
			return new WP_Error( 'not_found', __( 'No encontrado.', 'chuquipiondo-ai' ), array( 'status' => 404 ) );
		}
		return rest_ensure_response( chuquipiondo_ai_analyze_post_images( $post ) );
	}

	/**
	 * Generic AI generation endpoint (improve text, write paragraphs,
	 * generate HTML/PHP/JS blocks, SEO, etc.).
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function rest_generate( $request ) {
		$task    = sanitize_key( $request->get_param( 'task' ) );
		$context = $request->get_param( 'context' );
		$prompt  = $request->get_param( 'prompt' );

		$result = $this->client->run_task( $task, $context, $prompt, $request->get_params() );
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		return rest_ensure_response( $result );
	}

	/**
	 * Publish a brand new AI-generated article (requirement 3).
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function rest_publish( $request ) {
		$params = $request->get_params();
		$force  = ! empty( $params['force_publish'] );
		$result = Chuquipiondo_AI_Publish_Service::create( $params, $force );
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		return rest_ensure_response( $result );
	}

	/**
	 * Test the AI provider connection.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function rest_test_connection( $request ) {
		$out = $this->client->test();
		return rest_ensure_response( $out );
	}

	/**
	 * First-run admin notice.
	 *
	 * @return void
	 */
	public function first_run_notice() {
		if ( ! get_transient( 'chuquipiondo_ai_just_activated' ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		delete_transient( 'chuquipiondo_ai_just_activated' );
		$url = admin_url( 'admin.php?page=chuquipiondo-ai-settings' );
		?>
		<div class="notice notice-success is-dismissible">
			<p>
				<?php esc_html_e( 'CHUQUIPIONDO AI Studio activado. Configura tu API key de IA para empezar.', 'chuquipiondo-ai' ); ?>
				<a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Ir a Ajustes', 'chuquipiondo-ai' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Theme-compat integration.
	 *
	 * Uses only theme-agnostic hooks (`the_content`, `wp_head`, image
	 * size registration) so the plugin works with Astra, CHUQUIPIONDO
	 * and any well-behaved theme without conflicts.
	 *
	 * @return void
	 */
	public function compat_integration() {
		// Register the default AI image size (500h x 900w) in WP's image system.
		add_image_size( 'chuquipiondo-ai', 900, 500, true );

		// Astra-specific touches, only when Astra is the active theme.
		if ( chuquipiondo_ai_is_enabled( 'ai_compat_astra' ) && chuquipiondo_ai_is_astra_active() ) {
			add_filter( 'astra_single_post_before', array( $this, 'maybe_inject_ai_meta' ) );
		}
	}

	/**
	 * Optional Astra hook to inject AI meta info. Kept tiny and defensive.
	 *
	 * @return void
	 */
	public function maybe_inject_ai_meta() {
		if ( ! is_singular() ) {
			return;
		}
		// Intentionally no output by default; reserved for future AI badges.
	}
}
