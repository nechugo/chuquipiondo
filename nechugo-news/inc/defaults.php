<?php
/**
 * Valores por defecto del tema.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Devuelve el array completo de defaults.
 *
 * @return array
 */
function nechugo_defaults() {
	return array(
		// Global.
		'color_scheme'          => 'light',
		'primary_color'          => '#1a73e8',
		'secondary_color'        => '#e8453c',
		'accent_color'           => '#fbbc04',
		'background_color'       => '#ffffff',
		'text_color'             => '#1c1c1c',
		'header_bg_color'        => '#ffffff',
		'header_text_color'     => '#1c1c1c',
		'footer_bg_color'        => '#111111',
		'footer_text_color'      => '#eeeeee',
		'color_preset'           => 'blue',

		// Tipografia (articulo 12px, H1 16px por defecto).
		'body_font_size'         => 12,
		'h1_font_size'           => 16,
		'body_font_family'       => 'roboto',
		'heading_font_family'    => 'google-sans',
		'body_line_height'       => 1.7,

		// Layout.
		'content_width'          => 1200,
		'blog_layout'            => 'grid-3',
		'sidebar_position'       => 'right',
		'excerpt_length'         => 24,
		'read_more_text'         => 'Leer mas',

		// Header (4 diseños, full-width).
		'header_layout'          => 'header-1',
		'header_sticky'          => true,
		'header_search_enable'   => true,
		'header_social_enable'   => true,
		'header_topbar_enable'   => false,
		'header_date_enable'     => true,

		// Footer (3 diseños, full-width).
		'footer_layout'          => 'footer-1',
		'footer_columns'          => 3,
		'footer_copyright_enable' => true,
		'footer_copyright_text'   => '© {year} {sitename} — Todos los derechos reservados.',

		// Ads (AdSense).
		'ads_master_switch'      => false,
		'adsense_publisher_id'   => '',
		'ads_mode'               => 'manual',
		'ad_header_code'         => '',
		'ad_after_post_code'     => '',
		'ad_in_article_code'     => '',
		'ad_sidebar_code'        => '',

		// Extras.
		'show_breadcrumbs'       => true,
		'show_author'            => true,
		'show_date'              => true,
		'show_reading_time'      => true,
		'show_share_buttons'      => true,
		'comments_enable'        => true,
		'back_to_top_enable'     => true,
		'home_slider_enable'     => true,
		'home_slider_count'      => 5,
	);
}
