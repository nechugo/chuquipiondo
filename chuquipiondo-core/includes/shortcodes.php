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
	echo '<div class="post-grid chuquipiondo-core-posts" style="grid-template-columns: repeat(' . $columns . ', minmax(0, 1fr));">';

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
	) );

	if ( ! $q->have_posts() ) {
		return '<p>' . esc_html__( 'No hay canciones para mostrar.', 'chuquipiondo-core' ) . '</p>';
	}

	$columns = max( 1, min( 3, (int) $atts['columns'] ) );

	ob_start();
	echo '<div class="music-grid chuquipiondo-core-music" style="grid-template-columns: repeat(' . $columns . ', minmax(0, 1fr));">';

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
