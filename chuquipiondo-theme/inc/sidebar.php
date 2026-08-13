<?php
/**
 * Sidebar layout selection and rendering helpers.
 *
 * Supports independent sidebars for each view (blog/archive,
 * single post, page) with per-post override via meta box
 * (Astra-style). The sidebar width is configurable (default
 * 320px) and responsive: it can be shown/hidden independently
 * on desktop and mobile.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the sidebar position for the current view.
 *
 * Resolution order:
 *   1. Per-post override (meta box) if set and not "default".
 *   2. Customizer option for the current view type.
 *   3. Fallback to "right".
 *
 * Note: the per-device show/hide is handled separately via
 * chuquipiondo_sidebar_show_on() so the position (left/right)
 * is preserved regardless of device visibility.
 *
 * @return string left|right|none
 */
function chuquipiondo_get_sidebar_position() {
	$position = 'right';
	$view     = chuquipiondo_current_view();

	// Check per-post override first (Astra-style meta box).
	$override = chuquipiondo_get_layout_override();
	if ( $override && 'default' !== $override ) {
		$position = $override;
	} else {
		// Use the Customizer option for the current view.
		switch ( $view ) {
			case 'single':
				$position = chuquipiondo_get_option( 'single_sidebar' );
				break;
			case 'page':
				$position = chuquipiondo_get_option( 'page_sidebar' );
				break;
			case 'blog':
			default:
				$position = chuquipiondo_get_option( 'blog_sidebar' );
				break;
		}
	}

	/**
	 * Filters the sidebar position.
	 *
	 * @param string $position left|right|none
	 */
	$position = apply_filters( 'chuquipiondo_sidebar_position', $position );

	// If position is "none", no sidebar at all.
	if ( 'none' === $position ) {
		return 'none';
	}

	// Check if the active sidebar has widgets.
	$sidebar_id = chuquipiondo_get_sidebar_id();
	if ( $sidebar_id && ! is_active_sidebar( $sidebar_id ) ) {
		// Try the fallback sidebar before giving up.
		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			return 'none';
		}
	}

	return $position;
}

/**
 * Determine the current view type for sidebar selection.
 *
 * @return string blog|single|page|home
 */
function chuquipiondo_current_view() {
	if ( is_singular( 'post' ) ) {
		return 'single';
	}
	if ( is_page() ) {
		return 'page';
	}
	if ( is_home() || is_archive() || is_search() ) {
		return 'blog';
	}
	return 'blog';
}

/**
 * Get the sidebar ID for the current view.
 *
 * Each view has its own independent sidebar; if that sidebar is
 * empty, the fallback `sidebar-1` is used.
 *
 * @return string
 */
function chuquipiondo_get_sidebar_id() {
	$view = chuquipiondo_current_view();

	switch ( $view ) {
		case 'single':
			return 'sidebar-single';
		case 'page':
			return 'sidebar-page';
		case 'blog':
		default:
			return 'sidebar-blog';
	}
}

/**
 * Get the per-post layout override (from the meta box).
 *
 * @return string default|left|right|none
 */
function chuquipiondo_get_layout_override() {
	if ( ! is_singular() ) {
		return 'default';
	}
	$override = get_post_meta( get_the_ID(), '_chuquipiondo_sidebar', true );
	if ( ! $override ) {
		return 'default';
	}
	return $override;
}

/**
 * Check whether the sidebar should be shown on a specific device.
 *
 * Each view (blog, single, page) has its own "show on desktop" and
 * "show on mobile" toggle in the Customizer. This allows the user
 * to, for example, show the sidebar on desktop but hide it on mobile.
 *
 * @param string $device desktop|mobile.
 * @return bool
 */
function chuquipiondo_sidebar_show_on( $device ) {
	$view = chuquipiondo_current_view();

	$key_map = array(
		'blog'   => array(
			'desktop' => 'blog_sidebar_desktop',
			'mobile'  => 'blog_sidebar_mobile',
		),
		'single' => array(
			'desktop' => 'single_sidebar_desktop',
			'mobile'  => 'single_sidebar_mobile',
		),
		'page'   => array(
			'desktop' => 'page_sidebar_desktop',
			'mobile'  => 'page_sidebar_mobile',
		),
	);

	if ( ! isset( $key_map[ $view ] ) || ! isset( $key_map[ $view ][ $device ] ) ) {
		return true;
	}

	$key = $key_map[ $view ][ $device ];

	/**
	 * Filters whether the sidebar should show on a device.
	 *
	 * @param bool   $show   Whether to show.
	 * @param string $device desktop|mobile.
	 * @param string $view   Current view (blog|single|page).
	 */
	return (bool) apply_filters( 'chuquipiondo_sidebar_show_on', chuquipiondo_is_enabled( $key ), $device, $view );
}

/**
 * Get layout classes for content + sidebar grid.
 *
 * Includes device-visibility classes so the CSS can hide the
 * sidebar on mobile when configured to do so.
 *
 * @return array {
 *     @type string $content  Content CSS class.
 *     @type string $sidebar  Sidebar CSS class.
 *     @type string $wrap     Wrap CSS class.
 * }
 */
function chuquipiondo_get_layout_classes() {
	$position = chuquipiondo_get_sidebar_position();
	$view     = chuquipiondo_current_view();

	// Device visibility classes.
	$show_desktop = chuquipiondo_sidebar_show_on( 'desktop' );
	$show_mobile  = chuquipiondo_sidebar_show_on( 'mobile' );

	// If hidden on BOTH devices, treat as "none".
	if ( ! $show_desktop && ! $show_mobile ) {
		$position = 'none';
	}

	$wrap = 'layout-' . $position . ' layout-view-' . $view;

	// Add device-visibility modifier classes.
	if ( 'none' !== $position ) {
		if ( ! $show_mobile ) {
			$wrap .= ' sidebar-hide-mobile';
		}
		if ( ! $show_desktop ) {
			$wrap .= ' sidebar-hide-desktop';
		}
	}

	if ( 'none' === $position ) {
		return array(
			'wrap'    => $wrap,
			'content' => 'content--full',
			'sidebar' => '',
		);
	}

	return array(
		'wrap'    => $wrap,
		'content' => 'content--' . $position,
		'sidebar' => 'sidebar--' . $position,
	);
}

/**
 * Render the sidebar template-part.
 *
 * The sidebar markup is always rendered (so the grid stays intact)
 * but CSS classes control visibility per device.
 */
function chuquipiondo_get_sidebar() {
	if ( 'none' === chuquipiondo_get_sidebar_position() ) {
		return;
	}
	chuquipiondo_get_template_part( 'template-parts/sidebar' );
}
