<?php
/**
 * Helpers generales del tema.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Obtiene una opcion del customizer con su valor por defecto.
 *
 * @param string $key     Clave de la opcion.
 * @param mixed  $default Valor por defecto alternativo.
 * @return mixed
 */
function nechugo_get_option( $key, $default = null ) {
	$value = get_theme_mod( $key, null );
	if ( null === $value ) {
		$defaults = nechugo_defaults();
		if ( isset( $defaults[ $key ] ) ) {
			return $defaults[ $key ];
		}
	}
	if ( null === $value && null !== $default ) {
		return $default;
	}
	return $value;
}

/**
 * Comprueba si una opcion booleana esta activa.
 *
 * @param string $key Clave.
 * @return bool
 */
function nechugo_is_enabled( $key ) {
	return (bool) nechugo_get_option( $key );
}

/**
 * Clases body segun configuracion del personalizador.
 *
 * @param array $classes Clases existentes.
 * @return array
 */
function nechugo_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'nechugo-blog-grid';
	}
	if ( nechugo_is_elementor_page() ) {
		$classes[] = 'nechugo-elementor-page';
	}
	$classes[] = 'nechugo-header-' . nechugo_get_option( 'header_layout' );
	$classes[] = 'nechugo-footer-' . nechugo_get_option( 'footer_layout' );

	return $classes;
}
add_filter( 'body_class', 'nechugo_body_classes' );

/**
 * Detecta si la pagina actual fue construida con Elementor.
 *
 * @return bool
 */
function nechugo_is_elementor_page() {
	if ( ! class_exists( '\Elementor\Plugin' ) || ! is_singular() ) {
		return false;
	}
	$plugin = \Elementor\Plugin::instance();
	if ( ! isset( $plugin->documents ) || ! $plugin->documents ) {
		return false;
	}
	$document = $plugin->documents->get( get_the_ID() );
	return $document && $document->is_built_with_elementor();
}

/**
 * Decide si la barra lateral debe mostrarse en el contexto actual,
 * segun los toggles del personalizador y el ancho configurado.
 *
 * @return bool
 */
function nechugo_show_sidebar() {
	if ( 'none' === nechugo_get_option( 'sidebar_position' ) ) {
		return false;
	}
	if ( is_front_page() && is_home() ) {
		return nechugo_is_enabled( 'sidebar_on_blog' );
	}
	if ( is_home() ) {
		return nechugo_is_enabled( 'sidebar_on_blog' );
	}
	if ( is_singular( 'post' ) ) {
		return nechugo_is_enabled( 'sidebar_on_posts' );
	}
	if ( is_page() ) {
		return nechugo_is_enabled( 'sidebar_on_pages' );
	}
	if ( is_search() ) {
		return nechugo_is_enabled( 'sidebar_on_search' );
	}
	if ( is_archive() ) {
		return nechugo_is_enabled( 'sidebar_on_archive' );
	}
	return true;
}

/**
 * Clases del listado de entradas: layout + numero de columnas (1-4).
 *
 * @return string
 */
function nechugo_posts_classes() {
	$layout  = nechugo_get_option( 'blog_layout' );
	$columns = max( 1, min( 4, (int) nechugo_get_option( 'blog_columns' ) ) );
	return 'nechugo-posts nechugo-posts--' . sanitize_html_class( $layout ) . ' nechugo-posts--cols-' . $columns;
}

/**
 * Clases del layout principal: posicion del sidebar solo si este
 * se va a mostrar en el contexto actual.
 *
 * @return string
 */
function nechugo_layout_classes() {
	$classes = 'nechugo-layout';
	if ( nechugo_show_sidebar() ) {
		$classes .= ' nechugo-layout--sidebar-' . sanitize_html_class( nechugo_get_option( 'sidebar_position' ) );
	} else {
		$classes .= ' nechugo-layout--sidebar-none';
	}
	return $classes;
}
