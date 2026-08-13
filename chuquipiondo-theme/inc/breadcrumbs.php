<?php
/**
 * Lightweight native breadcrumbs (no plugin required).
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render breadcrumbs. Respects Rank Math / Yoast if installed.
 */
function chuquipiondo_breadcrumbs() {
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		rank_math_the_breadcrumbs();
		return;
	}
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="chuqui-breadcrumbs" aria-label="breadcrumb">', '</nav>' );
		return;
	}

	$items = chuquipiondo_build_breadcrumb_items();
	if ( empty( $items ) ) {
		return;
	}

	echo '<nav class="chuqui-breadcrumbs" aria-label="' . esc_attr__( 'Migas de pan', 'chuquipiondo' ) . '"><ol>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $item ) {
		$classes = 'breadcrumb-item';
		if ( $i === $last ) {
			$classes .= ' breadcrumb-item--current';
		}
		echo '<li class="' . esc_attr( $classes ) . '">';
		if ( $i === $last ) {
			echo esc_html( $item['label'] );
		} else {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
			echo '<span class="sep" aria-hidden="true">/</span>';
		}
		echo '</li>';
	}
	echo '</ol></nav>';
}

/**
 * Build the breadcrumb item list.
 *
 * @return array
 */
function chuquipiondo_build_breadcrumb_items() {
	$items   = array();
	$items[] = array(
		'label' => __( 'Inicio', 'chuquipiondo' ),
		'url'   => home_url( '/' ),
	);

	if ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( ! empty( $cats ) ) {
			$cat = $cats[0];
			while ( $cat->parent ) {
				$parent = get_category( $cat->parent );
				if ( $parent && ! is_wp_error( $parent ) ) {
					array_unshift( $items, array( 'label' => $parent->name, 'url' => get_category_link( $parent->term_id ) ) );
				}
			}
			$items[] = array( 'label' => $cat->name, 'url' => get_category_link( $cat->term_id ) );
		}
		$items[] = array( 'label' => get_the_title(), 'url' => get_permalink() );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			$items[] = array( 'label' => $term->name, 'url' => get_term_link( $term ) );
		}
	} elseif ( is_author() ) {
		$items[] = array( 'label' => get_the_author(), 'url' => '' );
	} elseif ( is_post_type_archive( 'musica' ) ) {
		$items[] = array( 'label' => __( 'Musica', 'chuquipiondo' ), 'url' => get_post_type_archive_link( 'musica' ) );
	} elseif ( is_singular( 'musica' ) ) {
		$items[] = array( 'label' => __( 'Musica', 'chuquipiondo' ), 'url' => get_post_type_archive_link( 'musica' ) );
		$items[] = array( 'label' => get_the_title(), 'url' => get_permalink() );
	} elseif ( is_archive() ) {
		$items[] = array( 'label' => get_the_archive_title(), 'url' => '' );
	} elseif ( is_search() ) {
		$items[] = array( 'label' => sprintf( __( 'Busqueda: %s', 'chuquipiondo' ), get_search_query() ), 'url' => '' );
	} elseif ( is_page() ) {
		$items[] = array( 'label' => get_the_title(), 'url' => get_permalink() );
	}

	return $items;
}
