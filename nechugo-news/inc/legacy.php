<?php
/**
 * Compatibilidad con entradas creadas con otras plantillas.
 *
 * Principios:
 * 1. El tema NUNCA escribe en la base de datos de entradas: solo
 *    renderiza. Los filtros (como el anuncio in-article) se aplican
 *    en ejecucion y no modifican el contenido guardado.
 * 2. Contenido creado con otros temas (aligncenter, wp-caption,
 *    galerias, tablas, shortcodes de plugins) se adapta al estilo
 *    de nechugo News automaticamente.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Limpieza al desinstalar el tema: borra SOLO las theme_mods propias.
 * Las entradas, paginas, categorias y medios NO se tocan; WordPress
 * mantiene el contenido en su naturaleza original.
 */
function nechugo_clean_theme_mods( $stylesheet ) {
	if ( 'nechugo-news' !== $stylesheet ) {
		return;
	}
	remove_theme_mods();
}
add_action( 'delete_theme', 'nechugo_clean_theme_mods' );

/**
 * Asegura que los shortcodes de otros plugins sigan funcionando
 * dentro del extracto de las cards (compatibilidad total de plugins).
 *
 * @param string $excerpt Extracto.
 * @return string
 */
function nechugo_excerpt_shortcodes( $excerpt ) {
	if ( is_admin() ) {
		return $excerpt;
	}
	return do_shortcode( $excerpt );
}
add_filter( 'the_excerpt', 'nechugo_excerpt_shortcodes', 12 );
