<?php
/**
 * Sanitization callbacks used by the Customizer.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize a checkbox / toggle.
 *
 * @param mixed $checked Value.
 * @return bool
 */
function chuquipiondo_sanitize_checkbox( $checked ) {
	return (bool) in_array( (string) $checked, array( '1', 1, 'on', 'yes', 'true' ), true );
}

/**
 * Sanitize a select / radio value against a choices array.
 *
 * @param string               $value   Value.
 * @param WP_Customize_Setting $setting Setting object.
 * @return mixed
 */
function chuquipiondo_sanitize_select( $value, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	if ( is_array( $choices ) && array_key_exists( $value, $choices ) ) {
		return $value;
	}
	return $setting->default;
}

/**
 * Sanitize a radio (alias of select).
 */
function chuquipiondo_sanitize_radio( $value, $setting ) {
	return chuquipiondo_sanitize_select( $value, $setting );
}

/**
 * Sanitize an integer.
 *
 * @param mixed $value Value.
 * @return int
 */
function chuquipiondo_sanitize_int( $value ) {
	return absint( (int) $value );
}

/**
 * Sanitize a number range.
 *
 * @param mixed $value  Value.
 * @param mixed $setting Setting object.
 * @return int
 */
function chuquipiondo_sanitize_range( $value, $setting ) {
	$value  = (int) $value;
	$input_attrs = array();
	if ( isset( $setting->manager->get_control( $setting->id )->input_attrs ) ) {
		$input_attrs = $setting->manager->get_control( $setting->id )->input_attrs;
	}
	$min = isset( $input_attrs['min'] ) ? (int) $input_attrs['min'] : 0;
	$max = isset( $input_attrs['max'] ) ? (int) $input_attrs['max'] : 9999;
	if ( $value < $min ) {
		$value = $min;
	}
	if ( $value > $max ) {
		$value = $max;
	}
	return $value;
}

/**
 * Sanitize a hex color.
 *
 * @param string $value Value.
 * @return string
 */
function chuquipiondo_sanitize_hex_color( $value ) {
	if ( '' === $value ) {
		return '';
	}
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $value ) ) {
		return $value;
	}
	return '';
}

/**
 * Sanitize a color (allow hex, rgb, rgba, var()).
 *
 * @param string $value Value.
 * @return string
 */
function chuquipiondo_sanitize_color( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	// Allow CSS color variables and rgba/hex.
	if ( preg_match( '/^(#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})|rgba?\([^)]*\)|var\(--[a-z0-9-]+\)|transparent)$/i', $value ) ) {
		return $value;
	}
	return '';
}

/**
 * Sanitize text / short HTML.
 *
 * @param string $value Value.
 * @return string
 */
function chuquipiondo_sanitize_text( $value ) {
	return sanitize_text_field( wp_unslash( $value ) );
}

/**
 * Sanitize raw HTML (used for header boxes, custom code). Uses wp_kses_post.
 *
 * @param string $value Value.
 * @return string
 */
function chuquipiondo_sanitize_html( $value ) {
	return wp_kses_post( wp_unslash( $value ) );
}

/**
 * Sanitize a textarea.
 *
 * @param string $value Value.
 * @return string
 */
function chuquipiondo_sanitize_textarea( $value ) {
	return sanitize_textarea_field( wp_unslash( $value ) );
}

/**
 * Sanitize a URL.
 *
 * @param string $value Value.
 * @return string
 */
function chuquipiondo_sanitize_url( $value ) {
	return esc_url_raw( wp_unslash( $value ) );
}

/**
 * Sanitize custom CSS (allows a broad but safe subset).
 *
 * @param string $value Value.
 * @return string
 */
function chuquipiondo_sanitize_css( $value ) {
	$value = wp_unslash( (string) $value );
	// Strip PHP tags and script tags for safety.
	$value = preg_replace( '/<\?.*?\?>/s', '', $value );
	$value = preg_replace( '/<script.*?<\/script>/is', '', $value );
	return $value;
}

/**
 * Sanitize a phone number (WhatsApp). Digits only.
 *
 * @param string $value Value.
 * @return string
 */
function chuquipiondo_sanitize_phone( $value ) {
	$value = preg_replace( '/[^0-9]/', '', (string) $value );
	return substr( $value, 0, 20 );
}

/**
 * Sanitize a sortable / JSON array of items (used for home builder order).
 *
 * @param mixed $value Value.
 * @return array
 */
function chuquipiondo_sanitize_sortable( $value ) {
	if ( is_array( $value ) ) {
		$clean = array();
		foreach ( $value as $item ) {
			$clean[] = sanitize_key( (string) $item );
		}
		return $clean;
	}
	// Allow comma-separated.
	$value = (string) $value;
	$parts = explode( ',', $value );
	$clean = array();
	foreach ( $parts as $part ) {
		$part = sanitize_key( trim( $part ) );
		if ( $part ) {
			$clean[] = $part;
		}
	}
	return $clean;
}

/**
 * Sanitize a phone list of slide items (stored as array).
 *
 * @param mixed $value Value.
 * @return array
 */
function chuquipiondo_sanitize_slides( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}
	$clean = array();
	foreach ( $value as $slide ) {
		if ( ! is_array( $slide ) ) {
			continue;
		}
		$item = array(
			'image_desktop' => esc_url_raw( isset( $slide['image_desktop'] ) ? $slide['image_desktop'] : '' ),
			'image_tablet'  => esc_url_raw( isset( $slide['image_tablet'] ) ? $slide['image_tablet'] : '' ),
			'image_mobile'  => esc_url_raw( isset( $slide['image_mobile'] ) ? $slide['image_mobile'] : '' ),
			'title'         => sanitize_text_field( isset( $slide['title'] ) ? $slide['title'] : '' ),
			'subtitle'      => sanitize_text_field( isset( $slide['subtitle'] ) ? $slide['subtitle'] : '' ),
			'button_text'   => sanitize_text_field( isset( $slide['button_text'] ) ? $slide['button_text'] : '' ),
			'button_url'    => esc_url_raw( isset( $slide['button_url'] ) ? $slide['button_url'] : '' ),
			'overlay'       => absint( isset( $slide['overlay'] ) ? $slide['overlay'] : 30 ),
		);
		if ( $item['image_desktop'] || $item['title'] ) {
			$clean[] = $item;
		}
	}
	return $clean;
}

/**
 * Sanitize an ad slot code (allow AdSense scripts + ins tags).
 *
 * @param string $value Value.
 * @return string
 */
function chuquipiondo_sanitize_ad_code( $value ) {
	$allowed = array(
		'script' => array(
			'async'       => true,
			'src'         => true,
			'crossorigin' => true,
			'type'        => true,
		),
		'ins'    => array(
			'class'          => true,
			'style'          => true,
			'data-ad-client' => true,
			'data-ad-slot'    => true,
			'data-ad-format'  => true,
			'data-full-width-responsive' => true,
		),
		'div'    => array( 'class' => true, 'id' => true, 'style' => true ),
		'span'   => array( 'class' => true ),
		'a'      => array( 'href' => true, 'target' => true, 'rel' => true, 'class' => true ),
		'img'    => array( 'src' => true, 'alt' => true, 'width' => true, 'height' => true ),
		'br'     => true,
		'p'      => array( 'class' => true, 'style' => true ),
	);
	return wp_kses( wp_unslash( (string) $value ), $allowed );
}

/**
 * Sanitize a choices array of social networks.
 *
 * @param mixed $value Value.
 * @return string
 */
function chuquipiondo_sanitize_networks( $value ) {
	if ( ! is_array( $value ) ) {
		return '';
	}
	return implode( ',', array_map( 'sanitize_key', $value ) );
}
