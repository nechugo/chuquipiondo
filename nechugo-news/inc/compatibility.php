<?php
/**
 * Compatibilidad con Elementor, WooCommerce y plugins populares.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declara soportes de Elementor y WooCommerce cuando estan activos.
 */
function nechugo_builder_support() {
	if ( did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' ) ) {
		add_theme_support( 'elementor' );
	}
	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'nechugo_builder_support' );

/**
 * En paginas construidas con Elementor, el tema se retira:
 * sin contenedor acotado ni paddings, para secciones full-width.
 */
function nechugo_builder_full_width() {
	if ( nechugo_is_elementor_page() ) {
		add_filter( 'nechugo_use_container', '__return_false' );
		add_filter( 'body_class', function ( $classes ) {
			$classes[] = 'nechugo-builder-canvas';
			return $classes;
		} );
	}
}
add_action( 'template_redirect', 'nechugo_builder_full_width' );

/**
 * Registra ubicaciones de Elementor Pro (Theme Builder) si el plugin
 * de ubicaciones esta disponible, para headers/footers construidos con Elementor.
 */
function nechugo_register_elementor_locations( $manager ) {
	$manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'nechugo_register_elementor_locations' );
