<?php
/**
 * Configuracion del tema (setup, soportes, menus, sidebars).
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Setup del tema.
 */
function nechugo_setup() {
	load_theme_textdomain( 'nechugo-news', NECHUGO_NEWS_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'custom-background', array( 'default-color' => 'ffffff' ) );

	// Imagenes responsivas adicionales.
	add_image_size( 'nechugo-card', 480, 300, true );
	add_image_size( 'nechugo-slider', 1200, 600, true );

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'nechugo-news' ),
			'topbar'  => __( 'Menu superior (topbar)', 'nechugo-news' ),
			'footer'  => __( 'Menu del pie de pagina', 'nechugo-news' ),
		)
	);

	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'nechugo_setup' );

/**
 * Ancho del contenido (1200px minimo configurable).
 */
function nechugo_content_width() {
	$width = (int) nechugo_get_option( 'content_width' );
	if ( $width < 1200 ) {
		$width = 1200;
	}
	$GLOBALS['content_width'] = $width;
}
add_action( 'after_setup_theme', 'nechugo_content_width', 0 );

/**
 * Registro de sidebars.
 */
function nechugo_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Barra lateral principal', 'nechugo-news' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Aparece junto al contenido del blog.', 'nechugo-news' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Pie de pagina 1', 'nechugo-news' ),
			'id'            => 'footer-1',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Pie de pagina 2', 'nechugo-news' ),
			'id'            => 'footer-2',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'Pie de pagina 3', 'nechugo-news' ),
			'id'            => 'footer-3',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'nechugo_widgets_init' );
