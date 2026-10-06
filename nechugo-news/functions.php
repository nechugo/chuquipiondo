<?php
/**
 * nechugo News functions.
 *
 * Este archivo permanece LIMPIO y MODULAR: solo constantes
 * y require_once. Toda la logica vive en /inc/.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'NECHUGO_NEWS_VERSION', '1.4.0' );
define( 'NECHUGO_NEWS_DIR', get_template_directory() );
define( 'NECHUGO_NEWS_URI', get_template_directory_uri() );

require_once NECHUGO_NEWS_DIR . '/inc/helpers.php';
require_once NECHUGO_NEWS_DIR . '/inc/sanitize.php';
require_once NECHUGO_NEWS_DIR . '/inc/defaults.php';
require_once NECHUGO_NEWS_DIR . '/inc/setup.php';
require_once NECHUGO_NEWS_DIR . '/inc/enqueue.php';
require_once NECHUGO_NEWS_DIR . '/inc/widgets.php';
require_once NECHUGO_NEWS_DIR . '/inc/template-tags.php';
require_once NECHUGO_NEWS_DIR . '/inc/schema.php';
require_once NECHUGO_NEWS_DIR . '/inc/images.php';
require_once NECHUGO_NEWS_DIR . '/inc/compatibility.php';
require_once NECHUGO_NEWS_DIR . '/inc/customizer/defaults.php';
require_once NECHUGO_NEWS_DIR . '/inc/customizer/register.php';
require_once NECHUGO_NEWS_DIR . '/inc/customizer/css.php';
require_once NECHUGO_NEWS_DIR . '/inc/customizer/preview.php';
require_once NECHUGO_NEWS_DIR . '/inc/header.php';
require_once NECHUGO_NEWS_DIR . '/inc/footer.php';
require_once NECHUGO_NEWS_DIR . '/inc/ads.php';
require_once NECHUGO_NEWS_DIR . '/inc/extras.php';
require_once NECHUGO_NEWS_DIR . '/inc/legacy.php';
