<?php
/**
 * Music Custom Post Type.
 *
 * Slug /musica/, supports cover, audio, video, lyrics, platforms.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the `musica` CPT.
 */
function chuquipiondo_register_music_cpt() {
	$labels = array(
		'name'               => __( 'Canciones', 'chuquipiondo' ),
		'singular_name'      => __( 'Cancion', 'chuquipiondo' ),
		'add_new'            => __( 'Anadir nueva', 'chuquipiondo' ),
		'add_new_item'       => __( 'Anadir nueva cancion', 'chuquipiondo' ),
		'edit_item'          => __( 'Editar cancion', 'chuquipiondo' ),
		'new_item'           => __( 'Nueva cancion', 'chuquipiondo' ),
		'view_item'          => __( 'Ver cancion', 'chuquipiondo' ),
		'search_items'       => __( 'Buscar canciones', 'chuquipiondo' ),
		'not_found'          => __( 'No se encontraron canciones', 'chuquipiondo' ),
		'not_found_in_trash' => __( 'No hay canciones en la papelera', 'chuquipiondo' ),
		'all_items'          => __( 'Todas las canciones', 'chuquipiondo' ),
		'menu_name'          => __( 'Musica', 'chuquipiondo' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'has_archive'        => true,
		'rewrite'            => array( 'slug' => 'musica', 'with_front' => false ),
		'menu_icon'          => 'dashicons-format-audio',
		'menu_position'      => 6,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
		'show_in_rest'       => true, // Gutenberg / block editor.
		'show_in_menu'       => true,
	);

	register_post_type( 'musica', $args );

	// Music genre taxonomy.
	register_taxonomy( 'genero', 'musica', array(
		'labels'            => array(
			'name'          => __( 'Generos', 'chuquipiondo' ),
			'singular_name' => __( 'Genero', 'chuquipiondo' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'genero' ),
	) );
}
add_action( 'init', 'chuquipiondo_register_music_cpt' );

/**
 * Flush rewrite rules on theme activation/deactivation.
 */
function chuquipiondo_music_rewrite_flush() {
	chuquipiondo_register_music_cpt();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'chuquipiondo_music_rewrite_flush' );
