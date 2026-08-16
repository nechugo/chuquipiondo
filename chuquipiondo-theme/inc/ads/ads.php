<?php
/**
 * Advertising engine.
 *
 * Master switch, modes, slot rendering, and smart paragraph
 * insertion that never breaks headings, lists, audio, video, etc.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether ads are globally active.
 *
 * @return bool
 */
function chuquipiondo_ads_active() {
	if ( ! chuquipiondo_is_enabled( 'ads_master_switch' ) ) {
		return false;
	}
	$mode = chuquipiondo_get_option( 'ads_mode' );
	return 'disabled' !== $mode;
}

/**
 * Whether a specific mode is active.
 *
 * @param string $mode Mode to check (sitekit|auto|manual|auto-manual).
 * @return bool
 */
function chuquipiondo_ads_mode_is( $mode ) {
	if ( ! chuquipiondo_ads_active() ) {
		return false;
	}
	return chuquipiondo_get_option( 'ads_mode' ) === $mode;
}

/**
 * Output the AdSense auto-ads script (only in auto / auto-manual modes).
 */
function chuquipiondo_adsense_auto_script() {
	if ( ! chuquipiondo_ads_active() ) {
		return;
	}
	if ( ! chuquipiondo_ads_mode_is( 'auto' ) && ! chuquipiondo_ads_mode_is( 'auto-manual' ) ) {
		return;
	}
	$client = chuquipiondo_get_option( 'ads_client_id' );
	if ( ! $client ) {
		return;
	}
	?>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo esc_attr( $client ); ?>" crossorigin="anonymous"></script>
	<?php
}
add_action( 'wp_head', 'chuquipiondo_adsense_auto_script', 5 );

/**
 * Render an ad slot by key.
 *
 * - If the master switch is OFF, nothing is rendered (no HTML).
 * - If the slot code is empty, nothing is rendered (no HTML).
 * - Otherwise the slot code is wrapped in a div with data-slot.
 *
 * @param string $slot Slot key (must exist in chuquipiondo_ad_slots()).
 */
function chuquipiondo_ad_slot( $slot ) {
	if ( ! chuquipiondo_ads_active() ) {
		return;
	}
	// Manual / auto-manual modes show manual slots.
	if ( ! chuquipiondo_ads_mode_is( 'manual' ) && ! chuquipiondo_ads_mode_is( 'auto-manual' ) ) {
		return;
	}

	$slots = chuquipiondo_ad_slots();
	if ( ! isset( $slots[ $slot ] ) ) {
		return;
	}

	/**
	 * Filters the ad code for a slot before rendering.
	 *
	 * @param string $code Ad code.
	 * @param string $slot Slot key.
	 */
	$code = apply_filters( 'chuquipiondo_ad_code', chuquipiondo_get_option( $slot ), $slot );

	if ( '' === trim( (string) $code ) ) {
		return; // No empty HTML.
	}

	printf( '<div class="chuqui-ad chuqui-ad--%1$s" data-slot="%1$s">', esc_attr( $slot ) );
	echo $code; // phpcs:ignore WordPress.Security.EscapeOutput -- sanitized on save via chuquipiondo_sanitize_ad_code.
	echo '</div>';
}

/**
 * Insert ads into post content after specific paragraph numbers.
 *
 * Never inserts inside headings, lists, blockquotes, audio, video,
 * tables, figures, pre, or nested containers. Only top-level <p>
 * tags are counted as insertion points.
 *
 * @param string $content Filtered post content.
 * @return string
 */
function chuquipiondo_insert_ads_in_content( $content ) {
	if ( ! chuquipiondo_ads_active() ) {
		return $content;
	}
	if ( ! ( chuquipiondo_ads_mode_is( 'manual' ) || chuquipiondo_ads_mode_is( 'auto-manual' ) ) ) {
		return $content;
	}

	// Only on single posts.
	if ( ! is_singular( 'post' ) ) {
		return $content;
	}

	// Split by top-level paragraph tags.
	$paragraphs = chuquipiondo_split_content_paragraphs( $content );
	if ( count( $paragraphs ) < 4 ) {
		return $content; // Too short for mid-content ads.
	}

	// Puntos de insercion dentro del contenido del articulo.
	// Secuencia: despues de la imagen del post, contar 4 lineas de texto,
	// en la 6ta linea va la primera caja de Ads. El slot 3 se maneja fuera
	// del filtro (via .article-row en single.php), asi que no se inserta aqui.
	$insertion_points = array_filter( array(
		6 => chuquipiondo_get_option( 'ads_after_paragraph_6' ),
		8 => chuquipiondo_get_option( 'ads_after_paragraph_8' ),
	) );

	if ( empty( $insertion_points ) ) {
		return $content;
	}

	$out         = '';
	$p_count     = 0;
	foreach ( $paragraphs as $block ) {
		$out .= $block;
		// Is this block a top-level paragraph?
		if ( preg_match( '/^<p[ >]/i', trim( $block ) ) ) {
			$p_count++;
			if ( isset( $insertion_points[ $p_count ] ) ) {
				$code = $insertion_points[ $p_count ];
				if ( '' !== trim( (string) $code ) ) {
					$out .= '<div class="chuqui-ad chuqui-ad--in-content" data-slot="ads_after_paragraph_' . (int) $p_count . '">';
					$out .= $code; // Already sanitized on save.
					$out .= '</div>';
				}
			}
		}
	}

	return $out;
}

/**
 * Split post content into top-level block chunks while preserving
 * nested HTML. Splits on </p>, </h1-6>, </li>, </blockquote>, etc.,
 * keeping each closing block as a chunk boundary.
 *
 * @param string $content Content.
 * @return array
 */
function chuquipiondo_split_content_paragraphs( $content ) {
	// Use a regex to split after each closing tag of a block element.
	$pattern = '/(.*?<\/(?:p|h[1-6]|li|blockquote|figure|figcaption|ul|ol|table|tr|div|pre|audio|video|iframe)>)/is';
	$parts   = preg_split( $pattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

	$chunks = array();
	foreach ( $parts as $part ) {
		$chunks[] = $part;
	}
	return $chunks;
}
