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

/**
 * Entradas por pagina segun columnas: 5 por columna (2 cols = 10, 3 = 15, 4 = 20).
 * Modo "columns" automatico o "manual" con cantidad fija.
 *
 * @param WP_Query $query Query.
 */
function nechugo_posts_per_page( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( ! $query->is_home() && ! $query->is_archive() && ! $query->is_search() ) {
		return;
	}
	$mode = nechugo_get_option( 'posts_per_page_mode' );
	if ( 'manual' === $mode ) {
		$query->set( 'posts_per_page', (int) nechugo_get_option( 'posts_per_page' ) );
		return;
	}
	$columns = max( 1, min( 4, (int) nechugo_get_option( 'blog_columns' ) ) );
	$query->set( 'posts_per_page', $columns * 5 );
}
add_action( 'pre_get_posts', 'nechugo_posts_per_page' );
