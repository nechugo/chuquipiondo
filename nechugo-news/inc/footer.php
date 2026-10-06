<?php
/**
 * Logica del pie de pagina.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Texto de copyright con variables {year} y {sitename}.
 *
 * @return string
 */
function nechugo_footer_copyright() {
	$text = nechugo_get_option( 'footer_copyright_text' );
	$text = str_replace(
		array( '{year}', '{sitename}' ),
		array( wp_date( 'Y' ), get_bloginfo( 'name' ) ),
		$text
	);
	return wp_kses( $text, array( 'a' => array( 'href' => array(), 'rel' => array(), 'target' => array() ) ) );
}
