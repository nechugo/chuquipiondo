<?php
/**
 * Social share buttons.
 *
 * Master switch, network selection, color modes, positions
 * (before/after article, floating sidebar, floating mobile bar).
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the list of active share networks.
 *
 * @return array
 */
function chuquipiondo_share_networks() {
	$raw = chuquipiondo_get_option( 'social_networks' );
	$networks = array_filter( array_map( 'trim', explode( ',', (string) $raw ) ) );

	$allowed = array( 'facebook', 'x', 'linkedin', 'whatsapp', 'telegram', 'email', 'copy' );
	$networks = array_values( array_filter( $networks, function ( $n ) use ( $allowed ) {
		return in_array( $n, $allowed, true );
	} ) );

	/**
	 * Filters the active share networks.
	 */
	return apply_filters( 'chuquipiondo_share_networks', $networks );
}

/**
 * Build a share URL for a network.
 *
 * @param string $network Network slug.
 * @param string $url     URL to share.
 * @param string $title   Title to share.
 * @return string
 */
function chuquipiondo_share_url( $network, $url, $title ) {
	$url   = rawurlencode( $url );
	$title = rawurlencode( $title );

	switch ( $network ) {
		case 'facebook':
			return 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
		case 'x':
			return 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title;
		case 'linkedin':
			return 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url;
		case 'whatsapp':
			return 'https://wa.me/?text=' . $title . '%20' . $url;
		case 'telegram':
			return 'https://t.me/share/url?url=' . $url . '&text=' . $title;
		case 'email':
			return 'mailto:?subject=' . $title . '&body=' . $url;
		case 'copy':
			return '#copy';
		default:
			return '';
	}
}

/**
 * Render the social share block.
 */
function chuquipiondo_social_share() {
	if ( ! chuquipiondo_is_enabled( 'social_master_switch' ) ) {
		return;
	}
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$networks = chuquipiondo_share_networks();
	if ( empty( $networks ) ) {
		return;
	}

	$url   = get_permalink();
	$title = get_the_title();
	$mode  = chuquipiondo_get_option( 'social_color_mode' );

	echo '<div class="social-share social-share--' . esc_attr( $mode ) . '" role="group" aria-label="' . esc_attr__( 'Compartir', 'chuquipiondo' ) . '">';
	echo '<span class="social-share__label">' . esc_html__( 'Compartir:', 'chuquipiondo' ) . '</span>';
	echo '<ul class="social-share__list">';

	foreach ( $networks as $network ) {
		$share_url = chuquipiondo_share_url( $network, $url, $title );
		$is_copy   = ( 'copy' === $network );
		echo '<li class="social-share__item social-share__item--' . esc_attr( $network ) . '">';
		echo '<a href="' . esc_url( $share_url ) . '"'
			. ( $is_copy ? '' : ' target="_blank" rel="noopener noreferrer"' )
			. ' class="social-share__link social-share__link--' . esc_attr( $network ) . '"'
			. ( $is_copy ? ' data-copy-url="' . esc_url( $url ) . '"' : '' )
			. ' aria-label="' . esc_attr( ucfirst( $network ) ) . '">';
		chuquipiondo_social_icon( $network );
		echo '</a></li>';
	}

	echo '</ul>';
	echo '</div>';
}

/**
 * Render the floating social share (desktop sidebar + mobile bottom bar).
 */
function chuquipiondo_social_share_floating() {
	if ( ! chuquipiondo_is_enabled( 'social_master_switch' ) ) {
		return;
	}
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	if ( ! ( chuquipiondo_is_enabled( 'social_floating' ) || chuquipiondo_is_enabled( 'social_floating_mobile' ) ) ) {
		return;
	}

	$networks = chuquipiondo_share_networks();
	if ( empty( $networks ) ) {
		return;
	}

	$url   = get_permalink();
	$title = get_the_title();
	$mode  = chuquipiondo_get_option( 'social_color_mode' );

	// Desktop floating sidebar.
	if ( chuquipiondo_is_enabled( 'social_floating' ) ) {
		echo '<div class="social-share-floating social-share-floating--desktop social-share--' . esc_attr( $mode ) . '" aria-label="' . esc_attr__( 'Compartir', 'chuquipiondo' ) . '">';
		foreach ( $networks as $network ) {
			$share_url = chuquipiondo_share_url( $network, $url, $title );
			$is_copy   = ( 'copy' === $network );
			echo '<a href="' . esc_url( $share_url ) . '"'
				. ( $is_copy ? '' : ' target="_blank" rel="noopener noreferrer"' )
				. ' class="social-share__link social-share__link--' . esc_attr( $network ) . '"'
				. ( $is_copy ? ' data-copy-url="' . esc_url( $url ) . '"' : '' )
				. ' aria-label="' . esc_attr( ucfirst( $network ) ) . '">';
			chuquipiondo_social_icon( $network );
			echo '</a>';
		}
		echo '</div>';
	}

	// Mobile floating bottom bar.
	if ( chuquipiondo_is_enabled( 'social_floating_mobile' ) ) {
		echo '<div class="social-share-floating social-share-floating--mobile social-share--' . esc_attr( $mode ) . '" aria-label="' . esc_attr__( 'Compartir', 'chuquipiondo' ) . '">';
		foreach ( $networks as $network ) {
			$share_url = chuquipiondo_share_url( $network, $url, $title );
			$is_copy   = ( 'copy' === $network );
			echo '<a href="' . esc_url( $share_url ) . '"'
				. ( $is_copy ? '' : ' target="_blank" rel="noopener noreferrer"' )
				. ' class="social-share__link social-share__link--' . esc_attr( $network ) . '"'
				. ( $is_copy ? ' data-copy-url="' . esc_url( $url ) . '"' : '' )
				. ' aria-label="' . esc_attr( ucfirst( $network ) ) . '">';
			chuquipiondo_social_icon( $network );
			echo '</a>';
		}
		echo '</div>';
	}
}
add_action( 'wp_footer', 'chuquipiondo_social_share_floating' );
