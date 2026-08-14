<?php
/**
 * Font registry.
 *
 * Central list of fonts available in the Customizer. Each font
 * defines its display label, the CSS font-family stack, and the
 * Google Fonts URL fragment (empty for system fonts).
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the available font choices.
 *
 * Each entry:
 *   'key' => array(
 *     'label'   => Human-readable name.
 *     'stack'   => CSS font-family value.
 *     'google'  => Google Fonts URL fragment (empty if system font).
 *   )
 *
 * @return array
 */
function chuquipiondo_fonts() {
	$fonts = array(
		'inter' => array(
			'label'  => 'Inter',
			'stack'  => '"Inter", system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif',
			'google' => 'Inter:wght@400;500;600;700',
		),
		'plus-jakarta' => array(
			'label'  => 'Plus Jakarta Sans',
			'stack'  => '"Plus Jakarta Sans", "Inter", system-ui, sans-serif',
			'google' => 'Plus+Jakarta+Sans:wght@600;700;800',
		),
		'product-sans' => array(
			'label'  => 'Product Sans',
			'stack'  => '"Product Sans", "Google Sans", system-ui, sans-serif',
			'google' => '', // Product Sans is not on Google Fonts; uses system fallback.
		),
		'google-sans' => array(
			'label'  => 'Google Sans',
			'stack'  => '"Google Sans", "Product Sans", system-ui, sans-serif',
			'google' => '', // Google Sans is not publicly on Google Fonts.
		),
		'open-sans' => array(
			'label'  => 'Open Sans',
			'stack'  => '"Open Sans", system-ui, -apple-system, sans-serif',
			'google' => 'Open+Sans:wght@400;500;600;700',
		),
		'roboto' => array(
			'label'  => 'Roboto',
			'stack'  => '"Roboto", system-ui, -apple-system, sans-serif',
			'google' => 'Roboto:wght@400;500;700',
		),
		'poppins' => array(
			'label'  => 'Poppins',
			'stack'  => '"Poppins", system-ui, sans-serif',
			'google' => 'Poppins:wght@400;500;600;700',
		),
		'montserrat' => array(
			'label'  => 'Montserrat',
			'stack'  => '"Montserrat", system-ui, sans-serif',
			'google' => 'Montserrat:wght@400;500;600;700',
		),
		'lato' => array(
			'label'  => 'Lato',
			'stack'  => '"Lato", system-ui, sans-serif',
			'google' => 'Lato:wght@400;700',
		),
		'raleway' => array(
			'label'  => 'Raleway',
			'stack'  => '"Raleway", system-ui, sans-serif',
			'google' => 'Raleway:wght@400;500;600;700',
		),
		'nunito' => array(
			'label'  => 'Nunito',
			'stack'  => '"Nunito", system-ui, sans-serif',
			'google' => 'Nunito:wght@400;600;700',
		),
		'playfair' => array(
			'label'  => 'Playfair Display (Serif)',
			'stack'  => '"Playfair Display", Georgia, "Times New Roman", serif',
			'google' => 'Playfair+Display:wght@400;500;600;700',
		),
		'merriweather' => array(
			'label'  => 'Merriweather (Serif)',
			'stack'  => '"Merriweather", Georgia, serif',
			'google' => 'Merriweather:wght@400;700',
		),
		'lora' => array(
			'label'  => 'Lora (Serif)',
			'stack'  => '"Lora", Georgia, serif',
			'google' => 'Lora:wght@400;500;600;700',
		),
		'libre-baskerville' => array(
			'label'  => 'Libre Baskerville (Serif)',
			'stack'  => '"Libre Baskerville", Georgia, serif',
			'google' => 'Libre+Baskerville:wght@400;700',
		),
		'source-serif' => array(
			'label'  => 'Source Serif Pro (Serif)',
			'stack'  => '"Source Serif Pro", Georgia, serif',
			'google' => 'Source+Serif+Pro:wght@400;600;700',
		),
		'jetbrains-mono' => array(
			'label'  => 'JetBrains Mono (Monospace)',
			'stack'  => '"JetBrains Mono", "SF Mono", "Fira Code", Consolas, monospace',
			'google' => 'JetBrains+Mono:wght@400;500;700',
		),
		'fira-code' => array(
			'label'  => 'Fira Code (Monospace)',
			'stack'  => '"Fira Code", "SF Mono", Consolas, monospace',
			'google' => 'Fira+Code:wght@400;500;700',
		),
		'roboto-mono' => array(
			'label'  => 'Roboto Mono (Monospace)',
			'stack'  => '"Roboto Mono", "SF Mono", Consolas, monospace',
			'google' => 'Roboto+Mono:wght@400;500;700',
		),
		'system-sans' => array(
			'label'  => 'Sans-serif (sistema)',
			'stack'  => 'system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif',
			'google' => '',
		),
		'system-serif' => array(
			'label'  => 'Serif (sistema)',
			'stack'  => 'Georgia, "Times New Roman", Times, serif',
			'google' => '',
		),
		'system-mono' => array(
			'label'  => 'Monospace (sistema)',
			'stack'  => '"SF Mono", "Fira Code", Consolas, "Liberation Mono", monospace',
			'google' => '',
		),
	);

	/**
	 * Filters the available fonts.
	 *
	 * @param array $fonts Font registry.
	 */
	return apply_filters( 'chuquipiondo_fonts', $fonts );
}

/**
 * Get the choices array for a select control (key => label).
 *
 * @return array
 */
function chuquipiondo_font_choices() {
	$choices = array();
	foreach ( chuquipiondo_fonts() as $key => $font ) {
		$choices[ $key ] = $font['label'];
	}
	return $choices;
}

/**
 * Get the CSS font-family stack for a font key.
 *
 * @param string $key Font key.
 * @return string
 */
function chuquipiondo_font_stack( $key ) {
	$fonts = chuquipiondo_fonts();
	if ( isset( $fonts[ $key ] ) ) {
		return $fonts[ $key ]['stack'];
	}
	// Fallback to the default body font.
	return $fonts['inter']['stack'];
}

/**
 * Get the Google Fonts URL for the currently selected body + heading fonts.
 *
 * Only loads the fonts that are actually selected and have a Google
 * Fonts URL. System fonts are skipped.
 *
 * @return string Empty if no Google Fonts are needed.
 */
function chuquipiondo_google_fonts_url() {
	$body_key    = chuquipiondo_get_option( 'font_body' );
	$heading_key = chuquipiondo_get_option( 'font_heading' );
	$fonts       = chuquipiondo_fonts();

	$families = array();
	$seen     = array();

	foreach ( array( $body_key, $heading_key ) as $key ) {
		if ( ! isset( $fonts[ $key ] ) ) {
			continue;
		}
		$google = $fonts[ $key ]['google'];
		if ( $google && ! isset( $seen[ $google ] ) ) {
			$families[] = $google;
			$seen[ $google ] = true;
		}
	}

	if ( empty( $families ) ) {
		return '';
	}

	$url = 'https://fonts.googleapis.com/css2?';
	$url .= implode( '&family=', $families );
	$url .= '&display=swap';

	return $url;
}
