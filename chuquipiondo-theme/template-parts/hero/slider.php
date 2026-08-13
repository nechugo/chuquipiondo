<?php
/**
 * Hero / Slider template-part.
 *
 * Supports modes: image, slider, video, html, shortcode, elementor.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$mode    = chuquipiondo_get_option( 'hero_mode' );
$effect  = chuquipiondo_get_option( 'hero_effect' );
$overlay  = (int) chuquipiondo_get_option( 'hero_overlay' );
$slides  = chuquipiondo_get_array_option( 'hero_slider' );
$content = chuquipiondo_get_option( 'header_multiuse_content' );

$wrapper_classes = array(
	'hero',
	'hero--' . sanitize_html_class( $mode ),
	'hero--effect-' . sanitize_html_class( $effect ),
);
if ( 'slider' === $mode && count( $slides ) >= 2 ) {
	$wrapper_classes[] = 'hero--slider-active';
}
?>
<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-hero-effect="<?php echo esc_attr( $effect ); ?>" data-hero-overlay="<?php echo esc_attr( $overlay ); ?>">

<?php if ( 'slider' === $mode || 'image' === $mode ) :
	$is_slider = ( 'slider' === $mode && count( $slides ) >= 2 );
	?>
	<div class="hero__track <?php echo $is_slider ? esc_attr( 'hero__track--slider' ) : ''; ?>">
	<?php foreach ( $slides as $index => $slide ) :
		$is_first    = ( 0 === $index );
		$img_desktop = chuquipiondo_hero_slide_image( $slide, 'desktop' );
		$img_tablet  = chuquipiondo_hero_slide_image( $slide, 'tablet' );
		$img_mobile  = chuquipiondo_hero_slide_image( $slide, 'mobile' );
		?>
		<div class="hero__slide <?php echo $is_first ? esc_attr( 'hero__slide--active' ) : ''; ?>">
			<?php if ( $img_desktop ) : ?>
				<picture class="hero__image">
					<?php if ( $img_mobile ) : ?>
						<source media="(max-width: 600px)" srcset="<?php echo esc_url( $img_mobile ); ?>">
					<?php endif; ?>
					<?php if ( $img_tablet ) : ?>
						<source media="(max-width: 1024px)" srcset="<?php echo esc_url( $img_tablet ); ?>">
					<?php endif; ?>
					<img src="<?php echo esc_url( $img_desktop ); ?>"
						alt="<?php echo esc_attr( isset( $slide['title'] ) ? $slide['title'] : '' ); ?>"
						<?php echo $is_first ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute strings ?>
						class="hero__img <?php echo 'kenburns' === $effect ? esc_attr( 'hero__img--kenburns' ) : ''; ?>">
				</picture>
			<?php endif; ?>

			<?php if ( $overlay > 0 ) : ?>
				<div class="hero__overlay" style="opacity: <?php echo esc_attr( $overlay / 100 ); ?>"></div>
			<?php endif; ?>

			<?php if ( ! empty( $slide['title'] ) || ! empty( $slide['subtitle'] ) || ! empty( $slide['button_text'] ) ) : ?>
				<div class="hero__content chuqui-container">
					<div class="hero__text">
						<?php if ( ! empty( $slide['title'] ) ) : ?>
							<h2 class="hero__title"><?php echo esc_html( $slide['title'] ); ?></h2>
						<?php endif; ?>
						<?php if ( ! empty( $slide['subtitle'] ) ) : ?>
							<p class="hero__subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $slide['button_text'] ) && ! empty( $slide['button_url'] ) ) : ?>
							<a href="<?php echo esc_url( $slide['button_url'] ); ?>" class="btn hero__btn"><?php echo esc_html( $slide['button_text'] ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
	</div>

	<?php if ( $is_slider ) : ?>
		<div class="hero__controls" role="group" aria-label="<?php esc_attr_e( 'Controles del slider', 'chuquipiondo' ); ?>">
			<button class="hero__arrow hero__arrow--prev" aria-label="<?php esc_attr_e( 'Anterior', 'chuquipiondo' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg></button>
			<div class="hero__dots" role="tablist"></div>
			<button class="hero__arrow hero__arrow--next" aria-label="<?php esc_attr_e( 'Siguiente', 'chuquipiondo' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M8.59 16.59 13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg></button>
		</div>
	<?php endif; ?>

<?php elseif ( 'video' === $mode ) : ?>
	<div class="hero__video"><?php echo wp_kses_post( $content ); ?></div>

<?php elseif ( 'html' === $mode ) : ?>
	<?php echo wp_kses_post( $content ); // phpcs:ignore WordPress.Security.EscapeOutput -- admin content. ?>

<?php elseif ( 'shortcode' === $mode ) : ?>
	<?php echo do_shortcode( '[' . $content . ']' ); // phpcs:ignore WordPress.Security.EscapeOutput -- shortcode output. ?>

<?php elseif ( 'elementor' === $mode ) :
	$template_id = apply_filters( 'chuquipiondo_hero_elementor_template', $content );
	if ( $template_id && function_exists( 'elementor_theme_do_location' ) ) {
		elementor_theme_do_location( 'hero' );
	} elseif ( $template_id && shortcode_exists( 'elementor-template' ) ) {
		echo do_shortcode( '[elementor-template id="' . absint( $template_id ) . '"]' ); // phpcs:ignore WordPress.Security.EscapeOutput -- shortcode output.
	}
endif;
?>
</div>
