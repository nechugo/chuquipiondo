<?php
/**
 * Color presets.
 *
 * Each preset maps to a set of theme_mod color keys that the
 * "Apply preset" control writes into the Customizer.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the list of available presets.
 *
 * Each preset contains the color theme_mod overrides that should
 * be applied when the user selects it.
 *
 * @return array
 */
function chuquipiondo_presets() {
	$presets = array(
		'original' => array(
			'label'         => __( 'Chuquipiondo Original', 'chuquipiondo' ),
			'color_navy'     => '#0a1f44',
			'color_navy_dark'=> '#06133a',
			'color_sky'      => '#27b6ff',
			'color_sky_soft' => '#7fd6ff',
			'color_background'=> '#f5f8ff',
			'color_text'      => '#1a2233',
			'color_muted'     => '#5b6678',
			'color_accent'    => '#27b6ff',
			'footer_bg'       => '#06133a',
			'footer_text'     => '#ffffff',
		),
		'dark' => array(
			'label'         => __( 'Oscuro', 'chuquipiondo' ),
			'color_navy'     => '#0f1729',
			'color_navy_dark'=> '#050a16',
			'color_sky'      => '#27b6ff',
			'color_sky_soft' => '#7fd6ff',
			'color_background'=> '#0b1120',
			'color_text'      => '#e6ecf5',
			'color_muted'     => '#94a3b8',
			'color_accent'    => '#27b6ff',
			'footer_bg'       => '#050a16',
			'footer_text'     => '#e6ecf5',
		),
		'light' => array(
			'label'         => __( 'Claro', 'chuquipiondo' ),
			'color_navy'     => '#1e3a8a',
			'color_navy_dark'=> '#172554',
			'color_sky'      => '#0ea5e9',
			'color_sky_soft' => '#38bdf8',
			'color_background'=> '#ffffff',
			'color_text'      => '#0f172a',
			'color_muted'     => '#64748b',
			'color_accent'    => '#0ea5e9',
			'footer_bg'       => '#172554',
			'footer_text'     => '#ffffff',
		),
		'editorial' => array(
			'label'         => __( 'Editorial', 'chuquipiondo' ),
			'color_navy'     => '#1a1a1a',
			'color_navy_dark'=> '#0a0a0a',
			'color_sky'      => '#d4a017',
			'color_sky_soft' => '#e8c25a',
			'color_background'=> '#fbfbf8',
			'color_text'      => '#222222',
			'color_muted'     => '#666666',
			'color_accent'    => '#d4a017',
			'footer_bg'       => '#0a0a0a',
			'footer_text'     => '#eeeeee',
		),
		'music' => array(
			'label'         => __( 'Music', 'chuquipiondo' ),
			'color_navy'     => '#3b0764',
			'color_navy_dark'=> '#1e0335',
			'color_sky'      => '#a855f7',
			'color_sky_soft' => '#c084fc',
			'color_background'=> '#faf5ff',
			'color_text'      => '#1e1033',
			'color_muted'     => '#6b567f',
			'color_accent'    => '#a855f7',
			'footer_bg'       => '#1e0335',
			'footer_text'     => '#f5e9ff',
		),
		'minimal' => array(
			'label'         => __( 'Minimal', 'chuquipiondo' ),
			'color_navy'     => '#111827',
			'color_navy_dark'=> '#030712',
			'color_sky'      => '#2563eb',
			'color_sky_soft' => '#60a5fa',
			'color_background'=> '#ffffff',
			'color_text'      => '#111827',
			'color_muted'     => '#6b7280',
			'color_accent'    => '#2563eb',
			'footer_bg'       => '#030712',
			'footer_text'     => '#f9fafb',
		),
	);

	/**
	 * Filters the available color presets.
	 *
	 * @param array $presets Presets list.
	 */
	return apply_filters( 'chuquipiondo_presets', $presets );
}

/**
 * Apply a preset: write its color mods.
 *
 * Triggered via the "preset" radio control's customize_save_after,
 * or directly when the user picks a preset in the preview.
 *
 * @param string $key Preset key.
 */
function chuquipiondo_apply_preset( $key ) {
	$presets = chuquipiondo_presets();
	if ( ! isset( $presets[ $key ] ) ) {
		return;
	}
	$preset = $presets[ $key ];
	unset( $preset['label'] );
	foreach ( $preset as $mod => $value ) {
		set_theme_mod( $mod, $value );
	}
	set_theme_mod( 'preset', $key );
}
