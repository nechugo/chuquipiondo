<?php
/**
 * Header 3 - Moderno: menu a la izquierda + logo a la derecha.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if ( nechugo_is_enabled( 'header_topbar_enable' ) ) : ?>
	<div class="nechugo-topbar">
		<div class="nechugo-container topbar__inner">
			<div class="topbar__left">
				<?php if ( nechugo_is_enabled( 'header_date_enable' ) ) : ?>
					<span class="topbar__date"><?php echo esc_html( wp_date( 'l, j F Y' ) ); ?></span>
				<?php endif; ?>
			</div>
			<div class="topbar__right">
				<?php if ( nechugo_is_enabled( 'header_social_enable' ) ) : ?>
					<?php get_template_part( 'template-parts/header/social' ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>
<div class="nechugo-header-main header--modern">
	<div class="nechugo-container header__inner header__inner--reverse">
		<div class="header__nav">
			<?php nechugo_menu_toggle(); ?>
			<?php nechugo_nav_menu( 'primary' ); ?>
		</div>
		<div class="header__tools">
			<?php nechugo_search_toggle(); ?>
		</div>
		<div class="header__brand">
			<?php nechugo_site_logo(); ?>
		</div>
	</div>
</div>
