<?php
/**
 * Blog / archive rendering: magazine cover + grid.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the blog/archive header (magazine-style cover).
 */
function chuquipiondo_blog_header() {
	if ( is_home() && ! is_front_page() ) {
		echo '<header class="page-header page-header--blog chuqui-container">';
		echo '<h1 class="page-title">' . esc_html__( 'Articulos', 'chuquipiondo' ) . '</h1>';
		echo '</header>';
	} elseif ( is_archive() ) {
		echo '<header class="page-header chuqui-container">';
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		the_archive_description( '<div class="archive-description">', '</div>' );
		echo '</header>';
	} elseif ( is_search() ) {
		echo '<header class="page-header chuqui-container">';
		/* translators: %s: search query */
		printf( '<h1 class="page-title">' . esc_html__( 'Resultados para: %s', 'chuquipiondo' ) . '</h1>', '<span>' . get_search_query() . '</span>' );
		echo '</header>';
	}
}

/**
 * Render the post grid.
 */
function chuquipiondo_blog_grid() {
	if ( ! have_posts() ) {
		echo '<p class="no-posts">' . esc_html__( 'No hay articulos para mostrar.', 'chuquipiondo' ) . '</p>';
		return;
	}

	$after_posts = (int) chuquipiondo_get_option( 'ads_blog_after_posts' );
	$style       = chuquipiondo_get_option( 'blog_card_style' );

	echo '<div class="post-grid">';

	$counter = 0;
	while ( have_posts() ) {
		the_post();
		$counter++;

		chuquipiondo_post_card( $style );

		// Insert ad after every Nth post.
		if ( $after_posts > 0 && 0 === ( $counter % $after_posts ) && $after_posts < $wp_query->post_count ) {
			echo '<div class="post-grid__ad">';
			chuquipiondo_ad_slot( 'ads_blog_after_row' );
			echo '</div>';
		}
	}

	echo '</div>';

	chuquipiondo_the_posts_pagination();
}

/**
 * Blog layout wrapper (content + sidebar).
 */
function chuquipiondo_blog() {
	chuquipiondo_blog_header();

	$classes = chuquipiondo_get_layout_classes();
	echo '<div class="chuqui-layout ' . esc_attr( $classes['wrap'] ) . '">';
	echo '<main id="primary" class="site-main ' . esc_attr( $classes['content'] ) . '" role="main">';

	// Breadcrumbs at the top.
	if ( chuquipiondo_is_enabled( 'single_show_breadcrumb' ) || is_archive() ) {
		chuquipiondo_breadcrumbs();
	}

	chuquipiondo_blog_grid();

	echo '</main>';

	chuquipiondo_get_sidebar();
	echo '</div>';
}
