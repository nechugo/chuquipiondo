<?php
/**
 * Floating WhatsApp button.
 *
 * Master switch, number, mode (private message / join group),
 * 9 positions, configurable size, pulse effect (respects
 * prefers-reduced-motion).
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build the WhatsApp link based on the configured mode.
 *
 * @return string
 */
function chuquipiondo_whatsapp_link() {
	$mode = chuquipiondo_get_option( 'whatsapp_mode' );

	if ( 'group' === $mode ) {
		$url = chuquipiondo_get_option( 'whatsapp_group_url' );
		return $url ? $url : '#';
	}

	// Private message.
	$number  = chuquipiondo_get_option( 'whatsapp_number' );
	$message = chuquipiondo_get_option( 'whatsapp_message' );
	if ( ! $number ) {
		return '#';
	}
	$link = 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $number );
	if ( $message ) {
		$link .= '?text=' . rawurlencode( $message );
	}
	return $link;
}

/**
 * Render the WhatsApp float button.
 */
function chuquipiondo_whatsapp_float() {
	if ( ! chuquipiondo_is_enabled( 'whatsapp_master_switch' ) ) {
		return;
	}

	chuquipiondo_get_template_part( 'template-parts/social/whatsapp-float' );
}
add_action( 'wp_footer', 'chuquipiondo_whatsapp_float', 20 );

/**
 * Render callback for the WhatsApp partial (used by selective refresh).
 */
function chuquipiondo_whatsapp_float_render() {
	if ( ! chuquipiondo_is_enabled( 'whatsapp_master_switch' ) ) {
		return;
	}
	chuquipiondo_get_template_part( 'template-parts/social/whatsapp-float' );
}
