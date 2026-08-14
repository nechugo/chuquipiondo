<?php
/**
 * Customizer panels: organizes sections into collapsible panels
 * like Astra Pro for a professional, intuitive navigation.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer panels and reorganize sections.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function chuquipiondo_register_panels( $wp_customize ) {

	// Panel: Global (colors, fonts, buttons, container).
	$wp_customize->add_panel( 'chuquipiondo_panel_global', array(
		'title'       => __( '🎨 Global', 'chuquipiondo' ),
		'description' => __( 'Colores, tipografias, botones, contenedor y ajustes globales.', 'chuquipiondo' ),
		'priority'    => 25,
	) );

	// Panel: Cabecera (pre-header, header, sticky).
	$wp_customize->add_panel( 'chuquipiondo_panel_header', array(
		'title'       => __( '🏛️ Cabecera', 'chuquipiondo' ),
		'description' => __( 'Pre-header, top bar, header principal, sticky y multiuso.', 'chuquipiondo' ),
		'priority'    => 26,
	) );

	// Panel: Contenido (hero, home, blog, single, page).
	$wp_customize->add_panel( 'chuquipiondo_panel_content', array(
		'title'       => __( '📄 Contenido', 'chuquipiondo' ),
		'description' => __( 'Hero, home builder, blog, articulo, pagina y sidebar.', 'chuquipiondo' ),
		'priority'    => 27,
	) );

	// Panel: Monetizacion (ads, social, whatsapp).
	$wp_customize->add_panel( 'chuquipiondo_panel_monetization', array(
		'title'       => __( '💰 Monetizacion', 'chuquipiondo' ),
		'description' => __( 'Publicidad, redes sociales, WhatsApp y musica.', 'chuquipiondo' ),
		'priority'    => 28,
	) );

	// Panel: Footer y avanzado.
	$wp_customize->add_panel( 'chuquipiondo_panel_footer', array(
		'title'       => __( '🦶 Footer y Avanzado', 'chuquipiondo' ),
		'description' => __( 'Pie de pagina, musica y codigo personalizado.', 'chuquipiondo' ),
		'priority'    => 29,
	) );

	// Reassign sections to panels.
	$section_map = array(
		// Global.
		'chuquipiondo_global'   => 'chuquipiondo_panel_global',
		'chuquipiondo_buttons'  => 'chuquipiondo_panel_global',
		// Header.
		'chuquipiondo_preheader'=> 'chuquipiondo_panel_header',
		'chuquipiondo_header'   => 'chuquipiondo_panel_header',
		// Content.
		'chuquipiondo_hero'     => 'chuquipiondo_panel_content',
		'chuquipiondo_home'     => 'chuquipiondo_panel_content',
		'chuquipiondo_blog'     => 'chuquipiondo_panel_content',
		'chuquipiondo_single'   => 'chuquipiondo_panel_content',
		'chuquipiondo_page'     => 'chuquipiondo_panel_content',
		// Monetization.
		'chuquipiondo_ads'      => 'chuquipiondo_panel_monetization',
		'chuquipiondo_social'   => 'chuquipiondo_panel_monetization',
		'chuquipiondo_whatsapp' => 'chuquipiondo_panel_monetization',
		// Footer + advanced.
		'chuquipiondo_footer'   => 'chuquipiondo_panel_footer',
		'chuquipiondo_music'    => 'chuquipiondo_panel_footer',
		'chuquipiondo_code'     => 'chuquipiondo_panel_footer',
	);

	foreach ( $section_map as $section_id => $panel_id ) {
		$section = $wp_customize->get_section( $section_id );
		if ( $section ) {
			$section->panel = $panel_id;
		}
	}
}
add_action( 'customize_register', 'chuquipiondo_register_panels', 5 );
