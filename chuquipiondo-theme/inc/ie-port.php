<?php
/**
 * Import / Export / Reset handlers.
 *
 * Exports the full theme_mod configuration as JSON, imports it back,
 * and provides a reset-to-defaults action. All handlers are nonce-protected
 * and require manage_options.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Export the theme configuration as a JSON download.
 */
function chuquipiondo_handle_export() {
	if ( ! isset( $_GET['chuquipiondo_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['chuquipiondo_nonce'] ), 'chuquipiondo_export' ) ) {
		wp_die( esc_html__( 'Nonce invalido.', 'chuquipiondo' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sin permisos.', 'chuquipiondo' ) );
	}

	$theme_mods = get_theme_mods();
	$export = array(
		'theme'      => 'chuquipiondo',
		'version'    => CHUQUIPIONDO_VERSION,
		'exported'   => gmdate( 'c' ),
		'theme_mods' => $theme_mods,
	);

	$json = wp_json_encode( $export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

	$filename = 'chuquipiondo-config-' . gmdate( 'Y-m-d' ) . '.json';
	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	header( 'Content-Length: ' . strlen( $json ) );
	echo $json; // phpcs:ignore WordPress.Security.EscapeOutput -- JSON export.
	exit;
}
add_action( 'admin_post_chuquipiondo_export', 'chuquipiondo_handle_export' );

/**
 * Handle the JSON import.
 */
function chuquipiondo_handle_import() {
	if ( ! isset( $_POST['chuquipiondo_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['chuquipiondo_nonce'] ), 'chuquipiondo_import' ) ) {
		wp_die( esc_html__( 'Nonce invalido.', 'chuquipiondo' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sin permisos.', 'chuquipiondo' ) );
	}
	if ( empty( $_FILES['chuquipiondo_import_file']['tmp_name'] ) ) {
		wp_safe_redirect( add_query_arg( 'chuquipiondo_import', 'error', admin_url( 'admin.php?page=chuquipiondo-options' ) ) );
		exit;
	}

	$file     = $_FILES['chuquipiondo_import_file']['tmp_name'];
	$contents = file_get_contents( $file );
	$data     = json_decode( $contents, true );

	if ( ! is_array( $data ) || ! isset( $data['theme_mods'] ) || ! is_array( $data['theme_mods'] ) ) {
		wp_safe_redirect( add_query_arg( 'chuquipiondo_import', 'invalid', admin_url( 'admin.php?page=chuquipiondo-options' ) ) );
		exit;
	}

	// Remove every existing theme_mod first, then import.
	$existing = get_theme_mods();
	if ( is_array( $existing ) ) {
		foreach ( array_keys( $existing ) as $key ) {
			remove_theme_mod( $key );
		}
	}

	foreach ( $data['theme_mods'] as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	wp_safe_redirect( add_query_arg( 'chuquipiondo_import', 'success', admin_url( 'admin.php?page=chuquipiondo-options' ) ) );
	exit;
}
add_action( 'admin_post_chuquipiondo_import', 'chuquipiondo_handle_import' );

/**
 * Handle the reset-to-defaults action.
 */
function chuquipiondo_handle_reset() {
	if ( ! isset( $_POST['chuquipiondo_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['chuquipiondo_nonce'] ), 'chuquipiondo_reset' ) ) {
		wp_die( esc_html__( 'Nonce invalido.', 'chuquipiondo' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sin permisos.', 'chuquipiondo' ) );
	}

	$existing = get_theme_mods();
	if ( is_array( $existing ) ) {
		foreach ( array_keys( $existing ) as $key ) {
			remove_theme_mod( $key );
		}
	}

	// Apply the original preset defaults.
	chuquipiondo_apply_preset( 'original' );

	wp_safe_redirect( add_query_arg( 'chuquipiondo_reset', 'success', admin_url( 'admin.php?page=chuquipiondo-options' ) ) );
	exit;
}
add_action( 'admin_post_chuquipiondo_reset', 'chuquipiondo_handle_reset' );

/**
 * Handle the apply-preset action from the admin panel.
 */
function chuquipiondo_handle_apply_preset() {
	if ( ! isset( $_POST['chuquipiondo_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['chuquipiondo_nonce'] ), 'chuquipiondo_apply_preset' ) ) {
		wp_die( esc_html__( 'Nonce invalido.', 'chuquipiondo' ) );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sin permisos.', 'chuquipiondo' ) );
	}
	$preset = isset( $_POST['chuquipiondo_preset'] ) ? sanitize_key( $_POST['chuquipiondo_preset'] ) : '';
	if ( $preset ) {
		chuquipiondo_apply_preset( $preset );
	}
	wp_safe_redirect( add_query_arg( 'chuquipiondo_preset', 'applied', admin_url( 'admin.php?page=chuquipiondo-options' ) ) );
	exit;
}
add_action( 'admin_post_chuquipiondo_apply_preset', 'chuquipiondo_handle_apply_preset' );

/**
 * Show admin notices for import/export/reset results.
 */
function chuquipiondo_admin_notices() {
	if ( ! isset( $_GET['chuquipiondo_import'] ) && ! isset( $_GET['chuquipiondo_reset'] ) && ! isset( $_GET['chuquipiondo_preset'] ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'toplevel_page_chuquipiondo-options' !== $screen->id ) {
		return;
	}

	if ( isset( $_GET['chuquipiondo_import'] ) ) {
		$status = sanitize_key( $_GET['chuquipiondo_import'] );
		$msg = 'success' === $status ? __( 'Configuracion importada correctamente.', 'chuquipiondo' ) : __( 'Error al importar la configuracion.', 'chuquipiondo' );
		$class = 'success' === $status ? 'notice-success' : 'notice-error';
		echo '<div class="notice ' . esc_attr( $class ) . '"><p>' . esc_html( $msg ) . '</p></div>';
	}
	if ( isset( $_GET['chuquipiondo_reset'] ) && 'success' === $_GET['chuquipiondo_reset'] ) {
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Configuracion restablecida.', 'chuquipiondo' ) . '</p></div>';
	}
	if ( isset( $_GET['chuquipiondo_preset'] ) && 'applied' === $_GET['chuquipiondo_preset'] ) {
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Preset aplicado.', 'chuquipiondo' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'chuquipiondo_admin_notices' );
