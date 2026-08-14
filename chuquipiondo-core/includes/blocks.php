<?php
/**
 * Custom Gutenberg blocks for the CHUQUIPIONDO Core plugin.
 *
 * @package CHUQUIPIONDO_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom blocks.
 */
function chuquipiondo_core_register_blocks() {
	// Block: CHUQUIPIONDO Button.
	register_block_type( 'chuquipiondo/button', array(
		'render_callback' => 'chuquipiondo_core_block_button_render',
		'attributes'      => array(
			'text'     => array( 'type' => 'string', 'default' => 'Boton' ),
			'url'       => array( 'type' => 'string', 'default' => '#' ),
			'icon'      => array( 'type' => 'string', 'default' => '' ),
			'position'  => array( 'type' => 'string', 'default' => 'after' ),
			'className' => array( 'type' => 'string', 'default' => '' ),
		),
	) );

	// Block: CHUQUIPIONDO Posts Grid.
	register_block_type( 'chuquipiondo/posts', array(
		'render_callback' => 'chuquipiondo_core_block_posts_render',
		'attributes'      => array(
			'count'    => array( 'type' => 'number', 'default' => 6 ),
			'columns'  => array( 'type' => 'number', 'default' => 3 ),
			'category' => array( 'type' => 'string', 'default' => '' ),
			'style'    => array( 'type' => 'string', 'default' => 'editorial' ),
		),
	) );

	// Block: CHUQUIPIONDO Music Grid.
	register_block_type( 'chuquipiondo/music', array(
		'render_callback' => 'chuquipiondo_core_block_music_render',
		'attributes'      => array(
			'count'   => array( 'type' => 'number', 'default' => 4 ),
			'columns' => array( 'type' => 'number', 'default' => 2 ),
		),
	) );

	// Block: CHUQUIPIONDO Categories.
	register_block_type( 'chuquipiondo/categories', array(
		'render_callback' => 'chuquipiondo_core_block_categories_render',
		'attributes'      => array(
			'count' => array( 'type' => 'number', 'default' => 6 ),
		),
	) );

	// Block: CHUQUIPIONDO Ad Slot.
	register_block_type( 'chuquipiondo/ad', array(
		'render_callback' => 'chuquipiondo_core_block_ad_render',
		'attributes'      => array(
			'slot' => array( 'type' => 'string', 'default' => '' ),
		),
	) );
}
add_action( 'init', 'chuquipiondo_core_register_blocks' );

/**
 * Render callback: Button block.
 */
function chuquipiondo_core_block_button_render( $attributes ) {
	return chuquipiondo_core_button_shortcode( array(
		'text'     => isset( $attributes['text'] ) ? $attributes['text'] : 'Boton',
		'url'       => isset( $attributes['url'] ) ? $attributes['url'] : '#',
		'icon'      => isset( $attributes['icon'] ) ? $attributes['icon'] : '',
		'position'  => isset( $attributes['position'] ) ? $attributes['position'] : 'after',
		'class'     => 'btn ' . ( isset( $attributes['className'] ) ? $attributes['className'] : '' ),
	) );
}

/**
 * Render callback: Posts grid block.
 */
function chuquipiondo_core_block_posts_render( $attributes ) {
	return chuquipiondo_core_posts_shortcode( array(
		'count'    => isset( $attributes['count'] ) ? $attributes['count'] : 6,
		'columns'  => isset( $attributes['columns'] ) ? $attributes['columns'] : 3,
		'category' => isset( $attributes['category'] ) ? $attributes['category'] : '',
		'style'    => isset( $attributes['style'] ) ? $attributes['style'] : 'editorial',
	) );
}

/**
 * Render callback: Music grid block.
 */
function chuquipiondo_core_block_music_render( $attributes ) {
	return chuquipiondo_core_music_shortcode( array(
		'count'   => isset( $attributes['count'] ) ? $attributes['count'] : 4,
		'columns' => isset( $attributes['columns'] ) ? $attributes['columns'] : 2,
	) );
}

/**
 * Render callback: Categories block.
 */
function chuquipiondo_core_block_categories_render( $attributes ) {
	return chuquipiondo_core_categories_shortcode( array(
		'count' => isset( $attributes['count'] ) ? $attributes['count'] : 6,
	) );
}

/**
 * Render callback: Ad slot block.
 */
function chuquipiondo_core_block_ad_render( $attributes ) {
	return chuquipiondo_core_ad_shortcode( array(
		'slot' => isset( $attributes['slot'] ) ? $attributes['slot'] : '',
	) );
}
