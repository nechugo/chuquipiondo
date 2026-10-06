<?php
/**
 * Slider multifuncional de la portada: categoria, cantidad, estilo
 * (hero / grid / carrusel), autoplay, intervalo y meta configurables
 * desde el personalizador.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$count  = max( 2, (int) nechugo_get_option( 'home_slider_count' ) );
$cat    = (int) nechugo_get_option( 'home_slider_category' );
$style  = nechugo_get_option( 'home_slider_style' );
$auto   = nechugo_is_enabled( 'home_slider_autoplay' ) ? 'true' : 'false';
$gap    = (int) nechugo_get_option( 'home_slider_interval' );
$show_meta = nechugo_is_enabled( 'home_slider_show_meta' );

$args = array(
	'posts_per_page'      => $count,
	'ignore_sticky_posts' => true,
	'meta_key'            => '_thumbnail_id',
);
if ( $cat > 0 ) {
	$args['cat'] = $cat;
}

$slides = new WP_Query( $args );

if ( ! $slides->have_posts() ) {
	return;
}

$data_attrs = sprintf(
	'data-slider data-style="%s" data-autoplay="%s" data-interval="%d"',
	esc_attr( $style ),
	$auto,
	esc_attr( max( 2000, $gap ) )
);
?>
<div class="nechugo-hero-slider nechugo-hero-slider--<?php echo esc_attr( $style ); ?>" <?php echo $data_attrs; // phpcs:ignore ?>>
	<div class="nechugo-hero-track">
		<?php
		$i = 0;
		while ( $slides->have_posts() ) :
			$slides->the_post();
			$active = 0 === $i ? ' is-active' : '';
			?>
			<div class="nechugo-hero-slide<?php echo esc_attr( $active ); ?>">
				<a href="<?php the_permalink(); ?>" class="hero-slide__link">
					<?php the_post_thumbnail( 'nechugo-slider' ); ?>
					<div class="hero-slide__caption">
						<span class="hero-slide__cat"><?php nechugo_first_category(); ?></span>
						<h2 class="hero-slide__title"><?php the_title(); ?></h2>
						<?php if ( $show_meta ) : ?>
							<div class="hero-slide__meta">
								<span><?php echo esc_html( get_the_author() ); ?></span>
								<span class="meta-sep">·</span>
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
							</div>
						<?php endif; ?>
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
