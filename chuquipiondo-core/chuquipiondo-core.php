<?php
/**
 * Plugin Name:       CHUQUIPIONDO Core
 * Plugin URI:        https://www.chuquipiondo.com
 * Description:       Plugin core del tema CHUQUIPIONDO. Anade shortcodes, bloques Gutenberg, widgets adicionales, importador de demos y hooks personalizados. Funciona junto al tema CHUQUIPIONDO.
 * Version:           1.12.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Nelson Chuquipiondo
 * Author URI:        https://www.chuquipiondo.com
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       chuquipiondo-core
 * Domain Path:       /languages
 *
 * @package CHUQUIPIONDO_Core
 * @author  Nelson Chuquipiondo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CHUQUIPIONDO_CORE_VERSION', '1.12.0' );
define( 'CHUQUIPIONDO_CORE_FILE', __FILE__ );
define( 'CHUQUIPIONDO_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'CHUQUIPIONDO_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'CHUQUIPIONDO_CORE_BASENAME', plugin_basename( __FILE__ ) );

require_once CHUQUIPIONDO_CORE_DIR . 'includes/class-chuquipiondo-core.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/shortcodes.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/blocks.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/widgets-extra.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/demo-importer.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/hooks.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/admin.php';

function chuquipiondo_core() {
	return Chuquipiondo_Core::instance();
}
add_action( 'plugins_loaded', 'chuquipiondo_core' );

/**
 * Upgrade routine: runs once per plugin version change (update).
 * Keeps rewrites and caches in sync with the new code.
 */
function chuquipiondo_core_upgrade_routine() {
	$stored = get_option( 'chuquipiondo_core_version' );
	if ( CHUQUIPIONDO_CORE_VERSION === $stored ) {
		return;
	}
	// Rewrite flush is deferred to init (after CPTs are registered): doing it
	// during plugins_loaded flushes rules WITHOUT the music CPT and breaks
	// permalinks until the next manual flush.
	add_action( 'init', 'flush_rewrite_rules', 99 );
	if ( function_exists( 'chuquipiondo_flush_dynamic_css_cache' ) ) {
		chuquipiondo_flush_dynamic_css_cache();
	}
	update_option( 'chuquipiondo_core_version', CHUQUIPIONDO_CORE_VERSION, false );
}
add_action( 'plugins_loaded', 'chuquipiondo_core_upgrade_routine', 20 );

function chuquipiondo_core_activate() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'chuquipiondo_core_activate' );

function chuquipiondo_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'chuquipiondo_core_deactivate' );
