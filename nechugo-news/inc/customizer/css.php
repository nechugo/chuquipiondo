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
	$h1_weight  = (int) nechugo_get_option( 'h1_font_weight' );
	$line       = (int) nechugo_get_option( 'body_line_height' ) / 100;
	$css        .= 'body{font-size:' . $body_size . 'px;line-height:' . $line . ';}';
	$css        .= 'h1{font-size:' . $h1_size . 'px;font-weight:' . $h1_weight . ';}';
	$css        .= '.entry-content{font-size:' . $body_size . 'px;line-height:' . $line . ';}';
	$css        .= '.entry-content h1{font-size:' . $h1_size . 'px;font-weight:' . $h1_weight . ';}';
	$css        .= '.entry-title{font-size:' . $h1_size . 'px;font-weight:' . $h1_weight . ';}';

	// Footer personalizable: colores, tamano y fuente propios.
	$foot_heading = nechugo_get_option( 'footer_heading_color' );
	$foot_link    = nechugo_get_option( 'footer_link_color' );
	$foot_size    = max( 11, (int) nechugo_get_option( 'footer_font_size' ) );
	$foot_font    = nechugo_font_stack( nechugo_get_option( 'footer_font_family' ) );
	$css         .= '.site-footer{font-family:' . $foot_font . ';font-size:' . $foot_size . 'px;}';
	$css         .= '.site-footer .widget-title,.site-footer h2,.site-footer .site-title-footer{color:' . sanitize_hex_color( $foot_heading ) . ';}';
	$css         .= '.site-footer a{color:' . sanitize_hex_color( $foot_link ) . ';}';
	if ( ! nechugo_is_enabled( 'footer_border_enable' ) ) {
		$css .= '.nechugo-footer-bottom{border-top:0;}';
	}

	// Ancho del contenedor del blog (minimo 1200).
	$width = (int) nechugo_get_option( 'content_width' );
	$width = max( 1200, $width );
	$css  .= '.nechugo-container{max-width:' . $width . 'px;}';

	// ============ Header personalizable ============
	$header_h   = max( 48, (int) nechugo_get_option( 'header_height' ) );
	$h_font     = nechugo_font_stack( nechugo_get_option( 'header_font_family' ) );
	$h_size     = max( 10, (int) nechugo_get_option( 'header_font_size' ) );
	$menu_size  = max( 10, (int) nechugo_get_option( 'header_menu_size' ) );
	$css       .= ':root{--nn-header-h:' . $header_h . 'px;}';
	$css       .= '.site-header{font-family:' . $h_font . ';font-size:' . $h_size . 'px;}';
	$css       .= '.header__inner{min-height:' . $header_h . 'px;}';
	$css       .= '.nechugo-nav a{font-size:' . $menu_size . 'px;}';
	$menu_color = nechugo_get_option( 'header_menu_color' );
	if ( ! empty( $menu_color ) ) {
		$css .= '.nechugo-nav > .nechugo-menu > li > a{color:' . sanitize_hex_color( $menu_color ) . ';}';
	}
	$hover_color = nechugo_get_option( 'header_menu_hover_color' );
	if ( ! empty( $hover_color ) ) {
		$css .= '.nechugo-nav a:hover,.nechugo-nav .current-menu-item > a{color:' . sanitize_hex_color( $hover_color ) . ';}';
		$css .= '.nechugo-nav a::after{background:' . sanitize_hex_color( $hover_color ) . ';}';
	}
	$css .= '.nechugo-nav ul ul{background:' . sanitize_hex_color( nechugo_get_option( 'header_submenu_bg' ) ) . ';}';
	$css .= '.nechugo-nav ul ul a{color:' . sanitize_hex_color( nechugo_get_option( 'header_submenu_color' ) ) . ';}';
	$css .= '.nechugo-nav ul ul a:hover{background:' . sanitize_hex_color( nechugo_get_option( 'header_submenu_hover_bg' ) ) . ';}';

	// Menu tipografia elegante.
	$m_font = nechugo_font_stack( nechugo_get_option( 'menu_font_family' ) );
	$css  .= '.nechugo-nav{font-family:' . $m_font . ';}';
	if ( nechugo_is_enabled( 'menu_uppercase' ) ) {
		$css .= '.nechugo-nav a{text-transform:uppercase;}';
	} else {
		$css .= '.nechugo-nav a{text-transform:none;}';
	}
	$css .= '.nechugo-nav a{letter-spacing:' . (int) nechugo_get_option( 'menu_letter_spacing' ) . 'px;}';

	// ============ Logo personalizable ============
	$logo_w = max( 120, (int) nechugo_get_option( 'logo_width' ) );
	$logo_h = max( 40, (int) nechugo_get_option( 'logo_height' ) );
	$css   .= '.custom-logo{width:' . $logo_w . 'px;min-width:' . $logo_w . 'px;height:' . $logo_h . 'px;object-fit:contain;}';

	// ============ Fondo de pagina personalizable ============
	$bg_type = nechugo_get_option( 'page_bg_type' );
	if ( 'image' === $bg_type ) {
		$bg_img  = nechugo_get_option( 'page_bg_image' );
		$fixed   = nechugo_is_enabled( 'page_bg_fixed' ) ? 'fixed' : 'scroll';
		$css    .= 'body{background-image:url(' . esc_url( $bg_img ) . ');background-size:cover;background-position:center;background-attachment:' . $fixed . ';}';
		$overlay = nechugo_get_option( 'page_bg_overlay' );
		if ( 'none' !== $overlay ) {
			$op = (int) nechugo_get_option( 'page_bg_overlay_opacity' ) / 100;
			if ( 'dark' === $overlay ) {
				$css .= 'body::before{content:"";position:fixed;inset:0;background:rgba(0,0,0,' . $op . ');pointer-events:none;z-index:-1;}';
			} elseif ( 'night-blue' === $overlay ) {
				$css .= 'body::before{content:"";position:fixed;inset:0;background:rgba(10,26,58,' . $op . ');pointer-events:none;z-index:-1;}';
			} elseif ( 'gradient' === $overlay ) {
				$css .= 'body::before{content:"";position:fixed;inset:0;background:linear-gradient(135deg,rgba(10,26,58,' . $op . '),rgba(18,60,110,' . $op . '));pointer-events:none;z-index:-1;}';
			}
		}
	} elseif ( 'gradient' === $bg_type ) {
		$from = sanitize_hex_color( nechugo_get_option( 'page_bg_gradient_from' ) );
		$to   = sanitize_hex_color( nechugo_get_option( 'page_bg_gradient_to' ) );
		$css .= 'body{background:linear-gradient(135deg,' . $from . ',' . $to . ') fixed;}';
	}

	// ============ Header: gap, alineaciones y fuente del menu ============
	$gap = max( 0, (int) nechugo_get_option( 'header_gap' ) );
	$css .= '.header__inner{gap:' . $gap . 'px;}';
	$css .= '.topbar__inner{gap:' . $gap . 'px;}';
	$css .= '.topbar__left,.topbar__right{gap:' . $gap . 'px;}';
	$css .= '.header-widgets__grid{gap:' . max( $gap, 10 ) . 'px;}';
	$css .= '.nechugo-menu{gap:' . $gap . 'px;}';

	// Fuente del menu: sans-serif 14px por defecto (configurable).
	$menu_size = max( 10, (int) nechugo_get_option( 'header_menu_size' ) );
	$css      .= '.nechugo-nav a{font-size:' . $menu_size . 'px;font-family:Helvetica Neue, Helvetica, Arial, sans-serif;}';

	// Alineacion del logo.
	$logo_align = nechugo_get_option( 'logo_align' );
	if ( 'center' === $logo_align ) {
		$css .= '.header__inner--split .header__brand{margin-left:auto;margin-right:auto;}';
	} elseif ( 'right' === $logo_align ) {
		$css .= '.header__inner--split .header__brand{margin-left:auto;order:3;}';
		$css .= '.header__inner--split .header__nav{margin-right:auto;margin-left:0;order:1;}';
		$css .= '.header__inner--split .header__tools{order:2;}';
	}

	// Alineacion del buscador (dentro de su caja).
	$search_align = nechugo_get_option( 'search_align' );
	if ( 'left' === $search_align ) {
		$css .= '.header__inner--split .header__tools{margin-left:0;margin-right:auto;}';
	}

	// ============ Tipografia por contexto (Astra Pro style) ============
	$pt_size = max( 12, (int) nechugo_get_option( 'post_title_font_size' ) );
	$pt_w    = (int) nechugo_get_option( 'post_title_font_weight' );
	$pg_size = max( 12, (int) nechugo_get_option( 'page_title_font_size' ) );
	$pg_w    = (int) nechugo_get_option( 'page_title_font_weight' );
	$wt_size = max( 10, (int) nechugo_get_option( 'widget_title_font_size' ) );
	$wt_w    = (int) nechugo_get_option( 'widget_title_font_weight' );
	$css    .= '.single .entry-title{font-size:' . $pt_size . 'px;font-weight:' . $pt_w . ';}';
	$css    .= '.nechugo-main--page .entry-title{font-size:' . $pg_size . 'px;font-weight:' . $pg_w . ';}';
	$css    .= '.widget-title{font-size:' . $wt_size . 'px;font-weight:' . $wt_w . ';}';

	// ============ Botones personalizados ============
	$btn_bg = nechugo_get_option( 'btn_bg_color' );
	$btn_hb = nechugo_get_option( 'btn_hover_bg_color' );
	$btn_tx = nechugo_get_option( 'btn_text_color' );
	$btn_r  = max( 0, (int) nechugo_get_option( 'btn_radius' ) );
	$btn_p  = max( 4, (int) nechugo_get_option( 'btn_padding' ) );
	$btn_fs = max( 10, (int) nechugo_get_option( 'btn_font_size' ) );
	$btn_fw = (int) nechugo_get_option( 'btn_font_weight' );
	$css   .= '.nechugo-btn,.nechugo-search-submit,button,input[type="submit"],input[type="button"],.wp-block-button__link{border-radius:' . $btn_r . 'px;padding:' . (int) ( $btn_p / 2 ) . 'px ' . $btn_p . 'px;font-size:' . $btn_fs . 'px;font-weight:' . $btn_fw . ';}';
	if ( ! empty( $btn_bg ) ) {
		$css .= '.nechugo-btn,.nechugo-search-submit,input[type="submit"],.wp-block-button__link{background:' . sanitize_hex_color( $btn_bg ) . ';}';
	}
	if ( ! empty( $btn_tx ) ) {
		$css .= '.nechugo-btn,.nechugo-search-submit,input[type="submit"],.wp-block-button__link{color:' . sanitize_hex_color( $btn_tx ) . ';}';
	}
	if ( ! empty( $btn_hb ) ) {
		$css .= '.nechugo-btn:hover,.nechugo-search-submit:hover,input[type="submit"]:hover,.wp-block-button__link:hover{background:' . sanitize_hex_color( $btn_hb ) . ';}';
	}

	// ============ Espaciados por componente ============
	$spt = max( 0, (int) nechugo_get_option( 'spacing_post_title' ) );
	$spm = max( 0, (int) nechugo_get_option( 'spacing_post_meta' ) );
	$css .= '.single .entry-title{margin-bottom:' . $spt . 'px;}';
	$css .= '.entry-meta{margin-bottom:' . $spm . 'px;}';

	// ============ Container boxed / full-width ============
	if ( 'full' === nechugo_get_option( 'container_type' ) ) {
		$css .= '.nechugo-container{max-width:100%;padding-left:2rem;padding-right:2rem;}';
	}

	// ============ Comentarios boxed / minimal ============
	if ( 'minimal' === nechugo_get_option( 'comments_style' ) ) {
		$css .= '.comment-body{box-shadow:none;background:transparent;border-bottom:1px solid #eee;border-radius:0;}';
	}

	// ============ Alineacion del menu (dentro de su caja) ============
	$align = nechugo_get_option( 'menu_align' );
	if ( 'left' === $align ) {
		$css .= '.header__inner--split .header__nav{margin-right:auto;}';
	} elseif ( 'center' === $align ) {
		$css .= '.header__inner--split .header__nav{margin-left:auto;margin-right:auto;}';
	} else {
		$css .= '.header__inner--split .header__nav{margin-left:auto;}';
	}

	// ============ Espaciados estructurales (default 30px) ============
	$sp_hb = max( 0, (int) nechugo_get_option( 'spacing_header_body' ) );
	$sp_bf = max( 0, (int) nechugo_get_option( 'spacing_body_footer' ) );
	$css  .= ':root{--nn-space-hb:' . $sp_hb . 'px;--nn-space-bf:' . $sp_bf . 'px;}';

	// ============ Estilo profesional de paginas ============
	$thumb_r = max( 0, (int) nechugo_get_option( 'page_thumb_radius' ) );
	$css   .= '.entry-thumb,.nechugo-card,.nechugo-hero-slider{border-radius:' . $thumb_r . 'px;}';
	if ( 'centered' === nechugo_get_option( 'page_title_style' ) ) {
		$css .= '.nechugo-main--page .entry-header{text-align:center;padding-bottom:' . $sp_hb . 'px;}';
	}

	// Ancho de la barra lateral (por defecto 300px).
	$sidebar = max( 240, (int) nechugo_get_option( 'sidebar_width' ) );
	$css    .= ':root{--nn-sidebar:' . $sidebar . 'px;}';

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
		'sans-serif'   => "Helvetica Neue, Helvetica, Arial, sans-serif",
		'google-sans'  => "'Google Sans', 'Product Sans', Roboto, -apple-system, sans-serif",
		'inter'        => "'Inter', sans-serif",
		'roboto'       => "'Roboto', sans-serif",
		'roboto-condensed' => "'Roboto Condensed', 'Roboto', sans-serif",
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
