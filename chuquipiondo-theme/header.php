<?php
/**
 * The header template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php
	/**
	 * CSS critico inline: garantiza que las correcciones de scroll,
	 * sidebar, imagenes 16:9 y layout se apliquen SIEMPRE, incluso
	 * si wp_head o los @import fallan.
	 */
	?>
	<style id="chuquipiondo-critical-css">
		html { overflow-x: hidden; overflow-y: scroll !important; }
		body { overflow-x: hidden; overflow-y: visible !important; }
		#page { overflow-y: visible !important; overflow-x: hidden; }
		.widget-area { overflow-y: hidden !important; scrollbar-width: none; -ms-overflow-style: none; }
		.widget-area::-webkit-scrollbar { display: none; }
		.widget-area .widget { border-radius: 0 !important; background: #fff; padding: 16px; margin-bottom: 12px; border: 1px solid rgba(10,31,68,0.06); box-sizing: border-box; }
		.layout-right .widget-area, .layout-left .widget-area { margin-inline: -5px; padding-inline: 5px; }
		.entry-content.single-article__content img,
		.entry-content.single-article__content figure img,
		.entry-content.single-article__content .wp-block-image img { width: 100%; height: auto; aspect-ratio: 16/9; object-fit: cover; }
		.entry-thumbnail.single-article__thumbnail { aspect-ratio: 16/9; overflow: hidden; }
		.entry-thumbnail.single-article__thumbnail img { width: 100%; height: 100%; aspect-ratio: 16/9; object-fit: cover; }
		.entry-content.single-article__content { font-size: 14px; line-height: 1.0; text-align: justify; background: transparent; }
		.entry-content.single-article__content p { margin: 0 0 12px; padding: 0; background: transparent; line-height: 1.0; text-align: justify; }
		.entry-content.single-article__content h1,
		.entry-content.single-article__content h2,
		.entry-content.single-article__content h3,
		.entry-content.single-article__content h4,
		.entry-content.single-article__content h5,
		.entry-content.single-article__content h6 { margin-top: 12px; margin-bottom: 12px; line-height: 1.0; padding: 0; background: transparent; }
		.chuqui-layout.single-layout { margin-top: 25px; }
		.chuqui-whatsapp svg { width: 55%; height: 55%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
		.chuqui-whatsapp__icon { width: 100%; height: 100%; position: relative; }
		.related-posts .post-card__media { aspect-ratio: 16/9; overflow: hidden; }
		.related-posts .post-card__media img { width: 100%; height: 100%; object-fit: cover; }
		.related-posts .post-card { background: #fff; border: 1px solid rgba(10,31,68,0.08); border-radius: 0; overflow: hidden; box-shadow: 0 1px 3px rgba(10,31,68,0.06); }
		.header-topbar { font-size: 13px; line-height: 1.4; }
		.header-main { font-size: 15px; }
		.header-multiuse { font-size: 14px; }
		.entry-content iframe, .entry-content embed, .entry-content video, .widget iframe, .chuqui-container iframe { max-width: 100% !important; width: 100% !important; height: auto; }
		.chuqui-ad { overflow: hidden; box-sizing: border-box; max-width: var(--container-width, 1280px); }
		.chuqui-ad > * { max-width: 100%; box-sizing: border-box; }
		.chuqui-ad iframe, .chuqui-ad ins, .chuqui-ad div { max-width: 100% !important; overflow: hidden; }
	</style>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Saltar al contenido', 'chuquipiondo' ); ?></a>

	<?php
	/**
	 * Header system (3 rows: top bar, main, multiuse).
	 */
	// Pre-header: 2 invisible columns above the header.
	chuquipiondo_preheader();

	do_action( 'chuquipiondo_header' );
	?>
