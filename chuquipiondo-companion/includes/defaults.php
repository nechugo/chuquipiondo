<?php
/**
 * Default options for the CHUQUIPIONDO Companion plugin.
 *
 * Single source of truth for every companion option. Mirrors the
 * pattern used by the theme (inc/defaults.php) and the core plugin.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the full companion defaults map.
 *
 * @return array
 */
function chuquipiondo_companion_defaults() {
	return array(

		// ===== Master switches per module =====
		'companion_header_builder_enable'   => '0',
		'companion_footer_builder_enable'   => '0',
		'companion_mega_menu_enable'        => '0',
		'companion_blog_pro_enable'         => '0',
		'companion_ads_pro_enable'          => '0',

		// ===== Header Builder =====
		'companion_header_layout'           => 'logo-left-menu-right', // logo-left-menu-right | logo-center-menu-split | logo-left-menu-center
		'companion_header_rows'             => array( 'topbar', 'main', 'multiuse' ),
		'companion_header_container_width'  => '1280',
		'companion_header_sticky_desktop'   => '1',
		'companion_header_sticky_mobile'    => '0',
		'companion_header_sticky_effect'    => 'none', // none | shrink | slide | fade
		'companion_header_bg'              => '#ffffff',
		'companion_header_text'            => '#0a1f44',
		'companion_header_height'           => '80',

		// ===== Footer Builder =====
		'companion_footer_layout'           => '4-cols', // 1-col | 2-cols | 3-cols | 4-cols
		'companion_footer_rows'             => array( 'widgets', 'menu', 'copyright' ),
		'companion_footer_container_width' => '1280',
		'companion_footer_bg'              => '#06133a',
		'companion_footer_text'            => '#ffffff',
		'companion_footer_link'            => '#27b6ff',
		'companion_footer_padding'         => '48',
		'companion_footer_copyright'       => '&copy; {year} Nelson Chuquipiondo. Todos los derechos reservados.',

		// ===== Mega Menu =====
		'companion_mega_menu_trigger'       => 'hover', // hover | click
		'companion_mega_menu_columns'      => '4',
		'companion_mega_menu_width'         => 'full', // full | container | custom
		'companion_mega_menu_custom_width' => '1200',
		'companion_mega_menu_bg'           => '#ffffff',
		'companion_mega_menu_text'         => '#0a1f44',

		// ===== Blog Pro =====
		'companion_blog_pro_style'         => 'magazine', // magazine | timeline | grid-pro | list-featured
		'companion_blog_pro_columns'       => '3',
		'companion_blog_pro_columns_tablet' => '2',
		'companion_blog_pro_columns_mobile' => '1',
		'companion_blog_pro_excerpt'       => '25',
		'companion_blog_pro_related_count' => '4',
		'companion_blog_pro_related_columns' => '4',
		'companion_blog_pro_filters'       => '1',
		'companion_blog_pro_load_more'     => '1',
		'companion_blog_pro_lazy'          => '1',

		// ===== Ads Pro =====
		'companion_ads_pro_mode'           => 'manual', // manual | rotation | ab | adsense-auto
		'companion_ads_pro_rotation_delay' => '8',
		'companion_ads_pro_ab_traffic'     => '50', // percentage to variant A
		'companion_ads_pro_analytics'      => '1',
		'companion_ads_pro_label'         => '0',
		'companion_ads_pro_label_text'    => 'Publicidad',
		'companion_ads_pro_locations'     => array(
			'after_title'      => '',
			'after_paragraph_3' => '',
			'before_related'   => '',
			'sidebar_top'      => '',
			'footer_after'     => '',
		),

		// ===== Starter sites =====
		'companion_last_imported_site'     => '',
	);
}
