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

	// Logo dimensions + gap.
	$vars['header-logo-width']  = chuquipiondo_get_option( 'header_main_logo_width' ) . 'px';
	$vars['header-logo-height'] = chuquipiondo_get_option( 'header_main_logo_height' ) . 'px';
	$vars['header-logo-gap']   = chuquipiondo_get_option( 'header_main_logo_gap' ) . 'px';
	$vars['header-menu-search-gap'] = chuquipiondo_get_option( 'header_menu_search_gap' ) . 'px';

	// Menu colors.
	$vars['header-menu-bg']           = chuquipiondo_get_option( 'header_menu_bg' );
	$vars['header-menu-text']         = chuquipiondo_get_option( 'header_menu_text' );
	$vars['header-menu-text-hover']   = chuquipiondo_get_option( 'header_menu_text_hover' );
	$vars['header-menu-active-bg']   = chuquipiondo_get_option( 'header_menu_active_bg' );
	$vars['header-menu-active-text'] = chuquipiondo_get_option( 'header_menu_active_text' );
	$vars['header-menu-item-gap']    = chuquipiondo_get_option( 'header_menu_item_gap' ) . 'px';

	// Footer colors (Astra-style).
	$vars['footer-link']          = chuquipiondo_get_option( 'footer_link' );
	$vars['footer-link-hover']    = chuquipiondo_get_option( 'footer_link_hover' );
	$vars['footer-title-color']    = chuquipiondo_get_option( 'footer_title' );
	$vars['footer-bottom-bg']     = chuquipiondo_get_option( 'footer_bottom_bg' );
	$vars['footer-bottom-text']   = chuquipiondo_get_option( 'footer_bottom_text' );

	// Footer Design variables (color por seccion: marca, widgets, menu, copyright).
	$vars['footer-brand-bg']        = chuquipiondo_get_option( 'footer_brand_bg' );
	$vars['footer-widgets-bg']      = chuquipiondo_get_option( 'footer_widgets_bg' );
	$vars['footer-menu-bg']         = chuquipiondo_get_option( 'footer_menu_bg' );
	$vars['footer-copyright-bg']    = chuquipiondo_get_option( 'footer_copyright_bg' );
	$fb_h = (int) chuquipiondo_get_option( 'footer_brand_height' );
	$fw_h = (int) chuquipiondo_get_option( 'footer_widgets_height' );
	$fm_h = (int) chuquipiondo_get_option( 'footer_menu_height' );
	$fc_h = (int) chuquipiondo_get_option( 'footer_copyright_height' );
	$vars['footer-brand-height']     = $fb_h > 0 ? $fb_h . 'px' : 'auto';
	$vars['footer-brand-padding']    = chuquipiondo_get_option( 'footer_brand_padding' ) . 'px';
	$vars['footer-widgets-height']   = $fw_h > 0 ? $fw_h . 'px' : 'auto';
	$vars['footer-widgets-padding']  = chuquipiondo_get_option( 'footer_widgets_padding' ) . 'px';
	$vars['footer-menu-height']      = $fm_h > 0 ? $fm_h . 'px' : 'auto';
	$vars['footer-menu-padding']     = chuquipiondo_get_option( 'footer_menu_padding' ) . 'px';
	$vars['footer-copyright-height'] = $fc_h > 0 ? $fc_h . 'px' : 'auto';
	$vars['footer-copyright-padding'] = chuquipiondo_get_option( 'footer_copyright_padding' ) . 'px';
	$vars['footer-widget-bg']       = chuquipiondo_get_option( 'footer_widget_bg' );
	$vars['footer-widget-border-c'] = chuquipiondo_get_option( 'footer_widget_border_color' );
	$vars['footer-bottom-link']    = chuquipiondo_get_option( 'footer_bottom_link' );
	$vars['footer-bottom-link-hover'] = chuquipiondo_get_option( 'footer_bottom_link_hover' );

	// Page background + title color.
	$vars['page-bg-color']    = chuquipiondo_get_option( 'page_bg_color' );
	$vars['page-title-color'] = chuquipiondo_get_option( 'page_title_color' );

	// Sidebar card style.
	$vars['sidebar-card-padding'] = chuquipiondo_get_option( 'sidebar_card_padding' ) . 'px';
	$vars['sidebar-card-gap']     = chuquipiondo_get_option( 'sidebar_card_gap' ) . 'px';

	// Related posts columns.
	$vars['related-cols'] = chuquipiondo_get_option( 'single_related_columns' );

	// Single + Page layout variables.
	$vars['header-content-gap'] = chuquipiondo_get_option( 'header_content_gap' ) . 'px';
	// Tipografia del contenido del articulo (configurable desde el personalizador).
	$sc_font = chuquipiondo_get_option( 'single_content_font' );
	$vars['single-content-font']   = '' !== $sc_font ? chuquipiondo_font_stack( $sc_font ) : 'var(--font-body)';
	$vars['single-content-size']   = chuquipiondo_get_option( 'single_content_size' ) . 'px';
	$vars['single-content-weight'] = chuquipiondo_get_option( 'single_content_weight' );
	$vars['single-content-lh']     = chuquipiondo_get_option( 'single_content_line_height' );
	$vars['related-gap']       = chuquipiondo_get_option( 'single_related_gap' ) . 'px';

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

	// ===== Footer General + Design (Astra) =====
	$footer_width = chuquipiondo_get_option( 'footer_width' );
	$footer_w_css = 'full' === $footer_width ? 'max-width: 100%;' : 'max-width: var(--container-width); margin-inline: auto;';
	$css .= ".site-footer .chuqui-container{ " . $footer_w_css . " }\n";
	$css .= ".footer-widgets{ background: var(--footer-widgets-bg); }\n";
	$css .= ".footer-brand{ background: var(--footer-brand-bg); }\n";
	$css .= ".footer-menu-section{ background: var(--footer-menu-bg); }\n";
	$css .= ".footer-copyright-section{ background: var(--footer-copyright-bg); }\n";
	$css .= ".footer-widgets{ grid-template-columns: repeat(" . chuquipiondo_get_option( 'footer_widget_layout' ) . ", minmax(0, 1fr)); }\n";
	$css .= ".footer-widget{ background: var(--footer-widget-bg); padding: " . chuquipiondo_get_option( 'footer_widget_padding_v' ) . "px " . chuquipiondo_get_option( 'footer_widget_padding_h' ) . "px; border-radius: " . chuquipiondo_get_option( 'footer_widget_radius' ) . "px; border-width: " . chuquipiondo_get_option( 'footer_widget_border' ) . "px; border-color: var(--footer-widget-border-c); }\n";
	if ( chuquipiondo_is_enabled( 'footer_widget_shadow' ) ) {
		$css .= ".footer-widget{ box-shadow: var(--shadow-sm); }\n";
	}
	$css .= ".footer-widget-title{ font-weight: " . chuquipiondo_get_option( 'footer_title_weight' ) . "; text-transform: " . chuquipiondo_get_option( 'footer_title_transform' ) . "; letter-spacing: " . chuquipiondo_get_option( 'footer_title_letter_spacing' ) . "em; margin-bottom: " . chuquipiondo_get_option( 'footer_title_margin_bottom' ) . "px; }\n";
	$css .= ".site-footer{ line-height: " . chuquipiondo_get_option( 'footer_line_height' ) . "; }\n";
	$css .= ".site-footer a{ font-weight: " . chuquipiondo_get_option( 'footer_link_weight' ) . "; text-transform: " . chuquipiondo_get_option( 'footer_link_transform' ) . "; letter-spacing: " . chuquipiondo_get_option( 'footer_link_letter_spacing' ) . "em; padding: " . chuquipiondo_get_option( 'footer_link_padding_v' ) . "px " . chuquipiondo_get_option( 'footer_link_padding_h' ) . "px; border-radius: " . chuquipiondo_get_option( 'footer_link_radius' ) . "px; }\n";
	$css .= ".site-footer a:hover{ background: " . chuquipiondo_get_option( 'footer_link_hover_bg' ) . "; }\n";
	// Footer bottom layout.
	$bottom_layout = chuquipiondo_get_option( 'footer_bottom_layout' );
	$bottom_align = chuquipiondo_get_option( 'footer_bottom_align' );
	$css .= ".footer-bottom{ padding: " . chuquipiondo_get_option( 'footer_bottom_padding_v' ) . "px 0; }\n";
	if ( 'stacked' === $bottom_layout ) {
		$css .= ".footer-bottom{ flex-direction: column; text-align: center; }\n";
	} else {
		$css .= ".footer-bottom{ justify-content: " . $bottom_align . "; }\n";
	}
	$css .= ".footer-bottom a{ color: var(--footer-bottom-link); }\n";
	$css .= ".footer-bottom a:hover{ color: var(--footer-bottom-link-hover); }\n";
	$css .= ".footer-widgets{ margin-bottom: " . chuquipiondo_get_option( 'footer_section_gap' ) . "px; }\n";


	// Footer column heights
	$col1_h = chuquipiondo_get_option( 'footer_column1_height' );
	$col2_h = chuquipiondo_get_option( 'footer_column2_height' );
	$col3_h = chuquipiondo_get_option( 'footer_column3_height' );
	if ( $col1_h ) {
		$css .= ".footer-widget:nth-child(1){ min-height: {$col1_h}px; }\n";
	}
	if ( $col2_h ) {
		$css .= ".footer-widget:nth-child(2){ min-height: {$col2_h}px; }\n";
	}
	if ( $col3_h ) {
		$css .= ".footer-widget:nth-child(3){ min-height: {$col3_h}px; }\n";
	}
	// ===== Menu dynamic styles =====
	$css .= ".primary-menu > li > a{ color: var(--header-menu-text); font-size: " . chuquipiondo_get_option( 'header_menu_font_size' ) . "px; font-weight: " . chuquipiondo_get_option( 'header_menu_font_weight' ) . "; text-transform: " . chuquipiondo_get_option( 'header_menu_text_transform' ) . "; letter-spacing: " . chuquipiondo_get_option( 'header_menu_letter_spacing' ) . "em; padding: " . chuquipiondo_get_option( 'header_menu_item_padding_v' ) . "px " . chuquipiondo_get_option( 'header_menu_item_padding_h' ) . "px; border-radius: " . chuquipiondo_get_option( 'header_menu_border_radius' ) . "px; }\n";
	$css .= ".primary-menu > li > a:hover{ color: var(--header-menu-text-hover); }\n";
	$css .= ".primary-menu > li.current-menu-item > a{ color: var(--header-menu-active-text); background: var(--header-menu-active-bg); }\n";
	$css .= ".primary-menu .sub-menu{ background: " . chuquipiondo_get_option( 'header_menu_submenu_bg' ) . "; min-width: " . chuquipiondo_get_option( 'header_menu_submenu_width' ) . "px; border-radius: " . chuquipiondo_get_option( 'header_menu_submenu_radius' ) . "px; }\n";
	$css .= ".primary-menu .sub-menu a{ color: " . chuquipiondo_get_option( 'header_menu_submenu_text' ) . "; }\n";
	$css .= ".primary-menu .sub-menu a:hover{ color: " . chuquipiondo_get_option( 'header_menu_submenu_text_hover' ) . "; }\n";
	if ( chuquipiondo_is_enabled( 'header_menu_submenu_shadow' ) ) {
		$css .= ".primary-menu .sub-menu{ box-shadow: var(--shadow); }\n";
	}

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

	// ===== Page dynamic styles =====
	$css .= ".single-page{ background: " . chuquipiondo_get_option( 'page_bg_color' ) . "; }\n";
	$css .= ".single-page .entry-title{ color: " . chuquipiondo_get_option( 'page_title_color' ) . "; }\n";
	$css .= ".related-posts__grid{ grid-template-columns: repeat(" . chuquipiondo_get_option( 'single_related_columns' ) . ", minmax(0, 1fr)); }\n";
	// Tipografia del contenido del articulo (desde el personalizador).
	$css .= ".entry-content.single-article__content{ font-family: var(--single-content-font); font-size: var(--single-content-size); font-weight: var(--single-content-weight); line-height: var(--single-content-lh); }\n";
	$css .= ".entry-content.single-article__content > p{ font-size: var(--single-content-size); font-weight: var(--single-content-weight); line-height: var(--single-content-lh); }\n";
	$related_count = (int) chuquipiondo_get_option( 'single_related_rows' ) * (int) chuquipiondo_get_option( 'single_related_columns' );
	$css .= ".related-posts__grid > *:nth-child(n+" . ($related_count + 1) . "){ display: none; }\n";

	// ===== Single post dynamic styles =====
	// line-height del contenido ahora controlado por single_content_line_height (mas arriba).
	$css .= ".entry-content.single-article__content h1, .entry-content.single-article__content h2, .entry-content.single-article__content h3, .entry-content.single-article__content h4, .entry-content.single-article__content h5, .entry-content.single-article__content h6{ margin-top: " . chuquipiondo_get_option( 'single_heading_gap' ) . "px; margin-bottom: " . chuquipiondo_get_option( 'single_para_gap' ) . "px; }\n";
	$css .= ".entry-content.single-article__content p{ margin-bottom: " . chuquipiondo_get_option( 'single_para_gap' ) . "px; }\n";
	$css .= ".related-posts__grid{ grid-template-columns: repeat(" . chuquipiondo_get_option( 'single_related_columns' ) . ", minmax(0, 1fr)); gap: " . chuquipiondo_get_option( 'single_related_gap' ) . "px; }\n";
	// Page: gap + layout.
	$css .= ".page-layout{ margin-top: " . chuquipiondo_get_option( 'page_content_gap' ) . "px; }\n";

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
