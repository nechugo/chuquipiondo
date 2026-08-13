<?php
/**
 * Customizer sections (header, hero, home, blog, single, ads,
 * social, whatsapp, footer, music, custom code).
 *
 * Included by register.php so all section groups live together.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ===================================================================== *
 * HEADER section: 3 headers with multiuse boxes.
 * ===================================================================== */

function chuquipiondo_register_header( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_header', array(
		'title'    => __( 'CHUQUIPIONDO: Cabecera', 'chuquipiondo' ),
		'priority' => 29,
	) );

	// Top bar.
	chuquipiondo_add_setting_control( $wp_customize, 'header_topbar_enable', array(
		'label'             => __( 'Activar Top Bar (Header 1)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'checkbox',
		'sanitize_callback' => 'chuquipiondo_sanitize_checkbox',
		'priority'          => 5,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'header_topbar_date', array(
		'label'             => __( 'Mostrar fecha en Top Bar', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'checkbox',
		'sanitize_callback' => 'chuquipiondo_sanitize_checkbox',
		'priority'          => 6,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'header_topbar_time', array(
		'label'             => __( 'Mostrar hora en Top Bar', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'checkbox',
		'sanitize_callback' => 'chuquipiondo_sanitize_checkbox',
		'priority'          => 7,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'header_topbar_email', array(
		'label'             => __( 'Email en Top Bar', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'text',
		'sanitize_callback' => 'sanitize_email',
		'priority'          => 8,
	) );

	// Main header.
	chuquipiondo_add_setting_control( $wp_customize, 'header_main_sticky', array(
		'label'             => __( 'Header principal sticky', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'checkbox',
		'sanitize_callback' => 'chuquipiondo_sanitize_checkbox',
		'priority'          => 15,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'header_main_height', array(
		'label'             => __( 'Altura del header principal (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 60, 'max' => 140, 'step' => 2 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 16,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'header_main_layout', array(
		'label'             => __( 'Distribucion principal', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'select',
		'choices'           => array(
			'logo-left-menu-right' => __( 'Logo izq / Menu der', 'chuquipiondo' ),
			'logo-center-menu-split' => __( 'Logo centro / Menu dividido', 'chuquipiondo' ),
			'logo-left-menu-center' => __( 'Logo izq / Menu centro', 'chuquipiondo' ),
		),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 17,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'header_search_enable', array(
		'label'             => __( 'Activar buscador', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'checkbox',
		'sanitize_callback' => 'chuquipiondo_sanitize_checkbox',
		'priority'          => 18,
	) );

	// Multiuse header (Header 3).
	chuquipiondo_add_setting_control( $wp_customize, 'header_multiuse_enable', array(
		'label'             => __( 'Activar Header 3 (multiuso)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'checkbox',
		'sanitize_callback' => 'chuquipiondo_sanitize_checkbox',
		'priority'          => 25,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'header_multiuse_content', array(
		'label'             => __( 'Contenido HTML/Shortcode (Header 3)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'textarea',
		'sanitize_callback' => 'chuquipiondo_sanitize_html',
		'priority'          => 26,
	) );

	// Multiuse boxes content + distribution.
	chuquipiondo_add_setting_control( $wp_customize, 'header_distribution', array(
		'label'             => __( 'Distribucion de cajas', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_header',
		'type'              => 'select',
		'choices'           => array(
			'100'          => '100%',
			'50-50'        => '50 / 50',
			'33-33-33'     => '33 / 33 / 33',
			'25-25-25-25'  => '25 / 25 / 25 / 25',
			'60-40'        => '60 / 40',
			'40-60'        => '40 / 60',
		),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 30,
	) );

	$box_types = array(
		'none'       => __( 'Ninguna', 'chuquipiondo' ),
		'logo'       => __( 'Logo', 'chuquipiondo' ),
		'menu'       => __( 'Menu', 'chuquipiondo' ),
		'search'     => __( 'Buscador', 'chuquipiondo' ),
		'text'       => __( 'Texto', 'chuquipiondo' ),
		'html'       => __( 'HTML / Shortcode', 'chuquipiondo' ),
		'widget'     => __( 'Widget', 'chuquipiondo' ),
	);
	for ( $i = 1; $i <= 4; $i++ ) {
		chuquipiondo_add_setting_control( $wp_customize, "header_box{$i}_type", array(
			/* translators: %d: box number */
			'label'             => sprintf( __( 'Caja %d: tipo', 'chuquipiondo' ), $i ),
			'section'           => 'chuquipiondo_header',
			'type'              => 'select',
			'choices'           => $box_types,
			'sanitize_callback' => 'chuquipiondo_sanitize_select',
			'priority'          => 31 + $i,
		) );
		chuquipiondo_add_setting_control( $wp_customize, "header_box{$i}_content", array(
			/* translators: %d: box number */
			'label'             => sprintf( __( 'Caja %d: contenido', 'chuquipiondo' ), $i ),
			'section'           => 'chuquipiondo_header',
			'type'              => 'textarea',
			'sanitize_callback' => 'chuquipiondo_sanitize_html',
			'priority'          => 35 + $i,
		) );
		chuquipiondo_add_setting_control( $wp_customize, "header_box{$i}_visible", array(
			/* translators: %d: box number */
			'label'             => sprintf( __( 'Caja %d: dispositivos (comma-sep)', 'chuquipiondo' ), $i ),
			'section'           => 'chuquipiondo_header',
			'sanitize_callback' => 'chuquipiondo_sanitize_text',
			'priority'          => 39 + $i,
			'description'       => 'desktop,tablet,mobile',
		) );
	}
}

/* ===================================================================== *
 * HERO section: master switch, modes, slides, effects.
 * ===================================================================== */

function chuquipiondo_register_hero( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_hero', array(
		'title'    => __( 'CHUQUIPIONDO: Hero / Slider', 'chuquipiondo' ),
		'priority' => 30,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'hero_enable', array(
		'label'             => __( 'Activar Hero', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_hero',
		'type'              => 'checkbox',
		'sanitize_callback' => 'chuquipiondo_sanitize_checkbox',
		'priority'          => 5,
		'description'       => __( 'Master Switch. Si esta OFF no se carga ningun script ni HTML.', 'chuquipiondo' ),
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'hero_mode', array(
		'label'             => __( 'Modo', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_hero',
		'type'              => 'select',
		'choices'           => array(
			'image'      => __( 'Imagen unica', 'chuquipiondo' ),
			'slider'     => __( 'Slider', 'chuquipiondo' ),
			'video'      => __( 'Video', 'chuquipiondo' ),
			'html'       => __( 'HTML', 'chuquipiondo' ),
			'shortcode'  => __( 'Shortcode', 'chuquipiondo' ),
			'elementor'  => __( 'Template de Elementor', 'chuquipiondo' ),
		),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 6,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'hero_effect', array(
		'label'             => __( 'Efecto', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_hero',
		'type'              => 'select',
		'choices'           => array(
			'fade'       => 'Fade',
			'slide'      => 'Slide',
			'kenburns'   => 'Ken Burns',
		),
		'sanitize_callback' => 'chuquipiondo_sanitize_select',
		'priority'          => 7,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'hero_height', array(
		'label'             => __( 'Altura (px)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_hero',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 320, 'max' => 800, 'step' => 10 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 8,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'hero_autoplay', array(
		'label'             => __( 'Autoplay', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_hero',
		'type'              => 'checkbox',
		'sanitize_callback' => 'chuquipiondo_sanitize_checkbox',
		'priority'          => 9,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'hero_speed', array(
		'label'             => __( 'Velocidad de slide (ms)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_hero',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 2000, 'max' => 10000, 'step' => 500 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 10,
	) );
	chuquipiondo_add_setting_control( $wp_customize, 'hero_overlay', array(
		'label'             => __( 'Overlay (%)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_hero',
		'type'              => 'range',
		'input_attrs'       => array( 'min' => 0, 'max' => 90, 'step' => 5 ),
		'sanitize_callback' => 'chuquipiondo_sanitize_range',
		'priority'          => 11,
	) );

	// Slides repeater (stored as array).
	$wp_customize->add_setting( 'hero_slider', array(
		'default'           => chuquipiondo_default( 'hero_slider' ),
		'transport'         => 'refresh',
		'sanitize_callback' => 'chuquipiondo_sanitize_slides',
	) );
	$wp_customize->add_control( new Chuquipiondo_Slides_Control( $wp_customize, 'hero_slider', array(
		'label'    => __( 'Slides', 'chuquipiondo' ),
		'section'  => 'chuquipiondo_hero',
		'priority' => 20,
	) ) );
}

/* ===================================================================== *
 * HOME builder section.
 * ===================================================================== */

function chuquipiondo_register_home( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_home', array(
		'title'    => __( 'CHUQUIPIONDO: Home Builder', 'chuquipiondo' ),
		'priority' => 31,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'home_modules', array(
		'label'             => __( 'Modulos activos (orden, comma-sep)', 'chuquipiondo' ),
		'section'           => 'chuquipiondo_home',
		'type'              => 'textarea',
		'sanitize_callback' => 'chuquipiondo_sanitize_sortable',
		'description'       => __( 'hero,featured,latest,categories,song,videos,about,newsletter', 'chuquipiondo' ),
		'priority'          => 5,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'home_featured_title', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Titulo: destacados', 'chuquipiondo' ), 'priority' => 10 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_featured_count', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Cantidad destacados', 'chuquipiondo' ), 'type' => 'range', 'input_attrs' => array( 'min' => 2, 'max' => 8, 'step' => 1 ), 'sanitize_callback' => 'chuquipiondo_sanitize_range', 'priority' => 11 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_latest_title', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Titulo: ultimos articulos', 'chuquipiondo' ), 'priority' => 15 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_latest_count', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Cantidad ultimos', 'chuquipiondo' ), 'type' => 'range', 'input_attrs' => array( 'min' => 3, 'max' => 12, 'step' => 1 ), 'sanitize_callback' => 'chuquipiondo_sanitize_range', 'priority' => 16 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_categories_title', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Titulo: categorias', 'chuquipiondo' ), 'priority' => 20 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_song_title', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Titulo: cancion destacada', 'chuquipiondo' ), 'priority' => 25 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_song_id', array( 'section' => 'chuquipiondo_home', 'label' => __( 'ID de la cancion destacada', 'chuquipiondo' ), 'sanitize_callback' => 'chuquipiondo_sanitize_int', 'priority' => 26 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_videos_title', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Titulo: videos', 'chuquipiondo' ), 'priority' => 30 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_videos_count', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Cantidad videos', 'chuquipiondo' ), 'type' => 'range', 'input_attrs' => array( 'min' => 1, 'max' => 6, 'step' => 1 ), 'sanitize_callback' => 'chuquipiondo_sanitize_range', 'priority' => 31 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_videos_playlist', array( 'section' => 'chuquipiondo_home', 'label' => __( 'URL playlist YouTube', 'chuquipiondo' ), 'sanitize_callback' => 'chuquipiondo_sanitize_url', 'priority' => 32 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_about_title', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Titulo: sobre Nelson', 'chuquipiondo' ), 'priority' => 35 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_about_text', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Texto: sobre Nelson', 'chuquipiondo' ), 'type' => 'textarea', 'sanitize_callback' => 'chuquipiondo_sanitize_textarea', 'priority' => 36 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_about_image', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Imagen: sobre Nelson (URL)', 'chuquipiondo' ), 'sanitize_callback' => 'chuquipiondo_sanitize_url', 'priority' => 37 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_newsletter_title', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Titulo: newsletter', 'chuquipiondo' ), 'priority' => 40 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_newsletter_text', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Texto: newsletter', 'chuquipiondo' ), 'type' => 'textarea', 'sanitize_callback' => 'chuquipiondo_sanitize_textarea', 'priority' => 41 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'home_newsletter_shortcode', array( 'section' => 'chuquipiondo_home', 'label' => __( 'Shortcode newsletter', 'chuquipiondo' ), 'priority' => 42 ) );
}

/* ===================================================================== *
 * BLOG / archive section.
 * ===================================================================== */

function chuquipiondo_register_blog( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_blog', array(
		'title'    => __( 'CHUQUIPIONDO: Blog', 'chuquipiondo' ),
		'priority' => 32,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'blog_columns', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Columnas (desktop)', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 5 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_columns_tablet', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Columnas (tablet)', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( '1' => '1', '2' => '2' ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 6 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_columns_mobile', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Columnas (movil)', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( '1' => '1', '2' => '2' ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 7 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_card_style', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Estilo de tarjeta', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( 'minimal' => 'Minimal', 'editorial' => 'Editorial', 'elegant' => 'Elegant', 'magazine' => 'Magazine', 'image-focus' => 'Image Focus' ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 8 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_sidebar', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Barra lateral', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( 'right' => __( 'Derecha', 'chuquipiondo' ), 'left' => __( 'Izquierda', 'chuquipiondo' ), 'none' => __( 'Ninguna', 'chuquipiondo' ) ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 9 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_excerpt_length', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Longitud del extracto (palabras)', 'chuquipiondo' ), 'type' => 'range', 'input_attrs' => array( 'min' => 0, 'max' => 60, 'step' => 2 ), 'sanitize_callback' => 'chuquipiondo_sanitize_range', 'priority' => 10 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_show_author', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Mostrar autor', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 11 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_show_date', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Mostrar fecha', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 12 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_show_category', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Mostrar categoria', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 13 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_show_excerpt', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Mostrar extracto', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 14 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'blog_image_lazy', array( 'section' => 'chuquipiondo_blog', 'label' => __( 'Carga diferida de imagenes', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 15 ) );
}

/* ===================================================================== *
 * SINGLE post section.
 * ===================================================================== */

function chuquipiondo_register_single( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_single', array(
		'title'    => __( 'CHUQUIPIONDO: Articulo', 'chuquipiondo' ),
		'priority' => 33,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'single_layout', array( 'section' => 'chuquipiondo_single', 'label' => __( 'Layout', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( 'classic' => 'Classic', 'editorial' => 'Editorial', 'wide' => 'Wide', 'hero-image' => 'Hero Image' ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 5 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'single_sidebar', array( 'section' => 'chuquipiondo_single', 'label' => __( 'Barra lateral', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( 'right' => __( 'Derecha', 'chuquipiondo' ), 'left' => __( 'Izquierda', 'chuquipiondo' ), 'none' => __( 'Ninguna', 'chuquipiondo' ) ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 6 ) );
	$single_toggles = array(
		'single_show_breadcrumb' => __( 'Breadcrumb', 'chuquipiondo' ),
		'single_show_category'   => __( 'Categorias', 'chuquipiondo' ),
		'single_show_author'     => __( 'Autor', 'chuquipiondo' ),
		'single_show_date'       => __( 'Fecha', 'chuquipiondo' ),
		'single_show_reading'    => __( 'Tiempo de lectura', 'chuquipiondo' ),
		'single_show_tags'       => __( 'Etiquetas', 'chuquipiondo' ),
		'single_show_bio'        => __( 'Biografia del autor', 'chuquipiondo' ),
		'single_show_related'   => __( 'Articulos relacionados', 'chuquipiondo' ),
	);
	$i = 10;
	foreach ( $single_toggles as $key => $label ) {
		chuquipiondo_add_setting_control( $wp_customize, $key, array( 'section' => 'chuquipiondo_single', 'label' => $label, 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => $i++ ) );
	}
	chuquipiondo_add_setting_control( $wp_customize, 'single_related_count', array( 'section' => 'chuquipiondo_single', 'label' => __( 'Cantidad de relacionados', 'chuquipiondo' ), 'type' => 'range', 'input_attrs' => array( 'min' => 2, 'max' => 6, 'step' => 1 ), 'sanitize_callback' => 'chuquipiondo_sanitize_range', 'priority' => 20 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'single_related_title', array( 'section' => 'chuquipiondo_single', 'label' => __( 'Titulo: relacionados', 'chuquipiondo' ), 'priority' => 21 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'single_nav_style', array( 'section' => 'chuquipiondo_single', 'label' => __( 'Estilo de navegacion prev/siguiente', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( 'cards' => 'Cards', 'text' => 'Texto', 'hidden' => 'Oculto' ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 25 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'single_extension_area', array( 'section' => 'chuquipiondo_single', 'label' => __( 'Post End Extension: HTML/Shortcode', 'chuquipiondo' ), 'type' => 'textarea', 'sanitize_callback' => 'chuquipiondo_sanitize_html', 'priority' => 30 ) );
}

/* ===================================================================== *
 * ADS section: master switch, modes, slots.
 * ===================================================================== */

function chuquipiondo_register_ads( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_ads', array(
		'title'    => __( 'CHUQUIPIONDO: Publicidad', 'chuquipiondo' ),
		'priority' => 34,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'ads_master_switch', array( 'section' => 'chuquipiondo_ads', 'label' => __( 'Master Switch (publicidad global)', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 5, 'description' => __( 'Activa o desactiva TODOS los anuncios del tema.', 'chuquipiondo' ) ) );
	chuquipiondo_add_setting_control( $wp_customize, 'ads_mode', array( 'section' => 'chuquipiondo_ads', 'label' => __( 'Modo', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( 'disabled' => __( 'Desactivado', 'chuquipiondo' ), 'sitekit' => 'Site Kit', 'auto' => 'Auto Ads', 'manual' => 'Manual', 'auto-manual' => 'Auto + Manual' ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 6 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'ads_client_id', array( 'section' => 'chuquipiondo_ads', 'label' => __( 'AdSense Client ID (ca-pub-...)', 'chuquipiondo' ), 'sanitize_callback' => 'chuquipiondo_sanitize_text', 'priority' => 7 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'ads_blog_after_posts', array( 'section' => 'chuquipiondo_ads', 'label' => __( 'Insertar anuncio cada X posts (blog)', 'chuquipiondo' ), 'type' => 'range', 'input_attrs' => array( 'min' => 2, 'max' => 12, 'step' => 1 ), 'sanitize_callback' => 'chuquipiondo_sanitize_range', 'priority' => 8 ) );

	// Ad slots (codes). Registered from /inc/ads/slots.php metadata.
	$slots = chuquipiondo_ad_slots();
	$i     = 20;
	foreach ( $slots as $slot_key => $slot ) {
		chuquipiondo_add_setting_control( $wp_customize, $slot_key, array(
			'section'           => 'chuquipiondo_ads',
			'label'             => $slot['label'],
			'type'              => 'textarea',
			'sanitize_callback' => 'chuquipiondo_sanitize_ad_code',
			'description'       => isset( $slot['description'] ) ? $slot['description'] : '',
			'priority'          => $i++,
		) );
	}
}

/* ===================================================================== *
 * SOCIAL share section.
 * ===================================================================== */

function chuquipiondo_register_social( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_social', array(
		'title'    => __( 'CHUQUIPIONDO: Redes Sociales', 'chuquipiondo' ),
		'priority' => 35,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'social_master_switch', array( 'section' => 'chuquipiondo_social', 'label' => __( 'Master Switch (share)', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 5 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'social_networks', array( 'section' => 'chuquipiondo_social', 'label' => __( 'Redes a compartir (comma-sep)', 'chuquipiondo' ), 'sanitize_callback' => 'chuquipiondo_sanitize_text', 'description' => 'facebook,x,linkedin,whatsapp,telegram,email,copy', 'priority' => 6 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'social_color_mode', array( 'section' => 'chuquipiondo_social', 'label' => __( 'Estilo de color', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( 'official' => __( 'Color oficial', 'chuquipiondo' ), 'mono' => __( 'Monocromatico', 'chuquipiondo' ), 'custom' => __( 'Personalizado', 'chuquipiondo' ) ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 7 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'social_custom_bg', array( 'section' => 'chuquipiondo_social', 'label' => __( 'Color fondo (custom)', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 8 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'social_custom_fg', array( 'section' => 'chuquipiondo_social', 'label' => __( 'Color icono (custom)', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 9 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'social_position', array( 'section' => 'chuquipiondo_social', 'label' => __( 'Posicion (articulo)', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( 'before' => __( 'Antes', 'chuquipiondo' ), 'after' => __( 'Despues', 'chuquipiondo' ), 'both' => __( 'Ambos', 'chuquipiondo' ), 'none' => __( 'Ninguna', 'chuquipiondo' ) ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 10 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'social_floating', array( 'section' => 'chuquipiondo_social', 'label' => __( 'Flotante lateral (desktop)', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 11 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'social_floating_mobile', array( 'section' => 'chuquipiondo_social', 'label' => __( 'Flotante inferior (movil)', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 12 ) );

	// Social profiles.
	$profiles = array(
		'social_facebook'  => __( 'Facebook URL', 'chuquipiondo' ),
		'social_x'         => __( 'X URL', 'chuquipiondo' ),
		'social_youtube'   => __( 'YouTube URL', 'chuquipiondo' ),
		'social_instagram' => __( 'Instagram URL', 'chuquipiondo' ),
		'social_linkedin'  => __( 'LinkedIn URL', 'chuquipiondo' ),
		'social_telegram'  => __( 'Telegram URL', 'chuquipiondo' ),
		'social_tiktok'    => __( 'TikTok URL', 'chuquipiondo' ),
	);
	$i = 20;
	foreach ( $profiles as $key => $label ) {
		chuquipiondo_add_setting_control( $wp_customize, $key, array( 'section' => 'chuquipiondo_social', 'label' => $label, 'sanitize_callback' => 'chuquipiondo_sanitize_url', 'priority' => $i++ ) );
	}
}

/* ===================================================================== *
 * WHATSAPP section.
 * ===================================================================== */

function chuquipiondo_register_whatsapp( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_whatsapp', array(
		'title'    => __( 'CHUQUIPIONDO: WhatsApp', 'chuquipiondo' ),
		'priority' => 36,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'whatsapp_master_switch', array( 'section' => 'chuquipiondo_whatsapp', 'label' => __( 'Master Switch', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 5 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'whatsapp_number', array( 'section' => 'chuquipiondo_whatsapp', 'label' => __( 'Numero (solo digitos)', 'chuquipiondo' ), 'sanitize_callback' => 'chuquipiondo_sanitize_phone', 'priority' => 6, 'description' => '51921497257' ) );
	chuquipiondo_add_setting_control( $wp_customize, 'whatsapp_mode', array( 'section' => 'chuquipiondo_whatsapp', 'label' => __( 'Modo', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( 'private' => __( 'Mensaje privado', 'chuquipiondo' ), 'group' => __( 'Unirse a grupo', 'chuquipiondo' ) ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 7 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'whatsapp_message', array( 'section' => 'chuquipiondo_whatsapp', 'label' => __( 'Mensaje predeterminado', 'chuquipiondo' ), 'sanitize_callback' => 'chuquipiondo_sanitize_text', 'priority' => 8 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'whatsapp_group_url', array( 'section' => 'chuquipiondo_whatsapp', 'label' => __( 'URL del grupo', 'chuquipiondo' ), 'sanitize_callback' => 'chuquipiondo_sanitize_url', 'priority' => 9 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'whatsapp_position', array( 'section' => 'chuquipiondo_whatsapp', 'label' => __( 'Posicion', 'chuquipiondo' ), 'type' => 'select', 'choices' => array(
		'top-left' => 'Top Left', 'top-center' => 'Top Center', 'top-right' => 'Top Right',
		'middle-left' => 'Middle Left', 'middle-right' => 'Middle Right',
		'bottom-left' => 'Bottom Left', 'bottom-center' => 'Bottom Center', 'bottom-right' => 'Bottom Right',
		'float-right-center' => 'Float Right Center',
	), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 10 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'whatsapp_size', array( 'section' => 'chuquipiondo_whatsapp', 'label' => __( 'Tamano desktop (px)', 'chuquipiondo' ), 'type' => 'range', 'input_attrs' => array( 'min' => 35, 'max' => 96, 'step' => 1 ), 'sanitize_callback' => 'chuquipiondo_sanitize_range', 'priority' => 11 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'whatsapp_mobile_size', array( 'section' => 'chuquipiondo_whatsapp', 'label' => __( 'Tamano movil (px)', 'chuquipiondo' ), 'type' => 'range', 'input_attrs' => array( 'min' => 35, 'max' => 96, 'step' => 1 ), 'sanitize_callback' => 'chuquipiondo_sanitize_range', 'priority' => 12 ) );
}

/* ===================================================================== *
 * FOOTER section.
 * ===================================================================== */

function chuquipiondo_register_footer( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_footer', array(
		'title'    => __( 'CHUQUIPIONDO: Pie de pagina', 'chuquipiondo' ),
		'priority' => 37,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'footer_columns', array( 'section' => 'chuquipiondo_footer', 'label' => __( 'Columnas', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4' ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 5 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'footer_about', array( 'section' => 'chuquipiondo_footer', 'label' => __( 'Texto sobre la marca', 'chuquipiondo' ), 'type' => 'textarea', 'sanitize_callback' => 'chuquipiondo_sanitize_textarea', 'priority' => 6 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'footer_copyright', array( 'section' => 'chuquipiondo_footer', 'label' => __( 'Copyright (usar {year})', 'chuquipiondo' ), 'sanitize_callback' => 'chuquipiondo_sanitize_text', 'priority' => 7 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'footer_bg', array( 'section' => 'chuquipiondo_footer', 'label' => __( 'Color de fondo', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 8 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'footer_text', array( 'section' => 'chuquipiondo_footer', 'label' => __( 'Color de texto', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 9 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'footer_show_social', array( 'section' => 'chuquipiondo_footer', 'label' => __( 'Mostrar redes sociales', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 10 ) );
}

/* ===================================================================== *
 * MUSIC section.
 * ===================================================================== */

function chuquipiondo_register_music( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_music', array(
		'title'    => __( 'CHUQUIPIONDO: Musica', 'chuquipiondo' ),
		'priority' => 38,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'music_mini_player', array( 'section' => 'chuquipiondo_music', 'label' => __( 'Mini player sticky', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 5 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'music_downloads_global', array( 'section' => 'chuquipiondo_music', 'label' => __( 'Permitir descargas (global)', 'chuquipiondo' ), 'type' => 'checkbox', 'sanitize_callback' => 'chuquipiondo_sanitize_checkbox', 'priority' => 6 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'music_player_color', array( 'section' => 'chuquipiondo_music', 'label' => __( 'Color del reproductor', 'chuquipiondo' ), 'type' => 'color', 'sanitize_callback' => 'chuquipiondo_sanitize_hex_color', 'priority' => 7 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'music_archive_columns', array( 'section' => 'chuquipiondo_music', 'label' => __( 'Columnas en archivo', 'chuquipiondo' ), 'type' => 'select', 'choices' => array( '1' => '1', '2' => '2', '3' => '3' ), 'sanitize_callback' => 'chuquipiondo_sanitize_select', 'priority' => 8 ) );
}

/* ===================================================================== *
 * CUSTOM CODE section.
 * ===================================================================== */

function chuquipiondo_register_custom_code( $wp_customize ) {
	chuquipiondo_add_section( $wp_customize, 'chuquipiondo_code', array(
		'title'    => __( 'CHUQUIPIONDO: Codigo personalizado', 'chuquipiondo' ),
		'priority' => 39,
	) );

	chuquipiondo_add_setting_control( $wp_customize, 'custom_css', array( 'section' => 'chuquipiondo_code', 'label' => __( 'CSS personalizado', 'chuquipiondo' ), 'type' => 'textarea', 'sanitize_callback' => 'chuquipiondo_sanitize_css', 'priority' => 5 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'custom_head', array( 'section' => 'chuquipiondo_code', 'label' => __( 'Codigo en <head>', 'chuquipiondo' ), 'type' => 'textarea', 'sanitize_callback' => 'chuquipiondo_sanitize_html', 'priority' => 6 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'custom_body', array( 'section' => 'chuquipiondo_code', 'label' => __( 'Codigo despues de <body>', 'chuquipiondo' ), 'type' => 'textarea', 'sanitize_callback' => 'chuquipiondo_sanitize_html', 'priority' => 7 ) );
	chuquipiondo_add_setting_control( $wp_customize, 'custom_footer', array( 'section' => 'chuquipiondo_code', 'label' => __( 'Codigo antes de </body>', 'chuquipiondo' ), 'type' => 'textarea', 'sanitize_callback' => 'chuquipiondo_sanitize_html', 'priority' => 8 ) );
}
