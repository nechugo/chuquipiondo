<?php
/**
 * Logica del encabezado: Google Fonts si aplica y carga de plantilla.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Encola Google Fonts solo si el usuario elige una (liviano por defecto).
 */
function nechugo_google_fonts() {
	$body = nechugo_get_option( 'body_font_family' );
	$head = nechugo_get_option( 'heading_font_family' );
	if ( 'system' === $body && 'system' === $head ) {
		return;
	}
	$families = array();
	if ( 'system' !== $body ) {
		$families[] = nechugo_google_font_name( $body );
	}
	if ( 'system' !== $head && $head !== $body ) {
		$families[] = nechugo_google_font_name( $head );
	}
	if ( empty( $families ) ) {
		return;
	}
	wp_enqueue_style(
		'nechugo-google-fonts',
		'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $families ) . '&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'nechugo_google_fonts', 5 );

/**
 * Nombre de fuente para Google Fonts.
 *
 * @param string $key Clave.
 * @return string
 */
function nechugo_google_font_name( $key ) {
	$map = array(
		'inter'        => 'Inter:wght@400;700',
		'roboto'       => 'Roboto:wght@400;700',
		'open-sans'    => 'Open+Sans:wght@400;700',
		'lato'         => 'Lato:wght@400;700',
		'montserrat'   => 'Montserrat:wght@400;700',
		'oswald'       => 'Oswald:wght@400;600',
		'merriweather' => 'Merriweather:wght@400;700',
		'playfair'     => 'Playfair+Display:wght@400;700',
	);
	return isset( $map[ $key ] ) ? $map[ $key ] : 'Inter:wght@400;700';
}
