<?php
/**
 * Uninstall CHUQUIPIONDO AI Studio.
 *
 * Removes all AI options. Does NOT touch posts, pages, media, terms or
 * post meta created by the plugin (those belong to site content).
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$option_keys = array_keys(
	array(
		'ai_provider'             => '',
		'ai_api_key'              => '',
		'ai_model'                => '',
		'ai_temperature'          => '',
		'ai_max_tokens'           => '',
		'ai_language'             => '',
		'ai_timeout'              => '',
		'ai_image_height'         => '',
		'ai_image_width'          => '',
		'ai_image_auto_resize'    => '',
		'ai_image_provider'       => '',
		'ai_image_quality'        => '',
		'ai_image_alt_lang'       => '',
		'ai_max_generated_images' => '',
		'ai_scope_posts'          => '',
		'ai_scope_pages'          => '',
		'ai_allowed_html'         => '',
		'ai_default_post_status'  => '',
		'ai_seo_meta_desc_len'    => '',
		'ai_seo_generate_tags'    => '',
		'ai_seo_generate_excerpt' => '',
		'ai_seo_generate_slug'    => '',
		'ai_seo_keywords_count'   => '',
		'ai_seo_add_schema'       => '',
		'ai_auto_featured_image'  => '',
		'ai_compat_safe_hooks'    => '',
		'ai_compat_astra'         => '',
		'ai_log_requests'         => '',
		'chuquipiondo_ai_version'  => '',
		'_chuquipiondo_ai_log'     => '',
	)
);

foreach ( $option_keys as $key ) {
	delete_option( $key );
}
