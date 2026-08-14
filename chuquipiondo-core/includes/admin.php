<?php
/**
 * Admin page for the CHUQUIPIONDO Core plugin.
 *
 * @package CHUQUIPIONDO_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the core admin menu.
 */
function chuquipiondo_core_admin_menu() {
	add_menu_page(
		__( 'CHUQUIPIONDO Core', 'chuquipiondo-core' ),
		__( 'Core', 'chuquipiondo-core' ),
		'manage_options',
		'chuquipiondo-core',
		'chuquipiondo_core_admin_page_render',
		'dashicons-admin-plugins',
		58
	);
}
add_action( 'admin_menu', 'chuquipiondo_core_admin_menu' );

/**
 * Enqueue admin assets.
 */
function chuquipiondo_core_admin_assets( $hook ) {
	if ( false === strpos( $hook, 'chuquipiondo' ) ) {
		return;
	}
	wp_enqueue_style(
		'chuquipiondo-core-admin',
		CHUQUIPIONDO_CORE_URL . 'assets/css/admin.css',
		array(),
		CHUQUIPIONDO_CORE_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'chuquipiondo_core_admin_assets' );

/**
 * Render the core admin page.
 */
function chuquipiondo_core_admin_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$theme_active = function_exists( 'chuquipiondo_is_enabled' );
	?>
	<div class="wrap chuquipiondo-core-admin">
		<h1><?php esc_html_e( 'CHUQUIPIONDO Core', 'chuquipiondo-core' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Plugin core del tema CHUQUIPIONDO. Anade shortcodes, bloques, widgets y herramientas adicionales.', 'chuquipiondo-core' ); ?></p>

		<div class="chuquipiondo-core-grid">
			<div class="chuquipiondo-core-card">
				<h2><?php esc_html_e( 'Estado del plugin', 'chuquipiondo-core' ); ?></h2>
				<table class="form-table">
					<tr>
						<th><?php esc_html_e( 'Version', 'chuquipiondo-core' ); ?></th>
						<td>v<?php echo esc_html( CHUQUIPIONDO_CORE_VERSION ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Tema activo', 'chuquipiondo-core' ); ?></th>
						<td>
							<?php if ( $theme_active ) : ?>
								<span class="chuqui-badge chuqui-badge--ok"><?php esc_html_e( 'CHUQUIPIONDO activo', 'chuquipiondo-core' ); ?></span>
							<?php else : ?>
								<span class="chuqui-badge chuqui-badge--warn"><?php esc_html_e( 'Tema CHUQUIPIONDO no activo', 'chuquipiondo-core' ); ?></span>
							<?php endif; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="chuquipiondo-core-card">
				<h2><?php esc_html_e( 'Shortcodes disponibles', 'chuquipiondo-core' ); ?></h2>
				<table class="widefat striped">
					<thead><tr><th>Shortcode</th><th>Descripcion</th></tr></thead>
					<tbody>
						<tr><td><code>[chuquipiondo_button]</code></td><td><?php esc_html_e( 'Boton con icono', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>[chuquipiondo_posts]</code></td><td><?php esc_html_e( 'Grid de articulos', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>[chuquipiondo_music]</code></td><td><?php esc_html_e( 'Grid de musica', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>[chuquipiondo_categories]</code></td><td><?php esc_html_e( 'Lista de categorias', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>[chuquipiondo_social_profiles]</code></td><td><?php esc_html_e( 'Perfiles sociales', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>[chuquipiondo_ad]</code></td><td><?php esc_html_e( 'Slot de anuncio', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>[chuquipiondo_breadcrumbs]</code></td><td><?php esc_html_e( 'Migas de pan', 'chuquipiondo-core' ); ?></td></tr>
					</tbody>
				</table>
			</div>

			<div class="chuquipiondo-core-card">
				<h2><?php esc_html_e( 'Bloques Gutenberg', 'chuquipiondo-core' ); ?></h2>
				<table class="widefat striped">
					<thead><tr><th>Bloque</th><th>Descripcion</th></tr></thead>
					<tbody>
						<tr><td><code>chuquipiondo/button</code></td><td><?php esc_html_e( 'Boton con icono', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>chuquipiondo/posts</code></td><td><?php esc_html_e( 'Grid de articulos', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>chuquipiondo/music</code></td><td><?php esc_html_e( 'Grid de musica', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>chuquipiondo/categories</code></td><td><?php esc_html_e( 'Categorias', 'chuquipiondo-core' ); ?></td></tr>
						<tr><td><code>chuquipiondo/ad</code></td><td><?php esc_html_e( 'Slot de anuncio', 'chuquipiondo-core' ); ?></td></tr>
					</tbody>
				</table>
			</div>

			<div class="chuquipiondo-core-card">
				<h2><?php esc_html_e( 'Widgets adicionales', 'chuquipiondo-core' ); ?></h2>
				<ul>
					<li><?php esc_html_e( 'Articulos recientes (con miniatura)', 'chuquipiondo-core' ); ?></li>
					<li><?php esc_html_e( 'Pestañas (recientes / populares)', 'chuquipiondo-core' ); ?></li>
					<li><?php esc_html_e( 'Estadisticas del sitio', 'chuquipiondo-core' ); ?></li>
				</ul>
			</div>

			<div class="chuquipiondo-core-card">
				<h2><?php esc_html_e( 'Herramientas', 'chuquipiondo-core' ); ?></h2>
				<p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=chuquipiondo-demo' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Importar Demo', 'chuquipiondo-core' ); ?></a>
				</p>
			</div>
		</div>
	</div>
	<?php
}
