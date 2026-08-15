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

		<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
		<div class="footer-widgets">
			<?php dynamic_sidebar( 'sidebar-footer' ); ?>
		</div>
		<?php endif; ?>

		<?php chuquipiondo_ad_slot( 'ads_footer_between' ); ?>

		<?php if ( $show_menu && has_nav_menu( 'footer' ) ) : ?>
		<nav class="footer-menu-section" aria-label="<?php esc_attr_e( 'Menu del pie de pagina', 'chuquipiondo' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'footer-menu',
				'depth'          => 1,
			) );
			?>
		</nav>
		<?php endif; ?>

		<?php if ( $show_copyright ) : ?>
		<div class="footer-copyright-section">
			<p class="footer-copyright"><?php echo esc_html( $copyright ); ?></p>
		</div>
		<?php endif; ?>
	</div>
</footer><!-- #colophon -->

<?php chuquipiondo_ad_slot( 'ads_footer_after' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
