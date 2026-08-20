<?php
/**
 * CHUQUIPIONDO Child Theme functions.
 *
 * @package CHUQUIPIONDO_Child
 * @author  Nelson Chuquipiondo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load only the child stylesheet. The parent theme already enqueues its own
 * stylesheet under the chuquipiondo-style handle.
 */
function chuquipiondo_child_enqueue_styles() {
	wp_enqueue_style(
		'chuquipiondo-child',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'chuquipiondo-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'chuquipiondo_child_enqueue_styles', 20 );

/* Ejemplos de personalizacion. */
// add_filter( 'chuquipiondo_home_modules', function ( $modules ) {
// 	return array( 'hero', 'featured', 'latest', 'newsletter' );
// } );

// add_action( 'chuquipiondo_after_home_module', function ( $module ) {
// 	if ( 'custom' === $module ) {
// 		echo '<section class="home-module"><h2>Mi modulo</h2></section>';
// 	}
// } );

// add_filter( 'chuquipiondo_card_style', function () {
// 	return 'magazine';
// } );

// add_filter( 'chuquipiondo_ad_slots', function ( $slots ) {
// 	$slots['ads_custom_zone'] = array( 'label' => 'Mi zona' );
// 	return $slots;
// } );
