<?php
/**
 * Footer 3 - Minimal: una linea + copyright.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="nechugo-footer-minimal">
	<div class="nechugo-container footer__minimal-inner">
		<span class="footer__copyright"><?php echo nechugo_footer_copyright(); // phpcs:ignore ?></span>
		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<?php nechugo_nav_menu( 'footer', 'footer-menu' ); ?>
		<?php endif; ?>
	</div>
</div>
