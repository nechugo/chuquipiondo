<?php
/**
 * Recommended plugins for the CHUQUIPIONDO theme.
 *
 * Uses TGM Plugin Activation to notify the user about plugins
 * that enhance the theme's functionality. None are required —
 * the theme works perfectly without them.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once CHUQUIPIONDO_DIR . '/inc/tgmpa/class-tgm-plugin-activation.php';

/**
 * Register the recommended plugins.
 */
function chuquipiondo_register_required_plugins() {
	$plugins = array(
		array(
			'name'     => 'Elementor Website Builder',
			'slug'     => 'elementor',
			'required' => false,
		),
		array(
			'name'     => 'Rank Math SEO',
			'slug'     => 'seo-by-rank-math',
			'required' => false,
		),
		array(
			'name'     => 'Site Kit by Google',
			'slug'     => 'google-site-kit',
			'required' => false,
		),
		array(
			'name'     => 'Jetpack',
			'slug'     => 'jetpack',
			'required' => false,
		),
	);

	$config = array(
		'id'           => 'chuquipiondo',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'chuquipiondo_register_required_plugins' );
