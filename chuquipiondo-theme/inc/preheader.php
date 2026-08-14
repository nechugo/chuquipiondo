<?php
/**
 * Pre-header: 2 invisible columns above the header.
 *
 * Left column: text (no background).
 * Right column: widget / HTML / shortcode / music player (default 300px,
 * expandable). The left column adapts its width to the right column's width.
 * Gap between columns: configurable (default 10px).
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the pre-header zone (2 invisible columns).
 */
function chuquipiondo_preheader() {
	if ( ! chuquipiondo_is_enabled( 'preheader_enable' ) ) {
		return;
	}

	/**
	 * Fires before the pre-header markup.
	 */
	do_action( 'chuquipiondo_before_preheader' );

	$right_width = (int) chuquipiondo_get_option( 'preheader_right_width' );
	$gap         = (int) chuquipiondo_get_option( 'preheader_gap' );
	$left_text   = chuquipiondo_get_option( 'preheader_left_text' );
	$right_type  = chuquipiondo_get_option( 'preheader_right_type' );
	$right_content = chuquipiondo_get_option( 'preheader_right_content' );
	$height_mode = chuquipiondo_get_option( 'preheader_height' );
	$fixed_height = (int) chuquipiondo_get_option( 'preheader_fixed_height' );

	$height_style = '';
	if ( 'fixed' === $height_mode && $fixed_height > 0 ) {
		$height_style = 'min-height: ' . $fixed_height . 'px;';
	}

	echo '<div class="chuqui-preheader" style="--preheader-right-width: ' . esc_attr( $right_width ) . 'px; --preheader-gap: ' . esc_attr( $gap ) . 'px; ' . esc_attr( $height_style ) . '" role="complementary" aria-label="' . esc_attr__( 'Zona superior', 'chuquipiondo' ) . '">';
	echo '<div class="chuqui-preheader__inner chuqui-container">';

	// Left column: text, no background, adapts width.
	echo '<div class="chuqui-preheader__left">';
	if ( $left_text ) {
		echo '<div class="chuqui-preheader__text">' . wp_kses_post( do_shortcode( $left_text ) ) . '</div>';
	}
	echo '</div>';

	// Right column: widget / HTML / shortcode / music player.
	echo '<div class="chuqui-preheader__right" style="width: ' . esc_attr( $right_width ) . 'px; flex-shrink: 0;">';
	chuquipiondo_preheader_right_content( $right_type, $right_content );
	echo '</div>';

	echo '</div>';
	echo '</div><!-- .chuqui-preheader -->';

	/**
	 * Fires after the pre-header markup.
	 */
	do_action( 'chuquipiondo_after_preheader' );
}

/**
 * Render the right column content based on type.
 *
 * @param string $type    Content type (widget|html|shortcode|music).
 * @param string $content Custom content.
 */
function chuquipiondo_preheader_right_content( $type, $content ) {
	switch ( $type ) {
		case 'widget':
			if ( is_active_sidebar( 'sidebar-preheader' ) ) {
				dynamic_sidebar( 'sidebar-preheader' );
			}
			break;
		case 'html':
			if ( $content ) {
				echo wp_kses_post( $content );
			}
			break;
		case 'shortcode':
			if ( $content ) {
				echo do_shortcode( $content );
			}
			break;
		case 'music':
			// Render the featured song player if configured.
			$song_id = (int) chuquipiondo_get_option( 'home_song_id' );
			if ( $song_id && 'publish' === get_post_status( $song_id ) ) {
				chuquipiondo_music_player( $song_id );
			}
			break;
	}
}
