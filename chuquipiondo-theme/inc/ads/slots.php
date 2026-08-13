<?php
/**
 * Ad slot registry: 30+ zones across the theme.
 *
 * Each slot is a key => metadata pair. The key is also the
 * theme_mod that holds the ad code (set in the Customizer
 * "Publicidad" section).
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the full registry of ad slots.
 *
 * @return array
 */
function chuquipiondo_ad_slots() {
	$slots = array(
		/* Header (6) */
		'ads_header_before'    => array( 'label' => __( 'Header: antes de todo', 'chuquipiondo' ) ),
		'ads_header_before_topbar' => array( 'label' => __( 'Header: antes del Top Bar', 'chuquipiondo' ) ),
		'ads_header_after_topbar'  => array( 'label' => __( 'Header: despues del Top Bar', 'chuquipiondo' ) ),
		'ads_header_between'    => array( 'label' => __( 'Header: entre Top Bar y Principal', 'chuquipiondo' ) ),
		'ads_header_after_main' => array( 'label' => __( 'Header: despues del Principal', 'chuquipiondo' ) ),
		'ads_header_after'     => array( 'label' => __( 'Header: despues de todo', 'chuquipiondo' ) ),

		/* Home (5) */
		'ads_home_after_hero'   => array( 'label' => __( 'Home: despues del hero', 'chuquipiondo' ) ),
		'ads_home_after_featured' => array( 'label' => __( 'Home: despues de destacados', 'chuquipiondo' ) ),
		'ads_home_after_latest' => array( 'label' => __( 'Home: despues de ultimos', 'chuquipiondo' ) ),
		'ads_home_after_categories' => array( 'label' => __( 'Home: despues de categorias', 'chuquipiondo' ) ),
		'ads_home_after_about'  => array( 'label' => __( 'Home: despues de sobre', 'chuquipiondo' ) ),

		/* Blog / archive (4) */
		'ads_blog_top'          => array( 'label' => __( 'Blog: arriba del grid', 'chuquipiondo' ) ),
		'ads_blog_after_row'    => array( 'label' => __( 'Blog: entre filas del grid', 'chuquipiondo' ) ),
		'ads_blog_middle'       => array( 'label' => __( 'Blog: en medio del grid', 'chuquipiondo' ) ),
		'ads_blog_bottom'       => array( 'label' => __( 'Blog: abajo del grid', 'chuquipiondo' ) ),

		/* Single post (6) */
		'ads_after_title'         => array( 'label' => __( 'Articulo: despues del titulo', 'chuquipiondo' ) ),
		'ads_after_thumbnail'      => array( 'label' => __( 'Articulo: despues de la imagen', 'chuquipiondo' ) ),
		'ads_after_paragraph_3'   => array( 'label' => __( 'Articulo: despues del parrafo 3', 'chuquipiondo' ) ),
		'ads_after_paragraph_6'   => array( 'label' => __( 'Articulo: despues del parrafo 6', 'chuquipiondo' ) ),
		'ads_after_paragraph_8'   => array( 'label' => __( 'Articulo: despues del parrafo 8', 'chuquipiondo' ) ),
		'ads_before_related'      => array( 'label' => __( 'Articulo: antes de relacionados', 'chuquipiondo' ) ),

		/* Sidebar (3) */
		'ads_sidebar_top'         => array( 'label' => __( 'Sidebar: arriba', 'chuquipiondo' ) ),
		'ads_sidebar_middle'      => array( 'label' => __( 'Sidebar: medio', 'chuquipiondo' ) ),
		'ads_sidebar_bottom'      => array( 'label' => __( 'Sidebar: abajo', 'chuquipiondo' ) ),

		/* Footer (3) */
		'ads_footer_before'       => array( 'label' => __( 'Footer: antes', 'chuquipiondo' ) ),
		'ads_footer_between'      => array( 'label' => __( 'Footer: entre widgets y copyright', 'chuquipiondo' ) ),
		'ads_footer_after'        => array( 'label' => __( 'Footer: despues', 'chuquipiondo' ) ),

		/* Music (2) */
		'ads_music_archive_top'   => array( 'label' => __( 'Musica: arriba del archivo', 'chuquipiondo' ) ),
		'ads_music_single_after'  => array( 'label' => __( 'Musica: despues del reproductor', 'chuquipiondo' ) ),
	);

	/**
	 * Filters the ad slot registry.
	 *
	 * @param array $slots Slots.
	 */
	return apply_filters( 'chuquipiondo_ad_slots', $slots );
}
