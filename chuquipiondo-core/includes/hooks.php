<?php
/**
 * Custom hooks for the CHUQUIPIONDO Core plugin.
 *
 * Extends the theme with additional hookable actions and filters.
 *
 * @package CHUQUIPIONDO_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add reading progress bar at the top of single posts.
 */
function chuquipiondo_core_reading_progress() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	?>
	<div class="chuqui-reading-progress" id="chuqui-reading-progress">
		<div class="chuqui-reading-progress__bar"></div>
	</div>
	<?php
}
add_action( 'wp_body_open', 'chuquipiondo_core_reading_progress' );

/**
 * Add estimated reading time to post meta.
 */
function chuquipiondo_core_reading_time_to_meta( $content ) {
	if ( is_singular( 'post' ) && is_main_query() && function_exists( 'chuquipiondo_reading_time' ) ) {
		$minutes = chuquipiondo_reading_time();
		$badge   = '<div class="chuqui-reading-time-badge">' . sprintf(
			/* translators: %d: minutes */
			esc_html( _n( '%d min de lectura', '%d mins de lectura', $minutes, 'chuquipiondo-core' ) ),
			(int) $minutes
		) . '</div>';
		return $badge . $content;
	}
	return $content;
}
add_filter( 'the_content', 'chuquipiondo_core_reading_time_to_meta' );

/**
 * Add "Back to top" button.
 */
function chuquipiondo_core_back_to_top() {
	?>
	<button class="chuqui-back-to-top" id="chuqui-back-to-top" aria-label="<?php esc_attr_e( 'Volver arriba', 'chuquipiondo-core' ); ?>" hidden>
		<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M7.41 15.41 12 10.83l4.59 4.58L18 14l-6-6-6 6z"/></svg>
	</button>
	<?php
}
add_action( 'wp_footer', 'chuquipiondo_core_back_to_top' );

/**
 * Add lazy loading to images that don't have it.
 */
function chuquipiondo_core_lazy_load_images( $content ) {
	if ( is_admin() || is_feed() ) {
		return $content;
	}
	// Add loading="lazy" to img tags that don't have it.
	$content = preg_replace_callback(
		'/<img(?![^>]*loading=)[^>]*>/i',
		function ( $matches ) {
			return str_replace( '<img', '<img loading="lazy"', $matches[0] );
		},
		$content
	);
	return $content;
}
add_filter( 'the_content', 'chuquipiondo_core_lazy_load_images' );

/**
 * Add custom body classes from the core plugin.
 */
function chuquipiondo_core_body_class( $classes ) {
	if ( is_singular( 'post' ) ) {
		$classes[] = 'has-reading-progress';
	}
	return $classes;
}
add_filter( 'body_class', 'chuquipiondo_core_body_class' );
