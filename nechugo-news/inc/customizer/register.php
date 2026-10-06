<?php
/**
 * Registro del personalizador: paneles, secciones y controles.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra todo en el Personalizador.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function nechugo_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// ============================== PANEL PRINCIPAL ==============================
	$wp_customize->add_panel(
		'nechugo_options',
		array(
			'title'       => __( 'nechugo News: Opciones', 'nechugo-news' ),
			'description' => __( 'Encabezados, pies de pagina, colores, tipografia, blog y anuncios.', 'nechugo-news' ),
			'priority'    => 10,
		)
	);

	// ============================== COLORES ==============================
	$wp_customize->add_section(
		'nechugo_colors',
		array(
			'title'    => __( 'Colores', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 20,
		)
	);

	$colors = array(
		'primary_color'    => __( 'Color primario', 'nechugo-news' ),
		'secondary_color'  => __( 'Color secundario', 'nechugo-news' ),
		'accent_color'     => __( 'Color de acento', 'nechugo-news' ),
		'background_color' => __( 'Fondo del sitio', 'nechugo-news' ),
		'text_color'       => __( 'Color de texto', 'nechugo-news' ),
		'header_bg_color'  => __( 'Fondo del encabezado', 'nechugo-news' ),
		'header_text_color' => __( 'Texto del encabezado', 'nechugo-news' ),
		'footer_bg_color'  => __( 'Fondo del pie de pagina', 'nechugo-news' ),
		'footer_text_color' => __( 'Texto del pie de pagina', 'nechugo-news' ),
	);
	foreach ( $colors as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'nechugo_colors',
				)
			)
		);
	}

	// Presets multicolor.
	$wp_customize->add_setting(
		'color_preset',
		array(
			'default'           => 'blue',
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'color_preset',
		array(
			'label'       => __( 'Preset multicolor', 'nechugo-news' ),
			'description' => __( 'Aplica una combinacion rapida de colores. Los colores individuales siguen editables.', 'nechugo-news' ),
			'section'     => 'nechugo_colors',
			'type'        => 'select',
			'choices'     => array(
				'blue'    => __( 'Azul', 'nechugo-news' ),
				'red'     => __( 'Rojo', 'nechugo-news' ),
				'green'   => __( 'Verde', 'nechugo-news' ),
				'orange'  => __( 'Naranja', 'nechugo-news' ),
				'purple'  => __( 'Morado', 'nechugo-news' ),
				'teal'    => __( 'Turquesa', 'nechugo-news' ),
				'dark'    => __( 'Oscuro', 'nechugo-news' ),
				'custom'  => __( 'Personalizado', 'nechugo-news' ),
			),
		)
	);

	// ============================== TIPOGRAFIA ==============================
	$wp_customize->add_section(
		'nechugo_typography',
		array(
			'title'    => __( 'Tipografia', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 30,
		)
	);

	$fonts = array(
		'system'        => __( 'Sistema (mas rapido)', 'nechugo-news' ),
		'sans-serif'    => 'Sans-serif',
		'google-sans'   => 'Google Sans',
		'roboto'        => 'Roboto',
		'roboto-condensed' => 'Roboto Condensed',
		'inter'         => 'Inter',
		'roboto'        => 'Roboto',
		'roboto-condensed' => 'Roboto Condensed',
		'open-sans'     => 'Open Sans',
		'lato'          => 'Lato',
		'montserrat'    => 'Montserrat',
		'oswald'        => 'Oswald',
		'merriweather'  => 'Merriweather',
		'playfair'      => 'Playfair Display',
	);

	$wp_customize->add_setting(
		'body_font_family',
		array(
			'default'           => 'system',
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'body_font_family',
		array(
			'label'   => __( 'Fuente del texto', 'nechugo-news' ),
			'section' => 'nechugo_typography',
			'type'    => 'select',
			'choices' => $fonts,
		)
	);

	$wp_customize->add_setting(
		'heading_font_family',
		array(
			'default'           => 'system',
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'heading_font_family',
		array(
			'label'   => __( 'Fuente de titulares', 'nechugo-news' ),
			'section' => 'nechugo_typography',
			'type'    => 'select',
			'choices' => $fonts,
		)
	);

	$sizes = array(
		'body_font_size'   => array( __( 'Tamano del texto en articulos (px)', 'nechugo-news' ), 10, 24 ),
		'h1_font_size'     => array( __( 'Tamano del H1 (px)', 'nechugo-news' ), 14, 48 ),
		'body_line_height' => array( __( 'Altura de linea del texto', 'nechugo-news' ), 130, 220 ),
	);
	foreach ( $sizes as $key => $data ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'nechugo_sanitize_number',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'       => $data[0],
				'section'     => 'nechugo_typography',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => $data[1],
					'max'  => $data[2],
					'step' => 'line_height' === $key ? 5 : 1,
				),
			)
		);
	}

	$wp_customize->add_setting(
		'h1_font_weight',
		array(
			'default'           => 900,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'h1_font_weight',
		array(
			'label'       => __( 'Peso del H1 (100-900)', 'nechugo-news' ),
			'section'     => 'nechugo_typography',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 100, 'max' => 900, 'step' => 100 ),
		)
	);

	// ============================== FONDO DE PAGINA ==============================
	$wp_customize->add_section(
		'nechugo_page_bg',
		array(
			'title'    => __( 'Fondo de pagina', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 25,
		)
	);

	$wp_customize->add_setting(
		'page_bg_type',
		array(
			'default'           => 'color',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'page_bg_type',
		array(
			'label'   => __( 'Tipo de fondo', 'nechugo-news' ),
			'section' => 'nechugo_page_bg',
			'type'    => 'radio',
			'choices' => array(
				'color'    => __( 'Color entero', 'nechugo-news' ),
				'gradient' => __( 'Degradado', 'nechugo-news' ),
				'image'    => __( 'Imagen con efectos', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'page_bg_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'page_bg_color',
			array(
				'label'   => __( 'Color entero', 'nechugo-news' ),
				'section' => 'nechugo_page_bg',
			)
		)
	);

	$wp_customize->add_setting(
		'page_bg_gradient_from',
		array(
			'default'           => '#0a1a3a',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'page_bg_gradient_from',
			array(
				'label'   => __( 'Degradado: color inicial', 'nechugo-news' ),
				'section' => 'nechugo_page_bg',
			)
		)
	);

	$wp_customize->add_setting(
		'page_bg_gradient_to',
		array(
			'default'           => '#123c6e',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'page_bg_gradient_to',
			array(
				'label'   => __( 'Degradado: color final', 'nechugo-news' ),
				'section' => 'nechugo_page_bg',
			)
		)
	);

	$wp_customize->add_setting(
		'page_bg_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'page_bg_image',
			array(
				'label'   => __( 'Imagen de fondo', 'nechugo-news' ),
				'section' => 'nechugo_page_bg',
			)
		)
	);

	$wp_customize->add_setting(
		'page_bg_overlay',
		array(
			'default'           => 'none',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'page_bg_overlay',
		array(
			'label'       => __( 'Efecto sobre la imagen', 'nechugo-news' ),
			'description' => __( 'Capa transparente sobre la imagen para legibilidad.', 'nechugo-news' ),
			'section'     => 'nechugo_page_bg',
			'type'        => 'select',
			'choices'     => array(
				'none'       => __( 'Sin efecto', 'nechugo-news' ),
				'dark'       => __( 'Negro transparente', 'nechugo-news' ),
				'night-blue' => __( 'Azul noche transparente', 'nechugo-news' ),
				'gradient'   => __( 'Degradado azul noche', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'page_bg_overlay_opacity',
		array(
			'default'           => 60,
			'sanitize_callback' => 'nechugo_sanitize_number',
		)
	);
	$wp_customize->add_control(
		'page_bg_overlay_opacity',
		array(
			'label'       => __( 'Opacidad del efecto (0-100)', 'nechugo-news' ),
			'section'     => 'nechugo_page_bg',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 5 ),
		)
	);

	$wp_customize->add_setting(
		'page_bg_fixed',
		array(
			'default'           => false,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'page_bg_fixed',
		array(
			'label'   => __( 'Imagen fija al hacer scroll (parallax)', 'nechugo-news' ),
			'section' => 'nechugo_page_bg',
			'type'    => 'checkbox',
		)
	);

	// ============================== MENU PRINCIPAL ==============================
	$wp_customize->add_section(
		'nechugo_menu',
		array(
			'title'    => __( 'Menu principal', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 45,
		)
	);

	$wp_customize->add_setting(
		'menu_align',
		array(
			'default'           => 'right',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'menu_align',
		array(
			'label'       => __( 'Alineacion del menu (dentro de su caja)', 'nechugo-news' ),
			'description' => __( 'Por defecto a la derecha, sin salirse de la caja del encabezado.', 'nechugo-news' ),
			'section'     => 'nechugo_menu',
			'type'        => 'radio',
			'choices'     => array(
				'right'  => __( 'Derecha (por defecto)', 'nechugo-news' ),
				'left'   => __( 'Izquierda', 'nechugo-news' ),
				'center' => __( 'Centro', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'menu_font_family',
		array(
			'default'           => 'roboto-condensed',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'menu_font_family',
		array(
			'label'   => __( 'Tipo de fuente del menu', 'nechugo-news' ),
			'section' => 'nechugo_menu',
			'type'    => 'select',
			'choices' => $fonts,
		)
	);

	$wp_customize->add_setting(
		'menu_uppercase',
		array(
			'default'           => true,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'menu_uppercase',
		array(
			'label'   => __( 'Texto en mayusculas', 'nechugo-news' ),
			'section' => 'nechugo_menu',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'menu_letter_spacing',
		array(
			'default'           => 2,
			'sanitize_callback' => 'nechugo_sanitize_number',
		)
	);
	$wp_customize->add_control(
		'menu_letter_spacing',
		array(
			'label'       => __( 'Espaciado entre letras (px)', 'nechugo-news' ),
			'section'     => 'nechugo_menu',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'max' => 6 ),
		)
	);

	// ============================== LOGO ==============================
	$wp_customize->add_section(
		'nechugo_logo',
		array(
			'title'    => __( 'Tamano del logo', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 35,
		)
	);

	$wp_customize->add_setting(
		'logo_width',
		array(
			'default'           => 220,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'logo_width',
		array(
			'label'       => __( 'Ancho del logo (px)', 'nechugo-news' ),
			'section'     => 'nechugo_logo',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 120, 'max' => 600, 'step' => 10 ),
		)
	);

	$wp_customize->add_setting(
		'logo_height',
		array(
			'default'           => 120,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'logo_height',
		array(
			'label'       => __( 'Alto del logo (px)', 'nechugo-news' ),
			'section'     => 'nechugo_logo',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 40, 'max' => 300, 'step' => 2 ),
		)
	);

	// Tipografia por contexto.
	$ctx_typo = array(
		'post_title_font_size'   => array( __( 'Tamano del titulo de entrada (px)', 'nechugo-news' ), 12, 40 ),
		'post_title_font_weight' => array( __( 'Peso del titulo de entrada', 'nechugo-news' ), 100, 900 ),
		'page_title_font_size'   => array( __( 'Tamano del titulo de pagina (px)', 'nechugo-news' ), 12, 40 ),
		'page_title_font_weight' => array( __( 'Peso del titulo de pagina', 'nechugo-news' ), 100, 900 ),
		'widget_title_font_size' => array( __( 'Tamano del titulo de widgets (px)', 'nechugo-news' ), 10, 24 ),
		'widget_title_font_weight' => array( __( 'Peso del titulo de widgets', 'nechugo-news' ), 100, 900 ),
	);
	foreach ( $ctx_typo as $key => $data ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'nechugo_sanitize_number',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'       => $data[0],
				'section'     => 'nechugo_typography',
				'type'        => 'number',
				'input_attrs' => array( 'min' => $data[1], 'max' => $data[2] ),
			)
		);
	}

	// ============================== HEADER (4 diseños) ==============================
	$wp_customize->add_section(
		'nechugo_header',
		array(
			'title'    => __( 'Encabezado (4 diseños)', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 40,
		)
	);

	$wp_customize->add_setting(
		'header_layout',
		array(
			'default'           => 'header-1',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'header_layout',
		array(
			'label'       => __( 'Diseno del encabezado', 'nechugo-news' ),
			'description' => __( 'Todos los encabezados se expanden de extremo a extremo.', 'nechugo-news' ),
			'section'     => 'nechugo_header',
			'type'        => 'radio',
			'choices'     => array(
				'header-1' => __( '1 - Clasico: logo + menu horizontal', 'nechugo-news' ),
				'header-2' => __( '2 - Revista: logo centrado + menu abajo', 'nechugo-news' ),
				'header-3' => __( '3 - Moderno: menu izquierda + logo derecha', 'nechugo-news' ),
				'header-4' => __( '4 - Compacto: logo + menu en una barra', 'nechugo-news' ),
			),
		)
	);

	$header_toggles = array(
		'header_topbar_enable' => __( 'Mostrar barra superior (topbar)', 'nechugo-news' ),
		'header_date_enable'   => __( 'Mostrar fecha actual en topbar', 'nechugo-news' ),
		'header_search_enable' => __( 'Mostrar boton de busqueda', 'nechugo-news' ),
		'header_social_enable' => __( 'Mostrar redes sociales en topbar', 'nechugo-news' ),
		'header_sticky'        => __( 'Encabezado fijo al hacer scroll', 'nechugo-news' ),
	);
	foreach ( $header_toggles as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'nechugo_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $label,
				'section' => 'nechugo_header',
				'type'    => 'checkbox',
			)
		);
	}

	$wp_customize->add_setting(
		'header_height',
		array(
			'default'           => 64,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'header_height',
		array(
			'label'       => __( 'Altura del encabezado (px)', 'nechugo-news' ),
			'section'     => 'nechugo_header',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 48, 'max' => 180, 'step' => 2 ),
		)
	);

	$wp_customize->add_setting(
		'header_font_family',
		array(
			'default'           => 'roboto-condensed',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'header_font_family',
		array(
			'label'   => __( 'Fuente del encabezado', 'nechugo-news' ),
			'section' => 'nechugo_header',
			'type'    => 'select',
			'choices' => $fonts,
		)
	);

	$wp_customize->add_setting(
		'header_font_size',
		array(
			'default'           => 13,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'header_font_size',
		array(
			'label'       => __( 'Tamano de texto del encabezado (px)', 'nechugo-news' ),
			'section'     => 'nechugo_header',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 10, 'max' => 22 ),
		)
	);

	$wp_customize->add_setting(
		'header_menu_size',
		array(
			'default'           => 13,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'header_menu_size',
		array(
			'label'       => __( 'Tamano de fuente del menu (px)', 'nechugo-news' ),
			'section'     => 'nechugo_header',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 10, 'max' => 22 ),
		)
	);

	$header_colors = array(
		'header_menu_color'      => __( 'Color de enlaces del menu', 'nechugo-news' ),
		'header_menu_hover_color' => __( 'Color al pasar el mouse (hover)', 'nechugo-news' ),
		'header_submenu_bg'      => __( 'Fondo de submenus', 'nechugo-news' ),
		'header_submenu_color'   => __( 'Texto de submenus', 'nechugo-news' ),
		'header_submenu_hover_bg' => __( 'Fondo hover de submenus', 'nechugo-news' ),
	);
	foreach ( $header_colors as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'nechugo_header',
				)
			)
		);
	}

	$wp_customize->add_setting(
		'header_gap',
		array(
			'default'           => 5,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'header_gap',
		array(
			'label'       => __( 'Separacion entre cajas del encabezado (px)', 'nechugo-news' ),
			'description' => __( 'Espacio que diferencia cada objeto (logo, menu, buscador). Aplica a todos los heads y al topbar.', 'nechugo-news' ),
			'section'     => 'nechugo_header',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'max' => 40, 'step' => 1 ),
		)
	);

	$wp_customize->add_setting(
		'logo_align',
		array(
			'default'           => 'left',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'logo_align',
		array(
			'label'   => __( 'Alineacion del logo', 'nechugo-news' ),
			'section' => 'nechugo_header',
			'type'    => 'radio',
			'choices' => array(
				'left'   => __( 'Izquierda (por defecto)', 'nechugo-news' ),
				'center' => __( 'Centro', 'nechugo-news' ),
				'right'  => __( 'Derecha', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'search_align',
		array(
			'default'           => 'right',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'search_align',
		array(
			'label'   => __( 'Alineacion del buscador', 'nechugo-news' ),
			'section' => 'nechugo_header',
			'type'    => 'radio',
			'choices' => array(
				'left'   => __( 'Izquierda', 'nechugo-news' ),
				'right'  => __( 'Derecha (por defecto)', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'header_widgets_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'header_widgets_enable',
		array(
			'label'       => __( 'Zona de 4 widgets bajo el encabezado', 'nechugo-news' ),
			'description' => __( 'Asigna widgets en Apariencia > Widgets: Header 1-4.', 'nechugo-news' ),
			'section'     => 'nechugo_header',
			'type'        => 'checkbox',
		)
	);

	// ============================== FOOTER (3 diseños) ==============================
	$wp_customize->add_section(
		'nechugo_footer',
		array(
			'title'    => __( 'Pie de pagina (3 diseños)', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 50,
		)
	);

	$wp_customize->add_setting(
		'footer_layout',
		array(
			'default'           => 'footer-1',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'footer_layout',
		array(
			'label'       => __( 'Diseno del pie de pagina', 'nechugo-news' ),
			'description' => __( 'Todos los pies de pagina se expanden de extremo a extremo.', 'nechugo-news' ),
			'section'     => 'nechugo_footer',
			'type'        => 'radio',
			'choices'     => array(
				'footer-1' => __( '1 - Columnas de widgets', 'nechugo-news' ),
				'footer-2' => __( '2 - Logo centrado + menu', 'nechugo-news' ),
				'footer-3' => __( '3 - Minimal: una linea + copyright', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'footer_columns',
		array(
			'default'           => 3,
			'sanitize_callback' => 'nechugo_sanitize_number',
		)
	);
	$wp_customize->add_control(
		'footer_columns',
		array(
			'label'       => __( 'Numero de columnas (diseno 1)', 'nechugo-news' ),
			'section'     => 'nechugo_footer',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 1, 'max' => 4 ),
		)
	);

	$wp_customize->add_setting(
		'footer_copyright_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'footer_copyright_enable',
		array(
			'label'   => __( 'Mostrar linea de copyright', 'nechugo-news' ),
			'section' => 'nechugo_footer',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'footer_copyright_text',
		array(
			'default'           => nechugo_get_option( 'footer_copyright_text' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'footer_copyright_text',
		array(
			'label'       => __( 'Texto de copyright', 'nechugo-news' ),
			'description' => __( 'Usa {year} y {sitename} como variables.', 'nechugo-news' ),
			'section'     => 'nechugo_footer',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'footer_widgets_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'footer_widgets_enable',
		array(
			'label'       => __( 'Zonas de widgets del pie (hasta 4)', 'nechugo-news' ),
			'description' => __( 'Asigna widgets en Apariencia > Widgets: Pie de pagina 1-4.', 'nechugo-news' ),
			'section'     => 'nechugo_footer',
			'type'        => 'checkbox',
		)
	);

	// Personalizacion de colores y tipografia del pie de pagina.
	$footer_colors = array(
		'footer_heading_color' => __( 'Color de titulares del pie', 'nechugo-news' ),
		'footer_link_color'    => __( 'Color de enlaces del pie', 'nechugo-news' ),
	);
	foreach ( $footer_colors as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'nechugo_footer',
				)
			)
		);
	}

	$wp_customize->add_setting(
		'footer_font_family',
		array(
			'default'           => 'roboto-condensed',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'footer_font_family',
		array(
			'label'   => __( 'Fuente del pie de pagina', 'nechugo-news' ),
			'section' => 'nechugo_footer',
			'type'    => 'select',
			'choices' => $fonts,
		)
	);

	$wp_customize->add_setting(
		'footer_font_size',
		array(
			'default'           => 13,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'footer_font_size',
		array(
			'label'       => __( 'Tamano de texto del pie (px)', 'nechugo-news' ),
			'section'     => 'nechugo_footer',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 11, 'max' => 20 ),
		)
	);

	$wp_customize->add_setting(
		'footer_border_enable',
		array(
			'default'           => true,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'footer_border_enable',
		array(
			'label'   => __( 'Mostrar linea divisoria superior', 'nechugo-news' ),
			'section' => 'nechugo_footer',
			'type'    => 'checkbox',
		)
	);

	// ============================== BLOG / LAYOUT ==============================
	$wp_customize->add_section(
		'nechugo_blog',
		array(
			'title'    => __( 'Blog y layout', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 60,
		)
	);

	$wp_customize->add_setting(
		'content_width',
		array(
			'default'           => 1200,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'content_width',
		array(
			'label'       => __( 'Ancho del contenedor del blog (px, minimo 1200)', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 1200, 'max' => 1600, 'step' => 10 ),
		)
	);

	$wp_customize->add_setting(
		'blog_layout',
		array(
			'default'           => 'grid',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'blog_layout',
		array(
			'label'   => __( 'Diseno del listado de entradas', 'nechugo-news' ),
			'section' => 'nechugo_blog',
			'type'    => 'radio',
			'choices' => array(
				'grid'    => __( 'Cuadricula de columnas', 'nechugo-news' ),
				'list'    => __( 'Lista horizontal (imagen + texto)', 'nechugo-news' ),
				'masonry' => __( 'Masonry', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'blog_columns',
		array(
			'default'           => 2,
			'sanitize_callback' => 'nechugo_sanitize_number',
		)
	);
	$wp_customize->add_control(
		'blog_columns',
		array(
			'label'       => __( 'Numero de columnas (cuadricula y masonry)', 'nechugo-news' ),
			'description' => __( 'Por defecto 2. Se ajusta automaticamente en pantallas pequenas.', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 1, 'max' => 4 ),
		)
	);

	// ---- Sidebar ----
	$wp_customize->add_setting(
		'posts_per_page_mode',
		array(
			'default'           => 'columns',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'posts_per_page_mode',
		array(
			'label'       => __( 'Entradas por pagina', 'nechugo-news' ),
			'description' => __( 'Automatico: 5 entradas por columna (2 columnas = 10, 3 = 15, 4 = 20).', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'radio',
			'choices'     => array(
				'columns' => __( 'Automatico segun columnas (5 por columna)', 'nechugo-news' ),
				'manual'  => __( 'Cantidad manual', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'posts_per_page',
		array(
			'default'           => 10,
			'sanitize_callback' => 'nechugo_sanitize_number',
		)
	);
	$wp_customize->add_control(
		'posts_per_page',
		array(
			'label'       => __( 'Cantidad manual de entradas', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 3, 'max' => 50 ),
		)
	);

	$wp_customize->add_setting(
		'container_type',
		array(
			'default'           => 'boxed',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'container_type',
		array(
			'label'   => __( 'Tipo de contenedor', 'nechugo-news' ),
			'section' => 'nechugo_blog',
			'type'    => 'radio',
			'choices' => array(
				'boxed' => __( 'Encajonado (ancho limitado)', 'nechugo-news' ),
				'full'  => __( 'Ancho completo de pantalla', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'sidebar_width',
		array(
			'default'           => 300,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sidebar_width',
		array(
			'label'       => __( 'Ancho de la barra lateral (px)', 'nechugo-news' ),
			'description' => __( 'Por defecto 300px.', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 240, 'max' => 480, 'step' => 10 ),
		)
	);

	$sidebar_toggles = array(
		'sidebar_on_blog'    => __( 'Mostrar sidebar en el blog / portada', 'nechugo-news' ),
		'sidebar_on_archive' => __( 'Mostrar sidebar en archivos y categorias', 'nechugo-news' ),
		'sidebar_on_posts'   => __( 'Mostrar sidebar en entradas', 'nechugo-news' ),
		'sidebar_on_pages'   => __( 'Mostrar sidebar en paginas', 'nechugo-news' ),
		'sidebar_on_search'  => __( 'Mostrar sidebar en resultados de busqueda', 'nechugo-news' ),
	);
	foreach ( $sidebar_toggles as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'nechugo_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $label,
				'section' => 'nechugo_blog',
				'type'    => 'checkbox',
			)
		);
	}

	$wp_customize->add_setting(
		'sidebar_position',
		array(
			'default'           => 'right',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'sidebar_position',
		array(
			'label'       => __( 'Posicion global de la barra lateral', 'nechugo-news' ),
			'description' => __( 'Valor heredado por los contextos en "Predeterminado". Cada contexto puede sobrescribirlo en su propia seccion.', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'radio',
			'choices'     => array(
				'right' => __( 'Derecha', 'nechugo-news' ),
				'left'  => __( 'Izquierda', 'nechugo-news' ),
				'none'  => __( 'Sin barra lateral (ancho completo)', 'nechugo-news' ),
			),
		)
	);

	$sidebar_choices = array(
		'default' => __( 'Predeterminado (hereda la global)', 'nechugo-news' ),
		'right'   => __( 'Derecha', 'nechugo-news' ),
		'left'    => __( 'Izquierda', 'nechugo-news' ),
		'none'    => __( 'Sin barra lateral (ancho completo)', 'nechugo-news' ),
	);
	$per_context = array(
		'sidebar_pos_blog'    => array( __( 'Blog / portada', 'nechugo-news' ), 'nechugo_blog' ),
		'sidebar_pos_archive' => array( __( 'Archivos y categorias', 'nechugo-news' ), 'nechugo_archive_layout' ),
		'sidebar_pos_posts'   => array( __( 'Entradas individuales', 'nechugo-news' ), 'nechugo_posts_layout' ),
		'sidebar_pos_search'  => array( __( 'Resultados de busqueda', 'nechugo-news' ), 'nechugo_search_layout' ),
	);
	foreach ( $per_context as $key => $data ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => 'default',
				'sanitize_callback' => 'sanitize_key',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $data[0],
				'section' => $data[1],
				'type'    => 'radio',
				'choices' => $sidebar_choices,
			)
		);
	}

	// Nuevas secciones de layout por contexto.
	$ctx_sections = array(
		'nechugo_archive_layout' => array( __( 'Layout: Archivos', 'nechugo-news' ), 61 ),
		'nechugo_posts_layout'   => array( __( 'Layout: Entradas', 'nechugo-news' ), 63 ),
		'nechugo_search_layout'  => array( __( 'Layout: Busqueda', 'nechugo-news' ), 64 ),
	);
	foreach ( $ctx_sections as $sec_id => $sec_data ) {
		$wp_customize->add_section(
			$sec_id,
			array(
				'title'    => $sec_data[0],
				'panel'    => 'nechugo_options',
				'priority' => $sec_data[1],
			)
		);
	}

	$wp_customize->add_setting(
		'excerpt_length',
		array(
			'default'           => 24,
			'sanitize_callback' => 'nechugo_sanitize_number',
		)
	);
	$wp_customize->add_control(
		'excerpt_length',
		array(
			'label'       => __( 'Palabras del extracto', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 5, 'max' => 80 ),
		)
	);

	$blog_toggles = array(
		'show_breadcrumbs'  => __( 'Mostrar breadcrumbs', 'nechugo-news' ),
		'show_author'       => __( 'Mostrar autor', 'nechugo-news' ),
		'show_date'         => __( 'Mostrar fecha', 'nechugo-news' ),
		'show_reading_time' => __( 'Mostrar tiempo de lectura', 'nechugo-news' ),
		'show_share_buttons' => __( 'Mostrar botones para compartir', 'nechugo-news' ),
		'home_slider_enable' => __( 'Slider de destacados en portada', 'nechugo-news' ),
		'back_to_top_enable' => __( 'Boton volver arriba', 'nechugo-news' ),
	);
	foreach ( $blog_toggles as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'nechugo_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $label,
				'section' => 'nechugo_blog',
				'type'    => 'checkbox',
			)
		);
	}

	$wp_customize->add_setting(
		'home_slider_category',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'home_slider_category',
		array(
			'label'       => __( 'Categoria del slider (0 = todas)', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'max' => 9999 ),
		)
	);

	$wp_customize->add_setting(
		'home_slider_style',
		array(
			'default'           => 'hero',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'home_slider_style',
		array(
			'label'   => __( 'Estilo del slider', 'nechugo-news' ),
			'section' => 'nechugo_blog',
			'type'    => 'radio',
			'choices' => array(
				'split'  => __( 'Dividido: 890x520 izquierda + 3 filas derecha (recomendado)', 'nechugo-news' ),
				'hero'   => __( 'Hero grande (una entrada por pantalla)', 'nechugo-news' ),
				'grid'   => __( 'Cuadricula 2x2 con activo grande', 'nechugo-news' ),
				'carousel' => __( 'Carrusel horizontal', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'home_slider_autoplay',
		array(
			'default'           => true,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'home_slider_autoplay',
		array(
			'label'   => __( 'Rotar automaticamente', 'nechugo-news' ),
			'section' => 'nechugo_blog',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'home_slider_interval',
		array(
			'default'           => 5000,
			'sanitize_callback' => 'nechugo_sanitize_number',
		)
	);
	$wp_customize->add_control(
		'home_slider_interval',
		array(
			'label'       => __( 'Intervalo de rotacion (ms)', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 2000, 'max' => 12000, 'step' => 500 ),
		)
	);

	$wp_customize->add_setting(
		'home_slider_show_meta',
		array(
			'default'           => true,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'home_slider_show_meta',
		array(
			'label'   => __( 'Mostrar fecha y autor en el slider', 'nechugo-news' ),
			'section' => 'nechugo_blog',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'home_slider_count',
		array(
			'default'           => 5,
			'sanitize_callback' => 'nechugo_sanitize_number',
		)
	);
	$wp_customize->add_control(
		'home_slider_count',
		array(
			'label'       => __( 'Entradas en el slider', 'nechugo-news' ),
			'description' => __( 'En el estilo dividido se usan las ultimas 6 entradas con imagen: 1 grande + 3 filas por grupo.', 'nechugo-news' ),
			'section'     => 'nechugo_blog',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 2, 'max' => 10 ),
		)
	);

	// Meta y contenido de entradas.
	$post_meta_toggles = array(
		'show_post_category' => __( 'Mostrar categorias en la entrada', 'nechugo-news' ),
		'show_post_tags'     => __( 'Mostrar etiquetas al final', 'nechugo-news' ),
		'show_post_nav'      => __( 'Mostrar navegacion anterior/siguiente', 'nechugo-news' ),
		'show_author_box'    => __( 'Mostrar caja del autor', 'nechugo-news' ),
	);
	foreach ( $post_meta_toggles as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'nechugo_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $label,
				'section' => 'nechugo_posts_layout',
				'type'    => 'checkbox',
			)
		);
	}

	$post_spacing = array(
		'spacing_post_title' => array( __( 'Espacio bajo el titulo de entrada (px)', 'nechugo-news' ), 0, 60 ),
		'spacing_post_meta' => array( __( 'Espacio bajo los metadatos (px)', 'nechugo-news' ), 0, 60 ),
	);
	foreach ( $post_spacing as $key => $data ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'nechugo_sanitize_number',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'       => $data[0],
				'section'     => 'nechugo_posts_layout',
				'type'        => 'number',
				'input_attrs' => array( 'min' => $data[1], 'max' => $data[2] ),
			)
		);
	}

	// Comentarios.
	$wp_customize->add_setting(
		'comments_style',
		array(
			'default'           => 'boxed',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'comments_style',
		array(
			'label'   => __( 'Estilo de comentarios', 'nechugo-news' ),
			'section' => 'nechugo_posts_layout',
			'type'    => 'radio',
			'choices' => array(
				'boxed'   => __( 'Con tarjetas y sombra', 'nechugo-news' ),
				'minimal' => __( 'Minimal con separadores', 'nechugo-news' ),
			),
		)
	);

	// ============================== PAGINAS ==============================
	$wp_customize->add_section(
		'nechugo_pages',
		array(
			'title'    => __( 'Paginas y contenido', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 62,
		)
	);

	$wp_customize->add_setting(
		'sidebar_on_pages',
		array(
			'default'           => true,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'sidebar_on_pages',
		array(
			'label'   => __( 'Mostrar sidebar en paginas', 'nechugo-news' ),
			'section' => 'nechugo_pages',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'sidebar_pos_pages',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'sidebar_pos_pages',
		array(
			'label'   => __( 'Posicion de la barra lateral en paginas', 'nechugo-news' ),
			'section' => 'nechugo_pages',
			'type'    => 'radio',
			'choices' => array(
				'default' => __( 'Predeterminado (hereda la global)', 'nechugo-news' ),
				'right'   => __( 'Derecha', 'nechugo-news' ),
				'left'    => __( 'Izquierda', 'nechugo-news' ),
				'none'    => __( 'Sin barra lateral (ancho completo)', 'nechugo-news' ),
			),
		)
	);


	$wp_customize->add_setting(
		'page_title_style',
		array(
			'default'           => 'centered',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'page_title_style',
		array(
			'label'   => __( 'Estilo del titulo de pagina', 'nechugo-news' ),
			'section' => 'nechugo_pages',
			'type'    => 'radio',
			'choices' => array(
				'centered' => __( 'Centrado con aire', 'nechugo-news' ),
				'left'     => __( 'Alineado a la izquierda', 'nechugo-news' ),
			),
		)
	);

	$wp_customize->add_setting(
		'page_show_thumb',
		array(
			'default'           => true,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'page_show_thumb',
		array(
			'label'   => __( 'Mostrar imagen destacada en paginas', 'nechugo-news' ),
			'section' => 'nechugo_pages',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'page_thumb_radius',
		array(
			'default'           => 12,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'page_thumb_radius',
		array(
			'label'       => __( 'Redondeo de imagenes (px)', 'nechugo-news' ),
			'section'     => 'nechugo_pages',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'max' => 30 ),
		)
	);

	$wp_customize->add_setting(
		'spacing_header_body',
		array(
			'default'           => 30,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'spacing_header_body',
		array(
			'label'       => __( 'Separacion encabezado-contenido (px)', 'nechugo-news' ),
			'section'     => 'nechugo_pages',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'max' => 120, 'step' => 5 ),
		)
	);

	$wp_customize->add_setting(
		'spacing_body_footer',
		array(
			'default'           => 30,
			'sanitize_callback' => 'nechugo_sanitize_number',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'spacing_body_footer',
		array(
			'label'       => __( 'Separacion contenido-pie de pagina (px)', 'nechugo-news' ),
			'section'     => 'nechugo_pages',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 0, 'max' => 120, 'step' => 5 ),
		)
	);

	// ============================== BOTONES ==============================
	$wp_customize->add_section(
		'nechugo_buttons',
		array(
			'title'    => __( 'Botones', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 46,
		)
	);

	$btn_colors = array(
		'btn_bg_color'        => __( 'Color de fondo', 'nechugo-news' ),
		'btn_text_color'      => __( 'Color del texto', 'nechugo-news' ),
		'btn_hover_bg_color'  => __( 'Color de fondo al pasar el mouse', 'nechugo-news' ),
	);
	foreach ( $btn_colors as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$key,
				array(
					'label'   => $label,
					'section' => 'nechugo_buttons',
				)
			)
		);
	}

	$btn_numbers = array(
		'btn_radius'     => array( __( 'Redondeo (px)', 'nechugo-news' ), 0, 40 ),
		'btn_padding'    => array( __( 'Relleno horizontal (px)', 'nechugo-news' ), 4, 40 ),
		'btn_font_size'  => array( __( 'Tamano de fuente (px)', 'nechugo-news' ), 10, 22 ),
		'btn_font_weight' => array( __( 'Peso de fuente (100-900)', 'nechugo-news' ), 100, 900 ),
	);
	foreach ( $btn_numbers as $key => $data ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => nechugo_get_option( $key ),
				'sanitize_callback' => 'nechugo_sanitize_number',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'       => $data[0],
				'section'     => 'nechugo_buttons',
				'type'        => 'number',
				'input_attrs' => array( 'min' => $data[1], 'max' => $data[2] ),
			)
		);
	}

	// ============================== ADSENSE ==============================
	$wp_customize->add_section(
		'nechugo_ads',
		array(
			'title'    => __( 'Google AdSense', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 70,
		)
	);

	$wp_customize->add_setting(
		'ads_master_switch',
		array(
			'default'           => false,
			'sanitize_callback' => 'nechugo_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'ads_master_switch',
		array(
			'label'       => __( 'Activar anuncios', 'nechugo-news' ),
			'description' => __( 'Interruptor principal de todos los bloques de anuncio.', 'nechugo-news' ),
			'section'     => 'nechugo_ads',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'adsense_publisher_id',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'adsense_publisher_id',
		array(
			'label'       => __( 'ID de publicante AdSense', 'nechugo-news' ),
			'description' => __( 'Ejemplo: ca-pub-1234567890123456', 'nechugo-news' ),
			'section'     => 'nechugo_ads',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'ads_mode',
		array(
			'default'           => 'manual',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'ads_mode',
		array(
			'label'   => __( 'Modo de anuncios', 'nechugo-news' ),
			'section' => 'nechugo_ads',
			'type'    => 'radio',
			'choices' => array(
				'auto'   => __( 'Auto Ads (Google coloca los anuncios)', 'nechugo-news' ),
				'manual' => __( 'Manual (solo los espacios del tema)', 'nechugo-news' ),
				'both'   => __( 'Ambos', 'nechugo-news' ),
			),
		)
	);

	$ad_slots = array(
		'ad_header_code'     => __( 'Anuncio bajo el encabezado (728x90 / responsive)', 'nechugo-news' ),
		'ad_in_article_code' => __( 'Anuncio dentro del articulo (tras el primer parrafo)', 'nechugo-news' ),
		'ad_after_post_code' => __( 'Anuncio tras el contenido', 'nechugo-news' ),
		'ad_sidebar_code'    => __( 'Anuncio al inicio de la barra lateral', 'nechugo-news' ),
	);
	foreach ( $ad_slots as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => '',
				'sanitize_callback' => 'nechugo_sanitize_ad_code',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'       => $label,
				'section'     => 'nechugo_ads',
				'type'        => 'textarea',
			)
		);
	}

	// Redes sociales (topbar y footer).
	$wp_customize->add_section(
		'nechugo_social',
		array(
			'title'    => __( 'Redes sociales', 'nechugo-news' ),
			'panel'    => 'nechugo_options',
			'priority' => 55,
		)
	);
	foreach ( array(
		'social_facebook'  => __( 'URL de Facebook', 'nechugo-news' ),
		'social_twitter'   => __( 'URL de Twitter / X', 'nechugo-news' ),
		'social_instagram' => __( 'URL de Instagram', 'nechugo-news' ),
		'social_youtube'   => __( 'URL de YouTube', 'nechugo-news' ),
	) as $key => $label ) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $label,
				'section' => 'nechugo_social',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'nechugo_customize_register' );
