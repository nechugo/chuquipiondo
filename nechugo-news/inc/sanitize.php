<?php
/**
 * Funciones de saneamiento de opciones del personalizador.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitiza una opcion checkbox del customizer.
 *
 * @param mixed $value Valor entrante.
 * @return bool
 */
function nechugo_sanitize_checkbox( $value ) {
	return ( isset( $value ) && ( true === $value || 'true' === $value || 1 === $value || '1' === $value ) );
}

/**
 * Sanitiza una seleccion dentro de una lista de valores permitidos.
 *
 * @param string               $value   Valor entrante.
 * @param WP_Customize_Setting $setting Setting.
 * @return string
 */
function nechugo_sanitize_select( $value, $setting ) {
	$value   = sanitize_key( $value );
	$choices = $setting->manager->get_control( $setting->id )->choices;
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}
	return $setting->default;
}

/**
 * Sanitiza un numero dentro de rango.
 *
 * @param int                  $value   Valor.
 * @param WP_Customize_Setting $setting Setting.
 * @return int
 */
function nechugo_sanitize_number( $value, $setting ) {
	$value = is_numeric( $value ) ? absint( round( (float) $value ) ) : $setting->default;
	$input = $setting->manager->get_control( $setting->id );
	if ( $input && isset( $input->input_attrs['min'], $input->input_attrs['max'] ) ) {
		$value = min( max( $value, $input->input_attrs['min'] ), $input->input_attrs['max'] );
	}
	return $value;
}

/**
 * Sanitiza codigo de anuncio conservando solo scripts de confianza (AdSense).
 *
 * 1. Elimina por completo los scripts inline (sin atributo src).
 * 2. Elimina los scripts externos cuyo host no este en la lista de confianza.
 * 3. Elimina atributos de evento (onclick, onerror, etc.) y URLs javascript:
 *    de cualquier etiqueta restante.
 *
 * @param string $value Codigo HTML.
 * @return string
 */
function nechugo_sanitize_ad_code( $value ) {
	$value = (string) $value;

	// Dominios de confianza para scripts de anuncios.
	$trusted = array(
		'pagead2.googlesyndication.com',
		'adservice.google.com',
		'www.googletagmanager.com',
		'cdn.ampproject.org',
	);

	// 1. Scripts inline: se eliminan por completo, con su contenido.
	$value = preg_replace( '/<script\b(?![^>]*\bsrc\s*=)[^>]*>.*?<\/script>/is', '', $value );
	$value = preg_replace( '/<script\b(?![^>]*\bsrc\s*=)[^>]*>$/i', '', $value );

	// 2. Scripts externos: conserva solo los de dominios confiables.
	if ( preg_match_all( '/<script[^>]*\bsrc\s*=\s*["\']([^"\']+)["\'][^>]*>\s*<\/script>/i', $value, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $match ) {
			$host = wp_parse_url( $match[1], PHP_URL_HOST );
			if ( ! $host || ! in_array( $host, $trusted, true ) ) {
				$value = str_replace( $match[0], '', $value );
			}
		}
	}

	// 3. Atributos de evento y URLs javascript: en cualquier etiqueta.
	$value = preg_replace( '/\son\w+\s*=\s*(?:"[^"]*"|\'[^\']*\')/i', '', $value );
	$value = preg_replace( '/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1=$2$2', $value );

	return trim( $value );
}
