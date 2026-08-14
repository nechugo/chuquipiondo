<?php
/**
 * CHUQUIPIONDO Child Theme functions.
 *
 * Este archivo se carga despues del functions.php del tema padre.
 * Usalo para agregar o sobrescribir funcionalidad del tema padre.
 *
 * @package CHUQUIPIONDO_Child
 * @author  Nelson Chuquipiondo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

/**
 * Encolar la hoja de estilo del tema hijo (junto con la del padre).
 */
function chuquipiondo_child_enqueue_styles() {
	// Carga la hoja de estilo del tema padre.
	wp_enqueue_style(
		'chuquipiondo-parent',
		get_template_directory_uri() . '/style.css',
		array(),
		'1.0.0'
	);

	// Carga la hoja de estilo del tema hijo.
	wp_enqueue_style(
		'chuquipiondo-child',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'chuquipiondo-parent' ),
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'chuquipiondo_child_enqueue_styles' );

/* ============================================================
 * Ejemplos de personalizacion (descomenta para usar).
 * ============================================================ */

/**
 * Cambiar la cantidad de articulos en el home.
 */
// add_filter( 'chuquipiondo_home_modules', function ( $modules ) {
// 	return array( 'hero', 'featured', 'latest', 'newsletter' );
// } );

/**
 * Agregar un modulo personalizado al home builder.
 */
// add_action( 'chuquipiondo_after_home_module', function ( $module ) {
// 	if ( 'custom' === $module ) {
// 		echo '<section class="home-module"><h2>Mi modulo</h2></section>';
// 	}
// } );

/**
 * Cambiar el estilo de tarjeta por defecto.
 */
// add_filter( 'chuquipiondo_card_style', function () {
// 	return 'magazine';
// } );

/**
 * Agregar un slot de anuncio personalizado.
 */
// add_filter( 'chuquipiondo_ad_slots', function ( $slots ) {
// 	$slots['ads_custom_zone'] = array( 'label' => 'Mi zona' );
// 	return $slots;
// } );
