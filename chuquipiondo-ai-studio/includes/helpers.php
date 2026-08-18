<?php
/**
 * Helper utilities for the CHUQUIPIONDO AI Studio plugin.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get an AI plugin option with a fallback default.
 *
 * @param string $key     Option key (must exist in the defaults map).
 * @param mixed  $default Optional override default.
 * @return mixed
 */
function chuquipiondo_ai_get_option( $key, $default = null ) {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = chuquipiondo_ai_defaults();
	}
	$fallback = array_key_exists( $key, $defaults ) ? $defaults[ $key ] : '';
	$fallback = ( null !== $default ) ? $default : $fallback;
	return get_option( $key, $fallback );
}

/**
 * Boolean helper for an AI toggle option.
 *
 * @param string $key Option key.
 * @return bool
 */
function chuquipiondo_ai_is_enabled( $key ) {
	$value = chuquipiondo_ai_get_option( $key );
	return in_array( (string) $value, array( '1', 1, 'on', 'yes', 'true' ), true );
}

/**
 * Get an AI option as a guaranteed array.
 *
 * @param string $key Option key.
 * @return array
 */
function chuquipiondo_ai_get_array_option( $key ) {
	$value = chuquipiondo_ai_get_option( $key, array() );
	if ( ! is_array( $value ) ) {
		$value = array();
	}
	return $value;
}

/**
 * Retrieve an AI option as an integer within bounds.
 *
 * @param string $key Option key.
 * @param int    $min Minimum.
 * @param int    $max Maximum.
 * @return int
 */
function chuquipiondo_ai_get_int_option( $key, $min = 0, $max = PHP_INT_MAX ) {
	$value = (int) chuquipiondo_ai_get_option( $key );
	if ( $value < $min ) {
		$value = $min;
	}
	if ( $value > $max ) {
		$value = $max;
	}
	return $value;
}

/**
 * Detect if the CHUQUIPIONDO theme (parent) is active.
 *
 * @return bool
 */
function chuquipiondo_ai_is_theme_active() {
	$theme = wp_get_theme();
	if ( ! $theme instanceof WP_Theme ) {
		return false;
	}
	if ( 'chuquipiondo' === $theme->get_template() || 'chuquipiondo' === $theme->get( 'TextDomain' ) ) {
		return true;
	}
	$parent = $theme->parent();
	if ( $parent instanceof WP_Theme && 'chuquipiondo' === $parent->get_template() ) {
		return true;
	}
	return false;
}

/**
 * Whether the active theme is Astra (parent or child).
 *
 * Astra support is a hard requirement, so we expose a helper for it
 * and for the compat layer to short-circuit safely.
 *
 * @return bool
 */
function chuquipiondo_ai_is_astra_active() {
	$theme = wp_get_theme();
	if ( ! $theme instanceof WP_Theme ) {
		return false;
	}
	$slugs = array( 'astra', 'astra-child' );
	if ( in_array( $theme->get_template(), $slugs, true ) || in_array( $theme->get( 'TextDomain' ), $slugs, true ) ) {
		return true;
	}
	$parent = $theme->parent();
	if ( $parent instanceof WP_Theme && in_array( $parent->get_template(), $slugs, true ) ) {
		return true;
	}
	return false;
}

/**
 * Cache-busting version for an AI asset file.
 *
 * @param string $relative Relative path inside the plugin.
 * @return string
 */
function chuquipiondo_ai_asset_version( $relative ) {
	$path = CHUQUIPIONDO_AI_DIR . ltrim( $relative, '/' );
	if ( file_exists( $path ) ) {
		return CHUQUIPIONDO_AI_VERSION . '-' . filemtime( $path );
	}
	return CHUQUIPIONDO_AI_VERSION;
}

/**
 * Safe JSON decode that always returns an array on failure.
 *
 * @param string $json Raw JSON string.
 * @param array  $default Fallback value.
 * @return array|mixed
 */
function chuquipiondo_ai_json_decode( $json, $default = array() ) {
	if ( ! is_string( $json ) || '' === trim( $json ) ) {
		return $default;
	}
	$decoded = json_decode( $json, true );
	if ( null === $decoded && JSON_ERROR_NONE !== json_last_error() ) {
		return $default;
	}
	return null === $decoded ? $default : $decoded;
}

/**
 * Mask a string for safe logging (never exposes the full key).
 *
 * @param string $value Secret value.
 * @return string
 */
function chuquipiondo_ai_mask_secret( $value ) {
	$value = (string) $value;
	$len   = strlen( $value );
	if ( $len <= 8 ) {
		return $len > 0 ? str_repeat( '*', $len ) : '';
	}
	return substr( $value, 0, 4 ) . str_repeat( '*', max( $len - 8, 4 ) ) . substr( $value, -4 );
}
