<?php
/**
 * Footer 2 - Logo centrado + redes + menu.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="nechugo-footer-center">
	<div class="nechugo-container footer__center-inner">
		<div class="footer__brand">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="site-title-footer"><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
			<p class="footer__tagline"><?php bloginfo( 'description' ); ?></p>
		</div>
		<?php get_template_part( 'template-parts/header/social' ); ?>
		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<div class="footer__menu">
				<?php nechugo_nav_menu( 'footer', 'footer-menu' ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php if ( nechugo_is_enabled( 'footer_copyright_enable' ) ) : ?>
	<div class="nechugo-footer-bottom">
		<div class="nechugo-container footer__bottom-inner">
			<span class="footer__copyright"><?php echo nechugo_footer_copyright(); // phpcs:ignore ?></span>
		</div>
	</div>
<?php endif; ?>
