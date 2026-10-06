<?php
/**
 * Slider de destacados en la portada del blog.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$count  = (int) nechugo_get_option( 'home_slider_count' );
$slides = new WP_Query(
	array(
		'posts_per_page'      => max( 2, $count ),
		'ignore_sticky_posts' => true,
		'meta_key'            => '_thumbnail_id',
	)
);

if ( ! $slides->have_posts() ) {
	return;
}
?>
<div class="nechugo-hero-slider" data-slider>
	<div class="nechugo-hero-track">
		<?php
		$i = 0;
		while ( $slides->have_posts() ) :
			$slides->the_post();
			?>
			<div class="nechugo-hero-slide<?php echo 0 === $i ? ' is-active' : ''; ?>">
				<a href="<?php the_permalink(); ?>" class="hero-slide__link">
					<?php the_post_thumbnail( 'nechugo-slider' ); ?>
					<div class="hero-slide__caption">
						<span class="hero-slide__cat"><?php nechugo_first_category(); ?></span>
						<h2 class="hero-slide__title"><?php the_title(); ?></h2>
					</div>
				</a>
			</div>
			<?php
			$i++;
		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<button class="nechugo-hero-prev" aria-label="<?php esc_attr_e( 'Anterior', 'nechugo-news' ); ?>">&lsaquo;</button>
	<button class="nechugo-hero-next" aria-label="<?php esc_attr_e( 'Siguiente', 'nechugo-news' ); ?>">&rsaquo;</button>
</div>
