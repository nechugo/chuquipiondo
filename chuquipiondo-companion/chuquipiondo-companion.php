<?php
/**
 * Plugin Name:       CHUQUIPIONDO Companion
 * Plugin URI:        https://www.chuquipiondo.com
 * Description:       Plugin companion del tema CHUQUIPIONDO (estilo Astra Pro). Anade Header Builder, Footer Builder, Mega Menu, modulos de blog/revista pro, sistema de ads avanzado y starter sites importables one-click. Requiere el tema CHUQUIPIONDO.
 * Version:           1.11.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Nelson Chuquipiondo
 * Author URI:        https://www.chuquipiondo.com
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       chuquipiondo-companion
 * Domain Path:       /languages
 *
 * @package CHUQUIPIONDO_Companion
 * @author  Nelson Chuquipiondo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CHUQUIPIONDO_COMPANION_VERSION', '1.11.0' );
define( 'CHUQUIPIONDO_COMPANION_FILE', __FILE__ );
define( 'CHUQUIPIONDO_COMPANION_DIR', plugin_dir_path( __FILE__ ) );
define( 'CHUQUIPIONDO_COMPANION_URL', plugin_dir_url( __FILE__ ) );
define( 'CHUQUIPIONDO_COMPANION_BASENAME', plugin_basename( __FILE__ ) );

require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/helpers.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/defaults.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/class-chuquipiondo-companion.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/settings.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/admin.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/assets.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/modules/header-builder.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/modules/footer-builder.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/modules/mega-menu.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/modules/blog-pro.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/modules/ads-pro.php';
require_once CHUQUIPIONDO_COMPANION_DIR . 'includes/modules/starter-sites.php';

function chuquipiondo_companion_activate() {
	$defaults = chuquipiondo_companion_defaults();
	foreach ( $defaults as $key => $value ) {
		if ( false === get_option( $key ) ) {
			add_option( $key, $value );
		}
	}
	update_option( 'chuquipiondo_companion_version', CHUQUIPIONDO_COMPANION_VERSION, false );
	set_transient( 'chuquipiondo_companion_just_activated', '1', 60 );
}
register_activation_hook( __FILE__, 'chuquipiondo_companion_activate' );

function chuquipiondo_companion() {
	return Chuquipiondo_Companion::instance();
}
add_action( 'plugins_loaded', 'chuquipiondo_companion' );
