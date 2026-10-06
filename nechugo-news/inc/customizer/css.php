<?php
/**
 * CSS dinamico del personalizador (colores, fuentes, anchos, presets).
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Genera el CSS inline desde las opciones.
 *
 * @return string
 */
function nechugo_customizer_css() {
	$css = '';

	// Presets multicolor: si hay preset activo distinto de custom, sobreescribe colores.
	$preset = nechugo_get_option( 'color_preset' );
	$presets = nechugo_color_presets();
	$p = isset( $presets[ $preset ] ) ? $presets[ $preset ] : null;

	$primary    = $p ? $p['primary'] : nechugo_get_option( 'primary_color' );
	$secondary  = $p ? $p['secondary'] : nechugo_get_option( 'secondary_color' );
	$accent     = $p ? $p['accent'] : nechugo_get_option( 'accent_color' );
	$background = nechugo_get_option( 'background_color' );
	$text       = nechugo_get_option( 'text_color' );
	$header_bg  = nechugo_get_option( 'header_bg_color' );
	$header_tx  = nechugo_get_option( 'header_text_color' );
	$footer_bg  = nechugo_get_option( 'footer_bg_color' );
	$footer_tx  = nechugo_get_option( 'footer_text_color' );

	$css .= ':root{';
	$css .= '--nn-primary:' . sanitize_hex_color( $primary ) . ';';
	$css .= '--nn-secondary:' . sanitize_hex_color( $secondary ) . ';';
	$css .= '--nn-accent:' . sanitize_hex_color( $accent ) . ';';
	$css .= '--nn-bg:' . sanitize_hex_color( $background ) . ';';
	$css .= '--nn-text:' . sanitize_hex_color( $text ) . ';';
	$css .= '--nn-header-bg:' . sanitize_hex_color( $header_bg ) . ';';
	$css .= '--nn-header-text:' . sanitize_hex_color( $header_tx ) . ';';
	$css .= '--nn-footer-bg:' . sanitize_hex_color( $footer_bg ) . ';';
	$css .= '--nn-footer-text:' . sanitize_hex_color( $footer_tx ) . ';';
	$css .= '}';

	// Tipografia: articulos 12px, H1 16px por defecto.
	$body_font = nechugo_font_stack( nechugo_get_option( 'body_font_family' ) );
	$head_font = nechugo_font_stack( nechugo_get_option( 'heading_font_family' ) );
	$css .= 'body{font-family:' . $body_font . ';}';
	$css .= 'h1,h2,h3,h4,h5,h6,.site-title{font-family:' . $head_font . ';}';

	$body_size  = max( 10, (int) nechugo_get_option( 'body_font_size' ) );
	$h1_size    = max( 14, (int) nechugo_get_option( 'h1_font_size' ) );
	$line       = (int) nechugo_get_option( 'body_line_height' ) / 100;
	$css        .= '.entry-content{font-size:' . $body_size . 'px;line-height:' . $line . ';}';
	$css        .= '.entry-content h1{font-size:' . $h1_size . 'px;}';
	// Escala relativa del resto de encabezados dentro del articulo.
	$css        .= '.entry-content h2{font-size:' . (int) ( $h1_size * 1.0 ) . 'px;}';

	// Ancho del contenedor del blog (minimo 1200).
	$width = (int) nechugo_get_option( 'content_width' );
	$width = max( 1200, $width );
	$css  .= '.nechugo-container{max-width:' . $width . 'px;}';

	return nechugo_minify_css( $css );
}

/**
 * Presets multicolor.
 *
 * @return array
 */
function nechugo_color_presets() {
	return array(
		'blue'   => array( 'primary' => '#1a73e8', 'secondary' => '#e8453c', 'accent' => '#fbbc04' ),
		'red'    => array( 'primary' => '#d93025', 'secondary' => '#1a73e8', 'accent' => '#fbbc04' ),
		'green'  => array( 'primary' => '#188038', 'secondary' => '#e8453c', 'accent' => '#fbbc04' ),
		'orange' => array( 'primary' => '#e8710a', 'secondary' => '#1a73e8', 'accent' => '#188038' ),
		'purple' => array( 'primary' => '#7627bb', 'secondary' => '#e8453c', 'accent' => '#fbbc04' ),
		'teal'   => array( 'primary' => '#0b8043', 'secondary' => '#d93025', 'accent' => '#fbbc04' ),
		'dark'   => array( 'primary' => '#202124', 'secondary' => '#8ab4f8', 'accent' => '#fbbc04' ),
	);
}

/**
 * Stack de fuentes por clave.
 *
 * @param string $key Clave de la fuente.
 * @return string
 */
function nechugo_font_stack( $key ) {
	$stacks = array(
		'system'       => "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif",
		'inter'        => "'Inter', sans-serif",
		'roboto'       => "'Roboto', sans-serif",
		'open-sans'    => "'Open Sans', sans-serif",
		'lato'         => "'Lato', sans-serif",
		'montserrat'   => "'Montserrat', sans-serif",
		'oswald'       => "'Oswald', sans-serif",
		'merriweather' => "'Merriweather', serif",
		'playfair'     => "'Playfair Display', serif",
	);
	return isset( $stacks[ $key ] ) ? $stacks[ $key ] : $stacks['system'];
}

/**
 * Minificado basico de CSS.
 *
 * @param string $css CSS entrante.
 * @return string
 */
function nechugo_minify_css( $css ) {
	$css = preg_replace( '/\s+/', ' ', $css );
	$css = str_replace( array( '; ', ': ', ' {', '{ ', ' }' ), array( ';', ':', '{', '{', '}' ), $css );
	return trim( $css );
}
