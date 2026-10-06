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
		'body_font_size'         => 14,
		'h1_font_size'           => 18,
		'body_font_family'       => 'roboto-condensed',
		'heading_font_family'    => 'roboto-condensed',
		'body_line_height'       => 1.7,
		'h1_font_weight'         => 900,

		// Layout.
		'posts_per_page_mode'    => 'columns',
		'posts_per_page'         => 10,
		'content_width'          => 1200,
		'blog_layout'            => 'grid',
		'blog_columns'           => 2,
		'sidebar_position'       => 'right',
		'sidebar_width'           => 300,
		'sidebar_on_blog'        => true,
		'sidebar_on_archive'     => true,
		'sidebar_on_posts'       => true,
		'sidebar_on_pages'       => true,
		'sidebar_on_search'      => true,
		'excerpt_length'         => 24,
		'read_more_text'         => 'Leer mas',

		// Header (4 diseños, full-width).
		'header_layout'          => 'header-1',
		'header_height'          => 64,
		'header_font_family'     => 'roboto-condensed',
		'header_font_size'       => 13,
		'header_menu_size'       => 13,
		'header_menu_color'      => '',
		'header_menu_hover_color' => '',
		'header_submenu_bg'      => '#ffffff',
		'header_submenu_color'   => '#1c1c1c',
		'header_submenu_hover_bg' => '#eef4fd',
		'header_widgets_enable'  => true,
		'header_sticky'          => true,
		'header_search_enable'   => true,
		'header_social_enable'   => true,
		'header_topbar_enable'   => false,
		'header_date_enable'     => true,

		// Logo personalizable.
		'logo_width'             => 220,
		'logo_height'            => 120,

		// Fondo de pagina personalizable.
		'page_bg_type'           => 'color',
		'page_bg_color'          => '#ffffff',
		'page_bg_image'          => '',
		'page_bg_overlay'        => 'none',
		'page_bg_overlay_opacity' => 60,
		'page_bg_gradient_from'  => '#0a1a3a',
		'page_bg_gradient_to'    => '#123c6e',
		'page_bg_fixed'          => false,

		// Menu personalizable elegante.
		'menu_font_family'       => 'roboto-condensed',
		'menu_uppercase'         => true,
		'menu_letter_spacing'    => 2,

		// Footer (3 diseños, full-width).
		'footer_layout'          => 'footer-1',
		'footer_widgets_enable'  => true,
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

		// Footer personalizable.
		'footer_heading_color'   => '#ffffff',
		'footer_link_color'      => '#8ab4f8',
		'footer_font_size'       => 13,
		'footer_font_family'     => 'roboto-condensed',
		'footer_border_enable'  => true,

		// Redes sociales.
		'social_facebook'        => '',
		'social_twitter'         => '',
		'social_instagram'       => '',
		'social_youtube'         => '',

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
		'home_slider_category'   => 0,
		'home_slider_style'      => 'split',
		'home_slider_autoplay'   => true,
		'home_slider_interval'  => 5000,
		'home_slider_show_meta'  => true,
	);
}
