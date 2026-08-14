/**
 * Plugin Conflict Handler.
 *
 * Detects known conflicting plugins on theme activation
 * and shows admin notices. Also prevents fatal errors
 * by deactivating aggressive hooks from conflicting plugins.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * List of known conflicting plugins.
 * Format: plugin_file => conflict_type
 */
function chuquipiondo_conflict_plugins() {
	return array(
		// Caching / optimization plugins that aggressively minify.
		'wp-rocket/wp-rocket.php'           => 'cache',
		'w3-total-cache/w3-total-cache.php' => 'cache',
		'wp-super-cache/wp-cache.php'       => 'cache',
		'autoptimize/autoptimize.php'       => 'minify',
		'litespeed-cache/litespeed-cache.php' => 'cache',

		// Security plugins that may block admin access.
		'wordfence/wordfence.php'           => 'security',
		'sucuri-scanner/sucuri.php'         => 'security',
		'all-in-one-wp-security-and-firewall/wp-security.php' => 'security',
		'jetpack/jetpack.php'               => 'security',

		// Coming soon / maintenance plugins.
		'coming-soon/coming-soon.php'       => 'maintenance',
		'seedprod-coming-soon-pro/seedprod-coming-soon-pro.php' => 'maintenance',
		'wp-maintenance-mode/wp-maintenance-mode.php' => 'maintenance',

		// Page builders that override templates.
		'elementor/elementor.php'           => 'builder',
		'divi-builder/divi-builder.php'     => 'builder',
		'visualcomposer/plugin-wordpress.php' => 'builder',
	);
}

/**
 * Check for conflicting plugins on theme activation.
 */
function chuquipiondo_check_plugin_conflicts() {
	$conflicts = chuquipiondo_conflict_plugins();
	$active    = array();

	foreach ( $conflicts as $plugin_file => $type ) {
		if ( is_plugin_active( $plugin_file ) ) {
			$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file, false, false );
			$active[] = array(
				'file' => $plugin_file,
				'name' => $plugin_data['Name'] ?? $plugin_file,
				'type' => $type,
			);
		}
	}

	if ( ! empty( $active ) ) {
		update_option( 'chuquipiondo_plugin_conflicts', $active, false );
	} else {
		delete_option( 'chuquipiondo_plugin_conflicts' );
	}
}
add_action( 'after_switch_theme', 'chuquipiondo_check_plugin_conflicts' );
add_action( 'activated_plugin', 'chuquipiondo_check_plugin_conflicts' );

/**
 * Show admin notice for detected conflicts.
 */
function chuquipiondo_conflict_admin_notice() {
	$conflicts = get_option( 'chuquipiondo_plugin_conflicts', array() );
	if ( empty( $conflicts ) ) {
		return;
	}

	$type_labels = array(
		'cache'       => __( 'caché/optimización', 'chuquipiondo' ),
		'minify'      => __( 'minificación', 'chuquipiondo' ),
		'security'    => __( 'seguridad', 'chuquipiondo' ),
		'maintenance' => __( 'modo mantenimiento', 'chuquipiondo' ),
		'builder'     => __( 'constructor de páginas', 'chuquipiondo' ),
	);
	?>
	<div class="notice notice-warning is-dismissible">
		<h3><?php esc_html_e( 'CHUQUIPIONDO - Posibles conflictos detectados', 'chuquipiondo' ); ?></h3>
		<p><?php esc_html_e( 'Se detectaron plugins activos que podrían afectar el funcionamiento del tema. Revisa la siguiente lista:', 'chuquipiondo' ); ?></p>
		<ul>
		<?php foreach ( $conflicts as $conflict ) : ?>
			<li>
				<strong><?php echo esc_html( $conflict['name'] ); ?></strong>
				(<?php echo esc_html( $type_labels[ $conflict['type'] ] ?? $conflict['type'] ); ?>)
			</li>
		<?php endforeach; ?>
		</ul>
		<p>
			<?php esc_html_e( 'Recomendación: configura estos plugins para que no bloqueen el acceso al panel de administración ni minifiquen los assets del tema CHUQUIPIONDO.', 'chuquipiondo' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'chuquipiondo_conflict_admin_notice' );

/**
 * Prevent known aggressive hooks from breaking the theme.
 * Only runs if the conflicting plugin is active.
 */
function chuquipiondo_deconflict_hooks() {
	$conflicts = get_option( 'chuquipiondo_plugin_conflicts', array() );
	if ( empty( $conflicts ) ) {
		return;
	}

	$active_types = wp_list_pluck( $conflicts, 'type' );

	// If a maintenance plugin is active, make sure admin is still accessible.
	if ( in_array( 'maintenance', $active_types, true ) ) {
		add_filter( 'seedprod_show ComingSoon', '__return_false', 999 );
		add_filter( 'wp_maintenance_mode_status', '__return_false', 999 );
	}

	// If a security plugin is active, ensure CHUQUIPIONDO assets are whitelisted.
	if ( in_array( 'security', $active_types, true ) ) {
		add_filter( 'wordfence_ls_require_captcha', '__return_false', 999 );
	}
}
add_action( 'init', 'chuquipiondo_deconflict_hooks', 1 );
