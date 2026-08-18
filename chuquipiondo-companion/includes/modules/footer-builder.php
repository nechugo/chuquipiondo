<?php
/**
 * Module 1b: Footer Builder.
 *
 * Replaces the theme footer with a configurable builder when
 * `companion_footer_builder_enable` is ON. The theme renders its
 * footer directly inside footer.php, so we:
 *   1. Add a body class that hides the theme's #colophon via CSS.
 *   2. Render the builder footer at the end of wp_footer (low priority)
 *      so it prints after the theme footer markup.
 *
 * This avoids fragile output buffering that would interfere with other
 * wp_footer callbacks (analytics, plugin scripts, etc.).
 *
 * Rows: widgets | menu | copyright (each toggleable).
 * Layouts: 1-col | 2-cols | 3-cols | 4-cols.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the footer builder should take over rendering.
 *
 * @return bool
 */
function chuquipiondo_companion_footer_builder_active() {
	return chuquipiondo_companion_is_enabled( 'companion_footer_builder_enable' ) && chuquipiondo_companion_is_theme_active();
}

/**
 * Add a body class that hides the theme footer so the builder can
 * replace it without editing the theme template.
 *
 * @param array $classes Body classes.
 * @return array
 */
function chuquipiondo_companion_footer_body_class( $classes ) {
	if ( ! chuquipiondo_companion_footer_builder_active() ) {
		return $classes;
	}
	$classes[] = 'chuqui-companion-footer-replace';
	return $classes;
}
add_filter( 'body_class', 'chuquipiondo_companion_footer_body_class' );

/**
 * Render the builder footer at the end of the page.
 *
 * Hooked on wp_footer with priority 100 (late) so it prints after the
 * theme footer markup. The theme footer is hidden via the body class.
 */
function chuquipiondo_companion_render_footer_on_wp_footer() {
	if ( ! chuquipiondo_companion_footer_builder_active() ) {
		return;
	}
	chuquipiondo_companion_render_footer_builder();
}
add_action( 'wp_footer', 'chuquipiondo_companion_render_footer_on_wp_footer', 100 );

/**
 * Render the builder footer.
 *
 * @param bool $return Whether to return the markup instead of echoing.
 * @return string
 */
function chuquipiondo_companion_render_footer_builder( $return = false ) {
	$rows   = chuquipiondo_companion_get_array_option( 'companion_footer_rows' );
	$layout = sanitize_html_class( chuquipiondo_companion_get_option( 'companion_footer_layout' ) );
	$width  = chuquipiondo_companion_get_option( 'companion_footer_container_width', '1280' );

	if ( $return ) {
		ob_start();
	}

	// Ad slots (reuse theme helper when present).
	if ( function_exists( 'chuquipiondo_ad_slot' ) ) {
		chuquipiondo_ad_slot( 'ads_footer_before' );
	}

	echo '<footer id="colophon-companion" class="site-footer chuqui-companion-footer footer-layout--' . esc_attr( $layout ) . '" role="contentinfo">';

	if ( in_array( 'widgets', $rows, true ) && is_active_sidebar( 'sidebar-footer' ) ) {
		echo '<div class="footer-widgets"><div class="chuqui-container" style="max-width:' . esc_attr( $width ) . 'px">';
		dynamic_sidebar( 'sidebar-footer' );
		echo '</div></div>';
	}

	if ( function_exists( 'chuquipiondo_ad_slot' ) ) {
		chuquipiondo_ad_slot( 'ads_footer_between' );
	}

	if ( in_array( 'menu', $rows, true ) && has_nav_menu( 'footer' ) ) {
		echo '<nav class="footer-menu-section" aria-label="' . esc_attr__( 'Menu del pie de pagina', 'chuquipiondo-companion' ) . '">';
		echo '<div class="chuqui-container" style="max-width:' . esc_attr( $width ) . 'px">';
		wp_nav_menu( array(
			'theme_location' => 'footer',
			'container'      => false,
			'menu_class'     => 'footer-menu',
			'depth'          => 1,
		) );
		echo '</div></nav>';
	}

	if ( in_array( 'copyright', $rows, true ) ) {
		$copyright = chuquipiondo_companion_get_option( 'companion_footer_copyright' );
		$copyright = str_replace( '{year}', gmdate( 'Y' ), $copyright );
		echo '<div class="footer-copyright-section"><div class="chuqui-container" style="max-width:' . esc_attr( $width ) . 'px">';
		echo '<p class="footer-copyright">' . wp_kses_post( $copyright ) . '</p>';
		echo '</div></div>';
	}

	echo '</footer><!-- #colophon-companion -->';

	if ( function_exists( 'chuquipiondo_ad_slot' ) ) {
		chuquipiondo_ad_slot( 'ads_footer_after' );
	}

	if ( $return ) {
		return ob_get_clean();
	}
	return '';
}
