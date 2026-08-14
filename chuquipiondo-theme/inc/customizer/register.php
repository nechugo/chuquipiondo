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
	chuquipiondo_register_buttons( $wp_customize );
	chuquipiondo_register_preheader( $wp_customize );
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

	// Content radius (buttons moved to their own section).
	chuquipiondo_add_setting_control( $wp_customize, 'content_radius', array(
		'label'             => __( 'Radio de tarjetas (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_global',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 0, 'max' => 40, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 50,
	) );

	// Listen for preset changes and write the color mods.
	if ( is_admin() && isset( $_POST['customized'] ) && doing_action( 'customize_save_after' ) ) {
		// Preset application handled on save via the customize_save_after hook below.
	}
}

/* ===================================================================== *
 * BUTTONS section: complete button customization.
 * ===================================================================== */

function chuquipiondo_register_buttons( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_buttons', array(
		'title'    => __( 'CHUQUIPIONDO: Botones', 'chuquipiondo' ),
		'priority' => 28,
	) );

	// ===== Dimensiones =====
	chuquipiondo_add_setting_control( $wp_customize, 'button_width_mode', array(
		'label'             => __( 'Modo de ancho', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'select',
		'choices'           => array(
			'auto'    => __( 'Automatico (segun texto)', 'chuquipiondo' ),
			'fixed'   => __( 'Fijo (px)', 'chuquipiondo' ),
			'full'    => __( '100% (ancho completo)', 'chuquipiondo' ),
			'percent' => __( 'Porcentual (1-100%)', 'chuquipiondo' ),
		),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 5,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_width', array(
		'label'             => __( 'Ancho fijo (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 20, 'max' => 500, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'description'       => __( 'Solo aplica en modo fijo. Default: 50px.', 'chuquipiondo' ),
		'priority'          => 6,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_width_percent', array(
		'label'             => __( 'Ancho porcentual (%)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 1, 'max' => 100, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'description'       => __( 'Solo aplica en modo porcentual. 1% a 100%.', 'chuquipiondo' ),
		'priority'          => 7,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_height', array(
		'label'             => __( 'Altura (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 20, 'max' => 120, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'description'       => __( 'Altura del boton. Default: 25px (ampliable).', 'chuquipiondo' ),
		'priority'          => 8,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_padding_h', array(
		'label'             => __( 'Padding horizontal (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 0, 'max' => 60, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'description'       => __( 'Espaciado interno horizontal (modo auto). Default: 20px.', 'chuquipiondo' ),
		'priority'          => 9,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_padding_v', array(
		'label'             => __( 'Padding vertical (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 0, 'max' => 40, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 10,
	) );

	// ===== Forma y radio =====
	chuquipiondo_add_setting_control( $wp_customize, 'button_shape', array(
		'label'             => __( 'Forma del boton', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'select',
		'choices'           => array(
			'square'  => __( 'Cuadrado (sin radio)', 'chuquipiondo' ),
			'rounded' => __( 'Redondeado (radio custom)', 'chuquipiondo' ),
			'pill'    => __( 'Pildora (radio total)', 'chuquipiondo' ),
		),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'description'       => __( 'Cuadrado = 0px, Redondeado = 30px (default), Pildora = total.', 'chuquipiondo' ),
		'priority'          => 15,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_radius', array(
		'label'             => __( 'Radio (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 0, 'max' => 60, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'description'       => __( 'Solo aplica en modo redondeado. Default: 30px.', 'chuquipiondo' ),
		'priority'          => 16,
	) );

	// ===== Tipografia =====
	chuquipiondo_add_setting_control( $wp_customize, 'button_font_size', array(
		'label'             => __( 'Tamano del texto (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 8, 'max' => 32, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'description'       => __( 'Tamano del texto dentro del boton. Default: 12px.', 'chuquipiondo' ),
		'priority'          => 20,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_font_weight', array(
		'label'             => __( 'Peso de la fuente', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'select',
		'choices'           => array( '400' => '400', '500' => '500', '600' => '600', '700' => '700', '800' => '800' ),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 21,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_text_transform', array(
		'label'             => __( 'Transformacion del texto', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'select',
		'choices'           => array( 'none' => __( 'Normal', 'chuquipiondo' ), 'uppercase' => __( 'MAYUSCULAS', 'chuquipiondo' ), 'lowercase' => __( 'minusculas', 'chuquipiondo' ), 'capitalize' => __( 'Capitalizado', 'chuquipiondo' ) ),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 22,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_letter_spacing', array(
		'label'             => __( 'Espaciado entre letras (em)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_buttons',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 0, 'max' => 0.3, 'step' => 0.01 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'description'       => __( '0 = sin espaciado extra. 0.05 = ligero.', 'chuquipiondo' ),
		'priority'          => 23,
	) );

	// ===== Colores =====
	chuquipiondo_add_setting_control( $wp_customize, 'button_bg', array( 'section' => 'chuquipiondo_buttons', 'label' => __( 'Color de fondo', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 30 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_text', array( 'section' => 'chuquipiondo_buttons', 'label' => __( 'Color del texto', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 31 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_hover_bg', array( 'section' => 'chuquipiondo_buttons', 'label' => __( 'Color de fondo (hover)', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 32 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_hover_text', array( 'section' => 'chuquipiondo_buttons', 'label' => __( 'Color del texto (hover)', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 33 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_border_width', array( 'section' => 'chuquipiondo_buttons', 'label' => __( 'Ancho del borde (px)', 'chuquipiondo' ), 'type' => 'range', 'input_attrs' => array( 'min' => 0, 'max' => 10, 'step' => 1 ), 'sanitize_callback' => 'chuquipiondo_sanitize_range', 'priority' => 34 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_border_color', array( 'section' => 'chuquipiondo_buttons', 'label' => __( 'Color del borde', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 35 ) );

	// ===== Sombra =====
	chuquipiondo_add_setting_control( $wp_customize, 'button_shadow_enable', array( 'section' => 'chuquipiondo_buttons', 'label' => __( 'Activar sombra', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 40 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_shadow_color', array( 'section' => 'chuquipiondo_buttons', 'label' => __( 'Color de sombra', 'chuquipiondo' ), 'type' => 'text', 'sanitize_callback' => 'chuquipiondo_sanitize_text', 'description' => 'rgba(0,0,0,0.2)', 'priority' => 41 ) );

	// ===== Iconos =====
	chuquipiondo_add_setting_control( $wp_customize, 'button_icon_enable', array( 'section' => 'chuquipiondo_buttons', 'label' => __( 'Activar icono', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'description' => __( 'Anade un icono dentro de los botones.', 'chuquipiondo' ), 'priority' => 50 ) );
	$wp_customize->add_setting( 'button_icon', array( 'default' => chuquipiondo_default( 'button_icon' ), 'transport' => 'refresh', 'sanitize_callback' => 'chuquipiondo_sanitize_select' ) );
	$wp_customize->add_control( 'button_icon', array(
		'label'    => __( 'Icono', 'chuquipiondo' ),
		'section'  => 'chuquipiondo_buttons',
		'type'     => 'select',
		'choices'  => chuquipiondo_button_icons(),
		'priority' => 51,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_icon_position', array(
		'section'           => 'chuquipiondo_buttons',
		'label'             => __( 'Posicion del icono', 'chuquipiondo' ),
		'type'              => 'select',
		'choices'           => array( 'before' => __( 'Antes del texto', 'chuquipiondo' ), 'after' => __( 'Despues del texto', 'chuquipiondo' ) ),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 52,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'button_icon_size', array(
		'section'           => 'chuquipiondo_buttons',
		'label'             => __( 'Tamano del icono (px)', 'chuquipiondo' ),
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 8, 'max' => 40, 'step' => 1 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 53,
	) );
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
