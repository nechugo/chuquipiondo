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
 * Sanitiza codigo de anuncio conservando scripts de confianza (AdSense).
 *
 * @param string $value Codigo HTML.
 * @return string
 */
function nechugo_sanitize_ad_code( $value ) {
	$value = (string) $value;

	// Lista de dominios de confianza para scripts de anuncios.
	$trusted = array(
		'pagead2.googlesyndication.com',
		'adservice.google.com',
		'www.googletagmanager.com',
		'cdn.ampproject.org',
	);

	// Extrae scripts y conserva solo los confiables.
	if ( preg_match_all( '/<script[^>]*src=["\']([^"\']+)["\'][^>]*><\/script>/i', $value, $matches ) ) {
		foreach ( $matches[1] as $i => $src ) {
			$host = wp_parse_url( $src, PHP_URL_HOST );
			if ( ! $host || ! in_array( $host, $trusted, true ) ) {
				return ''; // Script no confiable: se descarta todo el bloque.
			}
		}
	}

	return $value; // Se guarda tal cual; solo administradores con unfiltered_html pueden editarlo.
}
