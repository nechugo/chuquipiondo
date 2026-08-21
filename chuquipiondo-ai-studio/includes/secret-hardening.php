<?php
/**
 * Secret handling hardening for AI Studio.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Do not expose the stored API key in the settings page HTML/DOM.
 * The actual stored value remains available to provider requests elsewhere.
 *
 * @param mixed $value Stored option value.
 * @return mixed
 */
function chuquipiondo_ai_hide_api_key_on_settings_screen( $value ) {
	if ( is_admin() && isset( $_GET['page'] ) && 'chuquipiondo-ai-settings' === sanitize_key( wp_unslash( $_GET['page'] ) ) ) {
		return '';
	}
	return $value;
}
add_filter( 'option_ai_api_key', 'chuquipiondo_ai_hide_api_key_on_settings_screen' );

/**
 * Submitting an empty password field preserves the previous secret.
 *
 * @param mixed $new_value New option value.
 * @param mixed $old_value Previous option value.
 * @return string
 */
function chuquipiondo_ai_preserve_api_key_on_blank_update( $new_value, $old_value ) {
	$new_value = trim( (string) $new_value );
	if ( '' === $new_value ) {
		return (string) $old_value;
	}
	return sanitize_text_field( $new_value );
}
add_filter( 'pre_update_option_ai_api_key', 'chuquipiondo_ai_preserve_api_key_on_blank_update', 10, 2 );
