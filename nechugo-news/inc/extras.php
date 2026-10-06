<?php
/**
 * Funciones varias: excerpt, back-to-top, buscador, etc.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Longitud del extracto configurable.
 *
 * @param int $length Por defecto.
 * @return int
 */
function nechugo_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}
	return (int) nechugo_get_option( 'excerpt_length' );
}
add_filter( 'excerpt_length', 'nechugo_excerpt_length', 999 );

/**
 * Sufijo del extracto.
 *
 * @param string $more Por defecto.
 * @return string
 */
function nechugo_excerpt_more( $more ) {
	if ( is_admin() ) {
		return $more;
	}
	return '&hellip;';
}
add_filter( 'excerpt_more', 'nechugo_excerpt_more' );

/**
 * Boton volver arriba.
 */
function nechugo_back_to_top() {
	if ( ! nechugo_is_enabled( 'back_to_top_enable' ) ) {
		return;
	}
	echo '<button class="nechugo-back-top" aria-label="' . esc_attr__( 'Volver arriba', 'nechugo-news' ) . '">&uarr;</button>';
}
add_action( 'wp_footer', 'nechugo_back_to_top' );

/**
 * Atributo defer en scripts del tema para velocidad.
 *
 * @param string $tag    Tag script.
 * @param string $handle Handle.
 * @return string
 */
function nechugo_defer_scripts( $tag, $handle ) {
	if ( 'nechugo-news-js' === $handle ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'nechugo_defer_scripts', 10, 2 );
