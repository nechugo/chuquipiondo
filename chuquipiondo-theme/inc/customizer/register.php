<?php
/**
 * Customizer registration.
 *
 * Organizes every panel / section / setting / control under
 * Apariencia > Personalizar. Sections are grouped to mirror
 * a professional theme like Astra.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all Customizer panels, sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function chuquipiondo_customize_register( $wp_customize ) {
	// Enable selective refresh for widgets.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	if ( $wp_customize->get_setting( 'custom_logo' ) ) {
		$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
	}

	// Bring in each section group (defined in sections.php).
	require_once __DIR__ . "/sections.php";
	chuquipiondo_register_global( $wp_customize );
	chuquipiondo_register_header( $wp_customize );
	chuquipiondo_register_hero( $wp_customize );
	chuquipiondo_register_home( $wp_customize );
	chuquipiondo_register_blog( $wp_customize );
	chuquipiondo_register_single( $wp_customize );
	chuquipiondo_register_page( $wp_customize );
	chuquipiondo_register_ads( $wp_customize );
	chuquipiondo_register_social( $wp_customize );
	chuquipiondo_register_whatsapp( $wp_customize );
	chuquipiondo_register_footer( $wp_customize );
	chuquipiondo_register_music( $wp_customize );
	chuquipiondo_register_custom_code( $wp_customize );
}
add_action( 'customize_register', 'chuquipiondo_customize_register' );

/* ===================================================================== *
 * Helper: register a standard section.
 * ===================================================================== */

/**
 * Register a Customizer section if it does not exist.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 * @param string               $id          Section id.
 * @param array                $args        Section args.
 */
function chuquipiondo_add_section( $wp_customize, $id, $args ) {
	if ( ! $wp_customize->get_section( $id ) ) {
		$args = wp_parse_args( $args, array(
			'priority' => 30,
		) );
		$wp_customize->add_section( $id, $args );
	}
}

/**
 * Register a setting + control pair.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 * @param string               $id           Setting id (must exist in defaults).
 * @param array                $args         Setting + control args merged.
 */
function chuquipiondo_add_setting_control( $wp_customize, $id, $args ) {
	$defaults  = chuquipiondo_customizer_defaults();
	$default   = isset( $defaults[ $id ] ) ? $defaults[ $id ] : ( isset( $args['default'] ) ? $args['default'] : '' );

	$setting_args = wp_parse_args( $args, array(
		'default'           => $default,
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_setting( $id, array(
		'default'           => $setting_args['default'],
		'transport'         => $setting_args['transport'],
		'sanitize_callback' => $setting_args['sanitize_callback'],
	) );

	$control_args = wp_parse_args( $args, array(
		'label'    => '',
		'section'  => 'chuquipiondo_global',
		'type'     => 'text',
		'priority' => 10,
	) );

	// Pick the right control type.
	if ( 'checkbox' === $control_args['type'] ) {
		$wp_customize->add_control( $id, array(
			'label'       => $control_args['label'],
			'section'     => $control_args['section'],
			'type'        => 'checkbox',
			'description' => isset( $control_args['description'] ) ? $control_args['description'] : '',
			'priority'    => $control_args['priority'],
		) );
	} elseif ( 'select' === $control_args['type'] && isset( $control_args['choices'] ) ) {
		$wp_customize->add_control( $id, array(
			'label'       => $control_args['label'],
			'section'     => $control_args['section'],
			'type'        => 'select',
			'choices'     => $control_args['choices'],
			'description' => isset( $control_args['description'] ) ? $control_args['description'] : '',
			'priority'    => $control_args['priority'],
		) );
	} elseif ( 'radio' === $control_args['type'] && isset( $control_args['choices'] ) ) {
		$wp_customize->add_control( $id, array(
			'label'       => $control_args['label'],
			'section'     => $control_args['section'],
			'type'        => 'radio',
			'choices'     => $control_args['choices'],
			'description' => isset( $control_args['description'] ) ? $control_args['description'] : '',
			'priority'    => $control_args['priority'],
		) );
	} elseif ( 'textarea' === $control_args['type'] ) {
		$wp_customize->add_control( $id, array(
			'label'       => $control_args['label'],
			'section'     => $control_args['section'],
			'type'        => 'textarea',
			'description' => isset( $control_args['description'] ) ? $control_args['description'] : '',
			'priority'    => $control_args['priority'],
		) );
	} elseif ( 'range' === $control_args['type'] ) {
		$wp_customize->add_control( $id, array(
			'label'       => $control_args['label'],
			'section'     => $control_args['section'],
			'type'        => 'range',
			'input_attrs' => isset( $control_args['input_attrs'] ) ? $control_args['input_attrs'] : array(),
			'description' => isset( $control_args['description'] ) ? $control_args['description'] : '',
			'priority'    => $control_args['priority'],
		) );
	} elseif ( 'color' === $control_args['type'] ) {
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array(
			'label'       => $control_args['label'],
			'section'     => $control_args['section'],
			'description' => isset( $control_args['description'] ) ? $control_args['description'] : '',
			'priority'    => $control_args['priority'],
		) ) );
	} else {
		$wp_customize->add_control( $id, array(
			'label'       => $control_args['label'],
			'section'     => $control_args['section'],
			'type'        => 'text',
			'description' => isset( $control_args['description'] ) ? $control_args['description'] : '',
			'priority'    => $control_args['priority'],
		) );
	}
}

/* ===================================================================== *
 * GLOBAL section: presets, colors, typography, container, buttons.
 * ===================================================================== */

function chuquipiondo_register_global( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_global', array(
		'title'    => __( 'CHUQUIPIONDO: Global', 'chuquipiondo' ),
		'priority' => 28,
	) );

	// Presets radio.
	$preset_choices = array();
	foreach ( chuquipiondo_presets() as $key => $preset ) {
		$preset_choices[ $key ] = $preset['label'];
	}
	chuquipiondo_add_setting_control( $wp_customize, 'preset', array(
		'label'           => __( 'Preset de color', 'chuquipiondo' ),
		'section'         => 'chuquipiondo_global',
		'type'            => 'radio',
		'choices'         => $preset_choices,
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'description'     => __( 'Aplica un paquete de colores predefinido. Sobrescribe los colores actuales.', 'chuquipiondo' ),
		'priority'        => 5,
	) );

	// Colors.
	$colors = array(
		'color_navy'       => __( 'Azul marino', 'chuquipiondo' ),
		'color_navy_dark'  => __( 'Azul marino oscuro', 'chuquipiondo' ),
		'color_sky'        => __( 'Celeste brillante (acento)', 'chuquipiondo' ),
		'color_sky_soft'   => __( 'Celeste suave', 'chuquipiondo' ),
		'color_background' => __( 'Fondo', 'chuquipiondo' ),
		'color_text'       => __( 'Texto', 'chuquipiondo' ),
		'color_muted'      => __( 'Texto suave', 'chuquipiondo' ),
		'color_accent'     => __( 'Acento', 'chuquipiondo' ),
		'button_bg'        => __( 'Boton: fondo', 'chuquipiondo' ),
		'button_text'      => __( 'Boton: texto', 'chuquipiondo' ),
		'button_hover_bg'  => __( 'Boton hover: fondo', 'chuquipiondo' ),
		'button_hover_text'=> __( 'Boton hover: texto', 'chuquipiondo' ),
	);
	$i = 10;
	foreach ( $colors as $key => $label ) {
		chuquipiondo_add_setting_control( $wp_customize, $key, array(
			'label'             => $label,
			'section'           => 'chuquipiondo_global',
			'type'              => 'color',
			'sanitize_callback' => 'chuquipiondo_sanitize_hex_color',
			'priority'          => $i++,
		) );
	}

	// Typography.
	chuquipiondo_add_setting_control( $wp_customize, 'font_body', array(
		'label'             => __( 'Fuente del cuerpo', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'select',
		'choices'           => chuquipiondo_font_choices(),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 30,
		'description'       => __( 'Elige la tipografia del cuerpo del texto.', 'chuquipiondo' ),
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'font_heading', array(
		'label'             => __( 'Fuente de titulares', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'select',
		'choices'           => chuquipiondo_font_choices(),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 31,
		'description'       => __( 'Elige la tipografia de los titulares y encabezados.', 'chuquipiondo' ),
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'font_size_base', array(
		'label'             => __( 'Tamano base (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 13, 'max' => 22, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 32,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'font_weight_heading', array(
		'label'             => __( 'Peso titulares', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'select',
		'choices'           => array( '400' => '400', '500' => '500', '600' => '600', '700' => '700', '800' => '800' ),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 33,
	) );

	// Container & reading widths.
	chuquipiondo_add_setting_control( $wp_customize, 'container_width', array(
		'label'             => __( 'Ancho del contenedor (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 960, 'max' => 1440, 'step' => 10 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 40,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'reading_width', array(
		'label'             => __( 'Ancho de lectura del articulo (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 640, 'max' => 1000, 'step' => 10 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 41,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'sidebar_width', array(
		'label'             => __( 'Ancho de la barra lateral (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 280, 'max' => 420, 'step' => 10 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 42,
	) );

	// Button radius.
	chuquipiondo_add_setting_control( $wp_customize, 'button_radius', array(
		'label'             => __( 'Radio de botones (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 0, 'max' => 40, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 50,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'content_radius', array(
		'label'             => __( 'Radio de tarjetas (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 0, 'max' => 40, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 51,
	) );

	// Listen for preset changes and write the color mods.
	if ( is_admin() && isset( $_POST['customized'] ) && doing_action( 'customize_save_after' ) ) {
		// Preset application handled on save via the customize_save_after hook below.
	}
}

/**
 * Apply the chosen preset's color mods on save, unless the user has
 * just changed a color directly (we only run when 'preset' changes).
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function chuquipiondo_maybe_apply_preset( $wp_customize ) {
	$setting = $wp_customize->get_setting( 'preset' );
	if ( $setting && $setting->post_value() ) {
		$chosen = $setting->post_value();
		// Only apply if different from stored value.
		$current = get_theme_mod( 'preset' );
		if ( $chosen !== $current ) {
			chuquipiondo_apply_preset( $chosen );
		}
	}
}
add_action( 'customize_save_after', 'chuquipiondo_maybe_apply_preset' );
