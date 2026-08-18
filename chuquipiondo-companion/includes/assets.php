<?php
/**
 * Asset loading for the CHUQUIPIONDO Companion plugin.
 *
 * Mirrors the theme's conditional enqueue strategy: each module's
 * assets only load when the module is enabled and the page needs it.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end assets conditionally based on active modules.
 */
function chuquipiondo_companion_enqueue_front_assets() {
	// Base companion stylesheet (cheap, scoped under plugin classes).
	wp_register_style(
		'chuquipiondo-companion',
		CHUQUIPIONDO_COMPANION_URL . 'assets/css/companion.css',
		array(),
		chuquipiondo_companion_asset_version( 'assets/css/companion.css' )
	);

	// Only enqueue when at least one module that renders markup is active.
	$needs_css = (
		chuquipiondo_companion_is_enabled( 'companion_header_builder_enable' )
		|| chuquipiondo_companion_is_enabled( 'companion_footer_builder_enable' )
		|| chuquipiondo_companion_is_enabled( 'companion_mega_menu_enable' )
		|| chuquipiondo_companion_is_enabled( 'companion_blog_pro_enable' )
		|| chuquipiondo_companion_is_enabled( 'companion_ads_pro_enable' )
	);
	if ( $needs_css ) {
		wp_enqueue_style( 'chuquipiondo-companion' );
	}

	// Mega menu JS (desktop interactions only when enabled).
	if ( chuquipiondo_companion_is_enabled( 'companion_mega_menu_enable' ) ) {
		wp_enqueue_script(
			'chuquipiondo-companion-mega-menu',
			CHUQUIPIONDO_COMPANION_URL . 'assets/js/mega-menu.js',
			array(),
			chuquipiondo_companion_asset_version( 'assets/js/mega-menu.js' ),
			true
		);
		wp_localize_script( 'chuquipiondo-companion-mega-menu', 'chuquiCompanion', array(
			'megaTrigger' => chuquipiondo_companion_get_option( 'companion_mega_menu_trigger', 'hover' ),
		) );
	}

	// Blog pro (filters + load more) only where relevant.
	if ( chuquipiondo_companion_is_enabled( 'companion_blog_pro_enable' )
		&& ( is_home() || is_archive() || is_singular( 'post' ) ) ) {
		wp_enqueue_script(
			'chuquipiondo-companion-blog-pro',
			CHUQUIPIONDO_COMPANION_URL . 'assets/js/blog-pro.js',
			array(),
			chuquipiondo_companion_asset_version( 'assets/js/blog-pro.js' ),
			true
		);
		wp_localize_script( 'chuquipiondo-companion-blog-pro', 'chuquiBlogPro', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'chuquipiondo_blog_pro' ),
			'i18n'    => array(
				'noMore'    => __( 'No hay mas articulos', 'chuquipiondo-companion' ),
				'loading'   => __( 'Cargando...', 'chuquipiondo-companion' ),
				'error'     => __( 'Error al cargar', 'chuquipiondo-companion' ),
			),
		) );
	}

	// Dynamic CSS for builder variables (inline, in <head>).
	if ( $needs_css ) {
		wp_add_inline_style( 'chuquipiondo-companion', chuquipiondo_companion_dynamic_css() );
	}
}
add_action( 'wp_enqueue_scripts', 'chuquipiondo_companion_enqueue_front_assets' );

/**
 * Enqueue admin assets on companion screens.
 *
 * @param string $hook Current admin page hook.
 */
function chuquipiondo_companion_admin_assets( $hook ) {
	if ( false === strpos( $hook, 'chuquipiondo' ) ) {
		return;
	}
	wp_enqueue_style(
		'chuquipiondo-companion-admin',
		CHUQUIPIONDO_COMPANION_URL . 'assets/css/admin.css',
		array(),
		chuquipiondo_companion_asset_version( 'assets/css/admin.css' )
	);
	wp_enqueue_script(
		'chuquipiondo-companion-admin',
		CHUQUIPIONDO_COMPANION_URL . 'assets/js/admin.js',
		array(),
		chuquipiondo_companion_asset_version( 'assets/js/admin.js' ),
		true
	);
}
add_action( 'admin_enqueue_scripts', 'chuquipiondo_companion_admin_assets' );

/**
 * Build the dynamic CSS for the companion modules.
 *
 * Emits CSS custom properties consumed by companion.css and the
 * builder markup. Reads theme variables where available so the
 * companion inherits the active preset automatically.
 *
 * @return string
 */
function chuquipiondo_companion_dynamic_css() {
	// Inherit theme presets when the theme is active.
	$navy   = function_exists( 'chuquipiondo_get_option' ) ? chuquipiondo_get_option( 'color_navy' ) : '#0a1f44';
	$navy_dark = function_exists( 'chuquipiondo_get_option' ) ? chuquipiondo_get_option( 'color_navy_dark' ) : '#06133a';
	$sky    = function_exists( 'chuquipiondo_get_option' ) ? chuquipiondo_get_option( 'color_sky' ) : '#27b6ff';
	$white  = '#ffffff';

	$vars = array(
		'companion-header-bg'      => chuquipiondo_companion_get_option( 'companion_header_bg', $white ),
		'companion-header-text'    => chuquipiondo_companion_get_option( 'companion_header_text', $navy ),
		'companion-header-height'   => chuquipiondo_companion_get_option( 'companion_header_height', '80' ) . 'px',
		'companion-footer-bg'      => chuquipiondo_companion_get_option( 'companion_footer_bg', $navy_dark ),
		'companion-footer-text'    => chuquipiondo_companion_get_option( 'companion_footer_text', $white ),
		'companion-footer-link'    => chuquipiondo_companion_get_option( 'companion_footer_link', $sky ),
		'companion-footer-padding' => chuquipiondo_companion_get_option( 'companion_footer_padding', '48' ) . 'px',
		'companion-mega-bg'        => chuquipiondo_companion_get_option( 'companion_mega_menu_bg', $white ),
		'companion-mega-text'      => chuquipiondo_companion_get_option( 'companion_mega_menu_text', $navy ),
	);

	return ':root{ ' . chuquipiondo_companion_build_css_vars( $vars ) . '}';
}
