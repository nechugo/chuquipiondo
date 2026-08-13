<?php
/**
 * Sidebar layout selection and rendering helpers.
 *
 * Supports independent sidebars for each view (blog/archive,
 * single post, page) with per-post override via meta box
 * (Astra-style). The sidebar width is configurable and
 * responsive (collapses below the content on mobile/tablet).
 *
 * @package CHUQUIPIONDO
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

	// No sidebar if the active sidebar has no widgets.
	if ( 'none' !== $position ) {
		$sidebar_id = chuquipiondo_get_sidebar_id();
		if ( $sidebar_id && ! is_active_sidebar( $sidebar_id ) ) {
			// Try the fallback sidebar before giving up.
			if ( ! is_active_sidebar( 'sidebar-1' ) ) {
				$position = 'none';
			}
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
 * Get layout classes for content + sidebar grid.
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
	$wrap     = 'layout-' . $position . ' layout-view-' . $view;

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
 */
function chuquipiondo_get_sidebar() {
	if ( 'none' === chuquipiondo_get_sidebar_position() ) {
		return;
	}
	chuquipiondo_get_template_part( 'template-parts/sidebar' );
}
