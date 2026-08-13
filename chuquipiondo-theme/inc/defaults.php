<?php
/**
 * Theme option defaults.
 *
 * Central source of truth for every theme_mod used by the
 * Customizer. Keys here mirror the option keys consumed
 * across the theme via chuquipiondo_get_option().
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the full map of default values.
 *
 * Sections:
 *  - global
 *  - header
 *  - hero
 *  - home
 *  - blog
 *  - single
 *  - ads
 *  - social
 *  - whatsapp
 *  - footer
 *  - music
 *  - custom_code
 *
 * @return array
 */
function chuquipiondo_defaults() {
	return array(

		/* ===== Global: colors, typography, container, buttons ===== */
		'preset'                 => 'original',
		'color_navy'             => '#0a1f44',
		'color_navy_dark'        => '#06133a',
		'color_sky'              => '#27b6ff',
		'color_sky_soft'         => '#7fd6ff',
		'color_background'       => '#f5f8ff',
		'color_text'             => '#1a2233',
		'color_muted'            => '#5b6678',
		'color_white'            => '#ffffff',
		'color_accent'           => '#27b6ff',

		'font_body'              => 'inter',
		'font_heading'           => 'plus-jakarta',
		'font_size_base'         => '16',
		'font_weight_body'       => '400',
		'font_weight_heading'    => '700',
		'container_width'        => '1280',
		'reading_width'          => '800',
		'sidebar_width'          => '320',
		'content_radius'         => '12',
		'button_radius'          => '8',
		'button_bg'              => '#27b6ff',
		'button_text'            => '#0a1f44',
		'button_hover_bg'        => '#0a1f44',
		'button_hover_text'      => '#ffffff',
		'spacing_base'           => '8',

		/* ===== Header system ===== */
		'header_topbar_enable'   => '1',
		'header_topbar_date'     => '1',
		'header_topbar_time'     => '0',
		'header_topbar_email'    => 'contacto@chuquipiondo.com',
		'header_main_sticky'     => '1',
		'header_main_height'     => '80',
		'header_main_layout'     => 'logo-left-menu-right',
		'header_search_enable'   => '1',
		'header_multiuse_enable' => '0',
		'header_multiuse_content'=> '',
		'header_box1_type'       => 'logo',
		'header_box2_type'       => 'menu',
		'header_box3_type'       => 'text',
		'header_box4_type'       => 'search',
		'header_distribution'    => '50-50',
		'header_box1_visible'    => 'desktop,tablet,mobile',
		'header_box2_visible'    => 'desktop,tablet,mobile',
		'header_box3_visible'    => 'desktop,tablet',
		'header_box4_visible'    => 'desktop,tablet',
		'header_box1_content'    => '',
		'header_box2_content'    => '',
		'header_box3_content'    => 'Liderazgo, Gestion y Formacion con proposito',
		'header_box4_content'    => '',

		/* ===== Hero / Slider ===== */
		'hero_enable'            => '0',
		'hero_mode'              => 'slider',
		'hero_height'            => '560',
		'hero_effect'            => 'fade',
		'hero_autoplay'          => '1',
		'hero_speed'             => '5000',
		'hero_overlay'           => '35',
		'hero_slider'            => array(),

		/* ===== Home builder ===== */
		'home_modules'            => 'hero,featured,latest,categories,song,videos,about,newsletter',
		'home_featured_title'    => 'Articulos destacados',
		'home_featured_count'    => '4',
		'home_latest_title'      => 'Ultimos articulos',
		'home_latest_count'      => '6',
		'home_categories_title'  => 'Explora por categorias',
		'home_about_title'       => 'Sobre Nelson',
		'home_about_text'        => 'Nelson Chuquipiondo comunica liderazgo, gestion y formacion con proposito, con raices en la fe cristiana y la musica.',
		'home_about_image'       => '',
		'home_song_title'        => 'Cancion destacada',
		'home_song_id'           => '',
		'home_videos_title'      => 'Ultimos videos',
		'home_videos_count'     => '3',
		'home_videos_playlist'   => '',
		'home_newsletter_title'  => 'Suscribete',
		'home_newsletter_text'   => 'Recibe contenidos sobre liderazgo, gestion y formacion con proposito.',
		'home_newsletter_shortcode' => '',

		/* ===== Blog / Archive ===== */
		'blog_columns'           => '3',
		'blog_columns_tablet'    => '2',
		'blog_columns_mobile'    => '1',
		'blog_card_style'        => 'editorial',
		'blog_sidebar'           => 'right',
		'blog_sidebar_desktop'  => '1',
		'blog_sidebar_mobile'   => '0',
		'blog_excerpt_length'    => '24',
		'blog_show_author'       => '1',
		'blog_show_date'         => '1',
		'blog_show_category'     => '1',
		'blog_show_excerpt'      => '1',
		'blog_image_lazy'        => '1',

		/* ===== Single post ===== */
		'single_layout'          => 'editorial',
		'single_sidebar'         => 'right',
		'single_sidebar_desktop' => '1',
		'single_sidebar_mobile'  => '0',
		'single_show_breadcrumb' => '1',
		'single_show_category'   => '1',
		'single_show_author'     => '1',
		'single_show_date'       => '1',
		'single_show_reading'    => '1',
		'single_show_tags'        => '1',
		'single_show_bio'        => '1',
		'single_show_related'    => '1',
		'single_related_count'   => '3',
		'single_related_title'   => 'Articulos relacionados',
		'single_nav_style'       => 'cards',
		'single_extension_area'  => '',

		/* ===== Page individual ===== */
		'page_sidebar'           => 'none',
		'page_sidebar_desktop'  => '1',
		'page_sidebar_mobile'   => '0',
		'page_layout'            => 'wide',

		/* ===== Ads ===== */
		'ads_master_switch'      => '0',
		'ads_mode'               => 'manual',
		'ads_client_id'          => '',
		'ads_after_title'        => '',
		'ads_after_paragraph_3'  => '',
		'ads_after_paragraph_6'  => '',
		'ads_before_related'     => '',
		'ads_blog_top'           => '',
		'ads_blog_after_row'     => '',
		'ads_blog_after_posts'   => '3',
		'ads_sidebar_top'        => '',
		'ads_sidebar_middle'     => '',
		'ads_sidebar_bottom'     => '',
		'ads_home_after_hero'    => '',
		'ads_header_before'      => '',
		'ads_header_between'     => '',
		'ads_header_after'       => '',

		/* ===== Social share ===== */
		'social_master_switch'   => '1',
		'social_networks'         => 'facebook,x,linkedin,whatsapp,telegram,email,copy',
		'social_color_mode'      => 'official',
		'social_position'        => 'after',
		'social_floating'        => '1',
		'social_floating_mobile' => '1',
		'social_custom_bg'       => '#0a1f44',
		'social_custom_fg'       => '#ffffff',

		/* ===== Social profiles (top bar) ===== */
		'social_facebook'        => '',
		'social_x'               => '',
		'social_youtube'         => 'https://www.youtube.com/@chuquipiondo',
		'social_instagram'       => '',
		'social_linkedin'        => '',
		'social_telegram'        => '',
		'social_tiktok'          => '',

		/* ===== WhatsApp float ===== */
		'whatsapp_master_switch' => '1',
		'whatsapp_number'        => '51921497257',
		'whatsapp_mode'          => 'private',
		'whatsapp_position'      => 'bottom-right',
		'whatsapp_size'          => '52',
		'whatsapp_mobile_size'   => '48',
		'whatsapp_message'       => 'Hola Nelson, me gustaria conversar.',
		'whatsapp_group_url'     => '',

		/* ===== Footer ===== */
		'footer_columns'         => '4',
		'footer_about'           => 'CHUQUIPIONDO - Liderazgo, Gestion y Formacion con proposito. ¡Juntos, si podemos!',
		'footer_copyright'       => '© {year} Nelson Chuquipiondo. Todos los derechos reservados.',
		'footer_bg'              => '#06133a',
		'footer_text'            => '#ffffff',
		'footer_show_social'     => '1',

		/* ===== Music ===== */
		'music_mini_player'      => '0',
		'music_downloads_global' => '0',
		'music_player_color'     => '#27b6ff',
		'music_archive_columns'  => '2',

		/* ===== Custom code ===== */
		'custom_css'             => '',
		'custom_head'            => '',
		'custom_body'            => '',
		'custom_footer'          => '',
	);
}
