<?php
/**
 * Asset enqueuing (CSS / JS / fonts).
 *
 * Loads assets conditionally so the theme stays fast:
 *  - The slider JS only loads when the hero is active with 2+ slides.
 *  - The music player JS only loads on music views or when the mini player is on.
 *  - Social and WhatsApp JS are conditionally loaded.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end styles.
 */
function chuquipiondo_enqueue_styles() {
	// Google Fonts: loaded dynamically based on the selected body + heading fonts.
	$fonts_url = chuquipiondo_google_fonts_url();
	if ( $fonts_url ) {
		wp_enqueue_style(
			'chuquipiondo-fonts',
			$fonts_url,
			array(),
			null
		);
	}

	// Main stylesheet (style.css @imports modular partials).
	wp_enqueue_style(
		'chuquipiondo-style',
		get_stylesheet_uri(),
		array( 'chuquipiondo-fonts' ),
		chuquipiondo_asset_version( 'style.css' )
	);

	// Custom CSS from Customizer (printed inline in <head> via customizer/css.php).
}
add_action( 'wp_enqueue_scripts', 'chuquipiondo_enqueue_styles' );

/**
 * Enqueue front-end scripts conditionally.
 */
function chuquipiondo_enqueue_scripts() {
	// Navigation (mobile menu + accessibility).
	wp_enqueue_script(
		'chuquipiondo-navigation',
		CHUQUIPONDO_URI . '/assets/js/navigation.js',
		array(),
		chuquipiondo_asset_version( 'assets/js/navigation.js' ),
		true
	);

	wp_localize_script( 'chuquipiondo-navigation', 'chuquipiondoData', array(
		'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
		'nonce'       => wp_create_nonce( 'chuquipiondo_nonce' ),
		'menuLabel'   => __( 'Menu', 'chuquipiondo' ),
		'closeLabel'  => __( 'Cerrar', 'chuquipiondo' ),
		'searchLabel' => __( 'Buscar', 'chuquipiondo' ),
	) );

	// Slider / hero scripts: only if hero enabled and 2+ slides.
	if ( chuquipiondo_should_load_slider() ) {
		wp_enqueue_script(
			'chuquipiondo-slider',
			CHUQUIPONDO_URI . '/assets/js/slider.js',
			array(),
			chuquipiondo_asset_version( 'assets/js/slider.js' ),
			true
		);
		wp_localize_script( 'chuquipiondo-slider', 'chuquipiondoHero', array(
			'effect'   => chuquipiondo_get_option( 'hero_effect' ),
			'autoplay'  => chuquipiondo_is_enabled( 'hero_autoplay' ),
			'speed'     => (int) chuquipiondo_get_option( 'hero_speed' ),
			'reducedMotion' => (bool) get_user_meta( get_current_user_id(), 'reduce_motion', true ),
		) );
	}

	// Music player assets.
	if ( chuquipiondo_needs_music_assets() ) {
		wp_enqueue_script(
			'chuquipiondo-player',
			CHUQUIPONDO_URI . '/assets/js/player.js',
			array(),
			chuquipiondo_asset_version( 'assets/js/player.js' ),
			true
		);
		wp_localize_script( 'chuquipiondo-player', 'chuquipiondoMusic', array(
			'isMiniPlayer' => chuquipiondo_is_enabled( 'music_mini_player' ),
			'playLabel'    => __( 'Reproducir', 'chuquipiondo' ),
			'pauseLabel'   => __( 'Pausar', 'chuquipiondo' ),
		) );
	}

	// Social share.
	if ( chuquipiondo_is_enabled( 'social_master_switch' ) && is_singular( 'post' ) ) {
		wp_enqueue_script(
			'chuquipiondo-social',
			CHUQUIPONDO_URI . '/assets/js/social.js',
			array(),
			chuquipiondo_asset_version( 'assets/js/social.js' ),
			true
		);
		wp_localize_script( 'chuquipiondo-social', 'chuquipiondoSocial', array(
			'copyLabel' => __( 'Copiar enlace', 'chuquipiondo' ),
			'copied'    => __( 'Enlace copiado', 'chuquipiondo' ),
			'nonce'     => wp_create_nonce( 'chuquipiondo_social_nonce' ),
		) );
	}

	// WhatsApp float.
	if ( chuquipiondo_is_enabled( 'whatsapp_master_switch' ) ) {
		wp_enqueue_script(
			'chuquipiondo-whatsapp',
			CHUQUIPONDO_URI . '/assets/js/whatsapp.js',
			array(),
			chuquipiondo_asset_version( 'assets/js/whatsapp.js' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'chuquipiondo_enqueue_scripts' );

/**
 * Decide whether the slider JS should load.
 * Loads only when hero is enabled with 2+ slides (single image = no JS).
 *
 * @return bool
 */
function chuquipiondo_should_load_slider() {
	if ( ! chuquipiondo_is_enabled( 'hero_enable' ) ) {
		return false;
	}
	$mode = chuquipiondo_get_option( 'hero_mode' );
	if ( 'slider' !== $mode ) {
		return false;
	}
	$slides = chuquipiondo_get_array_option( 'hero_slider' );
	return count( $slides ) >= 2;
}

/**
 * Inline custom code hooks (head, body, footer).
 */
function chuquipiondo_inline_custom_head() {
	$code = chuquipiondo_get_option( 'custom_head' );
	if ( $code ) {
		echo "\n<!-- CHUQUIPIONDO custom head -->\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- admin-entered, intended.
	}
}
add_action( 'wp_head', 'chuquipiondo_inline_custom_head', 99 );

function chuquipiondo_inline_custom_footer() {
	$code = chuquipiondo_get_option( 'custom_footer' );
	if ( $code ) {
		echo "\n<!-- CHUQUIPIONDO custom footer -->\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- admin-entered, intended.
	}
}
add_action( 'wp_footer', 'chuquipiondo_inline_custom_footer', 99 );

/**
 * Add custom code right after <body> opening.
 */
function chuquipiondo_inline_custom_body() {
	$code = chuquipiondo_get_option( 'custom_body' );
	if ( $code ) {
		echo "\n<!-- CHUQUIPIONDO custom body -->\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- admin-entered, intended.
	}
}
add_action( 'wp_body_open', 'chuquipiondo_inline_custom_body', 5 );

/**
 * Defer non-critical scripts for performance (respects plugin scripts).
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string
 */
function chuquipiondo_defer_scripts( $tag, $handle ) {
	$defer = array(
		'chuquipiondo-navigation',
		'chuquipiondo-slider',
		'chuquipiondo-social',
		'chuquipiondo-whatsapp',
		'chuquipiondo-player',
	);
	if ( in_array( $handle, $defer, true ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'chuquipiondo_defer_scripts', 10, 2 );

/**
 * Preconnect to Google Fonts for performance.
 */
function chuquipiondo_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'chuquipiondo_resource_hints', 10, 2 );
