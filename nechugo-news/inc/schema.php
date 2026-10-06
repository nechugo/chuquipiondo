<?php
/**
 * Schema.org para SEO compatible con AdSense.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema de articulo en el head.
 */
function nechugo_schema_output() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	$schema = array(
		'@context'       => 'https://schema.org',
		'@type'          => 'NewsArticle',
		'headline'       => get_the_title(),
		'datePublished'  => get_the_date( 'c' ),
		'dateModified'   => get_the_modified_date( 'c' ),
		'mainEntityOfPage' => get_permalink(),
		'author'         => array(
			'@type' => 'Person',
			'name'  => get_the_author(),
		),
		'publisher'      => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		),
	);
	if ( has_post_thumbnail() ) {
		$schema['image'] = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
add_action( 'wp_head', 'nechugo_schema_output', 20 );
