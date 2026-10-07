<?php
/**
 * Encolado de assets. Ligero: un solo CSS, un JS minimo,
 * versionado para cache-busting.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Estilos y scripts del front-end.
 */
function nechugo_enqueue_assets() {
	wp_enqueue_style(
		'nechugo-news-style',
		get_stylesheet_uri(),
		array(),
		NECHUGO_NEWS_VERSION
	);

	wp_enqueue_style(
		'nechugo-news-main',
		NECHUGO_NEWS_URI . '/assets/css/main.css',
		array( 'nechugo-news-style' ),
		NECHUGO_NEWS_VERSION
	);

	// CSS dinamico generado por el personalizador (colores, fuentes, anchos).
	wp_add_inline_style( 'nechugo-news-main', nechugo_customizer_css() );

	wp_enqueue_script(
		'nechugo-news-js',
		NECHUGO_NEWS_URI . '/assets/js/main.js',
		array(),
		NECHUGO_NEWS_VERSION,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'nechugo_enqueue_assets' );

/**
 * Precarga de la tipografia del sistema si se elige una fuente Google.
 */
function nechugo_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && 'system' !== nechugo_get_option( 'body_font_family' ) ) {
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'nechugo_resource_hints', 10, 2 );

/**
 * Optimizaciones de velocidad: preconnect a Google Fonts y
 * eliminacion de emojis ya gestionada por la opcion de rendimiento.
 */
function nechugo_speed_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'nechugo_speed_resource_hints', 10, 2 );
