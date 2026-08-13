<?php
/**
 * Single post rendering: layouts, elements, related, nav.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the single post.
 */
function chuquipiondo_single() {
	while ( have_posts() ) {
		the_post();
		$layout = chuquipiondo_get_option( 'single_layout' );

		$classes = chuquipiondo_get_layout_classes();
		echo '<div class="chuqui-layout ' . esc_attr( $classes['wrap'] ) . ' single-layout single-layout--' . esc_attr( sanitize_html_class( $layout ) ) . '">';

		if ( 'hero-image' === $layout ) {
			chuquipiondo_single_hero_image();
		}

		echo '<main id="primary" class="site-main ' . esc_attr( $classes['content'] ) . '" role="main">';
		echo '<article id="post-' . get_the_ID() . '" class="' . esc_attr( implode( ' ', get_post_class( 'single-article' ) ) ) . '" role="article">';
		echo '<div class="single-article__inner" style="max-width: var(--reading-width); margin-inline: auto;">';

		// Breadcrumb.
		if ( chuquipiondo_is_enabled( 'single_show_breadcrumb' ) ) {
			chuquipiondo_breadcrumbs();
		}

		// Header.
		echo '<header class="entry-header single-article__header">';

		if ( chuquipiondo_is_enabled( 'single_show_category' ) ) {
			chuquipiondo_primary_category();
		}

		the_title( '<h1 class="entry-title single-article__title">', '</h1>' );

		echo '<div class="entry-meta single-article__meta">';
		if ( chuquipiondo_is_enabled( 'single_show_author' ) ) {
			chuquipiondo_the_author();
		}
		if ( chuquipiondo_is_enabled( 'single_show_date' ) ) {
			chuquipiondo_the_date();
		}
		if ( chuquipiondo_is_enabled( 'single_show_reading' ) ) {
			echo '<span class="reading-time">' . esc_html( '|' ) . ' ';
			chuquipiondo_the_reading_time();
			echo '</span>';
		}
		echo '</div>';
		echo '</header>';

		// Ad after title.
		chuquipiondo_ad_slot( 'ads_after_title' );

		// Featured image (for non-hero-image layouts).
		if ( 'hero-image' !== $layout && has_post_thumbnail() ) {
			echo '<figure class="entry-thumbnail single-article__thumbnail">';
			the_post_thumbnail( 'chuquipiondo-featured', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) );
			echo '</figure>';
		}

		// Content with paragraph ads.
		echo '<div class="entry-content single-article__content">';
		chuquipiondo_the_content_with_ads();
		echo '</div>';

		// Tags.
		if ( chuquipiondo_is_enabled( 'single_show_tags' ) && has_tag() ) {
			echo '<footer class="entry-tags single-article__tags">';
			the_tags( '<span class="tags-label">' . esc_html__( 'Etiquetas:', 'chuquipiondo' ) . '</span> ', ' ' );
			echo '</footer>';
		}

		// Social share (after).
		if ( in_array( chuquipiondo_get_option( 'social_position' ), array( 'after', 'both' ), true ) ) {
			chuquipiondo_social_share();
		}

		echo '</div><!-- .single-article__inner -->';

		// Post End Extension Area (widgets + shortcode + elementor).
		echo '<div class="post-end-extension">';
		chuquipiondo_post_end_extension();
		echo '</div>';

		// Author bio.
		if ( chuquipiondo_is_enabled( 'single_show_bio' ) ) {
			chuquipiondo_author_bio();
		}

		// Ad before related.
		chuquipiondo_ad_slot( 'ads_before_related' );

		// Related posts.
		if ( chuquipiondo_is_enabled( 'single_show_related' ) ) {
			chuquipiondo_related_posts();
		}

		// Social share (before).
		if ( in_array( chuquipiondo_get_option( 'social_position' ), array( 'before', 'both' ), true ) ) {
			chuquipiondo_social_share();
		}

		// Prev/Next navigation.
		chuquipiondo_single_nav();

		echo '</article>';
		echo '</main><!-- #primary -->';

		chuquipiondo_get_sidebar();
		echo '</div>';
	}
}

/**
 * Output the post content with ads inserted after specific paragraphs.
 */
function chuquipiondo_the_content_with_ads() {
	$content = get_the_content();
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );

	// Only insert ads if the master switch is on.
	if ( ! chuquipiondo_ads_active() ) {
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- post content.
		return;
	}

	echo chuquipiondo_insert_ads_in_content( $content ); // phpcs:ignore WordPress.Security.EscapeOutput -- filtered below.
}

/**
 * Render the Post End Extension area.
 * Supports: widgets, shortcodes, Elementor templates.
 */
function chuquipiondo_post_end_extension() {
	/**
	 * Fires before the Post End Extension area.
	 */
	do_action( 'chuquipiondo_before_post_end_extension' );

	// Widgets.
	if ( is_active_sidebar( 'sidebar-post-end' ) ) {
		echo '<div class="post-end-widgets">';
		dynamic_sidebar( 'sidebar-post-end' );
		echo '</div>';
	}

	// Shortcode / HTML from Customizer.
	$extension = chuquipiondo_get_option( 'single_extension_area' );
	if ( $extension ) {
		echo '<div class="post-end-custom">';
		echo do_shortcode( wp_kses_post( $extension ) );
		echo '</div>';
	}

	/**
	 * Fires after the Post End Extension area.
	 */
	do_action( 'chuquipiondo_after_post_end_extension' );
}

/**
 * Render the author bio.
 */
function chuquipiondo_author_bio() {
	$author_id = get_the_author_meta( 'ID' );
	if ( ! $author_id ) {
		return;
	}
	?>
	<section class="author-bio">
		<div class="author-bio__avatar"><?php echo get_avatar( $author_id, 96 ); ?></div>
		<div class="author-bio__body">
			<h3 class="author-bio__name"><?php the_author(); ?></h3>
			<p class="author-bio__desc"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
			<a class="author-bio__link" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php esc_html_e( 'Ver todos los articulos', 'chuquipiondo' ); ?></a>
		</div>
	</section>
	<?php
}

/**
 * Render related posts.
 */
function chuquipiondo_related_posts() {
	$count = (int) chuquipiondo_get_option( 'single_related_count' );
	$title = chuquipiondo_get_option( 'single_related_title' );

	$cats = get_the_category();
	if ( empty( $cats ) ) {
		return;
	}
	$cat_ids = wp_list_pluck( $cats, 'term_id' );

	$q = new WP_Query( array(
		'post_type'           => 'post',
		'posts_per_page'      => $count,
		'category__in'        => $cat_ids,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => 1,
		'orderby'             => 'rand',
	) );

	if ( ! $q->have_posts() ) {
		return;
	}

	echo '<section class="related-posts">';
	echo '<header class="related-posts__header"><h2 class="related-posts__title">' . esc_html( $title ) . '</h2></header>';
	echo '<div class="post-grid related-posts__grid">';

	while ( $q->have_posts() ) {
		$q->the_post();
		chuquipiondo_post_card( 'minimal' );
	}

	echo '</div>';
	echo '</section>';
	wp_reset_postdata();
}

/**
 * Render the previous/next navigation.
 */
function chuquipiondo_single_nav() {
	$style = chuquipiondo_get_option( 'single_nav_style' );
	if ( 'hidden' === $style ) {
		return;
	}

	$prev = get_previous_post();
	$next = get_next_post();

	if ( ! $prev && ! $next ) {
		return;
	}

	echo '<nav class="post-navigation post-navigation--' . esc_attr( $style ) . '" aria-label="' . esc_attr__( 'Navegacion entre articulos', 'chuquipiondo' ) . '">';
	echo '<div class="post-navigation__inner chuqui-container">';

	if ( $prev ) {
		echo '<a class="post-nav post-nav--prev" href="' . esc_url( get_permalink( $prev ) ) . '">';
		echo '<span class="post-nav__label">&larr; ' . esc_html__( 'Anterior', 'chuquipiondo' ) . '</span>';
		if ( 'cards' === $style ) {
			echo '<span class="post-nav__title">' . esc_html( get_the_title( $prev ) ) . '</span>';
		}
		echo '</a>';
	} else {
		echo '<span class="post-nav post-nav--prev post-nav--empty"></span>';
	}

	if ( $next ) {
		echo '<a class="post-nav post-nav--next" href="' . esc_url( get_permalink( $next ) ) . '">';
		echo '<span class="post-nav__label">' . esc_html__( 'Siguiente', 'chuquipiondo' ) . ' &rarr;</span>';
		if ( 'cards' === $style ) {
			echo '<span class="post-nav__title">' . esc_html( get_the_title( $next ) ) . '</span>';
		}
		echo '</a>';
	} else {
		echo '<span class="post-nav post-nav--next post-nav--empty"></span>';
	}

	echo '</div>';
	echo '</nav>';
}

/**
 * Render the hero image variant of the single post.
 */
function chuquipiondo_single_hero_image() {
	if ( ! has_post_thumbnail() ) {
		return;
	}
	?>
	<div class="single-hero-image">
		<?php the_post_thumbnail( 'chuquipiondo-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
		<div class="single-hero-image__overlay"></div>
	</div>
	<?php
}
