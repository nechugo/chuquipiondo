<?php
/**
 * Custom shortcodes for the CHUQUIPIONDO Core plugin.
 *
 * @package CHUQUIPIONDO_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode: [chuquipiondo_button text="Leer mas" url="#" icon="arrow-right"]
 */
function chuquipiondo_core_button_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'text'     => __( 'Boton', 'chuquipiondo-core' ),
		'url'       => '#',
		'icon'      => '',
		'target'    => '',
		'class'     => 'btn',
		'position'  => 'after',
	), $atts, 'chuquipiondo_button' );

	if ( function_exists( 'chuquipiondo_button' ) ) {
		ob_start();
		chuquipiondo_button( $atts['text'], $atts['url'], array(
			'class'         => $atts['class'],
			'target'        => $atts['target'],
			'icon'          => $atts['icon'],
			'icon_position' => $atts['position'],
		) );
		return ob_get_clean();
	}

	$target = $atts['target'] ? ' target="' . esc_attr( $atts['target'] ) . '"' : '';
	return '<a href="' . esc_url( $atts['url'] ) . '" class="' . esc_attr( $atts['class'] ) . '"' . $target . '>' . esc_html( $atts['text'] ) . '</a>';
}
add_shortcode( 'chuquipiondo_button', 'chuquipiondo_core_button_shortcode' );

/**
 * Shortcode: [chuquipiondo_posts count="6" columns="3" category="liderazgo"]
 */
function chuquipiondo_core_posts_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'count'    => '6',
		'columns'  => '3',
		'category' => '',
		'style'    => 'editorial',
		'orderby'   => 'date',
		'order'    => 'DESC',
	), $atts, 'chuquipiondo_posts' );

	$query_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => (int) $atts['count'],
		'orderby'             => $atts['orderby'],
		'order'               => $atts['order'],
		'ignore_sticky_posts' => 1,
	);

	if ( $atts['category'] ) {
		$query_args['category_name'] = $atts['category'];
	}

	$q = new WP_Query( $query_args );

	if ( ! $q->have_posts() ) {
		return '<p>' . esc_html__( 'No hay articulos para mostrar.', 'chuquipiondo-core' ) . '</p>';
	}

	$columns = max( 1, min( 4, (int) $atts['columns'] ) );
	$style   = sanitize_html_class( $atts['style'] );

	ob_start();
	echo '<div class="post-grid chuquipiondo-core-posts chuquipiondo-core-posts--cols-' . $columns . '">';

	while ( $q->have_posts() ) {
		$q->the_post();
		if ( function_exists( 'chuquipiondo_post_card' ) ) {
			chuquipiondo_post_card( $style );
		} else {
			echo '<article class="post-card"><h2><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h2></article>';
		}
	}

	echo '</div>';
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'chuquipiondo_posts', 'chuquipiondo_core_posts_shortcode' );

/**
 * Shortcode: [chuquipiondo_music count="4" columns="2"]
 */
function chuquipiondo_core_music_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'count'   => '4',
		'columns' => '2',
	), $atts, 'chuquipiondo_music' );

	$q = new WP_Query( array(
		'post_type'           => 'musica',
		'posts_per_page'      => (int) $atts['count'],
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => true,
	) );

	if ( ! $q->have_posts() ) {
		return '<p>' . esc_html__( 'No hay canciones para mostrar.', 'chuquipiondo-core' ) . '</p>';
	}

	$columns = max( 1, min( 3, (int) $atts['columns'] ) );

	ob_start();
	echo '<div class="music-grid chuquipiondo-core-music chuquipiondo-core-music--cols-' . $columns . '">';

	while ( $q->have_posts() ) {
		$q->the_post();
		?>
		<article class="music-card">
			<?php if ( has_post_thumbnail() ) : ?>
				<a href="<?php the_permalink(); ?>" class="music-card__cover">
					<?php the_post_thumbnail( 'chuquipiondo-square', array( 'loading' => 'lazy' ) ); ?>
				</a>
			<?php endif; ?>
			<div class="music-card__body">
				<h3 class="music-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</div>
		</article>
		<?php
	}

	echo '</div>';
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'chuquipiondo_music', 'chuquipiondo_core_music_shortcode' );

/**
 * Shortcode: [chuquipiondo_categories count="6"]
 */
function chuquipiondo_core_categories_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'count' => '6',
	), $atts, 'chuquipiondo_categories' );

	$cats = get_categories( array(
		'number'     => (int) $atts['count'],
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );

	if ( empty( $cats ) ) {
		return '';
	}

	ob_start();
	echo '<div class="home-categories__grid chuquipiondo-core-categories">';
	foreach ( $cats as $cat ) {
		echo '<a class="category-card" href="' . esc_url( get_category_link( $cat->term_id ) ) . '">';
		echo '<span class="category-card__name">' . esc_html( $cat->name ) . '</span>';
		echo '<span class="category-card__count">' . esc_html( $cat->count ) . '</span>';
		echo '</a>';
	}
	echo '</div>';
	return ob_get_clean();
}
add_shortcode( 'chuquipiondo_categories', 'chuquipiondo_core_categories_shortcode' );

/**
 * Shortcode: [chuquipiondo_social_profiles]
 */
function chuquipiondo_core_social_profiles_shortcode() {
	if ( ! function_exists( 'chuquipiondo_social_profiles_links' ) ) {
		return '';
	}
	ob_start();
	chuquipiondo_social_profiles_links();
	return ob_get_clean();
}
add_shortcode( 'chuquipiondo_social_profiles', 'chuquipiondo_core_social_profiles_shortcode' );

/**
 * Shortcode: [chuquipiondo_ad slot="ads_sidebar_top"]
 */
function chuquipiondo_core_ad_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'slot' => '',
	), $atts, 'chuquipiondo_ad' );

	if ( empty( $atts['slot'] ) || ! function_exists( 'chuquipiondo_ad_slot' ) ) {
		return '';
	}

	ob_start();
	chuquipiondo_ad_slot( $atts['slot'] );
	return ob_get_clean();
}
add_shortcode( 'chuquipiondo_ad', 'chuquipiondo_core_ad_shortcode' );

/**
 * Shortcode: [chuquipiondo_breadcrumbs]
 */
function chuquipiondo_core_breadcrumbs_shortcode() {
	if ( ! function_exists( 'chuquipiondo_breadcrumbs' ) ) {
		return '';
	}
	ob_start();
	chuquipiondo_breadcrumbs();
	return ob_get_clean();
}
add_shortcode( 'chuquipiondo_breadcrumbs', 'chuquipiondo_core_breadcrumbs_shortcode' );

/**
 * Shortcode: [chuquipiondo_trending count="4" days="30"]
 * Top posts by comments in a recent window; falls back to most commented.
 */
function chuquipiondo_core_trending_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'count' => '4', 'days' => '30' ), $atts, 'chuquipiondo_trending' );
	$days = max( 1, (int) $atts['days'] );
	$q = new WP_Query( array(
		'post_type'           => 'post',
		'posts_per_page'      => max( 1, (int) $atts['count'] ),
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => true,
		'orderby'             => 'comment_count',
		'date_query'          => array( array( 'after' => $days . ' days ago' ) ),
	) );
	if ( ! $q->have_posts() ) {
		$q = new WP_Query( array(
			'post_type'           => 'post',
			'posts_per_page'      => max( 1, (int) $atts['count'] ),
			'ignore_sticky_posts' => 1,
			'no_found_rows'       => true,
			'orderby'             => 'comment_count',
		) );
	}
	if ( ! $q->have_posts() ) {
		return '';
	}
	$out = '<ul class="chuqui-trending">';
	while ( $q->have_posts() ) {
		$q->the_post();
		$comments = (int) get_comments_number();
		$out .= '<li class="chuqui-trending__item">';
		$out .= '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
		$out .= '<span class="chuqui-trending__meta">' . esc_html( sprintf( _n( '%s comentario', '%s comentarios', $comments, 'chuquipiondo-core' ), number_format_i18n( $comments ) ) ) . '</span>';
		$out .= '</li>';
	}
	$out .= '</ul>';
	wp_reset_postdata();
	return $out;
}
add_shortcode( 'chuquipiondo_trending', 'chuquipiondo_core_trending_shortcode' );

/**
 * Shortcode: [chuquipiondo_quote text="..." author="..." align="center"]
 * Editorial pull-quote in the Chuquipiondo aesthetic.
 */
function chuquipiondo_core_quote_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts( array( 'text' => '', 'author' => '', 'align' => 'center' ), $atts, 'chuquipiondo_quote' );
	$text = '' !== trim( (string) $content ) ? trim( wp_strip_all_tags( $content ) ) : sanitize_text_field( $atts['text'] );
	if ( '' === $text ) {
		return '';
	}
	$align = in_array( $atts['align'], array( 'left', 'center', 'right' ), true ) ? $atts['align'] : 'center';
	$author = sanitize_text_field( $atts['author'] );
	$out = '<figure class="chuqui-quote chuqui-quote--' . esc_attr( $align ) . '">';
	$out .= '<blockquote class="chuqui-quote__text">&ldquo;' . esc_html( $text ) . '&rdquo;</blockquote>';
	if ( '' !== $author ) {
		$out .= '<figcaption class="chuqui-quote__author">&mdash; ' . esc_html( $author ) . '</figcaption>';
	}
	$out .= '</figure>';
	return $out;
}
add_shortcode( 'chuquipiondo_quote', 'chuquipiondo_core_quote_shortcode' );

/**
 * Shortcode: [chuquipiondo_series ids="12,34,56" title="Serie"]
 * Editorial series: linked parts with current-post highlighting.
 */
function chuquipiondo_core_series_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'ids' => '', 'title' => '' ), $atts, 'chuquipiondo_series' );
	$ids = array_filter( array_map( 'absint', explode( ',', $atts['ids'] ) ) );
	if ( empty( $ids ) ) {
		return '';
	}
	$q = new WP_Query( array(
		'post_type'      => 'any',
		'post__in'       => $ids,
		'orderby'        => 'post__in',
		'posts_per_page' => count( $ids ),
		'no_found_rows'  => true,
	) );
	if ( ! $q->have_posts() ) {
		return '';
	}
	$current = get_the_ID();
	$out = '<nav class="chuqui-series">';
	if ( '' !== $atts['title'] ) {
		$out .= '<h3 class="chuqui-series__title">' . esc_html( sanitize_text_field( $atts['title'] ) ) . '</h3>';
	}
	$out .= '<ol class="chuqui-series__list">';
	while ( $q->have_posts() ) {
		$q->the_post();
		$is_current = ( get_the_ID() === $current );
		$out .= '<li class="chuqui-series__item' . ( $is_current ? ' chuqui-series__item--current' : '' ) . '">';
		$out .= $is_current
			? '<span class="chuqui-series__current">' . esc_html( get_the_title() ) . '</span>'
			: '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
		$out .= '</li>';
	}
	$out .= '</ol>';
	$out .= '</nav>';
	wp_reset_postdata();
	return $out;
}
add_shortcode( 'chuquipiondo_series', 'chuquipiondo_core_series_shortcode' );
