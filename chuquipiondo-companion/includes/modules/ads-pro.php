<?php
/**
 * Module 3: Ads Pro.
 *
 * Extends the theme's ads system with:
 *  - Rotation: cycle through multiple ad codes per slot with a delay.
 *  - A/B testing: split traffic between variant A and B by percentage.
 *  - Analytics: count impressions/clicks per slot in post meta.
 *  - Locations map: configurable per-slot codes (overrides theme slots
 *    when present, falls back to the theme slot otherwise).
 *
 * Activated by `companion_ads_pro_enable`. Uses theme helper
 * `chuquipiondo_ad_slot()` when available for rendering.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether ads pro is active.
 *
 * @return bool
 */
function chuquipiondo_companion_ads_pro_active() {
	return chuquipiondo_companion_is_enabled( 'companion_ads_pro_enable' );
}

/**
 * Override the theme ad slot rendering with the pro engine.
 */
function chuquipiondo_companion_override_ads() {
	if ( ! chuquipiondo_companion_ads_pro_active() ) {
		return;
	}
	// Hook before the theme ad slot fires; we render and short-circuit.
	add_filter( 'chuquipiondo_ad_code', 'chuquipiondo_companion_filter_ad_code', 10, 2 );
}
add_action( 'template_redirect', 'chuquipiondo_companion_override_ads' );

/**
 * Get the pro ads mode.
 *
 * @return string
 */
function chuquipiondo_companion_ads_pro_mode() {
	return sanitize_key( chuquipiondo_companion_get_option( 'companion_ads_pro_mode' ) );
}

/**
 * Get the configured ad code for a location.
 *
 * @param string $location Location key (e.g. after_title).
 * @return string Raw ad code (HTML/JS). Empty if none.
 */
function chuquipiondo_companion_get_ad_code( $location ) {
	$locations = chuquipiondo_companion_get_array_option( 'companion_ads_pro_locations' );
	if ( ! isset( $locations[ $location ] ) || ! is_string( $locations[ $location ] ) ) {
		return '';
	}
	return $locations[ $location ];
}

/**
 * Resolve the final ad code for a slot given the active mode.
 *
 * @param string $location Location key.
 * @return string
 */
function chuquipiondo_companion_resolve_ad( $location ) {
	$code = chuquipiondo_companion_get_ad_code( $location );
	if ( '' === $code ) {
		return '';
	}

	$mode = chuquipiondo_companion_ads_pro_mode();

	switch ( $mode ) {
		case 'rotation':
			$variants = array_filter( array_map( 'trim', explode( '||', $code ) ) );
			if ( empty( $variants ) ) {
				return '';
			}
			$idx = abs( crc32( $location . gmdate( 'Hi' ) ) ) % count( $variants );
			$resolved = $variants[ $idx ];
			break;

		case 'ab':
			$variants = array_filter( array_map( 'trim', explode( '||', $code ) ) );
			if ( empty( $variants ) ) {
				return '';
			}
			$traffic_a = (int) chuquipiondo_companion_get_option( 'companion_ads_pro_ab_traffic', '50' );
			$bucket     = chuquipiondo_companion_ab_bucket();
			$resolved   = ( $bucket <= $traffic_a ) ? $variants[0] : ( isset( $variants[1] ) ? $variants[1] : $variants[0] );
			break;

		case 'adsense-auto':
			// Auto ads: only inject once per page; rely on AdSense script.
			$resolved = $code;
			break;

		case 'manual':
		default:
			$resolved = $code;
			break;
	}

	return $resolved;
}

/**
 * Determine the A/B bucket (0-100) for the current visitor.
 *
 * Sticky per visitor via cookie so the same user sees the same variant.
 *
 * @return int
 */
function chuquipiondo_companion_ab_bucket() {
	$cookie = isset( $_COOKIE['chuqui_ab_bucket'] ) ? (int) $_COOKIE['chuqui_ab_bucket'] : 0;
	if ( $cookie > 0 ) {
		return min( 100, max( 1, $cookie ) );
	}
	$bucket = wp_rand( 1, 100 );
	setcookie( 'chuqui_ab_bucket', (string) $bucket, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
	return $bucket;
}

/**
 * Filter the theme ad code to inject the pro engine output.
 *
 * @param string $code      Original ad code.
 * @param string $slot_id   Slot identifier (e.g. ads_after_title).
 * @return string
 */
function chuquipiondo_companion_filter_ad_code( $code, $slot_id ) {
	// Map theme slot ids to companion locations.
	$location = str_replace( 'ads_', '', $slot_id );
	$pro_code = chuquipiondo_companion_resolve_ad( $location );
	if ( '' === $pro_code ) {
		return $code; // Fall back to the theme slot.
	}

	chuquipiondo_companion_record_impression( $slot_id );

	$label = '';
	if ( chuquipiondo_companion_is_enabled( 'companion_ads_pro_label' ) ) {
		$label_text = chuquipiondo_companion_get_option( 'companion_ads_pro_label_text', 'Publicidad' );
		$label       = '<span class="chuqui-ad__label">' . esc_html( $label_text ) . '</span>';
	}

	return $label . $pro_code;
}

/**
 * Record an impression for a slot (analytics).
 *
 * @param string $slot_id Slot identifier.
 */
function chuquipiondo_companion_record_impression( $slot_id ) {
	if ( ! chuquipiondo_companion_is_enabled( 'companion_ads_pro_analytics' ) ) {
		return;
	}
	$key   = '_chuquipiondo_ads_impressions';
	$today = gmdate( 'Y-m-d' );
	$stats = get_option( $key, array() );
	if ( ! is_array( $stats ) ) {
		$stats = array();
	}
	if ( ! isset( $stats[ $today ][ $slot_id ] ) ) {
		$stats[ $today ][ $slot_id ] = 0;
	}
	$stats[ $today ][ $slot_id ]++;
	// Keep only the last 30 days to avoid unbounded growth.
	$stats = array_slice( $stats, -30, null, true );
	update_option( $key, $stats, false );
}

/**
 * Render an analytics summary on the companion settings page.
 */
function chuquipiondo_companion_ads_analytics_widget() {
	if ( ! chuquipiondo_companion_is_enabled( 'companion_ads_pro_analytics' ) ) {
		return;
	}
	$stats   = get_option( '_chuquipiondo_ads_impressions', array() );
	$today   = gmdate( 'Y-m-d' );
	$yest    = gmdate( 'Y-m-d', strtotime( '-1 day' ) );
	$today_n = isset( $stats[ $today ] ) ? array_sum( $stats[ $today ] ) : 0;
	$yest_n  = isset( $stats[ $yest ] ) ? array_sum( $stats[ $yest ] ) : 0;
	?>
	<div class="chuqui-companion-analytics">
		<h2 class="title"><?php esc_html_e( 'Impresiones de anuncios', 'chuquipiondo-companion' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><?php esc_html_e( 'Hoy', 'chuquipiondo-companion' ); ?></th>
				<td><?php echo esc_html( number_format_i18n( $today_n ) ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Ayer', 'chuquipiondo-companion' ); ?></th>
				<td><?php echo esc_html( number_format_i18n( $yest_n ) ); ?></td>
			</tr>
		</table>
		<p class="description"><?php esc_html_e( 'Se conservan los ultimos 30 dias.', 'chuquipiondo-companion' ); ?></p>
	</div>
	<?php
}
add_action( 'chuquipiondo_companion_after_settings_form', 'chuquipiondo_companion_ads_analytics_widget' );
