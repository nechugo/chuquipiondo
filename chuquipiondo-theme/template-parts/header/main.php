<?php
/**
 * Header 2: Main header (logo, menu, search).
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$layout   = chuquipiondo_get_option( 'header_main_layout' );
$search   = chuquipiondo_is_enabled( 'header_search_enable' );
?>
<div class="header-main">
	<div class="chuqui-container header-main__inner header-main--<?php echo esc_attr( sanitize_html_class( $layout ) ); ?>">
		<div class="header-main__brand">
			<?php chuquipiondo_site_logo(); ?>
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menu', 'chuquipiondo' ); ?>">
				<span class="menu-toggle__bar"></span>
				<span class="menu-toggle__bar"></span>
				<span class="menu-toggle__bar"></span>
			</button>
		</div>

		<nav class="header-main__nav" aria-label="<?php esc_attr_e( 'Navegacion principal', 'chuquipiondo' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu primary-menu',
					'fallback_cb'    => false,
				) );
			} else {
				chuquipiondo_fallback_menu();
			}
			?>
		</nav>

		<?php if ( $search ) : ?>
			<div class="header-main__search">
				<button class="search-toggle" aria-label="<?php esc_attr_e( 'Buscar', 'chuquipiondo' ); ?>" aria-expanded="false">
					<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M10 2a8 8 0 1 0 4.9 14.32l5.39 5.39 1.42-1.42-5.39-5.39A8 8 0 0 0 10 2Zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12Z"/></svg>
				</button>
				<div class="header-search-form" hidden>
					<?php get_search_form(); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
