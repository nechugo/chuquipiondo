<?php
/**
 * Uninstall CHUQUIPIONDO Companion.
 *
 * Removes all companion options. Does NOT touch theme_mods, posts,
 * menus or media created by the starter-sites importer (those belong
 * to the site content, not the plugin).
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$option_keys = array_keys(
	array(
		'companion_header_builder_enable'   => '',
		'companion_footer_builder_enable'   => '',
		'companion_mega_menu_enable'        => '',
		'companion_blog_pro_enable'         => '',
		'companion_ads_pro_enable'          => '',
		'companion_header_layout'           => '',
		'companion_header_rows'             => '',
		'companion_header_container_width'  => '',
		'companion_header_sticky_desktop'   => '',
		'companion_header_sticky_mobile'    => '',
		'companion_header_sticky_effect'    => '',
		'companion_header_bg'              => '',
		'companion_header_text'            => '',
		'companion_header_height'          => '',
		'companion_footer_layout'          => '',
		'companion_footer_rows'            => '',
		'companion_footer_container_width' => '',
		'companion_footer_bg'             => '',
		'companion_footer_text'           => '',
		'companion_footer_link'           => '',
		'companion_footer_padding'        => '',
		'companion_footer_copyright'      => '',
		'companion_mega_menu_trigger'      => '',
		'companion_mega_menu_columns'     => '',
		'companion_mega_menu_width'        => '',
		'companion_mega_menu_custom_width' => '',
		'companion_mega_menu_bg'          => '',
		'companion_mega_menu_text'        => '',
		'companion_blog_pro_style'        => '',
		'companion_blog_pro_columns'      => '',
		'companion_blog_pro_columns_tablet' => '',
		'companion_blog_pro_columns_mobile' => '',
		'companion_blog_pro_excerpt'      => '',
		'companion_blog_pro_related_count' => '',
		'companion_blog_pro_related_columns' => '',
		'companion_blog_pro_filters'      => '',
		'companion_blog_pro_load_more'    => '',
		'companion_blog_pro_lazy'         => '',
		'companion_ads_pro_mode'          => '',
		'companion_ads_pro_rotation_delay' => '',
		'companion_ads_pro_ab_traffic'    => '',
		'companion_ads_pro_analytics'     => '',
		'companion_ads_pro_label'         => '',
		'companion_ads_pro_label_text'    => '',
		'companion_ads_pro_locations'     => '',
		'companion_last_imported_site'    => '',
		'chuquipiondo_companion_version'  => '',
		'chuquipiondo_companion_just_activated' => '',
		'_chuquipiondo_ads_impressions'   => '',
	)
);

foreach ( $option_keys as $key ) {
	delete_option( $key );
}
