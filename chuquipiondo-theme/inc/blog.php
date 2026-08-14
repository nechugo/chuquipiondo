<?php
/**
 * Blog / archive rendering: magazine cover + grid.
 *
 * Interfaz actualizada v1.4.0:
 * - Hero slider con las 3 primeras entradas (imagen + título + copy)
 * - Grid de tarjetas con imagen 16:9, título mayúsculas, extracto, LEER MÁS
 * - ADS wide entre filas del grid
 * - Sidebar con ads y skeletons
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the blog/archive header.
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
		printf( '<h1 class="page-title">' . esc_html__( 'Resultados para: %s', 'chuquipiondo' ) . '</h1>', '<span>' . get_search_query() . '</span>' );
		echo '</header>';
	}
}

/**
 * Render the blog hero slider with the 3 latest posts.
 */
function chuquipiondo_blog_hero_slider() {
	$hero_q = new WP_Query( array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => 1,
		'orderby'             => 'date',
		'order'               => 'DESC',
	) );

	if ( ! $hero_q->have_posts() ) {
		return;
	}

	echo '<div class="blog-hero" aria-label="' . esc_attr__( 'Entradas destacadas', 'chuquipiondo' ) . '">';

	$i = 0;
	while ( $hero_q->have_posts() ) {
		$hero_q->the_post();
		$active = ( 0 === $i ) ? ' blog-hero__slide--active' : '';
		$excerpt = wp_trim_words( get_the_excerpt(), 18, '...' );
		?>
		<article class="blog-hero__slide<?php echo esc_attr( $active ); ?>">
			<a href="<?php the_permalink(); ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'chuquipiondo-featured', array( 'loading' => ( 0 === $i ? 'eager' : 'lazy' ) ) ); ?>
				<?php else : ?>
					<img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1280 720'%3E%3Crect fill='%23ddd' width='1280' height='720'/%3E%3C/svg%3E" alt="<?php the_title_attribute(); ?>">
				<?php endif; ?>
			</a>
			<div class="blog-hero__copy"><?php echo esc_html( $excerpt ); ?></div>
			<div class="blog-hero__title"><?php the_title(); ?></div>
		</article>
		<?php
		$i++;
	}

	// Arrows.
	echo '<button class="blog-hero__arrow blog-hero__arrow--prev" aria-label="' . esc_attr__( 'Anterior', 'chuquipiondo' ) . '">&lsaquo;</button>';
	echo '<button class="blog-hero__arrow blog-hero__arrow--next" aria-label="' . esc_attr__( 'Siguiente', 'chuquipiondo' ) . '">&rsaquo;</button>';

	echo '</div><!-- .blog-hero -->';
	wp_reset_postdata();

	// Inline JS for slider (lightweight, no dependency).
	?>
	<script>
	(function() {
		var slides = document.querySelectorAll('.blog-hero__slide');
		if (slides.length < 2) return;
		var index = 0;
		function show(n) {
			slides[index].classList.remove('blog-hero__slide--active');
			index = (n + slides.length) % slides.length;
			slides[index].classList.add('blog-hero__slide--active');
		}
		var prev = document.querySelector('.blog-hero__arrow--prev');
		var next = document.querySelector('.blog-hero__arrow--next');
		if (prev) prev.onclick = function() { show(index - 1); };
		if (next) next.onclick = function() { show(index + 1); };
		setInterval(function() { show(index + 1); }, 6000);
	})();
	</script>
	<?php
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

		// Insert wide ad after every 2 posts (like the design).
		if ( $counter > 0 && 0 === ( $counter % 2 ) ) {
			echo '<div class="wide-ad">';
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

	// Hero slider with 3 latest posts.
	chuquipiondo_blog_hero_slider();

	// Ad slot: blog top.
	chuquipiondo_ad_slot( 'ads_blog_top' );

	// Post grid.
	chuquipiondo_blog_grid();

	// Ad slot: blog bottom.
	chuquipiondo_ad_slot( 'ads_blog_bottom' );

	echo '</main>';

	chuquipiondo_get_sidebar();
	echo '</div>';
}
