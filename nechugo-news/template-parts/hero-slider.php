<?php
/**
 * Slider multifuncional de la portada. Estilos:
 * - hero: una entrada a pantalla completa
 * - grid: cuadricula con activo grande
 * - carousel: carrusel horizontal
 * - split: columna izquierda de una imagen (620x520 aprox) y columna
 *   derecha de 3 filas (ratio 16:9) con 5px de separacion (default)
 *
 * Configurable: categoria, cantidad, autoplay, intervalo y meta.
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
	'posts_per_page'      => 'split' === $style ? max( 4, $count ) : $count,
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

if ( 'split' === $style ) :
	// Estilo dividido: 1 grande + 3 filas.
	?>
	<div class="nechugo-hero-slider nechugo-hero-slider--split">
		<div class="nechugo-split">
			<?php
			$i = 0;
			while ( $slides->have_posts() ) :
				$slides->the_post();
				if ( 0 === $i ) :
					?>
					<div class="nechugo-split__main">
						<a href="<?php the_permalink(); ?>" class="split-slide__link">
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
					<div class="nechugo-split__side">
					<?php
				else :
					?>
					<a href="<?php the_permalink(); ?>" class="nechugo-split__row">
						<div class="split-row__thumb"><?php the_post_thumbnail( 'nechugo-card' ); ?></div>
						<div class="split-row__body">
							<h3 class="split-row__title"><?php the_title(); ?></h3>
							<?php if ( $show_meta ) : ?>
								<div class="hero-slide__meta hero-slide__meta--dark">
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								</div>
							<?php endif; ?>
						</div>
					</a>
					<?php
				endif;
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
			</div>
		</div>
	</div>
	<?php
	return;
endif;

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
