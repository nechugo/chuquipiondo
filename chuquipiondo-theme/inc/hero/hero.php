<?php
/**
 * Hero / Slider system.
 *
 * Master switch controls whether ANY hero HTML or script loads.
 * The slider JS only loads when 2+ slides are present (see enqueue.php).
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the hero should render at all.
 *
 * @return bool
 */
function chuquipiondo_hero_enabled() {
	if ( ! chuquipiondo_is_enabled( 'hero_enable' ) ) {
		return false;
	}
	// Only show on the front page by default.
	if ( ! is_front_page() && ! is_home() ) {
		return false;
	}
	$mode = chuquipiondo_get_option( 'hero_mode' );
	if ( 'slider' === $mode ) {
		$slides = chuquipiondo_get_array_option( 'hero_slider' );
		return count( $slides ) >= 1;
	}
	return true;
}

/**
 * Get a slide image URL based on the device (responsive).
 *
 * @param array  $slide  Slide data.
 * @param string $device desktop|tablet|mobile.
 * @return string
 */
function chuquipiondo_hero_slide_image( $slide, $device = 'desktop' ) {
	$key = 'image_' . $device;
	if ( ! empty( $slide[ $key ] ) ) {
		return $slide[ $key ];
	}
	// Fallback to desktop image.
	if ( 'desktop' !== $device && ! empty( $slide['image_desktop'] ) ) {
		return $slide['image_desktop'];
	}
	return '';
}

/**
 * Render the hero section.
 */
function chuquipiondo_hero() {
	if ( ! chuquipiondo_hero_enabled() ) {
		return;
	}

	/**
	 * Fires before the hero markup.
	 */
	do_action( 'chuquipiondo_before_hero' );

	chuquipiondo_get_template_part( 'template-parts/hero/slider' );

	/**
	 * Fires after the hero markup.
	 */
	do_action( 'chuquipiondo_after_hero' );
}
