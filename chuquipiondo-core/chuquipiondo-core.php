<?php
/**
 * Plugin Name:       CHUQUIPIONDO Core
 * Plugin URI:        https://www.chuquipiondo.com
 * Description:       Plugin core del tema CHUQUIPIONDO. Anade shortcodes, bloques Gutenberg, widgets adicionales, importador de demos y hooks personalizados. Funciona junto al tema CHUQUIPIONDO.
 * Version:           1.0.0
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

define( 'CHUQUIPIONDO_CORE_VERSION', '1.0.0' );
define( 'CHUQUIPIONDO_CORE_FILE', __FILE__ );
define( 'CHUQUIPIONDO_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'CHUQUIPIONDO_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'CHUQUIPIONDO_CORE_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Core plugin includes (modular, like the theme).
 */
require_once CHUQUIPIONDO_CORE_DIR . 'includes/class-chuquipiondo-core.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/shortcodes.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/blocks.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/widgets-extra.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/demo-importer.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/hooks.php';
require_once CHUQUIPIONDO_CORE_DIR . 'includes/admin.php';

/**
 * Initialize the core plugin.
 */
function chuquipiondo_core() {
	return Chuquipiondo_Core::instance();
}
add_action( 'plugins_loaded', 'chuquipiondo_core' );

/**
 * Activation hook: flush rewrite rules.
 */
function chuquipiondo_core_activate() {
	// Register CPTs and taxonomies from the theme if needed.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'chuquipiondo_core_activate' );

/**
 * Deactivation hook.
 */
function chuquipiondo_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'chuquipiondo_core_deactivate' );
