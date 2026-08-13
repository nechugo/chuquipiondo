<?php
/**
 * Theme setup: after_setup_theme hooks.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme support and features.
 */
function chuquipiondo_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'chuquipiondo', CHUQUIPIONDO_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Custom logo.
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	// Custom header (used by hero single image fallback).
	add_theme_support( 'custom-header', array(
		'width'         => 1920,
		'height'        => 560,
		'flex-height'   => true,
		'flex-width'    => true,
		'default-image' => '',
	) );

	// Editor styles (Gutenberg).
	add_editor_style( 'assets/css/_editor.css' );

	// Image sizes for the magazine layout.
	add_image_size( 'chuquipiondo-card', 640, 400, true );
	add_image_size( 'chuquipiondo-card-large', 960, 540, true );
	add_image_size( 'chuquipiondo-featured', 1280, 720, true );
	add_image_size( 'chuquipiondo-square', 600, 600, true );
	add_image_size( 'chuquipiondo-hero', 1920, 800, true );

	// Navigation menus.
	register_nav_menus( array(
		'primary'   => __( 'Menu principal', 'chuquipiondo' ),
		'topbar'    => __( 'Menu top bar', 'chuquipiondo' ),
		'footer'    => __( 'Menu pie de pagina', 'chuquipiondo' ),
		'mobile'    => __( 'Menu movil', 'chuquipiondo' ),
		'social'    => __( 'Menu redes sociales', 'chuquipiondo' ),
	) );

	// Content width.
	if ( ! isset( $content_width ) ) {
		$content_width = 800;
	}
}
add_action( 'after_setup_theme', 'chuquipiondo_setup' );

/**
 * Register image sizes for the media library choice list.
 *
 * @param array $sizes Sizes.
 * @return array
 */
function chuquipiondo_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'chuquipiondo-card'         => __( 'Tarjeta (640x400)', 'chuquipiondo' ),
		'chuquipiondo-card-large'  => __( 'Tarjeta grande (960x540)', 'chuquipiondo' ),
		'chuquipiondo-featured'     => __( 'Destacada (1280x720)', 'chuquipiondo' ),
		'chuquipiondo-square'       => __( 'Cuadrada (600x600)', 'chuquipiondo' ),
		'chuquipiondo-hero'         => __( 'Hero (1920x800)', 'chuquipiondo' ),
	) );
}
add_filter( 'image_size_names_choose', 'chuquipiondo_image_sizes' );

/**
 * Add a body class with the active preset and layout for scoped CSS.
 *
 * @param array $classes Body classes.
 * @return array
 */
function chuquipiondo_body_class( $classes ) {
	$classes[] = 'chuquipiondo-theme';
	$classes[] = 'preset-' . sanitize_html_class( chuquipiondo_get_option( 'preset' ) );

	if ( chuquipiondo_is_enabled( 'hero_enable' ) ) {
		$classes[] = 'has-hero';
	}
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Layout-aware class + view type for sidebar resolution.
	if ( is_singular( 'post' ) ) {
		$classes[] = 'single-layout-' . sanitize_html_class( chuquipiondo_get_option( 'single_layout' ) );
		$classes[] = 'view-single';
	} elseif ( is_page() ) {
		$classes[] = 'page-layout-' . sanitize_html_class( chuquipiondo_get_option( 'page_layout' ) );
		$classes[] = 'view-page';
	} elseif ( is_home() || is_archive() || is_search() ) {
		$classes[] = 'archive-layout';
		$classes[] = 'view-blog';
	}

	// Sidebar position class (enables per-view CSS targeting).
	$sidebar_pos = chuquipiondo_get_sidebar_position();
	$classes[] = 'sidebar-' . sanitize_html_class( $sidebar_pos );

	return $classes;
}
add_filter( 'body_class', 'chuquipiondo_body_class' );

/**
 * Register widget areas.
 */
function chuquipiondo_register_sidebars() {
	// Independent sidebars for each view (Astra-style): blog/archive, single, page.
	register_sidebar( array(
		'name'          => __( 'Sidebar: Blog / Archivo', 'chuquipiondo' ),
		'id'            => 'sidebar-blog',
		'description'   => __( 'Barra lateral del blog, archivos y busqueda. Independiente del single y la pagina.', 'chuquipiondo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Sidebar: Articulo', 'chuquipiondo' ),
		'id'            => 'sidebar-single',
		'description'   => __( 'Barra lateral del articulo individual. Independiente del blog y la pagina.', 'chuquipiondo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Sidebar: Pagina', 'chuquipiondo' ),
		'id'            => 'sidebar-page',
		'description'   => __( 'Barra lateral de las paginas individuales. Independiente del blog y el single.', 'chuquipiondo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	// Legacy sidebar kept for backwards compatibility (falls back if the specific one is empty).
	register_sidebar( array(
		'name'          => __( 'Sidebar: General (fallback)', 'chuquipiondo' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Barra lateral de respaldo. Se usa si el sidebar especifico de una vista esta vacio.', 'chuquipiondo' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	// Pre-header widget zone (right column).
	register_sidebar( array(
		'name'          => __( 'Pre-header (derecha)', 'chuquipiondo' ),
		'id'            => 'sidebar-preheader',
		'description'   => __( 'Caja derecha del pre-header (encima del header). Para reproductor de musica, widgets o HTML.', 'chuquipiondo' ),
		'before_widget' => '<div id="%1$s" class="preheader-widget widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="preheader-widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Pie de pagina', 'chuquipiondo' ),
		'id'            => 'sidebar-footer',
		'description'   => __( 'Widgets del pie de pagina.', 'chuquipiondo' ),
		'before_widget' => '<section id="%1$s" class="footer-widget widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="footer-widget-title">',
		'after_title'   => '</h3>',
	) );

	// Post End Extension Area widget zone.
	register_sidebar( array(
		'name'          => __( 'Post End Extension', 'chuquipiondo' ),
		'id'            => 'sidebar-post-end',
		'description'   => __( 'Area extensible despues del contenido del articulo y antes de los relacionados.', 'chuquipiondo' ),
		'before_widget' => '<section id="%1$s" class="post-end-widget widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="post-end-widget-title">',
		'after_title'   => '</h3>',
	) );

	// Multiuse header widget zone.
	register_sidebar( array(
		'name'          => __( 'Header multiuso', 'chuquipiondo' ),
		'id'            => 'sidebar-header-multiuse',
		'description'   => __( 'Cajas multiuso de la cabecera.', 'chuquipiondo' ),
		'before_widget' => '<div id="%1$s" class="header-widget widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="header-widget-title">',
		'after_title'   => '</h3>',
	) );

	// Home builder ad slots.
	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar( array(
			/* translators: %d: home ad slot number */
			'name'          => sprintf( __( 'Home - Publicidad %d', 'chuquipiondo' ), $i ),
			'id'            => 'sidebar-home-ads-' . $i,
			'description'   => __( 'Zona de publicidad entre modulos del home.', 'chuquipiondo' ),
			'before_widget' => '<div id="%1$s" class="home-ads-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="home-ads-title">',
			'after_title'   => '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'chuquipiondo_register_sidebars' );
