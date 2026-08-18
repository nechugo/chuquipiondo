<?php
/**
 * Plugin Name:       CHUQUIPIONDO AI Studio
 * Plugin URI:        https://www.chuquipiondo.com
 * Description:        Estudio de IA para editar Entradas y Paginas con IA: mejora textos, parrafos, SEO, etiquetas, anade HTML/PHP/JS, gestiona imagenes por defecto a 500px de alto x 900px de ancho y publica nuevos articulos optimizados. Compatible con multiples temas (especialmente Astra) y libre de conflictos.
 * Version:           1.0.0
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

/**
 * Plugin version, reused for cache-busting assets.
 */
define( 'CHUQUIPIONDO_AI_VERSION', '1.0.0' );
define( 'CHUQUIPIONDO_AI_FILE', __FILE__ );
define( 'CHUQUIPIONDO_AI_DIR', plugin_dir_path( __FILE__ ) );
define( 'CHUQUIPIONDO_AI_URL', plugin_dir_url( __FILE__ ) );
define( 'CHUQUIPIONDO_AI_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Includes (modular, ordered like the companion plugin).
 *
 * Order matters: helpers first, then defaults, then the main class,
 * then the AI client, content/image/publish services, admin UI and assets.
 */
require_once CHUQUIPIONDO_AI_DIR . 'includes/helpers.php';
require_once CHUQUIPIONDO_AI_DIR . 'includes/defaults.php';
require_once CHUQUIPIONDO_AI_DIR . 'includes/class-chuquipiondo-ai.php';
require_once CHUQUIPIONDO_AI_DIR . 'includes/class-ai-client.php';
require_once CHUQUIPIONDO_AI_DIR . 'includes/content-service.php';
require_once CHUQUIPIONDO_AI_DIR . 'includes/image-service.php';
require_once CHUQUIPIONDO_AI_DIR . 'includes/publish-service.php';
require_once CHUQUIPIONDO_AI_DIR . 'includes/settings.php';
require_once CHUQUIPIONDO_AI_DIR . 'includes/admin.php';
require_once CHUQUIPIONDO_AI_DIR . 'includes/assets.php';

/**
 * Activation hook: register default options and flag first run.
 */
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
 * Boot the plugin.
 *
 * @return Chuquipiondo_AI
 */
function chuquipiondo_ai() {
	return Chuquipiondo_AI::instance();
}
add_action( 'plugins_loaded', 'chuquipiondo_ai' );
