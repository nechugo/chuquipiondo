<?php
/**
 * Live preview del personalizador (postMessage).
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Script de preview en el personalizador.
 */
function nechugo_customize_preview_js() {
	wp_enqueue_script(
		'nechugo-news-customizer',
		NECHUGO_NEWS_URI . '/assets/js/customizer.js',
		array( 'customize-preview' ),
		NECHUGO_NEWS_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'nechugo_customize_preview_js' );
