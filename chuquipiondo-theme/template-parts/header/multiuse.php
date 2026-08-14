<?php
/**
 * Header 3: Multiuse header (banner / AdSense / CTA / music).
 *
 * Renders up to 4 multiuse boxes in a configurable grid distribution.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$columns = chuquipiondo_header_distribution_columns();
$content = chuquipiondo_get_option( 'header_multiuse_content' );
?>
<div class="header-multiuse">
	<div class="chuqui-container header-multiuse__inner" style="grid-template-columns: <?php echo esc_attr( $columns ); ?>;">
		<?php
		// Render the 4 multiuse boxes.
		for ( $i = 1; $i <= 4; $i++ ) {
			chuquipiondo_render_header_box( $i );
		}
		?>
		<?php if ( $content ) : ?>
			<div class="header-multiuse-content">
				<?php echo wp_kses_post( do_shortcode( $content ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
