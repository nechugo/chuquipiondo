<?php
/**
 * Generate dynamic CSS from Customizer options.
 *
 * Outputs a <style> block in <head> containing the CSS custom
 * properties (colors, widths, radii, fonts) and inline custom CSS.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build the dynamic CSS string.
 *
 * @return string
 */
function chuquipiondo_dynamic_css() {
	$vars = array(
		'navy'           => chuquipiondo_get_option( 'color_navy' ),
		'navy-dark'      => chuquipiondo_get_option( 'color_navy_dark' ),
		'sky'             => chuquipiondo_get_option( 'color_sky' ),
		'sky-soft'        => chuquipiondo_get_option( 'color_sky_soft' ),
		'background'      => chuquipiondo_get_option( 'color_background' ),
		'text'            => chuquipiondo_get_option( 'color_text' ),
		'muted'           => chuquipiondo_get_option( 'color_muted' ),
		'white'           => chuquipiondo_get_option( 'color_white' ),
		'accent'          => chuquipiondo_get_option( 'color_accent' ),
		'button-bg'       => chuquipiondo_get_option( 'button_bg' ),
		'button-text'     => chuquipiondo_get_option( 'button_text' ),
		'button-hover-bg' => chuquipiondo_get_option( 'button_hover_bg' ),
		'button-hover'    => chuquipiondo_get_option( 'button_hover_text' ),
		'footer-bg'       => chuquipiondo_get_option( 'footer_bg' ),
		'footer-text'     => chuquipiondo_get_option( 'footer_text' ),
		'font-body'       => chuquipiondo_font_stack( chuquipiondo_get_option( 'font_body' ) ),
		'font-heading'    => chuquipiondo_font_stack( chuquipiondo_get_option( 'font_heading' ) ),
		'font-size-base'  => chuquipiondo_get_option( 'font_size_base' ) . 'px',
		'font-weight-heading' => chuquipiondo_get_option( 'font_weight_heading' ),
		'container-width' => chuquipiondo_get_option( 'container_width' ) . 'px',
		'reading-width'   => chuquipiondo_get_option( 'reading_width' ) . 'px',
		'sidebar-width'   => chuquipiondo_get_option( 'sidebar_width' ) . 'px',
		'radius-content'  => chuquipiondo_get_option( 'content_radius' ) . 'px',
		'radius-button'     => chuquipiondo_get_option( 'button_radius' ) . 'px',
		'button-bg'         => chuquipiondo_get_option( 'button_bg' ),
		'button-text'       => chuquipiondo_get_option( 'button_text' ),
		'button-hover-bg'   => chuquipiondo_get_option( 'button_hover_bg' ),
		'button-hover-text' => chuquipiondo_get_option( 'button_hover_text' ),
		'button-width'      => chuquipiondo_get_option( 'button_width' ) . 'px',
		'button-width-pct'  => chuquipiondo_get_option( 'button_width_percent' ) . '%',
		'button-height'     => chuquipiondo_get_option( 'button_height' ) . 'px',
		'button-font-size'  => chuquipiondo_get_option( 'button_font_size' ) . 'px',
		'button-font-weight'=> chuquipiondo_get_option( 'button_font_weight' ),
		'button-radius'     => chuquipiondo_get_option( 'button_radius' ) . 'px',
		'button-padding-h'  => chuquipiondo_get_option( 'button_padding_h' ) . 'px',
		'button-padding-v'  => chuquipiondo_get_option( 'button_padding_v' ) . 'px',
		'button-icon-size'  => chuquipiondo_get_option( 'button_icon_size' ) . 'px',
		'button-border-w'   => chuquipiondo_get_option( 'button_border_width' ) . 'px',
		'button-border-c'   => chuquipiondo_get_option( 'button_border_color' ),
		'button-shadow'     => chuquipiondo_get_option( 'button_shadow_color' ),
		'button-letter-sp'  => chuquipiondo_get_option( 'button_letter_spacing' ) . 'em',
		'spacing-base'       => chuquipiondo_get_option( 'spacing_base' ) . 'px',
	);

	// Header colors (Astra-style per-row customization).
	$vars['topbar-bg']           = chuquipiondo_get_option( 'header_topbar_bg' );
	$vars['topbar-text']         = chuquipiondo_get_option( 'header_topbar_text' );
	$vars['topbar-link']         = chuquipiondo_get_option( 'header_topbar_link' );
	$vars['header-main-bg']      = chuquipiondo_get_option( 'header_main_bg' );
	$vars['header-main-text']     = chuquipiondo_get_option( 'header_main_text' );
	$vars['header-main-link']     = chuquipiondo_get_option( 'header_main_link' );
	$vars['header-main-link-hover'] = chuquipiondo_get_option( 'header_main_link_hover' );
	$vars['multiuse-bg']          = chuquipiondo_get_option( 'header_multiuse_bg' );
	$vars['multiuse-text']        = chuquipiondo_get_option( 'header_multiuse_text' );

	// Footer colors (Astra-style).
	$vars['footer-link']          = chuquipiondo_get_option( 'footer_link' );
	$vars['footer-link-hover']    = chuquipiondo_get_option( 'footer_link_hover' );
	$vars['footer-title-color']    = chuquipiondo_get_option( 'footer_title' );
	$vars['footer-bottom-bg']     = chuquipiondo_get_option( 'footer_bottom_bg' );
	$vars['footer-bottom-text']   = chuquipiondo_get_option( 'footer_bottom_text' );

	// Sticky header colors.
	$vars['header-sticky-bg']   = chuquipiondo_get_option( 'header_sticky_bg' );
	$vars['header-sticky-text'] = chuquipiondo_get_option( 'header_sticky_text' );

	// WhatsApp sizes.
	$vars['whatsapp-size']        = chuquipiondo_get_option( 'whatsapp_size' ) . 'px';
	$vars['whatsapp-size-mobile'] = chuquipiondo_get_option( 'whatsapp_mobile_size' ) . 'px';

	// Music player color.
	$vars['music-player'] = chuquipiondo_get_option( 'music_player_color' );

	// Social custom colors.
	$vars['social-bg'] = chuquipiondo_get_option( 'social_custom_bg' );
	$vars['social-fg'] = chuquipiondo_get_option( 'social_custom_fg' );

	// Header height.
	$vars['header-height'] = chuquipiondo_get_option( 'header_main_height' ) . 'px';

	// Hero height.
	$vars['hero-height'] = chuquipiondo_get_option( 'hero_height' ) . 'px';

	/**
	 * Filters the dynamic CSS variable map.
	 *
	 * @param array $vars Variable pairs.
	 */
	$vars = apply_filters( 'chuquipiondo_css_vars', $vars );

	$css  = ':root{ ' . chuquipiondo_build_css_vars( $vars ) . ' }' . "\n";

	// Sidebar widths and grid columns.
	$columns_desktop = (int) chuquipiondo_get_option( 'blog_columns' );
	$columns_tablet  = (int) chuquipiondo_get_option( 'blog_columns_tablet' );
	$columns_mobile  = (int) chuquipiondo_get_option( 'blog_columns_mobile' );

	$css .= ".post-grid{ grid-template-columns: repeat({$columns_mobile}, minmax(0, 1fr)); }\n";
	$css .= "@media(min-width:768px){ .post-grid{ grid-template-columns: repeat({$columns_tablet}, minmax(0, 1fr)); } }\n";
	$css .= "@media(min-width:1024px){ .post-grid{ grid-template-columns: repeat({$columns_desktop}, minmax(0, 1fr)); } }\n";

	// Footer columns.
	$footer_cols = (int) chuquipiondo_get_option( 'footer_columns' );
	$css .= ".footer-widgets{ grid-template-columns: repeat({$footer_cols}, minmax(0, 1fr)); }\n";
	if ( $footer_cols >= 2 ) {
		$css .= "@media(max-width:767px){ .footer-widgets{ grid-template-columns: 1fr; } }\n";
	}

	// Music archive columns.
	$music_cols = (int) chuquipiondo_get_option( 'music_archive_columns' );
	$css .= ".music-grid{ grid-template-columns: repeat({$music_cols}, minmax(0, 1fr)); }\n";

	// Social share color modes.
	$mode = chuquipiondo_get_option( 'social_color_mode' );
	if ( 'custom' === $mode ) {
		$css .= ".social-share a{ background: var(--social-bg); color: var(--social-fg); }\n";
	}

	// ===== Header dynamic styles =====
	$css .= ".header-topbar{ background: var(--topbar-bg); color: var(--topbar-text); }\n";
	$css .= ".header-topbar a{ color: var(--topbar-link); }\n";
	$css .= ".header-main{ background: var(--header-main-bg); color: var(--header-main-text); }\n";
	$css .= ".header-main a{ color: var(--header-main-link); }\n";
	$css .= ".header-main a:hover{ color: var(--header-main-link-hover); }\n";
	$css .= ".header-multiuse{ background: var(--multiuse-bg); color: var(--multiuse-text); }\n";

	// ===== Footer dynamic styles =====
	$css .= ".site-footer a{ color: var(--footer-link); }\n";
	$css .= ".site-footer a:hover{ color: var(--footer-link-hover); }\n";
	$css .= ".footer-widget-title{ color: var(--footer-title-color); }\n";
	$css .= ".footer-bottom{ background: var(--footer-bottom-bg); color: var(--footer-bottom-text); }\n";
	$css .= ".site-footer{ padding-top: " . chuquipiondo_get_option( 'footer_padding_top' ) . "px; padding-bottom: " . chuquipiondo_get_option( 'footer_padding_bottom' ) . "px; }\n";
	$css .= ".footer-widgets{ gap: " . chuquipiondo_get_option( 'footer_widget_gap' ) . "px; }\n";
	$css .= ".site-footer{ font-size: " . chuquipiondo_get_option( 'footer_font_size' ) . "px; }\n";
	$css .= ".footer-widget-title{ font-size: " . chuquipiondo_get_option( 'footer_title_size' ) . "px; }\n";
	if ( chuquipiondo_is_enabled( 'footer_border_top' ) ) {
		$css .= ".footer-bottom{ border-top: " . chuquipiondo_get_option( 'footer_border_top' ) . "px solid " . chuquipiondo_get_option( 'footer_border_color' ) . "; }\n";
	}

	// ===== Button styles =====
	$shape     = chuquipiondo_get_option( 'button_shape' );
	$width_mode= chuquipiondo_get_option( 'button_width_mode' );
	$radius    = '0px';
	if ( 'rounded' === $shape ) {
		$radius = chuquipiondo_get_option( 'button_radius' ) . 'px';
	} elseif ( 'pill' === $shape ) {
		$radius = '9999px';
	}

	$width_css = '';
	if ( 'fixed' === $width_mode ) {
		$width_css = 'width: ' . chuquipiondo_get_option( 'button_width' ) . 'px;';
	} elseif ( 'full' === $width_mode ) {
		$width_css = 'width: 100%;';
	} elseif ( 'percent' === $width_mode ) {
		$width_css = 'width: ' . chuquipiondo_get_option( 'button_width_percent' ) . '%;';
	}

	$shadow_css = '';
	if ( chuquipiondo_is_enabled( 'button_shadow_enable' ) ) {
		$shadow_css = 'box-shadow: 0 4px 12px ' . chuquipiondo_get_option( 'button_shadow_color' ) . ';';
	}

	$css .= ".btn, .button, button[type=\"submit\"], input[type=\"submit\"] {\n";
	$css .= "  min-height: var(--button-height);\n";
	$css .= "  font-size: var(--button-font-size);\n";
	$css .= "  font-weight: var(--button-font-weight);\n";
	$css .= "  border-radius: {$radius};\n";
	$css .= "  padding: var(--button-padding-v) var(--button-padding-h);\n";
	$css .= "  text-transform: " . chuquipiondo_get_option( 'button_text_transform' ) . ";\n";
	$css .= "  letter-spacing: var(--button-letter-sp);\n";
	$css .= "  border-width: var(--button-border-w);\n";
	$css .= "  border-style: solid;\n";
	$css .= "  border-color: var(--button-border-c);\n";
	if ( $width_css ) { $css .= "  {$width_css}\n"; }
	if ( $shadow_css ) { $css .= "  {$shadow_css}\n"; }
	$css .= "}\n";

	// Icon styles.
	$css .= ".btn-icon { width: var(--button-icon-size); height: var(--button-icon-size); }\n";
	$css .= ".btn .btn-icon { flex-shrink: 0; }\n";

	// Custom user CSS.
	$custom_css = chuquipiondo_get_option( 'custom_css' );
	if ( $custom_css ) {
		$css .= "\n/* Custom CSS */\n" . $custom_css . "\n";
	}

	/**
	 * Filters the complete dynamic CSS string.
	 *
	 * @param string $css CSS.
	 */
	return apply_filters( 'chuquipiondo_dynamic_css', $css );
}

/**
 * Print the dynamic CSS in <head>.
 */
function chuquipiondo_print_dynamic_css() {
	$css = chuquipiondo_dynamic_css();
	if ( $css ) {
		echo '<style id="chuquipiondo-dynamic-css">' . wp_strip_all_tags( $css ) . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- CSS only, stripped.
	}
}
add_action( 'wp_head', 'chuquipiondo_print_dynamic_css', 20 );
