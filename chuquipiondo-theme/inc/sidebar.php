<?php
/**
 * Sidebar layout selection and rendering helpers.
 *
 * Handles the configurable sidebar (right / left / none) used by
 * the blog archive and single posts.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the sidebar position for the current view.
 *
 * @return string left|right|none
 */
function chuquipiondo_get_sidebar_position() {
	$position = 'right';
	if ( is_singular( 'post' ) ) {
		$position = chuquipiondo_get_option( 'single_sidebar' );
	} elseif ( is_home() || is_archive() || is_search() ) {
		$position = chuquipiondo_get_option( 'blog_sidebar' );
	}

	/**
	 * Filters the sidebar position.
	 *
	 * @param string $position left|right|none
	 */
	$position = apply_filters( 'chuquipiondo_sidebar_position', $position );

	// No sidebar if there are no widgets.
	if ( 'none' !== $position && ! is_active_sidebar( 'sidebar-1' ) ) {
		$position = 'none';
	}

	return $position;
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
	$wrap     = 'layout-' . $position;

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
