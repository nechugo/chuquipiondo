<?php
/**
 * Single post rendering: layouts, elements, related, nav (v1.5.0).
 *
 * Interfaz actualizada:
 * - Author bar con avatar, compartir, A-/A/A+, copiar enlace, imprimir
 * - Hero con imagen 16:9 y stamp decorativo
 * - Párrafos en cajas blancas que se adaptan a los ads
 * - Ads: 728x90 wide, 336x280 box, responsive
 * - Carrusel de artículos recomendados (3 mínimo)
 * - Relacionados en grid de 4 con skeleton cards
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
		echo '<div class="single-article__inner">';

		// Author bar with tools.
		chuquipiondo_single_author_bar();

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

		// Ad after title (728x90 wide).
		echo '<div class="article-ad-wide">';
		chuquipiondo_ad_slot( 'ads_after_title' );
		echo '</div>';

		// Featured image with stamp.
		if ( 'hero-image' !== $layout && has_post_thumbnail() ) {
			echo '<figure class="entry-thumbnail single-article__thumbnail">';
			the_post_thumbnail( 'chuquipiondo-featured', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) );
			echo '<div class="entry-thumbnail__stamp">' . esc_html__( '16:9 responsive', 'chuquipiondo' ) . '</div>';
			echo '</figure>';
		}

		// Content with paragraph ads.
		echo '<div class="entry-content single-article__content">';
		chuquipiondo_the_content_with_ads();
		echo '</div>';

		// Ad box (336x280) + paragraph row.
		echo '<div class="article-row">';
		echo '<div class="article-ad-box">';
		chuquipiondo_ad_slot( 'ads_after_paragraph_3' );
		echo '</div>';
		echo '<div class="paragraph-box"><p>' . esc_html__( 'Parrafo', 'chuquipiondo' ) . '</p></div>';
		echo '</div>';

		// Responsive ad.
		echo '<div class="article-ad-responsive">';
		chuquipiondo_ad_slot( 'ads_after_paragraph_6' );
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

		// Carousel of recommended articles.
		chuquipiondo_single_carousel();

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
		echo '</main>';

		chuquipiondo_get_sidebar();
		echo '</div>';
	}
}

/**
 * Render the author bar with avatar and tools.
 */
function chuquipiondo_single_author_bar() {
	$author_id = get_the_author_meta( 'ID' );
	if ( ! $author_id ) {
		return;
	}
	?>
	<div class="author-bar">
		<div class="author-bar__author">
			<span class="author-bar__avatar">
				<?php echo get_avatar( $author_id, 20, '', '', array( 'class' => 'author-bar__avatar-img' ) ); ?>
			</span>
			<span><?php esc_html_e( 'Por', 'chuquipiondo' ); ?> <?php the_author(); ?></span>
		</div>
		<div class="author-bar__tools">
			<span class="author-bar__share-label"><?php esc_html_e( 'COMPARTIR', 'chuquipiondo' ); ?></span>
			<button class="author-bar__tool" onclick="chuquiCopyLink()" aria-label="<?php esc_attr_e( 'Copiar enlace', 'chuquipiondo' ); ?>"><?php esc_html_e( 'Copiar', 'chuquipiondo' ); ?></button>
			<button class="author-bar__tool" onclick="window.print()" aria-label="<?php esc_attr_e( 'Imprimir', 'chuquipiondo' ); ?>"><?php esc_html_e( 'Imprimir', 'chuquipiondo' ); ?></button>
			<button class="author-bar__tool" onclick="chuquiFontSize(-1)" aria-label="<?php esc_attr_e( 'Reducir texto', 'chuquipiondo' ); ?>">A−</button>
			<button class="author-bar__tool" onclick="chuquiFontSize(0)" aria-label="<?php esc_attr_e( 'Texto normal', 'chuquipiondo' ); ?>">A</button>
			<button class="author-bar__tool" onclick="chuquiFontSize(1)" aria-label="<?php esc_attr_e( 'Aumentar texto', 'chuquipiondo' ); ?>">A+</button>
		</div>
	</div>
	<script>
	function chuquiCopyLink() {
		if (navigator.clipboard) {
			navigator.clipboard.writeText(window.location.href).then(function() {
				alert('<?php esc_html_e( 'Enlace copiado', 'chuquipiondo' ); ?>');
			});
		}
	}
	function chuquiFontSize(dir) {
		var content = document.querySelector('.entry-content.single-article__content');
		if (!content) return;
		var current = parseFloat(content.style.fontSize || '14');
		var newSize = dir === 0 ? 14 : Math.max(11, Math.min(22, current + dir));
		content.style.fontSize = newSize + 'px';
	}
	</script>
	<?php
}

/**
 * Render the carousel of recommended articles (3 minimum).
 */
function chuquipiondo_single_carousel() {
	$prev = get_previous_post();
	$next = get_next_post();
	$related_count = (int) chuquipiondo_get_option( 'single_related_count' );
	$carousel_count = max( 3, $related_count );

	$q = new WP_Query( array(
		'post_type'           => 'post',
		'posts_per_page'      => $carousel_count,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => 1,
		'orderby'             => 'rand',
	) );

	if ( ! $q->have_posts() ) {
		return;
	}

	$titles = array();
	while ( $q->have_posts() ) {
		$q->the_post();
		$titles[] = array(
			'title' => get_the_title(),
			'url'   => get_permalink(),
		);
	}
	wp_reset_postdata();

	echo '<div class="article-carousel" id="chuqui-article-carousel">';
	echo '<button class="article-carousel__btn" onclick="chuquiCarouselMove(-1)" aria-label="' . esc_attr__( 'Anterior', 'chuquipiondo' ) . '">&lsaquo;</button>';
	echo '<div class="article-carousel__title" id="chuqui-carousel-title">';
	if ( ! empty( $titles ) ) {
		echo '<a href="' . esc_url( $titles[0]['url'] ) . '">' . esc_html( $titles[0]['title'] ) . '</a>';
	}
	echo '</div>';
	echo '<button class="article-carousel__btn" onclick="chuquiCarouselMove(1)" aria-label="' . esc_attr__( 'Siguiente', 'chuquipiondo' ) . '">&rsaquo;</button>';
	echo '</div>';

	// Inline JS for carousel.
	$js_titles = wp_json_encode( $titles );
	?>
	<script>
	var chuquiCarouselTitles = <?php echo $js_titles; // phpcs:ignore ?>;
	var chuquiCarouselIndex = 0;
	function chuquiCarouselMove(step) {
		if (!chuquiCarouselTitles || chuquiCarouselTitles.length < 2) return;
		chuquiCarouselIndex = (chuquiCarouselIndex + step + chuquiCarouselTitles.length) % chuquiCarouselTitles.length;
		var item = chuquiCarouselTitles[chuquiCarouselIndex];
		var el = document.getElementById('chuqui-carousel-title');
		if (el && item) {
			el.innerHTML = '<a href="' + item.url + '">' + item.title + '</a>';
		}
	}
	</script>
	<?php
}

/**
 * Output the post content with ads inserted after specific paragraphs.
 */
function chuquipiondo_the_content_with_ads() {
	$content = get_the_content();
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );

	if ( ! chuquipiondo_ads_active() ) {
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- post content.
		return;
	}

	echo chuquipiondo_insert_ads_in_content( $content ); // phpcs:ignore WordPress.Security.EscapeOutput -- filtered below.
}

/**
 * Render the Post End Extension area.
 */
function chuquipiondo_post_end_extension() {
	do_action( 'chuquipiondo_before_post_end_extension' );

	if ( is_active_sidebar( 'sidebar-post-end' ) ) {
		echo '<div class="post-end-widgets">';
		dynamic_sidebar( 'sidebar-post-end' );
		echo '</div>';
	}

	$extension = chuquipiondo_get_option( 'single_extension_area' );
	if ( $extension ) {
		echo '<div class="post-end-custom">';
		echo do_shortcode( wp_kses_post( $extension ) );
		echo '</div>';
	}

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
		chuquipiondo_post_card( chuquipiondo_get_option( 'single_related_style' ) );
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
	echo '<div class="post-navigation__inner">';

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
