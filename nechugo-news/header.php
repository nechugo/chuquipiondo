<?php
/**
 * Cabecera del sitio.
 *
 * @package nechugo_News
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Saltar al contenido', 'nechugo-news' ); ?></a>

<header id="masthead" class="site-header nechugo-header--full">
	<?php get_template_part( 'template-parts/header/variants', nechugo_get_option( 'header_layout' ) ); ?>
	<?php if ( nechugo_is_enabled( 'header_widgets_enable' ) ) : ?>
		<?php get_template_part( 'template-parts/header/widgets' ); ?>
	<?php endif; ?>
</header><!-- #masthead -->

<?php nechugo_ad_slot( 'ad_header_code' ); ?>

<div id="content" class="site-content">
