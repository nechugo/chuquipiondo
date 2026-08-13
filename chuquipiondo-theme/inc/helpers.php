<?php
/**
 * Theme helper utilities.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a theme option from the Customizer with a fallback default.
 *
 * @param string $key     Option key (must exist in CHUQUIPIONDO\defaults).
 * @param mixed  $default Optional override default.
 * @return mixed
 */
function chuquipiondo_get_option( $key, $default = null ) {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = chuquipiondo_defaults();
	}
	$fallback = array_key_exists( $key, $defaults ) ? $defaults[ $key ] : '';
	$fallback = ( null !== $default ) ? $default : $fallback;
	return get_theme_mod( $key, $fallback );
}

/**
 * Echo a theme option safely (escaped on output by callers).
 *
 * @param string $key Option key.
 */
function chuquipiondo_the_option( $key ) {
	echo chuquipiondo_get_option( $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- callers escape.
}

/**
 * Boolean helper for a theme_mod toggle.
 *
 * @param string $key Option key.
 * @return bool
 */
function chuquipiondo_is_enabled( $key ) {
	$value = chuquipiondo_get_option( $key );
	return in_array( (string) $value, array( '1', 1, 'on', 'yes', 'true' ), true );
}

/**
 * Get the theme version.
 *
 * @return string
 */
function chuquipiondo_version() {
	return CHUQUIPIONDO_VERSION;
}

/**
 * Cache-busting version for an asset file.
 *
 * @param string $relative Relative path inside the theme (e.g. "assets/js/slider.js").
 * @return string
 */
function chuquipiondo_asset_version( $relative ) {
	$path = CHUQUIPIONDO_DIR . '/' . ltrim( $relative, '/' );
	if ( file_exists( $path ) ) {
		$mtime = filemtime( $path );
		if ( $mtime ) {
			return CHUQUIPIONDO_VERSION . '-' . $mtime;
		}
	}
	return CHUQUIPIONDO_VERSION;
}

/**
 * Output an inline CSS variable map from an associative array.
 *
 * @param array $vars Variable pairs (name => value).
 * @return string
 */
function chuquipiondo_build_css_vars( $vars ) {
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
function chuquipiondo_truthy( $value ) {
	return in_array( (string) $value, array( '1', 1, 'on', 'yes', 'true' ), true );
}

/**
 * Get an array option as an array (never null).
 *
 * @param string $key Option key.
 * @return array
 */
function chuquipiondo_get_array_option( $key ) {
	$value = chuquipiondo_get_option( $key, array() );
	if ( ! is_array( $value ) ) {
		$value = array();
	}
	return $value;
}

/**
 * Render a template part with arguments, mirroring get_template_part
 * but allowing $args to flow through for template-parts.
 *
 * @param string $slug Slug.
 * @param string $name Optional name.
 * @param array  $args Optional args passed to the template.
 */
function chuquipiondo_get_template_part( $slug, $name = null, $args = array() ) {
	$templates = array();
	$name       = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "{$slug}-{$name}.php";
	}
	$templates[] = "{$slug}.php";

	$located = '';
	foreach ( $templates as $template_name ) {
		$path = CHUQUIPIONDO_DIR . '/' . $template_name;
		if ( file_exists( $path ) ) {
			$located = $path;
			break;
		}
	}

	/**
	 * Filters the located template path.
	 *
	 * @param string $located       Located path.
	 * @param string $slug          Slug requested.
	 * @param string $name          Name requested.
	 * @param array  $args          Arguments.
	 */
	$located = apply_filters( 'chuquipiondo_located_template', $located, $slug, $name, $args );

	if ( $located ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- template args.
		}
		include $located;
	}
}

/**
 * Retrieve a theme mod as an integer within bounds.
 *
 * @param string $key  Option key.
 * @param int    $min  Minimum.
 * @param int    $max  Maximum.
 * @return int
 */
function chuquipiondo_get_int_option( $key, $min = 0, $max = PHP_INT_MAX ) {
	$value = (int) chuquipiondo_get_option( $key );
	if ( $value < $min ) {
		$value = $min;
	}
	if ( $value > $max ) {
		$value = $max;
	}
	return $value;
}

/**
 * Determine if the current request should load the music player assets.
 *
 * @return bool
 */
function chuquipiondo_needs_music_assets() {
	return (bool) ( is_singular( 'musica' ) || is_post_type_archive( 'musica' ) || chuquipiondo_is_enabled( 'music_mini_player' ) );
}
