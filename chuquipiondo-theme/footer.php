<?php
/**
 * The footer template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$columns     = (int) chuquipiondo_get_option( 'footer_columns' );
$footer_about= chuquipiondo_get_option( 'footer_about' );
$copyright   = chuquipiondo_get_option( 'footer_copyright' );
$copyright   = str_replace( '{year}', date( 'Y' ), $copyright );
$show_social = chuquipiondo_is_enabled( 'footer_show_social' );
$show_brand = chuquipiondo_is_enabled( 'footer_show_brand' );
$show_copyright = chuquipiondo_is_enabled( 'footer_show_copyright' );
$show_menu = chuquipiondo_is_enabled( 'footer_show_menu' );
?>

<?php chuquipiondo_ad_slot( 'ads_footer_before' ); ?>

<footer id="colophon" class="site-footer">
	<div class="chuqui-container">
		<?php if ( ( $footer_about && $show_brand ) || $show_social ) : ?>
			<div class="footer-brand">
				<?php if ( $footer_about && $show_brand ) : ?>
					<p class="footer-about footer-about-text"><?php echo esc_html( $footer_about ); ?></p>
				<?php endif; ?>
				<?php if ( $show_social ) : ?>
					<div class="footer-social"><?php chuquipiondo_social_profiles_links(); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="footer-widgets">
			<?php
			if ( is_active_sidebar( 'sidebar-footer' ) ) {
				dynamic_sidebar( 'sidebar-footer' );
			} else {
				for ( $i = 0; $i < $columns; $i++ ) {
					echo '<div class="footer-widget footer-widget--placeholder">';
					echo '<h3 class="footer-widget-title">' . esc_html__( 'Widget area', 'chuquipiondo' ) . '</h3>';
					echo '<p>' . esc_html__( 'Anade widgets desde Apariencia > Widgets.', 'chuquipiondo' ) . '</p>';
					echo '</div>';
				}
			}
			?>
		</div>

		<?php chuquipiondo_ad_slot( 'ads_footer_between' ); ?>

		<div class="footer-bottom">
			<?php if ( $show_copyright ) : ?>
			<p class="footer-copyright"><?php echo esc_html( $copyright ); ?></p>
			<?php endif; ?>
			<?php
			if ( $show_menu && has_nav_menu( 'footer' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => 'nav',
					'container_class'=> 'footer-nav',
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
				) );
			}
			?>
		</div>
	</div>
</footer><!-- #colophon -->

<?php chuquipiondo_ad_slot( 'ads_footer_after' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
