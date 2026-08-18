<?php
/**
 * Module 1a: Header Builder.
 *
 * Replaces the theme header with a configurable 3-row builder when
 * `companion_header_builder_enable` is ON. Hooks into the theme's own
 * `chuquipiondo_header` action so no template edits are needed.
 *
 * Rows: topbar | main | multiuse (each toggleable).
 * Layouts: logo-left-menu-right | logo-center-menu-split | logo-left-menu-center.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the header builder should take over rendering.
 *
 * @return bool
 */
function chuquipiondo_companion_header_builder_active() {
	return chuquipiondo_companion_is_enabled( 'companion_header_builder_enable' ) && chuquipiondo_companion_is_theme_active();
}

/**
 * Replace the theme header render callback with the builder.
 *
 * The theme registers `chuquipiondo_header()` on `chuquipiondo_header`.
 * We remove it and plug our builder in at the same priority.
 */
function chuquipiondo_companion_override_header() {
	if ( ! chuquipiondo_companion_header_builder_active() ) {
		return;
	}
	remove_action( 'chuquipiondo_header', 'chuquipiondo_header' );
	add_action( 'chuquipiondo_header', 'chuquipiondo_companion_render_header' );
}
add_action( 'template_redirect', 'chuquipiondo_companion_override_header', 20 );

/**
 * Render the builder header (3 rows).
 */
function chuquipiondo_companion_render_header() {
	do_action( 'chuquipiondo_before_header' );

	$rows   = chuquipiondo_companion_get_array_option( 'companion_header_rows' );
	$layout = sanitize_html_class( chuquipiondo_companion_get_option( 'companion_header_layout' ) );

	$classes = array( 'site-header', 'chuqui-companion-header', 'header-layout--' . $layout );
	// Inherit the theme sticky system if the theme option is on.
	if ( function_exists( 'chuquipiondo_is_enabled' ) && chuquipiondo_is_enabled( 'header_main_sticky' ) ) {
		$classes[] = 'is-sticky-header';
		if ( chuquipiondo_companion_is_enabled( 'companion_header_sticky_desktop' ) ) {
			$classes[] = 'sticky-desktop';
		}
		if ( chuquipiondo_companion_is_enabled( 'companion_header_sticky_mobile' ) ) {
			$classes[] = 'sticky-mobile';
		}
		$classes[] = 'sticky-effect--' . sanitize_html_class( chuquipiondo_companion_get_option( 'companion_header_sticky_effect' ) );
	}
	$classes[] = 'header-layout--' . $layout;

	echo '<header id="masthead" class="' . esc_attr( implode( ' ', $classes ) ) . '" role="banner">';

	if ( in_array( 'topbar', $rows, true ) ) {
		chuquipiondo_companion_render_header_row( 'topbar' );
	}
	if ( in_array( 'main', $rows, true ) ) {
		chuquipiondo_companion_render_header_row( 'main' );
	}
	if ( in_array( 'multiuse', $rows, true ) ) {
		chuquipiondo_companion_render_header_row( 'multiuse' );
	}

	echo '</header><!-- #masthead -->';

	do_action( 'chuquipiondo_after_header' );
}

/**
 * Render a single header row.
 *
 * @param string $row Row id: topbar | main | multiuse.
 */
function chuquipiondo_companion_render_header_row( $row ) {
	$container_width = chuquipiondo_companion_get_option( 'companion_header_container_width', '1280' );

	echo '<div class="chuqui-companion-header__row chuqui-companion-header__row--' . esc_attr( $row ) . '">';
	echo '<div class="chuqui-container" style="max-width:' . esc_attr( $container_width ) . 'px">';

	switch ( $row ) {
		case 'topbar':
			chuquipiondo_companion_render_topbar();
			break;
		case 'main':
			chuquipiondo_companion_render_main_row();
			break;
		case 'multiuse':
			chuquipiondo_companion_render_multiuse_row();
			break;
	}

	echo '</div></div>';
}

/**
 * Render the top bar row.
 */
function chuquipiondo_companion_render_topbar() {
	$left  = array();
	$right = array();

	// Date.
	if ( function_exists( 'chuquipiondo_is_enabled' ) && chuquipiondo_is_enabled( 'header_topbar_date' ) ) {
		$left[] = '<span class="topbar-date">' . esc_html( wp_date( get_option( 'date_format' ) ) ) . '</span>';
	}
	// Time.
	if ( function_exists( 'chuquipiondo_is_enabled' ) && chuquipiondo_is_enabled( 'header_topbar_time' ) ) {
		$left[] = '<span class="topbar-time">' . esc_html( wp_date( 'g:i A' ) ) . '</span>';
	}
	// Email.
	$email = function_exists( 'chuquipiondo_get_option' ) ? chuquipiondo_get_option( 'header_topbar_email' ) : '';
	if ( $email ) {
		$left[] = '<a class="topbar-email" href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
	}
	// Topbar menu.
	if ( has_nav_menu( 'topbar' ) ) {
		$right[] = wp_nav_menu( array(
			'theme_location' => 'topbar',
			'container'      => false,
			'menu_class'     => 'topbar-menu',
			'echo'           => false,
			'depth'          => 1,
			'fallback_cb'    => false,
		) );
	}
	// Socials.
	if ( function_exists( 'chuquipiondo_social_profiles_links' ) ) {
		ob_start();
		echo '<span class="topbar-socials">';
		chuquipiondo_social_profiles_links();
		echo '</span>';
		$right[] = ob_get_clean();
	}

	echo '<div class="topbar__inner">';
	echo '<div class="topbar__left">' . implode( '<span class="topbar-sep" aria-hidden="true">|</span>', array_filter( $left ) ) . '</div>';
	echo '<div class="topbar__right">' . implode( '', array_filter( $right ) ) . '</div>';
	echo '</div>';
}

/**
 * Render the main row (logo + menu + search).
 */
function chuquipiondo_companion_render_main_row() {
	$layout     = sanitize_html_class( chuquipiondo_companion_get_option( 'companion_header_layout' ) );
	$has_search = function_exists( 'chuquipiondo_is_enabled' ) && chuquipiondo_is_enabled( 'header_search_enable' );

	echo '<div class="header-main__inner header-main--' . esc_attr( $layout ) . '">';

	// Brand + mobile toggle.
	echo '<div class="header-main__brand">';
	if ( function_exists( 'chuquipiondo_site_logo' ) ) {
		chuquipiondo_site_logo();
	} else {
		echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-logo--text">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
	}
	echo '<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="' . esc_attr__( 'Abrir menu', 'chuquipiondo-companion' ) . '">';
	echo '<span class="menu-toggle__bar"></span><span class="menu-toggle__bar"></span><span class="menu-toggle__bar"></span>';
	echo '</button>';
	echo '</div>';

	// Nav (with mega menu walker when enabled).
	echo '<nav class="header-main__nav" aria-label="' . esc_attr__( 'Navegacion principal', 'chuquipiondo-companion' ) . '">';
	if ( has_nav_menu( 'primary' ) ) {
		$nav_args = array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'menu primary-menu',
			'fallback_cb'    => false,
		);
		if ( chuquipiondo_companion_is_enabled( 'companion_mega_menu_enable' ) ) {
			$nav_args['walker'] = new Chuquipiondo_Companion_Mega_Menu_Walker();
		}
		wp_nav_menu( $nav_args );
	} elseif ( function_exists( 'chuquipiondo_fallback_menu' ) ) {
		chuquipiondo_fallback_menu();
	}
	echo '</nav>';

	// Search.
	if ( $has_search ) {
		echo '<div class="header-main__search">';
		echo '<button class="search-toggle" aria-label="' . esc_attr__( 'Buscar', 'chuquipiondo-companion' ) . '" aria-expanded="false">';
		echo '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10 2a8 8 0 1 0 4.9 14.32l5.39 5.39 1.42-1.42-5.39-5.39A8 8 0 0 0 10 2Zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12Z"/></svg>';
		echo '</button>';
		echo '<div class="header-search-form" hidden>';
		get_search_form();
		echo '</div>';
		echo '</div>';
	}

	echo '</div>';
}

/**
 * Render the multiuse row (up to 4 boxes, reuses theme boxes).
 */
function chuquipiondo_companion_render_multiuse_row() {
	echo '<div class="header-multiuse__inner">';
	for ( $i = 1; $i <= 4; $i++ ) {
		if ( function_exists( 'chuquipiondo_render_header_box' ) ) {
			chuquipiondo_render_header_box( $i );
		}
	}
	echo '</div>';
}
