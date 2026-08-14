<?php
/**
 * Demo importer for the CHUQUIPIONDO Core plugin.
 *
 * Allows importing a demo configuration with sample content,
 * widgets, and Customizer settings.
 *
 * @package CHUQUIPIONDO_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the demo importer admin page.
 */
function chuquipiondo_core_demo_admin_menu() {
	add_submenu_page(
		'chuquipiondo-options',
		__( 'Importar Demo', 'chuquipiondo-core' ),
		__( 'Importar Demo', 'chuquipiondo-core' ),
		'manage_options',
		'chuquipiondo-demo',
		'chuquipiondo_core_demo_page_render'
	);
}
add_action( 'admin_menu', 'chuquipiondo_core_demo_admin_menu' );

/**
 * Render the demo importer page.
 */
function chuquipiondo_core_demo_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$demos = chuquipiondo_core_get_demos();

	?>
	<div class="wrap chuquipiondo-core-demo">
		<h1><?php esc_html_e( 'CHUQUIPIONDO - Importar Demo', 'chuquipiondo-core' ); ?></h1>
		<p><?php esc_html_e( 'Importa una configuracion demo con contenido de prueba. Esto creara articulos, paginas, canciones y configurara el tema.', 'chuquipiondo-core' ); ?></p>

		<div class="chuquipiondo-demo-grid">
			<?php foreach ( $demos as $demo_id => $demo ) : ?>
				<div class="chuquipiondo-demo-card">
					<h3><?php echo esc_html( $demo['name'] ); ?></h3>
					<p><?php echo esc_html( $demo['description'] ); ?></p>
					<form method="post">
						<input type="hidden" name="chuquipiondo_demo_id" value="<?php echo esc_attr( $demo_id ); ?>">
						<?php wp_nonce_field( 'chuquipiondo_demo_import', 'chuquipiondo_demo_nonce' ); ?>
						<button type="submit" class="button button-primary"><?php esc_html_e( 'Importar', 'chuquipiondo-core' ); ?></button>
					</form>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Get available demos.
 *
 * @return array
 */
function chuquipiondo_core_get_demos() {
	return array(
		'editorial' => array(
			'name'        => __( 'Portal Editorial', 'chuquipiondo-core' ),
			'description' => __( 'Configuracion completa: articulos, categorias, menu, musica y widget config.', 'chuquipiondo-core' ),
			'content'     => array(
				'posts'     => 8,
				'pages'     => 3,
				'music'     => 4,
				'categories' => array( 'Liderazgo', 'Gestion', 'Formacion', 'Fe', 'Musica' ),
			),
		),
		'minimal' => array(
			'name'        => __( 'Minimal', 'chuquipiondo-core' ),
			'description' => __( 'Configuracion simple: 3 articulos y una pagina.', 'chuquipiondo-core' ),
			'content'     => array(
				'posts'     => 3,
				'pages'     => 1,
				'categories' => array( 'General' ),
			),
		),
		'music' => array(
			'name'        => __( 'Plataforma Musical', 'chuquipiondo-core' ),
			'description' => __( 'Enfocado en musica: 6 canciones + 2 articulos.', 'chuquipiondo-core' ),
			'content'     => array(
				'posts'     => 2,
				'pages'     => 1,
				'music'     => 6,
				'categories' => array( 'Musica', 'Fe' ),
			),
		),
	);
}

/**
 * Handle the demo import.
 */
function chuquipiondo_core_handle_demo_import() {
	if ( ! isset( $_POST['chuquipiondo_demo_id'] ) ) {
		return;
	}
	if ( ! isset( $_POST['chuquipiondo_demo_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['chuquipiondo_demo_nonce'] ), 'chuquipiondo_demo_import' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$demo_id = sanitize_key( $_POST['chuquipiondo_demo_id'] );
	$demos   = chuquipiondo_core_get_demos();

	if ( ! isset( $demos[ $demo_id ] ) ) {
		return;
	}

	$demo    = $demos[ $demo_id ];
	$content = $demo['content'];
	$user_id = get_current_user_id();

	// Create categories.
	$cat_ids = array();
	if ( isset( $content['categories'] ) ) {
		foreach ( $content['categories'] as $cat_name ) {
			$existing = get_term_by( 'name', $cat_name, 'category' );
			if ( $existing ) {
				$cat_ids[] = $existing->term_id;
			} else {
				$result = wp_insert_term( $cat_name, 'category' );
				if ( ! is_wp_error( $result ) ) {
					$cat_ids[] = $result['term_id'];
				}
			}
		}
	}

	// Create posts.
	if ( isset( $content['posts'] ) ) {
		for ( $i = 1; $i <= $content['posts']; $i++ ) {
			$cat_id = ! empty( $cat_ids ) ? array( $cat_ids[ array_rand( $cat_ids ) ] ) : array();
			wp_insert_post( array(
				'post_title'   => sprintf( __( 'Articulo de prueba %d', 'chuquipiondo-core' ), $i ),
				/* translators: %d: article number */
				'post_content' => '<p>' . sprintf( __( 'Este es el contenido de prueba del articulo %d. Liderazgo, Gestion y Formacion con proposito.', 'chuquipiondo-core' ), $i ) . '</p><h2>Subtitulo</h2><p>' . __( 'Mas contenido aqui para verificar el diseno.', 'chuquipiondo-core' ) . '</p>',
				'post_status'  => 'publish',
				'post_author'  => $user_id,
				'post_category' => $cat_id,
			) );
		}
	}

	// Create pages.
	if ( isset( $content['pages'] ) ) {
		for ( $i = 1; $i <= $content['pages']; $i++ ) {
			wp_insert_post( array(
				/* translators: %d: page number */
				'post_title'  => sprintf( __( 'Pagina %d', 'chuquipiondo-core' ), $i ),
				'post_content' => '<p>' . __( 'Contenido de la pagina de prueba.', 'chuquipiondo-core' ) . '</p>',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => $user_id,
			) );
		}
	}

	// Create music.
	if ( isset( $content['music'] ) && post_type_exists( 'musica' ) ) {
		for ( $i = 1; $i <= $content['music']; $i++ ) {
			wp_insert_post( array(
				/* translators: %d: song number */
				'post_title'  => sprintf( __( 'Cancion %d', 'chuquipiondo-core' ), $i ),
				'post_content' => '<p>' . __( 'Letra de la cancion de prueba.', 'chuquipiondo-core' ) . '</p>',
				'post_status'  => 'publish',
				'post_type'    => 'musica',
				'post_author'  => $user_id,
			) );
		}
	}

	// Apply the "Chuquipiondo Original" preset if the theme is active.
	if ( function_exists( 'chuquipiondo_apply_preset' ) ) {
		chuquipiondo_apply_preset( 'original' );
	}

	wp_safe_redirect( add_query_arg( 'chuquipiondo_demo_imported', '1', admin_url( 'admin.php?page=chuquipiondo-demo' ) ) );
	exit;
}
add_action( 'admin_init', 'chuquipiondo_core_handle_demo_import' );

/**
 * Admin notice for successful import.
 */
function chuquipiondo_core_demo_imported_notice() {
	if ( ! isset( $_GET['chuquipiondo_demo_imported'] ) ) {
		return;
	}
	echo '<div class="notice notice-success"><p>' . esc_html__( 'Demo importado correctamente. Revisa tus articulos, paginas y musica.', 'chuquipiondo-core' ) . '</p></div>';
}
add_action( 'admin_notices', 'chuquipiondo_core_demo_imported_notice' );
