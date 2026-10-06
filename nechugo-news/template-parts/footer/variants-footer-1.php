<?php
/**
 * Footer 1 - Columnas de widgets.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$columns = (int) nechugo_get_option( 'footer_columns' );
$columns = max( 1, min( 4, $columns ) );
?>
<div class="nechugo-footer-widgets">
	<div class="nechugo-container footer__grid footer__grid--<?php echo esc_attr( $columns ); ?>">
		<?php for ( $i = 1; $i <= $columns; $i++ ) : ?>
			<div class="footer__column">
				<?php
				if ( is_active_sidebar( 'footer-' . $i ) ) {
					dynamic_sidebar( 'footer-' . $i );
				} else {
					echo '<h2 class="widget-title">' . esc_html( get_bloginfo( 'name' ) ) . '</h2>';
					echo '<p>' . esc_html( get_bloginfo( 'description' ) ) . '</p>';
				}
				?>
			</div>
		<?php endfor; ?>
	</div>
</div>
<?php if ( nechugo_is_enabled( 'footer_copyright_enable' ) ) : ?>
	<div class="nechugo-footer-bottom">
		<div class="nechugo-container footer__bottom-inner">
			<span class="footer__copyright"><?php echo nechugo_footer_copyright(); // phpcs:ignore ?></span>
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<?php nechugo_nav_menu( 'footer', 'footer-menu' ); ?>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
