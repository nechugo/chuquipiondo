<?php
/**
 * Selective refresh / live preview for the Customizer.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Customizer preview assets.
 */
function chuquipiondo_customize_preview_js() {
	wp_enqueue_script(
		'chuquipiondo-customizer-preview',
		CHUQUIPONDO_URI . '/assets/js/customizer-preview.js',
		array( 'customize-preview' ),
		chuquipiondo_asset_version( 'assets/js/customizer-preview.js' ),
		true
	);
}
add_action( 'customize_preview_init', 'chuquipiondo_customize_preview_js' );

/**
 * Register selective refresh partials.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function chuquipiondo_selective_refresh( $wp_customize ) {
	// Live-update the blog name and description.
	if ( $wp_customize->get_setting( 'blogname' ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title',
			'render_callback' => function () {
				bloginfo( 'name' );
			},
		) );
	}

	// WhatsApp number live preview (refresh partial).
	$wp_customize->selective_refresh->add_partial( 'whatsapp_number', array(
		'selector'            => '.chuqui-whatsapp',
		'container_inclusive' => true,
		'render_callback'     => 'chuquipiondo_whatsapp_float_render',
	) );

	// Footer about text live preview.
	$wp_customize->selective_refresh->add_partial( 'footer_about', array(
		'selector'        => '.footer-about-text',
		'render_callback' => function () {
			echo esc_html( chuquipiondo_get_option( 'footer_about' ) );
		},
	) );

	// Footer copyright live preview.
	$wp_customize->selective_refresh->add_partial( 'footer_copyright', array(
		'selector'        => '.footer-copyright',
		'render_callback' => function () {
			$copyright = chuquipiondo_get_option( 'footer_copyright' );
			$copyright = str_replace( '{year}', date( 'Y' ), $copyright );
			echo esc_html( $copyright );
		},
	) );
}
add_action( 'customize_register', 'chuquipiondo_selective_refresh', 20 );
