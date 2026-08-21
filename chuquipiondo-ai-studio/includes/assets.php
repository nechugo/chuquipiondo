<?php
/**
 * Asset enqueuing for CHUQUIPIONDO AI Studio.
 *
 * CSS/JS are only loaded on our admin screens to avoid polluting the
 * rest of wp-admin and to stay conflict-free with any theme/plugin.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue admin assets on our screens only.
 *
 * @param string $hook Current admin page hook.
 * @return void
 */
function chuquipiondo_ai_admin_assets( $hook ) {
	$screen = get_current_screen();
	$is_ai_page = ( false !== strpos( (string) $hook, 'chuquipiondo-ai' ) );

	// Always load the small meta-box stylesheet where the meta box appears.
	if ( $screen && in_array( $screen->base, array( 'post', 'page' ), true ) ) {
		wp_enqueue_style(
			'chuquipiondo-ai-mb',
			CHUQUIPIONDO_AI_URL . 'assets/admin-metabox.css',
			array(),
			chuquipiondo_ai_asset_version( 'assets/admin-metabox.css' )
		);
	}

	if ( ! $is_ai_page ) {
		return;
	}

	wp_enqueue_style(
		'chuquipiondo-ai-admin',
		CHUQUIPIONDO_AI_URL . 'assets/admin.css',
		array(),
		chuquipiondo_ai_asset_version( 'assets/admin.css' )
	);
	wp_enqueue_script(
		'chuquipiondo-ai-admin',
		CHUQUIPIONDO_AI_URL . 'assets/admin.js',
		array( 'wp-api-fetch', 'wp-element' ),
		chuquipiondo_ai_asset_version( 'assets/admin.js' ),
		true
	);
	wp_localize_script(
		'chuquipiondo-ai-admin',
		'CAI',
		array(
			'rest'  => esc_url_raw( rest_url( 'chuquipiondo-ai/v1' ) ),
			// X-WP-Nonce must use WordPress core's canonical REST action.
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'i18n'  => array(
				'loading'     => __( 'Cargando...', 'chuquipiondo-ai' ),
				'saved'       => __( 'Guardado.', 'chuquipiondo-ai' ),
				'error'       => __( 'Error: ', 'chuquipiondo-ai' ),
				'analyzing'   => __( 'Analizando imagenes...', 'chuquipiondo-ai' ),
				'generating'  => __( 'Generando con IA...', 'chuquipiondo-ai' ),
				'noImage'     => __( 'Sin imagen destacada.', 'chuquipiondo-ai' ),
				'firstImg'    => __( 'Primera imagen', 'chuquipiondo-ai' ),
				'extra'       => __( 'Imagenes adicionales en el articulo', 'chuquipiondo-ai' ),
				'recommended' => __( 'Espacios recomendados para mas imagenes', 'chuquipiondo-ai' ),
				'needsResize' => __( 'Imagenes que no respetan 500x900', 'chuquipiondo-ai' ),
			),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'chuquipiondo_ai_admin_assets' );
