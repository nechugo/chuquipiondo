<?php
/**
 * Header 2: Main header (logo, menu, language switcher, search).
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
exit;
}

$layout      = chuquipiondo_get_option( 'header_main_layout' );
$search      = chuquipiondo_is_enabled( 'header_search_enable' );
$lang_switch = chuquipiondo_is_enabled( 'header_language_switcher_enable' );
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

<?php if ( $lang_switch || $search ) : ?>
<div class="header-main__actions">
<?php if ( $lang_switch ) : ?>
<div class="header-language-switcher">
<button class="language-toggle" aria-label="<?php esc_attr_e( 'Cambiar idioma', 'chuquipiondo' ); ?>" aria-expanded="false">
<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" width="22" height="22"><path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
</button>
<div class="language-dropdown" hidden>
<ul>
<li><a href="<?php echo esc_url( get_home_url( null, '?lang=es' ) ); ?>" class="<?php echo ( get_locale() === 'es_ES' || empty( $_GET['lang'] ) ) ? 'active' : ''; ?>">
<span class="lang-flag">🇪🇸</span>
<span class="lang-name"><?php esc_html_e( 'Español', 'chuquipiondo' ); ?></span>
</a></li>
<li><a href="<?php echo esc_url( get_home_url( null, '?lang=en' ) ); ?>" class="<?php echo ( get_locale() === 'en_US' || ( isset( $_GET['lang'] ) && $_GET['lang'] === 'en' ) ) ? 'active' : ''; ?>">
<span class="lang-flag">🇺🇸</span>
<span class="lang-name"><?php esc_html_e( 'English', 'chuquipiondo' ); ?></span>
</a></li>
</ul>
</div>
</div>
<?php endif; ?>

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
<?php endif; ?>
</div>
</div>
