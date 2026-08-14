<?php
/**
 * CHUQUIPIONDO Theme functions.
 *
 * Golden Rule: this file stays CLEAN and MODULAR.
 * It contains ONLY require_once statements. All logic
 * lives inside the files under /inc/.
 *
 * @package CHUQUIPIONDO
 * @author  Nelson Chuquipiondo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

/**
 * Theme version, reused for cache-busting assets.
 */
define( 'CHUQUIPIONDO_VERSION', '1.5.0' );
define( 'CHUQUIPIONDO_DIR', get_template_directory() );
define( 'CHUQUIPONDO_URI', get_template_directory_uri() );

require_once CHUQUIPIONDO_DIR . '/inc/helpers.php';
require_once CHUQUIPIONDO_DIR . '/inc/sanitize.php';
require_once CHUQUIPIONDO_DIR . '/inc/defaults.php';
require_once CHUQUIPIONDO_DIR . '/inc/fonts.php';
require_once CHUQUIPIONDO_DIR . '/inc/icons.php';
require_once CHUQUIPIONDO_DIR . '/inc/setup.php';
require_once CHUQUIPIONDO_DIR . '/inc/enqueue.php';
require_once CHUQUIPIONDO_DIR . '/inc/widgets.php';
require_once CHUQUIPIONDO_DIR . '/inc/sidebar.php';
require_once CHUQUIPIONDO_DIR . '/inc/meta-layout.php';
require_once CHUQUIPIONDO_DIR . '/inc/template-tags.php';
require_once CHUQUIPIONDO_DIR . '/inc/breadcrumbs.php';
require_once CHUQUIPIONDO_DIR . '/inc/schema.php';

// Customizer (defaults -> presets -> css -> sections).
require_once CHUQUIPIONDO_DIR . '/inc/customizer/presets.php';
require_once CHUQUIPIONDO_DIR . '/inc/customizer/defaults.php';
require_once CHUQUIPIONDO_DIR . '/inc/customizer/controls.php';
require_once CHUQUIPIONDO_DIR . '/inc/customizer/panels.php';
require_once CHUQUIPIONDO_DIR . '/inc/customizer/register.php';
require_once CHUQUIPIONDO_DIR . '/inc/customizer/css.php';
require_once CHUQUIPIONDO_DIR . '/inc/customizer/preview.php';

// Header system (3 headers with multiuse boxes).
require_once CHUQUIPIONDO_DIR . '/inc/preheader.php';
require_once CHUQUIPIONDO_DIR . '/inc/header.php';

// Hero / Slider system.
require_once CHUQUIPIONDO_DIR . '/inc/hero/hero.php';

// Home builder modules.
require_once CHUQUIPIONDO_DIR . '/inc/home.php';

// Blog & Single layouts.
require_once CHUQUIPIONDO_DIR . '/inc/blog.php';
require_once CHUQUIPIONDO_DIR . '/inc/single.php';

// Advertising system.
require_once CHUQUIPIONDO_DIR . '/inc/ads/slots.php';
require_once CHUQUIPIONDO_DIR . '/inc/ads/ads.php';

// Social share + WhatsApp float.
require_once CHUQUIPIONDO_DIR . '/inc/social/share.php';
require_once CHUQUIPIONDO_DIR . '/inc/social/whatsapp.php';

// Music CPT + player.
require_once CHUQUIPIONDO_DIR . '/inc/music/cpt.php';
require_once CHUQUIPIONDO_DIR . '/inc/music/meta-boxes.php';
require_once CHUQUIPIONDO_DIR . '/inc/music/player.php';

// Advanced options panel.
require_once CHUQUIPIONDO_DIR . '/inc/welcome.php';
require_once CHUQUIPIONDO_DIR . '/inc/admin-panel.php';

// Import / Export / Reset.
require_once CHUQUIPIONDO_DIR . '/inc/ie-port.php';
require_once CHUQUIPIONDO_DIR . '/inc/security.php';
require_once CHUQUIPIONDO_DIR . '/inc/tgmpa/recommended-plugins.php';

// Plugin conflict detection & handling.
require_once CHUQUIPIONDO_DIR . '/inc/plugin-conflict-handler.php';
