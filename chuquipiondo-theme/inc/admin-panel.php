<?php
/**
 * Advanced options panel ("CHUQUIPIONDO").
 *
 * A separate admin menu for advanced configuration: import/export,
 * presets reset, hooks overview. Does NOT duplicate Customizer controls.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the admin menu page.
 */
function chuquipiondo_admin_menu() {
	$hook = add_menu_page(
		__( 'CHUQUIPIONDO', 'chuquipiondo' ),
		__( 'CHUQUIPIONDO', 'chuquipiondo' ),
		'manage_options',
		'chuquipiondo-options',
		'chuquipiondo_admin_page_render',
		'dashicons-admin-customizer',
		59
	);
	add_action( 'load-' . $hook, 'chuquipiondo_admin_page_assets' );
}
add_action( 'admin_menu', 'chuquipiondo_admin_menu' );

/**
 * Enqueue admin page assets.
 */
function chuquipiondo_admin_page_assets() {
	wp_enqueue_style(
		'chuquipiondo-admin',
		CHUQUIPONDO_URI . '/assets/css/admin.css',
		array(),
		chuquipiondo_asset_version( 'assets/css/admin.css' )
	);
	wp_enqueue_script(
		'chuquipiondo-admin',
		CHUQUIPONDO_URI . '/assets/js/admin.js',
		array( 'jquery' ),
		chuquipiondo_asset_version( 'assets/js/admin.js' ),
		true
	);
	wp_localize_script( 'chuquipiondo-admin', 'chuquipiondoAdmin', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'chuquipiondo_admin_nonce' ),
		'strings' => array(
			'confirmReset'   => __( 'Esto restablecera todos los valores. Continuar?', 'chuquipiondo' ),
			'confirmImport'  => __( 'Esto sobrescribira la configuracion actual. Continuar?', 'chuquipiondo' ),
		),
	) );
}

/**
 * Render the admin page.
 */
function chuquipiondo_admin_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap chuquipiondo-admin">
		<h1><?php esc_html_e( 'CHUQUIPIONDO - Opciones avanzadas', 'chuquipiondo' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Panel de configuracion avanzada. Para cambios visuales usa Apariencia > Personalizar.', 'chuquipiondo' ); ?></p>

		<div class="chuquipiondo-admin__grid">
			<div class="chuquipiondo-admin__card">
				<h2><?php esc_html_e( 'Exportar / Importar', 'chuquipiondo' ); ?></h2>
				<p><?php esc_html_e( 'Exporta o importa toda la configuracion del tema en formato JSON.', 'chuquipiondo' ); ?></p>
				<p>
					<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=chuquipiondo_export' ), 'chuquipiondo_export', 'chuquipiondo_nonce' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Exportar configuracion', 'chuquipiondo' ); ?></a>
				</p>
				<form method="post" enctype="multipart/form-data" id="chuquipiondo-import-form">
					<p>
						<input type="file" name="chuquipiondo_import_file" accept="application/json" required>
						<input type="hidden" name="action" value="chuquipiondo_import">
						<?php wp_nonce_field( 'chuquipiondo_import', 'chuquipiondo_nonce' ); ?>
						<button type="submit" class="button"><?php esc_html_e( 'Importar', 'chuquipiondo' ); ?></button>
					</p>
				</form>
			</div>

			<div class="chuquipiondo-admin__card">
				<h2><?php esc_html_e( 'Restablecer', 'chuquipiondo' ); ?></h2>
				<p><?php esc_html_e( 'Vuelve todos los valores del tema a sus defaults.', 'chuquipiondo' ); ?></p>
				<form method="post">
					<input type="hidden" name="action" value="chuquipiondo_reset">
					<?php wp_nonce_field( 'chuquipiondo_reset', 'chuquipiondo_nonce' ); ?>
					<button type="submit" class="button button-link-delete" id="chuquipiondo-reset-btn"><?php esc_html_e( 'Restablecer todo', 'chuquipiondo' ); ?></button>
				</form>
			</div>

			<div class="chuquipiondo-admin__card">
				<h2><?php esc_html_e( 'Presets de color', 'chuquipiondo' ); ?></h2>
				<p><?php esc_html_e( 'Aplica un preset predefinido.', 'chuquipiondo' ); ?></p>
				<form method="post">
					<select name="chuquipiondo_preset">
						<?php foreach ( chuquipiondo_presets() as $key => $preset ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $preset['label'] ); ?></option>
						<?php endforeach; ?>
					</select>
					<input type="hidden" name="action" value="chuquipiondo_apply_preset">
					<?php wp_nonce_field( 'chuquipiondo_apply_preset', 'chuquipiondo_nonce' ); ?>
					<button type="submit" class="button"><?php esc_html_e( 'Aplicar', 'chuquipiondo' ); ?></button>
				</form>
			</div>

			<div class="chuquipiondo-admin__card chuquipiondo-admin__card--wide">
				<h2><?php esc_html_e( 'Hooks y filtros disponibles', 'chuquipiondo' ); ?></h2>
				<p><?php esc_html_e( 'Referencia de acciones y filtros para desarrolladores.', 'chuquipiondo' ); ?></p>
				<table class="widefat striped">
					<thead>
						<tr><th><?php esc_html_e( 'Tipo', 'chuquipiondo' ); ?></th><th><?php esc_html_e( 'Nombre', 'chuquipiondo' ); ?></th><th><?php esc_html_e( 'Uso', 'chuquipiondo' ); ?></th></tr>
					</thead>
					<tbody>
						<?php foreach ( chuquipiondo_hooks_reference() as $hook ) : ?>
							<tr>
								<td><?php echo esc_html( $hook['type'] ); ?></td>
								<td><code><?php echo esc_html( $hook['name'] ); ?></code></td>
								<td><?php echo esc_html( $hook['usage'] ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Reference of hooks and filters for the documentation table.
 *
 * @return array
 */
function chuquipiondo_hooks_reference() {
	return array(
		array( 'type' => 'action', 'name' => 'chuquipiondo_before_header', 'usage' => __( 'Antes de la cabecera', 'chuquipiondo' ) ),
		array( 'type' => 'action', 'name' => 'chuquipiondo_after_header', 'usage' => __( 'Despues de la cabecera', 'chuquipiondo' ) ),
		array( 'type' => 'action', 'name' => 'chuquipiondo_before_hero', 'usage' => __( 'Antes del hero', 'chuquipiondo' ) ),
		array( 'type' => 'action', 'name' => 'chuquipiondo_after_hero', 'usage' => __( 'Despues del hero', 'chuquipiondo' ) ),
		array( 'type' => 'action', 'name' => 'chuquipiondo_home', 'usage' => __( 'Render del home builder', 'chuquipiondo' ) ),
		array( 'type' => 'action', 'name' => 'chuquipiondo_before_home_module', 'usage' => __( 'Antes de cada modulo del home', 'chuquipiondo' ) ),
		array( 'type' => 'action', 'name' => 'chuquipiondo_after_home_module', 'usage' => __( 'Despues de cada modulo del home', 'chuquipiondo' ) ),
		array( 'type' => 'action', 'name' => 'chuquipiondo_before_post_end_extension', 'usage' => __( 'Antes del area Post End', 'chuquipiondo' ) ),
		array( 'type' => 'filter', 'name' => 'chuquipiondo_card_style', 'usage' => __( 'Estilo de tarjeta', 'chuquipiondo' ) ),
		array( 'type' => 'filter', 'name' => 'chuquipiondo_home_modules', 'usage' => __( 'Modulos activos del home', 'chuquipiondo' ) ),
		array( 'type' => 'filter', 'name' => 'chuquipiondo_css_vars', 'usage' => __( 'Variables CSS dinamicas', 'chuquipiondo' ) ),
		array( 'type' => 'filter', 'name' => 'chuquipiondo_ad_code', 'usage' => __( 'Codigo de un slot de anuncio', 'chuquipiondo' ) ),
		array( 'type' => 'filter', 'name' => 'chuquipiondo_ad_slots', 'usage' => __( 'Registro de slots de anuncio', 'chuquipiondo' ) ),
		array( 'type' => 'filter', 'name' => 'chuquipiondo_share_networks', 'usage' => __( 'Redes de share', 'chuquipiondo' ) ),
		array( 'type' => 'filter', 'name' => 'chuquipiondo_presets', 'usage' => __( 'Presets de color', 'chuquipiondo' ) ),
		array( 'type' => 'filter', 'name' => 'chuquipiondo_sidebar_position', 'usage' => __( 'Posicion de la barra lateral', 'chuquipiondo' ) ),
	);
}
