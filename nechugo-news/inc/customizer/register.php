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
			'label'   => __( 'Posicion de la barra lateral', 'nechugo-news' ),
			'section' => 'nechugo_blog',
			'type'    => 'radio',
			'choices' => array(
				'right' => __( 'Derecha', 'nechugo-news' ),
				'left'  => __( 'Izquierda', 'nechugo-news' ),
				'none'  => __( 'Sin barra lateral (ancho completo)', 'nechugo-news' ),
			),
		)
	);

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
				'split'  => __( 'Dividido: imagen grande + 3 filas (recomendado)', 'nechugo-news' ),
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
			'section'     => 'nechugo_blog',
			'type'        => 'number',
			'input_attrs' => array( 'min' => 2, 'max' => 10 ),
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

