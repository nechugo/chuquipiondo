<?php
/**
 * Schema.org JSON-LD structured data.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output schema markup for the current page.
 */
function chuquipiondo_schema() {
	if ( is_singular( 'post' ) ) {
		chuquipiondo_article_schema();
	} elseif ( is_singular( 'musica' ) ) {
		chuquipiondo_music_schema();
	} elseif ( is_front_page() || is_home() ) {
		chuquipiondo_person_schema();
	}
}
add_action( 'wp_head', 'chuquipiondo_schema', 20 );

/**
 * Article schema.
 */
function chuquipiondo_article_schema() {
	$schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title(),
		'datePublished'    => get_the_date( 'c' ),
		'dateModified'     => get_the_modified_date( 'c' ),
		'author'           => array(
			'@type' => 'Person',
			'name'  => get_the_author(),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		),
		'mainEntityOfPage' => array(
			'@type' => 'WebPage',
			'@id'   => get_permalink(),
		),
	);
	if ( has_post_thumbnail() ) {
		$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		if ( $img ) {
			$schema['image'] = array(
				'@type'  => 'ImageObject',
				'url'    => $img[0],
				'width'  => $img[1],
				'height' => $img[2],
			);
		}
	}

	/**
	 * Filters the article schema.
	 */
	$schema = apply_filters( 'chuquipiondo_article_schema', $schema );

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}

/**
 * Music recording schema.
 */
function chuquipiondo_music_schema() {
	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'        => 'MusicRecording',
		'name'         => get_the_title(),
		'byArtist'     => array(
			'@type' => 'Person',
			'name'  => get_post_meta( get_the_ID(), '_music_artist', true ) ? get_post_meta( get_the_ID(), '_music_artist', true ) : get_bloginfo( 'name' ),
		),
		'url'          => get_permalink(),
		'datePublished'=> get_the_date( 'c' ),
	);
	$audio = get_post_meta( get_the_ID(), '_music_audio_url', true );
	if ( $audio ) {
		$schema['audio'] = array(
			'@type' => 'AudioObject',
			'contentUrl' => $audio,
		);
	}

	$schema = apply_filters( 'chuquipiondo_music_schema', $schema );

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}

/**
 * Person / organization schema for the home page.
 */
function chuquipiondo_person_schema() {
	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'Person',
		'name'       => 'Nelson Chuquipiondo',
		'url'        => home_url( '/' ),
		'jobTitle'   => __( 'Liderazgo, Gestion y Formacion', 'chuquipiondo' ),
		'description'=> get_theme_mod( 'home_about_text', '' ),
		'knowsAbout' => array( 'Liderazgo', 'Gestion', 'Formacion', 'Fe cristiana', 'Musica' ),
	);
	if ( get_option( 'site_icon' ) ) {
		$schema['image'] = get_site_icon_url( 512 );
	}

	$schema = apply_filters( 'chuquipiondo_person_schema', $schema );

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
