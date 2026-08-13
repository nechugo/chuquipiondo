<?php
/**
 * Meta box: per-post / per-page sidebar layout override.
 *
 * Allows each post or page to choose its sidebar position
 * independently of the global Customizer setting (Astra-style).
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the layout meta box on posts and pages.
 */
function chuquipiondo_layout_meta_box() {
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'chuquipiondo_layout',
			__( 'CHUQUIPIONDO: Layout', 'chuquipiondo' ),
			'chuquipiondo_layout_meta_box_render',
			$screen,
			'side',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'chuquipiondo_layout_meta_box' );

/**
 * Render the layout meta box.
 *
 * @param WP_Post $post Post object.
 */
function chuquipiondo_layout_meta_box_render( $post ) {
	wp_nonce_field( 'chuquipiondo_layout_meta', 'chuquipiondo_layout_meta_nonce' );

	$current = get_post_meta( $post->ID, '_chuquipiondo_sidebar', true );
	if ( ! $current ) {
		$current = 'default';
	}

	$options = array(
		'default' => __( 'Default (del tema)', 'chuquipiondo' ),
		'none'    => __( 'Sin sidebar (ancho completo)', 'chuquipiondo' ),
		'right'   => __( 'Sidebar a la derecha', 'chuquipiondo' ),
		'left'    => __( 'Sidebar a la izquierda', 'chuquipiondo' ),
	);

	echo '<p class="description">' . esc_html__( 'Sobrescribe el layout del sidebar para esta publicacion. "Default" usa la configuracion global del Customizer.', 'chuquipiondo' ) . '</p>';

	echo '<select name="chuquipiondo_sidebar_override" id="chuquipiondo_sidebar_override" style="width:100%;margin-top:8px;">';
	foreach ( $options as $value => $label ) {
		echo '<option value="' . esc_attr( $value ) . '" ' . selected( $current, $value, false ) . '>' . esc_html( $label ) . '</option>';
	}
	echo '</select>';

	// Also allow overriding the content width for this post.
	$content_width_override = get_post_meta( $post->ID, '_chuquipiondo_content_width', true );
	echo '<p style="margin-top:12px;">';
	echo '<label for="chuquipiondo_content_width"><strong>' . esc_html__( 'Ancho de lectura (px)', 'chuquipiondo' ) . '</strong></label>';
	echo '<input type="number" min="600" max="1200" step="10" name="chuquipiondo_content_width" id="chuquipiondo_content_width" value="' . esc_attr( $content_width_override ) . '" style="width:100%;" placeholder="' . esc_attr__( 'Usar default del tema', 'chuquipiondo' ) . '">';
	echo '<p class="description">' . esc_html__( 'Dejar vacio para usar el ancho del Customizer.', 'chuquipiondo' ) . '</p>';
	echo '</p>';
}

/**
 * Save the layout meta box data.
 *
 * @param int $post_id Post ID.
 */
function chuquipiondo_layout_meta_box_save( $post_id ) {
	if ( ! isset( $_POST['chuquipiondo_layout_meta_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( $_POST['chuquipiondo_layout_meta_nonce'] ), 'chuquipiondo_layout_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save sidebar override.
	if ( isset( $_POST['chuquipiondo_sidebar_override'] ) ) {
		$value = sanitize_key( $_POST['chuquipiondo_sidebar_override'] );
		$allowed = array( 'default', 'none', 'right', 'left' );
		if ( in_array( $value, $allowed, true ) ) {
			update_post_meta( $post_id, '_chuquipiondo_sidebar', $value );
		}
	}

	// Save content width override.
	if ( isset( $_POST['chuquipiondo_content_width'] ) ) {
		$width = (int) $_POST['chuquipiondo_content_width'];
		if ( $width >= 600 && $width <= 1200 ) {
			update_post_meta( $post_id, '_chuquipiondo_content_width', $width );
		} else {
			delete_post_meta( $post_id, '_chuquipiondo_content_width' );
		}
	}
}
add_action( 'save_post', 'chuquipiondo_layout_meta_box_save' );

/**
 * Apply the per-post content width override on single views.
 */
function chuquipiondo_apply_content_width_override() {
	if ( ! is_singular() ) {
		return;
	}
	$override = get_post_meta( get_the_ID(), '_chuquipiondo_content_width', true );
	if ( $override ) {
		echo '<style id="chuquipiondo-content-width-override">.single-article__inner, .single-page .entry-content { max-width: ' . (int) $override . 'px !important; margin-inline: auto; }</style>' . "\n";
	}
}
add_action( 'wp_head', 'chuquipiondo_apply_content_width_override', 25 );
