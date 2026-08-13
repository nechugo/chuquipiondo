<?php
/**
 * Icon registry for buttons and UI elements.
 *
 * Each icon is an inline SVG path. Icons can be placed before or
 * after the button text via the Customizer.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the available button icons.
 *
 * @return array key => label
 */
function chuquipiondo_button_icons() {
	$icons = array(
		'none'           => __( 'Sin icono', 'chuquipiondo' ),
		'arrow-right'    => __( 'Flecha derecha', 'chuquipiondo' ),
		'arrow-left'     => __( 'Flecha izquierda', 'chuquipiondo' ),
		'check'          => __( 'Check (confirmar)', 'chuquipiondo' ),
		'download'       => __( 'Descarga', 'chuquipiondo' ),
		'play'           => __( 'Play', 'chuquipiondo' ),
		'star'           => __( 'Estrella', 'chuquipiondo' ),
		'heart'          => __( 'Corazon', 'chuquipiondo' ),
		'external'       => __( 'Enlace externo', 'chuquipiondo' ),
		'email'          => __( 'Email', 'chuquipiondo' ),
		'search'         => __( 'Buscar', 'chuquipiondo' ),
		'user'           => __( 'Usuario', 'chuquipiondo' ),
		'calendar'       => __( 'Calendario', 'chuquipiondo' ),
		'chevron-right'  => __( 'Chevron derecho', 'chuquipiondo' ),
		'chevron-left'   => __( 'Chevron izquierdo', 'chuquipiondo' ),
		'plus'           => __( 'Mas (+)', 'chuquipiondo' ),
		'minus'          => __( 'Menos (-)', 'chuquipiondo' ),
		'close'          => __( 'Cerrar (X)', 'chuquipiondo' ),
		'menu'           => __( 'Menu', 'chuquipiondo' ),
		'whatsapp'       => __( 'WhatsApp', 'chuquipiondo' ),
		'share'          => __( 'Compartir', 'chuquipiondo' ),
	);

	/**
	 * Filters the available button icons.
	 *
	 * @param array $icons Icons list.
	 */
	return apply_filters( 'chuquipiondo_button_icons', $icons );
}

/**
 * Get the SVG markup for a button icon.
 *
 * @param string $icon Icon key.
 * @return string SVG markup (empty if not found or 'none').
 */
function chuquipiondo_button_icon_svg( $icon ) {
	$svgs = array(
		'arrow-right'   => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M4 11h12.17l-5.59-5.59L12 4l8 8-8 8-1.41-1.41L16.17 13H4z"/></svg>',
		'arrow-left'    => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20z"/></svg>',
		'check'         => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M9 16.17 4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
		'download'      => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>',
		'play'          => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M8 5v14l11-7z"/></svg>',
		'star'          => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
		'heart'         => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>',
		'external'      => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M19 19H5V5h7V3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>',
		'email'         => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg>',
		'search'        => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 1 0-.7.7l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0A4.5 4.5 0 1 1 14 9.5 4.5 4.5 0 0 1 9.5 14z"/></svg>',
		'user'          => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z"/></svg>',
		'calendar'      => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M19 3h-1V1h-2v2H8V1H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zm0 16H5V8h14z"/></svg>',
		'chevron-right' => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M8.59 16.59 13.17 12 8.59 7.41 10 6l6 6-6 6z"/></svg>',
		'chevron-left'  => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>',
		'plus'          => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6z"/></svg>',
		'minus'         => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M19 13H5v-2h14z"/></svg>',
		'close'         => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>',
		'menu'          => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M3 18h18v-2H3zm0-5h18v-2H3zm0-7v2h18V6z"/></svg>',
		'whatsapp'      => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M.06 24l1.68-6.14a11.86 11.86 0 0 1-1.6-5.95C.14 5.33 5.47 0 12.06 0a11.82 11.82 0 0 1 8.41 3.49 11.82 11.82 0 0 1 3.48 8.42c0 6.59-5.33 11.95-11.95 11.95a11.94 11.94 0 0 1-5.72-1.46L.06 24Zm6.6-3.8c1.68.99 3.28 1.58 5.39 1.58 5.47 0 9.93-4.45 9.93-9.92a9.86 9.86 0 0 0-2.91-7.02 9.86 9.86 0 0 0-7.01-2.92c-5.48 0-9.93 4.46-9.93 9.93 0 2.23.65 3.89 1.74 5.63l-1 3.65 3.79-.93Z"/></svg>',
		'share'         => '<svg class="btn-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81a3 3 0 1 0-3-3c0 .24.04.47.09.7L8.04 9.81A3 3 0 1 0 6 15a3 3 0 0 0 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65a2.92 2.92 0 1 0 2.92-2.92Z"/></svg>',
	);

	if ( 'none' === $icon || ! isset( $svgs[ $icon ] ) ) {
		return '';
	}

	/**
	 * Filters the button icon SVG.
	 *
	 * @param string $svg  SVG markup.
	 * @param string $icon Icon key.
	 */
	return apply_filters( 'chuquipiondo_button_icon_svg', $svgs[ $icon ], $icon );
}

/**
 * Render a button with the configured icon.
 *
 * @param string $text   Button text.
 * @param string $url    Button URL.
 * @param array  $args    Optional overrides (class, target, rel, icon, icon_position).
 */
function chuquipiondo_button( $text, $url = '#', $args = array() ) {
	$args = wp_parse_args( $args, array(
		'class'         => 'btn',
		'target'        => '',
		'rel'           => '',
		'icon'          => '',
		'icon_position' => '',
	) );

	$icon_enable    = $args['icon'] ? true : chuquipiondo_is_enabled( 'button_icon_enable' );
	$icon_key       = $args['icon'] ? $args['icon'] : chuquipiondo_get_option( 'button_icon' );
	$icon_position  = $args['icon_position'] ? $args['icon_position'] : chuquipiondo_get_option( 'button_icon_position' );

	$icon_html = '';
	if ( $icon_enable && 'none' !== $icon_key ) {
		$icon_html = chuquipiondo_button_icon_svg( $icon_key );
	}

	$attr_target = $args['target'] ? ' target="' . esc_attr( $args['target'] ) . '"' : '';
	$attr_rel    = $args['rel'] ? ' rel="' . esc_attr( $args['rel'] ) . '"' : '';

	echo '<a href="' . esc_url( $url ) . '" class="' . esc_attr( $args['class'] ) . '"' . $attr_target . $attr_rel . '>';
	if ( $icon_html && 'before' === $icon_position ) {
		echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
	}
	echo '<span class="btn-text">' . esc_html( $text ) . '</span>';
	if ( $icon_html && 'after' === $icon_position ) {
		echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG.
	}
	echo '</a>';
}
