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
	if ( nechugo_is_enabled( 'header_sticky' ) ) {
		$classes[] = 'nechugo-sticky';
	}
	$classes[] = 'nechugo-menu-align-' . nechugo_get_option( 'menu_align' );

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
 * Posicion del sidebar para el contexto actual (estilo Astra Pro):
 * cada contexto tiene su propia opcion; 'default' hereda la global.
 *
 * @return string right|left|none
 */
function nechugo_sidebar_position() {
	$map = array(
		'blog'    => 'sidebar_pos_blog',
		'archive' => 'sidebar_pos_archive',
		'post'    => 'sidebar_pos_posts',
		'page'    => 'sidebar_pos_pages',
		'search'  => 'sidebar_pos_search',
	);
	$context = 'archive';
	if ( ( is_front_page() && is_home() ) || is_home() ) {
		$context = 'blog';
	} elseif ( is_singular( 'post' ) ) {
		$context = 'post';
	} elseif ( is_page() ) {
		$context = 'page';
	} elseif ( is_search() ) {
		$context = 'search';
	}
	$pos = nechugo_get_option( $map[ $context ] );
	if ( 'default' === $pos || '' === $pos ) {
		$pos = nechugo_get_option( 'sidebar_position' );
	}
	return $pos;
}

/**
 * Decide si la barra lateral debe mostrarse en el contexto actual,
 * combinando los toggles de visibilidad y la posicion por contexto.
 *
 * @return bool
 */
function nechugo_show_sidebar() {
	if ( 'none' === nechugo_sidebar_position() ) {
		return false;
	}
	if ( ( is_front_page() && is_home() ) || is_home() ) {
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
 * Posicion efectiva del sidebar (la que se usa en las clases CSS).
 *
 * @return string
 */
function nechugo_sidebar_class_position() {
	return nechugo_show_sidebar() ? nechugo_sidebar_position() : 'none';
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
 * Clases del layout principal: posicion del sidebar efectiva del contexto.
 *
 * @return string
 */
function nechugo_layout_classes() {
	return 'nechugo-layout nechugo-layout--sidebar-' . sanitize_html_class( nechugo_sidebar_class_position() );
}
