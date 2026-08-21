<?php
/**
 * Asset enqueuing (CSS / JS / fonts).
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function chuquipiondo_enqueue_styles() {
	$fonts_url = chuquipiondo_google_fonts_url();
	if ( $fonts_url ) {
		wp_enqueue_style( 'chuquipiondo-fonts', $fonts_url, array(), null );
	}

	// style.css remains the WordPress theme manifest; the optimized concatenated
	// front-end CSS is kept in assets/css/main.css.
	wp_enqueue_style(
		'chuquipiondo-style',
		get_template_directory_uri() . '/assets/css/main.css',
		array( 'chuquipiondo-fonts' ),
		chuquipiondo_asset_version( 'assets/css/main.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'chuquipiondo_enqueue_styles' );

function chuquipiondo_enqueue_scripts() {
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

	if ( chuquipiondo_should_load_slider() ) {
		wp_enqueue_script(
			'chuquipiondo-slider',
			CHUQUIPONDO_URI . '/assets/js/slider.js',
			array(),
			chuquipiondo_asset_version( 'assets/js/slider.js' ),
			true
		);
		wp_localize_script( 'chuquipiondo-slider', 'chuquipiondoHero', array(
			'effect'        => chuquipiondo_get_option( 'hero_effect' ),
			'autoplay'      => chuquipiondo_is_enabled( 'hero_autoplay' ),
			'speed'         => (int) chuquipiondo_get_option( 'hero_speed' ),
			'reducedMotion' => (bool) get_user_meta( get_current_user_id(), 'reduce_motion', true ),
		) );
	}

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

	if ( chuquipiondo_should_load_social() ) {
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

function chuquipiondo_should_load_slider() {
	if ( ! chuquipiondo_is_enabled( 'hero_enable' ) ) {
		return false;
	}
	if ( 'slider' !== chuquipiondo_get_option( 'hero_mode' ) ) {
		return false;
	}
	$slides = chuquipiondo_get_array_option( 'hero_slider' );
	return count( $slides ) >= 2;
}

function chuquipiondo_inline_custom_head() {
	$code = chuquipiondo_get_option( 'custom_head' );
	if ( $code ) {
		echo "\n<!-- CHUQUIPIONDO custom head -->\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- administrator-entered custom code.
	}
}
add_action( 'wp_head', 'chuquipiondo_inline_custom_head', 99 );

function chuquipiondo_inline_custom_footer() {
	$code = chuquipiondo_get_option( 'custom_footer' );
	if ( $code ) {
		echo "\n<!-- CHUQUIPIONDO custom footer -->\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- administrator-entered custom code.
	}
}
add_action( 'wp_footer', 'chuquipiondo_inline_custom_footer', 99 );

function chuquipiondo_inline_custom_body() {
	$code = chuquipiondo_get_option( 'custom_body' );
	if ( $code ) {
		echo "\n<!-- CHUQUIPIONDO custom body -->\n" . $code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- administrator-entered custom code.
	}
}
add_action( 'wp_body_open', 'chuquipiondo_inline_custom_body', 5 );

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

function chuquipiondo_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
	}
	if ( 'dns-prefetch' === $relation_type ) {
		$urls[] = 'https://www.google-analytics.com';
		$urls[] = 'https://www.googletagmanager.com';
		$urls[] = 'https://connect.facebook.net';
		$urls[] = 'https://platform.twitter.com';
		$urls[] = 'https://stats.wp.com';
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'chuquipiondo_resource_hints', 10, 2 );

function chuquipiondo_disable_emojis() {
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_render_title_tag', 'wp_render_emoji_title_tag' );
}
add_action( 'init', 'chuquipiondo_disable_emojis', 9999 );

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
