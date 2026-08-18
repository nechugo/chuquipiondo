<?php
/**
 * Module 4: Starter Sites.
 *
 * Gallery of pre-built site configurations importable with one click.
 * Each starter site bundles theme_mods + a content recipe. When the
 * CHUQUIPIONDO Core plugin is active, content import is delegated to
 * its demo importer (chuquipiondo_core_do_demo_import); otherwise only
 * the theme configuration (theme_mods) is applied.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the starter sites admin page.
 */
function chuquipiondo_companion_starter_sites_menu() {
	add_submenu_page(
		'chuquipiondo-companion',
		__( 'Starter Sites', 'chuquipiondo-companion' ),
		__( 'Starter Sites', 'chuquipiondo-companion' ),
		'manage_options',
		'chuquipiondo-starter-sites',
		'chuquipiondo_companion_starter_sites_render'
	);
}
add_action( 'admin_menu', 'chuquipiondo_companion_starter_sites_menu' );

/**
 * Get the available starter sites.
 *
 * Each site ships:
 *  - name, description, screenshot url, accent color
 *  - theme_mods: array of theme_mod overrides
 *  - content: optional demo id handled by the core importer
 *
 * @return array
 */
function chuquipiondo_companion_get_starter_sites() {
	return array(
		'editorial' => array(
			'name'        => __( 'Portal Editorial', 'chuquipiondo-companion' ),
			'description' => __( 'Portal editorial completo: topbar, hero slider, home builder con 8 modulos, footer de 4 columnas y preset Chuquipiondo Original.', 'chuquipiondo-companion' ),
			'screenshot'  => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&h=400&fit=crop',
			'accent'      => '#27b6ff',
			'content'     => 'editorial',
			'theme_mods'  => array(
				'header_topbar_enable'  => '1',
				'header_topbar_date'    => '1',
				'header_topbar_time'    => '1',
				'hero_enable'          => '1',
				'hero_mode'            => 'slider',
				'hero_autoplay'        => '1',
				'footer_columns'       => '4',
				'footer_show_brand'    => '1',
				'footer_show_social'   => '1',
				'home_modules'         => 'hero,featured,latest,categories,song,videos,about,newsletter',
			),
		),
		'magazine' => array(
			'name'        => __( 'Revista Digital', 'chuquipiondo-companion' ),
			'description' => __( 'Layout tipo revista: portada con slider de 3 entradas, tarjetas magazine y sidebar de noticias. Preset Editorial.', 'chuquipiondo-companion' ),
			'screenshot'  => 'https://images.unsplash.com/photo-1504813184591-fed0ac3a42a8?w=600&h=400&fit=crop',
			'accent'      => '#c8102e',
			'content'     => 'editorial',
			'theme_mods'  => array(
				'header_topbar_enable' => '0',
				'hero_enable'         => '0',
				'footer_columns'      => '3',
				'home_modules'        => 'featured,latest,categories,song',
				'blog_columns'        => '3',
			),
		),
		'music' => array(
			'name'        => __( 'Plataforma Musical', 'chuquipiondo-companion' ),
			'description' => __( 'Enfocado en musica: mini player activo, archivo de canciones en grid 3 columnas, hero con cancion destacada. Preset Music.', 'chuquipiondo-companion' ),
			'screenshot'  => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=600&h=400&fit=crop',
			'accent'      => '#1db954',
			'content'     => 'music',
			'theme_mods'  => array(
				'music_mini_player'   => '1',
				'music_columns'       => '3',
				'hero_enable'         => '1',
				'hero_mode'           => 'image',
				'home_modules'        => 'hero,song,featured,latest,categories',
				'footer_columns'      => '4',
			),
		),
		'minimal' => array(
			'name'        => __( 'Minimalista', 'chuquipiondo-companion' ),
			'description' => __( 'Configuracion ligera: sin topbar, sin hero, tarjetas minimal y footer de 2 columnas. Preset Minimal.', 'chuquipiondo-companion' ),
			'screenshot'  => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=600&h=400&fit=crop',
			'accent'      => '#0a1f44',
			'content'     => 'minimal',
			'theme_mods'  => array(
				'header_topbar_enable' => '0',
				'hero_enable'         => '0',
				'footer_columns'      => '2',
				'home_modules'        => 'latest,about',
				'blog_columns'        => '2',
			),
		),
		'fe' => array(
			'name'        => __( 'Fe y Reflexion', 'chuquipiondo-companion' ),
			'description' => __( 'Portal de contenido espiritual: hero con imagen, categorias destacadas, single editorial. Preset Chuquipiondo Original suave.', 'chuquipiondo-companion' ),
			'screenshot'  => 'https://images.unsplash.com/photo-1507692049790-de58290a4334?w=600&h=400&fit=crop',
			'accent'      => '#f5a623',
			'content'     => 'editorial',
			'theme_mods'  => array(
				'header_topbar_enable'  => '1',
				'header_topbar_date'    => '1',
				'hero_enable'          => '1',
				'hero_mode'            => 'image',
				'home_modules'         => 'hero,featured,latest,categories,about,newsletter',
				'footer_show_brand'    => '1',
				'footer_show_social'   => '1',
				'footer_columns'       => '4',
			),
		),
	);
}

/**
 * Apply a starter site.
 *
 * @param string $site_id Starter site id.
 */
function chuquipiondo_companion_apply_starter_site( $site_id ) {
	$sites = chuquipiondo_companion_get_starter_sites();
	if ( ! isset( $sites[ $site_id ] ) ) {
		return;
	}
	$site = $sites[ $site_id ];

	// 1. Apply theme_mods overrides.
	foreach ( $site['theme_mods'] as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	// 2. Apply the matching color preset when the theme helper exists.
	$preset_map = array(
		'editorial' => 'original',
		'magazine'  => 'editorial',
		'music'     => 'music',
		'minimal'   => 'minimal',
		'fe'        => 'original',
	);
	$preset = isset( $preset_map[ $site_id ] ) ? $preset_map[ $site_id ] : 'original';
	if ( function_exists( 'chuquipiondo_apply_preset' ) ) {
		chuquipiondo_apply_preset( $preset );
	}

	// 3. Delegate content import to the core plugin when available.
	if ( ! empty( $site['content'] ) && function_exists( 'chuquipiondo_core_do_demo_import' ) ) {
		chuquipiondo_core_do_demo_import( $site['content'] );
	}

	update_option( 'companion_last_imported_site', $site_id, false );
}

/**
 * Handle the starter site import form submission.
 */
function chuquipiondo_companion_handle_starter_site_import() {
	if ( ! isset( $_POST['chuquipiondo_starter_site'] ) ) {
		return;
	}
	if ( ! isset( $_POST['chuquipiondo_starter_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['chuquipiondo_starter_nonce'] ), 'chuquipiondo_starter_import' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$site_id = sanitize_key( $_POST['chuquipiondo_starter_site'] );
	chuquipiondo_companion_apply_starter_site( $site_id );
	wp_safe_redirect( add_query_arg( 'chuquipiondo_starter_imported', $site_id, admin_url( 'admin.php?page=chuquipiondo-starter-sites' ) ) );
	exit;
}
add_action( 'admin_init', 'chuquipiondo_companion_handle_starter_site_import' );

/**
 * Render the starter sites gallery.
 */
function chuquipiondo_companion_starter_sites_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$sites = chuquipiondo_companion_get_starter_sites();
	$last  = get_option( 'companion_last_imported_site', '' );
	$core  = function_exists( 'chuquipiondo_core_do_demo_import' );
	?>
	<div class="wrap chuquipiondo-companion-starter">
		<h1><?php esc_html_e( 'CHUQUIPIONDO - Starter Sites', 'chuquipiondo-companion' ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'Importa un sitio preconfigurado con un clic. Se aplicaran los colores, el layout y el home builder.', 'chuquipiondo-companion' ); ?>
			<?php if ( ! $core ) : ?>
				<br><strong><?php esc_html_e( 'Nota: CHUQUIPIONDO Core no esta activo. Se aplicara la configuracion del tema pero no se importara contenido de muestra.', 'chuquipiondo-companion' ); ?></strong>
			<?php endif; ?>
		</p>

		<div class="starter-sites-grid">
			<?php foreach ( $sites as $site_id => $site ) : ?>
				<div class="starter-site-card<?php echo ( $last === $site_id ) ? ' is-imported' : ''; ?>">
					<div class="starter-site-card__media">
						<img src="<?php echo esc_url( $site['screenshot'] ); ?>" alt="<?php echo esc_attr( $site['name'] ); ?>" loading="lazy">
						<span class="starter-site-card__accent" style="background:<?php echo esc_attr( $site['accent'] ); ?>"></span>
					</div>
					<div class="starter-site-card__body">
						<h3><?php echo esc_html( $site['name'] ); ?></h3>
						<p><?php echo esc_html( $site['description'] ); ?></p>
						<form method="post">
							<input type="hidden" name="chuquipiondo_starter_site" value="<?php echo esc_attr( $site_id ); ?>">
							<?php wp_nonce_field( 'chuquipiondo_starter_import', 'chuquipiondo_starter_nonce' ); ?>
							<button type="submit" class="button button-primary">
								<?php echo ( $last === $site_id ) ? esc_html__( 'Reimportar', 'chuquipiondo-companion' ) : esc_html__( 'Importar', 'chuquipiondo-companion' ); ?>
							</button>
						</form>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Admin notice after a successful import.
 */
function chuquipiondo_companion_starter_imported_notice() {
	if ( ! isset( $_GET['chuquipiondo_starter_imported'] ) ) {
		return;
	}
	$site_id = sanitize_key( $_GET['chuquipiondo_starter_imported'] );
	$sites   = chuquipiondo_companion_get_starter_sites();
	$name    = isset( $sites[ $site_id ] ) ? $sites[ $site_id ]['name'] : $site_id;
	echo '<div class="notice notice-success"><p>' . sprintf(
		/* translators: %s: site name */
		esc_html__( 'Starter site "%s" aplicado correctamente. Revisa tu sitio.', 'chuquipiondo-companion' ),
		'<strong>' . esc_html( $name ) . '</strong>'
	) . '</p></div>';
}
add_action( 'admin_notices', 'chuquipiondo_companion_starter_imported_notice' );
