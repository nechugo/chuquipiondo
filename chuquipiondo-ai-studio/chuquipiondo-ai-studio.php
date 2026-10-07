<?php
/**
 * Plugin Name:       CHUQUIPIONDO AI Studio
 * Plugin URI:        https://www.chuquipiondo.com
 * Description:        Estudio de IA para editar Entradas y Paginas con IA: mejora textos, parrafos, SEO, etiquetas, anade HTML/PHP/JS, gestiona imagenes por defecto a 500px de alto x 900px de ancho y publica nuevos articulos optimizados. Compatible con multiples temas (especialmente Astra) y libre de conflictos.
 * Version:           1.11.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Nelson Chuquipiondo
 * Author URI:        https://www.chuquipiondo.com
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       chuquipiondo-ai
 * Domain Path:       /languages
 *
 * @package CHUQUIPIONDO_AI
 * @author  Nelson Chuquipiondo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CHUQUIPIONDO_AI_VERSION', '1.11.0' );
define( 'CHUQUIPIONDO_AI_FILE', __FILE__ );
define( 'CHUQUIPIONDO_AI_DIR', plugin_dir_path( __FILE__ ) );
define( 'CHUQUIPIONDO_AI_URL', plugin_dir_url( __FILE__ ) );
define( 'CHUQUIPIONDO_AI_BASENAME', plugin_basename( __FILE__ ) );

// Idempotent loading: if a stale copy of the plugin is already in memory
// (update without deactivating), do not redeclare anything.
if ( ! function_exists( 'chuquipiondo_ai_get_option' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/helpers.php';
}
if ( ! function_exists( 'chuquipiondo_ai_defaults' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/defaults.php';
}
if ( ! function_exists( 'chuquipiondo_ai_secret_key' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/secret-hardening.php';
}
if ( ! class_exists( 'Chuquipiondo_AI_Client' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/class-ai-client.php';
}
if ( ! class_exists( 'Chuquipiondo_AI' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/class-chuquipiondo-ai.php';
}
if ( ! function_exists( 'chuquipiondo_ai_sanitize_content' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/content-service.php';
}
if ( ! function_exists( 'chuquipiondo_ai_generate_image' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/image-service.php';
}
if ( ! class_exists( 'Chuquipiondo_AI_Publish_Service' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/publish-service.php';
}
if ( ! function_exists( 'chuquipiondo_ai_queue_batch' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/queue-service.php';
}
if ( ! function_exists( 'chuquipiondo_ai_read_site' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/site-reader.php';
}
if ( ! function_exists( 'chuquipiondo_ai_register_settings' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/settings.php';
}
if ( ! function_exists( 'chuquipiondo_ai_admin_menu' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/admin.php';
}
if ( ! function_exists( 'chuquipiondo_ai_admin_assets' ) ) {
	require_once CHUQUIPIONDO_AI_DIR . 'includes/assets.php';
}

function chuquipiondo_ai_activate() {
	$defaults = chuquipiondo_ai_defaults();
	foreach ( $defaults as $key => $value ) {
		if ( false === get_option( $key ) ) {
			add_option( $key, $value );
		}
	}
	update_option( 'chuquipiondo_ai_version', CHUQUIPIONDO_AI_VERSION, false );
	set_transient( 'chuquipiondo_ai_just_activated', '1', 60 );
}
register_activation_hook( __FILE__, 'chuquipiondo_ai_activate' );

/**
 * On deactivation: no data cleanup needed (options survive for reactivation).
 */
function chuquipiondo_ai_deactivate() {
	// Intentionally empty: AI Studio writes no caches and hooks nothing
	// persistent in the front-end; deactivation is instantly clean.
}
register_deactivation_hook( __FILE__, 'chuquipiondo_ai_deactivate' );

function chuquipiondo_ai() {
	return Chuquipiondo_AI::instance();
}
add_action( 'plugins_loaded', 'chuquipiondo_ai' );

/**
 * Upgrade routine: runs once per plugin version change (update).
 * Seeds new defaults without overwriting user values.
 */
function chuquipiondo_ai_upgrade_routine() {
	$stored = get_option( 'chuquipiondo_ai_version' );
	if ( CHUQUIPIONDO_AI_VERSION === $stored ) {
		return;
	}
	$defaults = chuquipiondo_ai_defaults();
	foreach ( $defaults as $key => $value ) {
		if ( false === get_option( $key ) ) {
			add_option( $key, $value );
		}
	}
	update_option( 'chuquipiondo_ai_version', CHUQUIPIONDO_AI_VERSION, false );
}
add_action( 'plugins_loaded', 'chuquipiondo_ai_upgrade_routine', 20 );
