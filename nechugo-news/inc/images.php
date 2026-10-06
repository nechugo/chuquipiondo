<?php
/**
 * Imagenes: calidad y tamaños por defecto.
 *
 * Caja por defecto de cada entrada: 900x520 (recorte centrado).
 * Se aumenta la calidad de compresion para no perder nitidez.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calidad de compresion alta (menos perdida de calidad).
 *
 * @param int $quality Calidad por defecto (10-100).
 * @return int
 */
function nechugo_image_quality( $quality ) {
	return 92;
}
add_filter( 'jpeg_quality', 'nechugo_image_quality' );
add_filter( 'wp_editor_set_quality', 'nechugo_image_quality' );
