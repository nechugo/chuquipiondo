<?php
/**
 * Music player: HTML5 audio player with sticky mini player.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the music player for a given song.
 *
 * @param int $post_id Music post ID.
 */
function chuquipiondo_music_player( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id || 'musica' !== get_post_type( $post_id ) ) {
		return;
	}

	$audio = get_post_meta( $post_id, '_music_audio_url', true );
	$artist = get_post_meta( $post_id, '_music_artist', true );
	$cover = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'chuquipiondo-square' ) : '';

	chuquipiondo_get_template_part( 'template-parts/music/player', null, array(
		'post_id' => $post_id,
		'audio'   => $audio,
		'artist'  => $artist,
		'cover'   => $cover,
		'title'   => get_the_title( $post_id ),
		'permalink' => get_permalink( $post_id ),
	) );
}

/**
 * Render the sticky mini player (in footer).
 */
function chuquipiondo_music_mini_player() {
	if ( ! chuquipiondo_is_enabled( 'music_mini_player' ) ) {
		return;
	}

	// The mini player is populated via JS when the user plays a song.
	echo '<div class="music-mini-player" id="chuqui-mini-player" hidden aria-hidden="true">';
	echo '<div class="music-mini-player__cover"><img src="" alt="" class="music-mini-player__cover-img"></div>';
	echo '<div class="music-mini-player__info">';
	echo '<span class="music-mini-player__title"></span>';
	echo '<span class="music-mini-player__artist"></span>';
	echo '</div>';
	echo '<audio class="music-mini-player__audio" preload="none"></audio>';
	echo '<div class="music-mini-player__controls">';
	echo '<button class="music-mini-player__play" aria-label="' . esc_attr__( 'Reproducir', 'chuquipiondo' ) . '">';
	echo '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M8 5v14l11-7z"/></svg>';
	echo '</button>';
	echo '</div>';
	echo '<button class="music-mini-player__close" aria-label="' . esc_attr__( 'Cerrar', 'chuquipiondo' ) . '">&times;</button>';
	echo '</div>';
}
add_action( 'wp_footer', 'chuquipiondo_music_mini_player', 15 );

/**
 * Render the music download button (only if allowed globally + per song).
 *
 * @param int $post_id Music post ID.
 */
function chuquipiondo_music_download_button( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( ! chuquipiondo_is_enabled( 'music_downloads_global' ) ) {
		return;
	}
	if ( '1' !== get_post_meta( $post_id, '_music_allow_download', true ) ) {
		return;
	}
	$download_url = get_post_meta( $post_id, '_music_download_url', true );
	if ( ! $download_url ) {
		$download_url = get_post_meta( $post_id, '_music_audio_url', true );
	}
	if ( ! $download_url ) {
		return;
	}

	echo '<a href="' . esc_url( $download_url ) . '" class="music-download btn" download>';
	echo esc_html__( 'Descargar', 'chuquipiondo' );
	echo '</a>';
}
