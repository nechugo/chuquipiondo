<?php
/**
 * Home builder: modular, reorderable sections.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the ordered list of active home modules.
 *
 * @return array
 */
function chuquipiondo_home_modules() {
	$raw      = chuquipiondo_get_option( 'home_modules' );
	$modules  = is_array( $raw ) ? $raw : array_filter( array_map( 'trim', explode( ',', (string) $raw ) ) );

	$allowed  = array( 'hero', 'featured', 'latest', 'categories', 'song', 'videos', 'about', 'newsletter' );
	$modules  = array_values( array_filter( $modules, function ( $m ) use ( $allowed ) {
		return in_array( $m, $allowed, true );
	} ) );

	/**
	 * Filters the active home modules and their order.
	 *
	 * @param array $modules Module slugs.
	 */
	return apply_filters( 'chuquipiondo_home_modules', $modules );
}

/**
 * Render the home page modules.
 */
function chuquipiondo_home() {
	if ( ! ( is_front_page() || is_home() ) ) {
		return;
	}

	$modules = chuquipiondo_home_modules();
	if ( empty( $modules ) ) {
		return;
	}

	echo '<div class="home-builder">';

	$ad_index = 1;
	foreach ( $modules as $index => $module ) {
		chuquipiondo_home_module( $module );

		// Insert home ad slot between modules.
		if ( $ad_index <= 3 && is_active_sidebar( 'sidebar-home-ads-' . $ad_index ) ) {
			echo '<div class="home-builder__ads home-builder__ads--' . (int) $ad_index . '">';
			dynamic_sidebar( 'sidebar-home-ads-' . $ad_index );
			echo '</div>';
			$ad_index++;
		}
	}

	echo '</div><!-- .home-builder -->';
}
add_action( 'chuquipiondo_home', 'chuquipiondo_home' );

/**
 * Render a single home module.
 *
 * @param string $module Module slug.
 */
function chuquipiondo_home_module( $module ) {
	/**
	 * Fires before a home module renders.
	 *
	 * @param string $module Module slug.
	 */
	do_action( 'chuquipiondo_before_home_module', $module );

	switch ( $module ) {
		case 'hero':
			// Hero is rendered via its own hook on home.
			chuquipiondo_hero();
			break;
		case 'featured':
			chuquipiondo_home_featured();
			break;
		case 'latest':
			chuquipiondo_home_latest();
			break;
		case 'categories':
			chuquipiondo_home_categories();
			break;
		case 'song':
			chuquipiondo_home_song();
			break;
		case 'videos':
			chuquipiondo_home_videos();
			break;
		case 'about':
			chuquipiondo_home_about();
			break;
		case 'newsletter':
			chuquipiondo_home_newsletter();
			break;
	}

	/**
	 * Fires after a home module renders (allows custom modules).
	 *
	 * @param string $module Module slug.
	 */
	do_action( 'chuquipiondo_after_home_module', $module );
}

/**
 * Featured articles module.
 */
function chuquipiondo_home_featured() {
	$count = (int) chuquipiondo_get_option( 'home_featured_count' );
	$title = chuquipiondo_get_option( 'home_featured_title' );

	$q = new WP_Query( array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => 1,
		'tax_query'           => array(
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => 'destacado',
				'operator' => 'IN',
			),
		),
	) );

	// Fallback to latest if no featured tag.
	if ( ! $q->have_posts() ) {
		$q = new WP_Query( array(
			'post_type'           => 'post',
			'posts_per_page'      => $count,
			'ignore_sticky_posts' => 1,
			'orderby'             => 'date',
			'order'               => 'DESC',
		) );
	}

	if ( ! $q->have_posts() ) {
		return;
	}

	echo '<section class="home-module home-featured chuqui-container">';
	echo '<header class="home-module__header"><h2 class="home-module__title">' . esc_html( $title ) . '</h2></header>';
	echo '<div class="home-featured__grid">';

	while ( $q->have_posts() ) {
		$q->the_post();
		chuquipiondo_post_card( 'magazine' );
	}

	echo '</div>';
	echo '</section>';
	wp_reset_postdata();
}

/**
 * Latest articles module.
 */
function chuquipiondo_home_latest() {
	$count = (int) chuquipiondo_get_option( 'home_latest_count' );
	$title = chuquipiondo_get_option( 'home_latest_title' );

	$q = new WP_Query( array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => 1,
		'orderby'             => 'date',
		'order'               => 'DESC',
	) );

	if ( ! $q->have_posts() ) {
		return;
	}

	echo '<section class="home-module home-latest chuqui-container">';
	echo '<header class="home-module__header"><h2 class="home-module__title">' . esc_html( $title ) . '</h2>';
	echo '<a class="home-module__more" href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'Ver todo', 'chuquipiondo' ) . ' &rarr;</a>';
	echo '</header>';
	echo '<div class="post-grid">';

	while ( $q->have_posts() ) {
		$q->the_post();
		chuquipiondo_post_card();
	}

	echo '</div>';
	echo '</section>';
	wp_reset_postdata();
}

/**
 * Categories module.
 */
function chuquipiondo_home_categories() {
	$title = chuquipiondo_get_option( 'home_categories_title' );
	$cats  = get_categories( array(
		'number'     => 6,
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );

	if ( empty( $cats ) ) {
		return;
	}

	echo '<section class="home-module home-categories chuqui-container">';
	echo '<header class="home-module__header"><h2 class="home-module__title">' . esc_html( $title ) . '</h2></header>';
	echo '<div class="home-categories__grid">';

	foreach ( $cats as $cat ) {
		echo '<a class="category-card" href="' . esc_url( get_category_link( $cat->term_id ) ) . '">';
		echo '<span class="category-card__name">' . esc_html( $cat->name ) . '</span>';
		/* translators: %d: post count */
		echo '<span class="category-card__count">' . esc_html( sprintf( _n( '%d articulo', '%d articulos', $cat->count, 'chuquipiondo' ), $cat->count ) ) . '</span>';
		echo '</a>';
	}

	echo '</div>';
	echo '</section>';
}

/**
 * Featured song module.
 */
function chuquipiondo_home_song() {
	$song_id = (int) chuquipiondo_get_option( 'home_song_id' );
	$title   = chuquipiondo_get_option( 'home_song_title' );

	if ( ! $song_id || 'publish' !== get_post_status( $song_id ) ) {
		return;
	}

	echo '<section class="home-module home-song chuqui-container">';
	echo '<header class="home-module__header"><h2 class="home-module__title">' . esc_html( $title ) . '</h2></header>';
	echo '<div class="home-song__inner">';
	chuquipiondo_music_player( $song_id );
	echo '</div>';
	echo '</section>';
}

/**
 * Videos module.
 */
function chuquipiondo_home_videos() {
	$count    = (int) chuquipiondo_get_option( 'home_videos_count' );
	$title    = chuquipiondo_get_option( 'home_videos_title' );
	$playlist = chuquipiondo_get_option( 'home_videos_playlist' );

	if ( ! $playlist ) {
		return;
	}

	echo '<section class="home-module home-videos chuqui-container">';
	echo '<header class="home-module__header"><h2 class="home-module__title">' . esc_html( $title ) . '</h2></header>';
	echo '<div class="home-videos__embed">';
	echo wp_oembed_get( esc_url( $playlist ) );
	echo '</div>';
	echo '</section>';
}

/**
 * About module.
 */
function chuquipiondo_home_about() {
	$title  = chuquipiondo_get_option( 'home_about_title' );
	$text   = chuquipiondo_get_option( 'home_about_text' );
	$avatar = chuquipiondo_get_option( 'home_about_image' );

	echo '<section class="home-module home-about chuqui-container">';
	echo '<div class="home-about__inner">';
	if ( $avatar ) {
		echo '<div class="home-about__avatar"><img src="' . esc_url( $avatar ) . '" alt="' . esc_attr( $title ) . '" loading="lazy"></div>';
	}
	echo '<div class="home-about__body">';
	echo '<header class="home-module__header"><h2 class="home-module__title">' . esc_html( $title ) . '</h2></header>';
	echo '<p class="home-about__text">' . esc_html( $text ) . '</p>';
	echo '</div>';
	echo '</div>';
	echo '</section>';
}

/**
 * Newsletter / CTA module.
 */
function chuquipiondo_home_newsletter() {
	$title     = chuquipiondo_get_option( 'home_newsletter_title' );
	$text      = chuquipiondo_get_option( 'home_newsletter_text' );
	$shortcode = chuquipiondo_get_option( 'home_newsletter_shortcode' );

	echo '<section class="home-module home-newsletter">';
	echo '<div class="home-newsletter__inner chuqui-container">';
	echo '<header class="home-module__header"><h2 class="home-module__title">' . esc_html( $title ) . '</h2></header>';
	echo '<p class="home-newsletter__text">' . esc_html( $text ) . '</p>';
	if ( $shortcode ) {
		echo '<div class="home-newsletter__form">' . do_shortcode( $shortcode ) . '</div>';
	} else {
		echo '<div class="home-newsletter__form home-newsletter__form--placeholder">';
		echo '<input type="email" placeholder="' . esc_attr__( 'Tu correo electronico', 'chuquipiondo' ) . '" disabled>';
		echo '<button type="button" disabled>' . esc_html__( 'Suscribirme', 'chuquipiondo' ) . '</button>';
		echo '</div>';
	}
	echo '</div>';
	echo '</section>';
}
