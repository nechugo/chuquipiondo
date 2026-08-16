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
	 * CSS critico inline: previene el flash de scroll horizontal y fija
	 * aspect-ratios de medios ANTES de que cargue la hoja de estilos.
	 */
	?>
	<style id="chuquipiondo-critical-css">
		/* Reglas estructurales anti-FOUC (scroll horizontal). No forzar overflow-y en html/body:
		   rompe la rueda del mouse cuando hay header fijo + hero 100vh (home). */
		html { overflow-x: hidden; }
		body { overflow-x: hidden; }
		.layout-right .widget-area, .layout-left .widget-area { margin-inline: -5px; padding-inline: 5px; }
		/* Imagenes del contenido: sin aspect-ratio forzado (no se deformen). */
		.entry-content.single-article__content img,
		.entry-content.single-article__content figure img,
		.entry-content.single-article__content .wp-block-image img { max-width: 100%; height: auto; }
		.entry-thumbnail.single-article__thumbnail { aspect-ratio: 16/9; overflow: hidden; }
		.entry-thumbnail.single-article__thumbnail img { width: 100%; height: 100%; aspect-ratio: 16/9; object-fit: cover; }
		/* Tipografia del articulo controlada por theme.json + _base.css + _single.css (no forzar aqui: rompe legibilidad). */
		/* Gap header -> contenido (anti-FOUC; el valor real lo da _layout.css + Customizer). */
		.chuqui-layout { margin-top: var(--header-content-gap, 25px); }
		.chuqui-whatsapp svg { width: 55%; height: 55%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
		.chuqui-whatsapp__icon { width: 100%; height: 100%; position: relative; }
		.related-posts .post-card__media { aspect-ratio: 16/9; overflow: hidden; }
		.related-posts .post-card__media img { width: 100%; height: 100%; object-fit: cover; }
		.related-posts .post-card { background: #fff; border: 1px solid rgba(10,31,68,0.08); border-radius: 0; overflow: hidden; box-shadow: 0 1px 3px rgba(10,31,68,0.06); }
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
