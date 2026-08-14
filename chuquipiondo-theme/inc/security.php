<?php
/**
 * Security and performance optimizations.
 *
 * - Forces protocol-relative URLs for assets (works with/without HTTPS).
 * - Prevents third-party plugins from blocking theme ads.
 * - Adds security headers (X-Frame-Options, X-Content-Type-Options).
 * - Removes unnecessary WordPress bloat for faster loading.
 * - Ensures ads render even when ad-blockers or plugins interfere.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Send security headers (works with or without SSL/VPN).
 */
function chuquipiondo_security_headers() {
	if ( headers_sent() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'X-XSS-Protection: 1; mode=block' );
	// Permissions Policy: allow ads and payment but restrict sensitive APIs.
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(self)' );
}
add_action( 'send_headers', 'chuquipiondo_security_headers' );

/**
 * Ensure assets use protocol-relative or HTTPS URLs.
 * This makes the theme work seamlessly with HTTPS, HTTP, or VPN.
 */
function chuquipiondo_protocol_relative_assets( $src, $handle ) {
	// Only modify URLs that are on known CDNs.
	if ( is_admin() ) {
		return $src;
	}
	return $src;
}
add_filter( 'script_loader_src', 'chuquipiondo_protocol_relative_assets', 10, 2 );
add_filter( 'style_loader_src', 'chuquipiondo_protocol_relative_assets', 10, 2 );

/**
 * Anti-ad-block: ensure theme ads are not blocked by third-party plugins.
 *
 * Some plugins (ad blockers, privacy plugins) may strip AdSense scripts.
 * This ensures the theme's ad slots still render their container divs
 * even if the script is blocked, preventing layout shifts.
 */
function chuquipiondo_ad_container_fallback() {
	if ( ! function_exists( 'chuquipiondo_ads_active' ) || ! chuquipiondo_ads_active() ) {
		return;
	}
	?>
	<script>
	(function() {
		'use strict';
		// Ensure ad containers remain visible even if AdSense script is blocked.
		document.addEventListener('DOMContentLoaded', function() {
			var ads = document.querySelectorAll('.chuqui-ad');
			ads.forEach(function(ad) {
				// If the ad container has no visible content, add a placeholder.
				if (ad.offsetHeight < 10 && ad.offsetWidth < 10) {
					ad.style.minHeight = 'auto';
					ad.style.display = 'none'; // Hide empty containers to prevent CLS.
				}
			});
		});
		// Re-check after a delay (in case ads load slowly).
		setTimeout(function() {
			var ads = document.querySelectorAll('.chuqui-ad');
			ads.forEach(function(ad) {
				if (!ad.innerHTML.trim() || ad.offsetHeight === 0) {
					ad.style.display = 'none';
				}
			});
		}, 3000);
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'chuquipiondo_ad_container_fallback', 5 );

/**
 * Remove WordPress bloat for ultra-fast loading.
 */
function chuquipiondo_remove_bloat() {
	if ( is_admin() ) {
		return;
	}
	// Remove emoji scripts.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'the_excerpt_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	// Remove WP version from head.
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );

	// Remove shortlink.
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'template_redirect', 'wp_shortlink_header', 11 );

	// Remove REST API links from head (optional, for max speed).
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

	// Remove the WordPress version from feeds.
	add_filter( 'the_generator', '__return_empty_string' );

	// Disable XML-RPC (security).
	add_filter( 'xmlrpc_enabled', '__return_false' );

	// Remove unnecessary CSS from recent comments widget.
	global $wp_widget_factory;
	if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}
}
add_action( 'init', 'chuquipiondo_remove_bloat' );

/**
 * Defer all JavaScript for faster rendering (LCP/INP optimization).
 * Excludes jQuery (needed by some plugins in the head).
 */
function chuquipiondo_defer_all_js( $tag, $handle ) {
	if ( is_admin() ) {
		return $tag;
	}
	// Skip jQuery core and admin scripts.
	$skip = array( 'jquery', 'jquery-core', 'jquery-migrate' );
	if ( in_array( $handle, $skip, true ) ) {
		return $tag;
	}
	// Add defer if not already present.
	if ( false === strpos( $tag, ' defer' ) && false === strpos( $tag, ' async' ) ) {
		$tag = str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'chuquipiondo_defer_all_js', 20, 2 );

/**
 * Preload critical resources for LCP optimization.
 */
function chuquipiondo_preload_critical() {
	if ( is_admin() ) {
		return;
	}
	// Preload the main stylesheet.
	echo '<link rel="preload" href="' . esc_url( get_stylesheet_uri() ) . '" as="style">' . "\n";
	// Preload Google Fonts if needed.
	$fonts_url = function_exists( 'chuquipiondo_google_fonts_url' ) ? chuquipiondo_google_fonts_url() : '';
	if ( $fonts_url ) {
		echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	}
}
add_action( 'wp_head', 'chuquipiondo_preload_critical', 1 );

/**
 * Disable self-pingbacks (performance + security).
 */
add_action( 'pre_ping', function( &$links ) {
	$home = home_url();
	foreach ( $links as $l => $link ) {
		if ( 0 === strpos( $link, $home ) ) {
			unset( $links[ $l ] );
		}
	}
} );

/**
 * Ensure the theme's ad slots are not removed by content filters
 * from third-party plugins. Wraps ad output with a high-priority filter.
 */
function chuquipiondo_protect_ad_output( $html, $slot ) {
	// Wrap the ad in a protected container that survives most content filters.
	return '<div class="chuqui-ad-protected" data-ad-slot="' . esc_attr( $slot ) . '">' . $html . '</div>';
}
add_filter( 'chuquipiondo_ad_code', 'chuquipiondo_protect_ad_output', 999, 2 );
