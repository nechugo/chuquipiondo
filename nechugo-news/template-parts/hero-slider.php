<?php
/**
 * Slider multifuncional de la portada. Estilos:
 * - split (default): columna izquierda 890x520 con la primera entrada grande
 *   y columna derecha de 300px con 3 filas (3 entradas), separacion de 5px,
 *   altura total 520px. Titulos sobre la imagen en letras pequenas.
 *   Automatico: rota entre las ultimas 6 entradas con imagen destacada.
 * - hero: una entrada a pantalla completa
 * - grid: cuadricula con activo grande
 * - carousel: carrusel horizontal
 *
 * Configurable: categoria, cantidad, autoplay, intervalo y meta.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$style     = nechugo_get_option( 'home_slider_style' );
$show_meta = nechugo_is_enabled( 'home_slider_show_meta' );
$cat       = (int) nechugo_get_option( 'home_slider_category' );
$count     = (int) nechugo_get_option( 'home_slider_count' );
$auto      = nechugo_is_enabled( 'home_slider_autoplay' ) ? 'true' : 'false';
$interval  = max( 2000, (int) nechugo_get_option( 'home_slider_interval' ) );

if ( 'split' === $style ) :
	// Estilo dividido: 890x520 izquierda + 300px con 3 filas a la derecha.
	// Automatico: usa las ultimas 6 entradas con imagen destacada.
	$args = array(
		'posts_per_page'      => max( 6, $count ),
		'ignore_sticky_posts' => true,
		'meta_key'            => '_thumbnail_id',
		'no_found_rows'       => true,
	);
	if ( $cat > 0 ) {
		$args['cat'] = $cat;
	}
	$slides = new WP_Query( $args );

	if ( ! $slides->have_posts() ) {
		return;
	}
	?>
	<div class="nechugo-hero-slider nechugo-hero-slider--split" data-split-slides="<?php echo esc_attr( min( 6, (int) $slides->post_count ) ); ?>">
		<div class="nechugo-split">
			<div class="nechugo-split__main">
				<?php
				$i = 0;
				$group_open = false;
				while ( $slides->have_posts() ) :
					$slides->the_post();
					if ( 0 === $i % 4 ) :
						// Cada grupo: 1 grande + 3 filas. Solo el primero visible.
						$active = 0 === $i ? ' is-active' : '';
						if ( $group_open ) {
							echo '</div></div>';
						}
						?>
						<div class="split-group<?php echo esc_attr( $active ); ?>">
						<div class="split-main__slide">
							<a href="<?php the_permalink(); ?>" class="split-slide__link">
								<?php the_post_thumbnail( 'nechugo-slider' ); ?>
								<div class="split-caption">
									<span class="hero-slide__cat"><?php nechugo_first_category(); ?></span>
									<h2 class="split-caption__title"><?php the_title(); ?></h2>
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
						$group_open = true;
					else :
						?>
						<a href="<?php the_permalink(); ?>" class="nechugo-split__row">
							<div class="split-row__inner">
								<?php the_post_thumbnail( 'nechugo-card' ); ?>
								<div class="split-row__caption">
									<h3 class="split-row__title"><?php the_title(); ?></h3>
									<?php if ( $show_meta ) : ?>
										<time class="split-row__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
									<?php endif; ?>
								</div>
							</div>
						</a>
						<?php
					endif;
					$i++;
				endwhile;
				wp_reset_postdata();
				if ( $group_open ) {
					echo '</div></div>';
				}
				?>
			</div>
		</div>
		<button class="nechugo-hero-prev" aria-label="<?php esc_attr_e( 'Anterior', 'nechugo-news' ); ?>">&lsaquo;</button>
		<button class="nechugo-hero-next" aria-label="<?php esc_attr_e( 'Siguiente', 'nechugo-news' ); ?>">&rsaquo;</button>
	</div>
	<?php
	return;
endif;

// Resto de estilos (hero / grid / carousel).
$args = array(
	'posts_per_page'      => max( 2, $count ),
	'ignore_sticky_posts' => true,
	'meta_key'            => '_thumbnail_id',
	'no_found_rows'       => true,
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
	esc_attr( $interval )
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
