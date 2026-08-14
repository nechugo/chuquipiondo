<?php
/**
 * Header system: 3 rows (Top Bar, Main, Multiuse).
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the complete header (3 rows).
 */
function chuquipiondo_header() {
	/**
	 * Fires before the header markup.
	 */
	do_action( 'chuquipiondo_before_header' );

	// Build sticky classes (Astra-style: fixed on scroll, PC + mobile).
	$header_classes = array( 'site-header' );
	if ( chuquipiondo_is_enabled( 'header_main_sticky' ) ) {
		$header_classes[] = 'is-sticky-header';
		$header_classes[] = 'sticky-mode--' . sanitize_html_class( chuquipiondo_get_option( 'header_sticky_mode' ) );
		$header_classes[] = 'sticky-effect--' . sanitize_html_class( chuquipiondo_get_option( 'header_sticky_effect' ) );
		if ( chuquipiondo_is_enabled( 'header_sticky_desktop' ) ) {
			$header_classes[] = 'sticky-desktop';
		}
		if ( chuquipiondo_is_enabled( 'header_sticky_mobile' ) ) {
			$header_classes[] = 'sticky-mobile';
		}
		if ( chuquipiondo_is_enabled( 'header_sticky_shadow' ) ) {
			$header_classes[] = 'sticky-shadow';
		}
	}

	echo '<header id="masthead" class="' . esc_attr( implode( ' ', $header_classes ) ) . '" role="banner">';

	// Header 1: Top Bar.
	if ( chuquipiondo_is_enabled( 'header_topbar_enable' ) ) {
		chuquipiondo_get_template_part( 'template-parts/header/topbar' );
	}

	// Header 2: Main.
	chuquipiondo_get_template_part( 'template-parts/header/main' );

	// Header 3: Multiuse.
	if ( chuquipiondo_is_enabled( 'header_multiuse_enable' ) ) {
		chuquipiondo_get_template_part( 'template-parts/header/multiuse' );
	}

	echo '</header><!-- #masthead -->';

	/**
	 * Fires after the header markup.
	 */
	do_action( 'chuquipiondo_after_header' );
}
add_action( 'chuquipiondo_header', 'chuquipiondo_header' );

/**
 * Fallback menu when no menu is assigned.
 */
function chuquipiondo_fallback_menu() {
	echo '<ul id="primary-menu" class="menu nav-menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Inicio', 'chuquipiondo' ) . '</a></li>';
	wp_list_pages( array(
		'title_li' => '',
		'depth'    => 1,
	) );
	echo '</ul>';
}

/**
 * Build the box visibility classes from the comma-separated device list.
 *
 * @param string $key Option key for the box visibility (e.g. header_box1_visible).
 * @return string CSS classes (e.g. "show-desktop show-tablet hide-mobile").
 */
function chuquipiondo_box_visibility_classes( $key ) {
	$value   = chuquipiondo_get_option( $key );
	$devices = array( 'desktop', 'tablet', 'mobile' );
	$active  = array_filter( array_map( 'trim', explode( ',', (string) $value ) ) );

	$classes = array();
	foreach ( $devices as $device ) {
		$classes[] = in_array( $device, $active, true ) ? 'show-' . $device : 'hide-' . $device;
	}
	return implode( ' ', $classes );
}

/**
 * Get the grid-template-columns value for the active distribution.
 *
 * @return string
 */
function chuquipiondo_header_distribution_columns() {
	$distribution = chuquipiondo_get_option( 'header_distribution' );
	$map = array(
		'100'          => '1fr',
		'50-50'        => '1fr 1fr',
		'33-33-33'     => '1fr 1fr 1fr',
		'25-25-25-25'  => '1fr 1fr 1fr 1fr',
		'60-40'        => '3fr 2fr',
		'40-60'        => '2fr 3fr',
	);
	return isset( $map[ $distribution ] ) ? $map[ $distribution ] : '1fr 1fr';
}

/**
 * Render a single multiuse box by index (1..4).
 *
 * @param int $index Box index 1-4.
 */
function chuquipiondo_render_header_box( $index ) {
	$type    = chuquipiondo_get_option( 'header_box' . $index . '_type' );
	$content = chuquipiondo_get_option( 'header_box' . $index . '_content' );
	$visible = chuquipiondo_box_visibility_classes( 'header_box' . $index . '_visible' );

	if ( 'none' === $type ) {
		return;
	}

	echo '<div class="header-box header-box--' . esc_attr( $index ) . ' header-box--' . esc_attr( $type ) . ' ' . esc_attr( $visible ) . '">';

	switch ( $type ) {
		case 'logo':
			chuquipiondo_site_logo();
			break;
		case 'menu':
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => 'nav',
					'container_class'=> 'box-menu',
					'menu_class'     => 'menu nav-menu',
					'fallback_cb'    => false,
				) );
			}
			break;
		case 'search':
			get_search_form();
			break;
		case 'text':
			echo '<span class="header-box-text">' . esc_html( $content ) . '</span>';
			break;
		case 'html':
			echo wp_kses_post( $content );
			break;
		case 'widget':
			if ( is_active_sidebar( 'sidebar-header-multiuse' ) ) {
				dynamic_sidebar( 'sidebar-header-multiuse' );
			}
			break;
	}

	echo '</div>';
}
