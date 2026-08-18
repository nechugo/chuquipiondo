<?php
/**
 * Helper utilities for the CHUQUIPIONDO Companion plugin.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a companion option with a fallback default.
 *
 * @param string $key     Option key (must exist in the defaults map).
 * @param mixed  $default Optional override default.
 * @return mixed
 */
function chuquipiondo_companion_get_option( $key, $default = null ) {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = chuquipiondo_companion_defaults();
	}
	$fallback = array_key_exists( $key, $defaults ) ? $defaults[ $key ] : '';
	$fallback = ( null !== $default ) ? $default : $fallback;
	return get_option( $key, $fallback );
}

/**
 * Boolean helper for a companion toggle option.
 *
 * @param string $key Option key.
 * @return bool
 */
function chuquipiondo_companion_is_enabled( $key ) {
	$value = chuquipiondo_companion_get_option( $key );
	return in_array( (string) $value, array( '1', 1, 'on', 'yes', 'true' ), true );
}

/**
 * Get a companion option as a guaranteed array.
 *
 * @param string $key Option key.
 * @return array
 */
function chuquipiondo_companion_get_array_option( $key ) {
	$value = chuquipiondo_companion_get_option( $key, array() );
	if ( ! is_array( $value ) ) {
		$value = array();
	}
	return $value;
}

/**
 * Retrieve a companion option as an integer within bounds.
 *
 * @param string $key  Option key.
 * @param int    $min  Minimum.
 * @param int    $max  Maximum.
 * @return int
 */
function chuquipiondo_companion_get_int_option( $key, $min = 0, $max = PHP_INT_MAX ) {
	$value = (int) chuquipiondo_companion_get_option( $key );
	if ( $value < $min ) {
		$value = $min;
	}
	if ( $value > $max ) {
		$value = $max;
	}
	return $value;
}

/**
 * Cache-busting version for a companion asset file.
 *
 * @param string $relative Relative path inside the plugin.
 * @return string
 */
function chuquipiondo_companion_asset_version( $relative ) {
	$path = CHUQUIPIONDO_COMPANION_DIR . ltrim( $relative, '/' );
	if ( file_exists( $path ) ) {
		$mtime = filemtime( $path );
		if ( $mtime ) {
			return CHUQUIPIONDO_COMPANION_VERSION . '-' . $mtime;
		}
	}
	return CHUQUIPIONDO_COMPANION_VERSION;
}

/**
 * Detect whether the CHUQUIPIONDO theme (or a child of it) is active.
 *
 * @return bool
 */
function chuquipiondo_companion_is_theme_active() {
	$theme = wp_get_theme();
	return ( 'CHUQUIPIONDO' === $theme->get( 'Name' )
		|| 'chuquipiondo-theme' === $theme->get_template()
		|| 'chuquipiondo-theme' === $theme->get_stylesheet() );
}

/**
 * Build the CSS variable map from an associative array.
 *
 * @param array $vars Variable pairs (name => value).
 * @return string
 */
function chuquipiondo_companion_build_css_vars( $vars ) {
	$out = '';
	foreach ( $vars as $name => $value ) {
		if ( '' !== $value && null !== $value ) {
			$out .= '--' . $name . ': ' . $value . '; ';
		}
	}
	return $out;
}

/**
 * Sanitizable checkbox returns.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function chuquipiondo_companion_truthy( $value ) {
	return in_array( (string) $value, array( '1', 1, 'on', 'yes', 'true' ), true );
}
