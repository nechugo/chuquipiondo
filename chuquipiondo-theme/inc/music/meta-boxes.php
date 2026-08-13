<?php
/**
 * Music meta boxes: audio, video, lyrics, platforms, downloads.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the music meta boxes.
 */
function chuquipiondo_music_meta_boxes() {
	add_meta_box(
		'chuquipiondo_music_details',
		__( 'Detalles de la cancion', 'chuquipiondo' ),
		'chuquipiondo_music_meta_box_render',
		'musica',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'chuquipiondo_music_meta_boxes' );

/**
 * Render the music meta box.
 *
 * @param WP_Post $post Post object.
 */
function chuquipiondo_music_meta_box_render( $post ) {
	wp_nonce_field( 'chuquipiondo_music_meta', 'chuquipiondo_music_meta_nonce' );

	$fields = chuquipiondo_music_meta_fields();

	echo '<table class="form-table chuquipiondo-music-meta">';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, '_' . $key, true );
		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th>';
		echo '<td>';
		if ( 'textarea' === $field['type'] ) {
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="' . (int) $field['rows'] . '" class="large-text">' . esc_textarea( $value ) . '</textarea>';
		} elseif ( 'checkbox' === $field['type'] ) {
			echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="1" ' . checked( $value, '1', false ) . '>';
		} else {
			echo '<input type="' . esc_attr( $field['type'] ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="large-text">';
		}
		if ( ! empty( $field['description'] ) ) {
			echo '<p class="description">' . esc_html( $field['description'] ) . '</p>';
		}
		echo '</td></tr>';
	}
	echo '</table>';
}

/**
 * Field definitions for the music meta box.
 *
 * @return array
 */
function chuquipiondo_music_meta_fields() {
	return array(
		'music_artist'      => array( 'label' => __( 'Artista', 'chuquipiondo' ), 'type' => 'text' ),
		'music_audio_url'    => array( 'label' => __( 'Audio (URL o archivo)', 'chuquipiondo' ), 'type' => 'url', 'description' => __( 'URL directa al archivo de audio (mp3, ogg, m4a).', 'chuquipiondo' ) ),
		'music_video_url'    => array( 'label' => __( 'Video (YouTube)', 'chuquipiondo' ), 'type' => 'url' ),
		'music_lyrics'       => array( 'label' => __( 'Letra', 'chuquipiondo' ), 'type' => 'textarea', 'rows' => 8 ),
		'music_spotify'      => array( 'label' => __( 'Spotify URL', 'chuquipiondo' ), 'type' => 'url' ),
		'music_apple_music'  => array( 'label' => __( 'Apple Music URL', 'chuquipiondo' ), 'type' => 'url' ),
		'music_youtube'      => array( 'label' => __( 'YouTube URL', 'chuquipiondo' ), 'type' => 'url' ),
		'music_allow_download' => array( 'label' => __( 'Permitir descarga de esta cancion', 'chuquipiondo' ), 'type' => 'checkbox', 'description' => __( 'Solo si tienes derechos para distribuirla legalmente.', 'chuquipiondo' ) ),
		'music_download_url' => array( 'label' => __( 'Archivo de descarga (URL)', 'chuquipiondo' ), 'type' => 'url' ),
	);
}

/**
 * Save the music meta box data.
 *
 * @param int $post_id Post ID.
 */
function chuquipiondo_music_meta_box_save( $post_id ) {
	if ( ! isset( $_POST['chuquipiondo_music_meta_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( $_POST['chuquipiondo_music_meta_nonce'] ), 'chuquipiondo_music_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( ! isset( $_POST['post_type'] ) || 'musica' !== $_POST['post_type'] ) {
		return;
	}

	$fields = chuquipiondo_music_meta_fields();
	foreach ( $fields as $key => $field ) {
		$input_key = $key;
		if ( ! isset( $_POST[ $input_key ] ) ) {
			if ( 'checkbox' === $field['type'] ) {
				update_post_meta( $post_id, '_' . $key, '0' );
			}
			continue;
		}
		$raw = wp_unslash( $_POST[ $input_key ] );
		if ( 'checkbox' === $field['type'] ) {
			$value = '1';
		} elseif ( 'url' === $field['type'] ) {
			$value = esc_url_raw( $raw );
		} elseif ( 'textarea' === $field['type'] ) {
			$value = sanitize_textarea_field( $raw );
		} else {
			$value = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, '_' . $key, $value );
	}
}
add_action( 'save_post_musica', 'chuquipiondo_music_meta_box_save' );
