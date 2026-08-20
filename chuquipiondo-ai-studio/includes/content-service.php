<?php
/**
 * Content service: helpers to read/scan/edit post & page content.
 *
 * @package CHUQUIPIONDO_AI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function chuquipiondo_ai_allowed_html() {
	global $allowedposttags;
	$allowed = is_array( $allowedposttags ) ? $allowedposttags : array();
	$allowed['script'] = array( 'type' => true, 'src' => true, 'async' => true, 'defer' => true, 'class' => true, 'id' => true, 'language' => true );
	$allowed['style'] = array( 'type' => true, 'class' => true, 'id' => true, 'media' => true );
	$allowed['iframe'] = array( 'src' => true, 'width' => true, 'height' => true, 'frameborder' => true, 'allow' => true, 'allowfullscreen' => true, 'title' => true, 'class' => true, 'loading' => true );
	$allowed['source'] = array( 'src' => true, 'type' => true, 'media' => true, 'srcset' => true, 'sizes' => true );
	$allowed['video'] = array_merge( isset( $allowed['video'] ) ? $allowed['video'] : array(), array( 'controls' => true, 'autoplay' => true, 'loop' => true, 'muted' => true, 'preload' => true, 'poster' => true ) );
	$allowed['audio'] = array_merge( isset( $allowed['audio'] ) ? $allowed['audio'] : array(), array( 'controls' => true, 'autoplay' => true, 'loop' => true, 'muted' => true, 'preload' => true ) );
	$allowed['img'] = array_merge( isset( $allowed['img'] ) ? $allowed['img'] : array(), array( 'width' => true, 'height' => true, 'loading' => true, 'srcset' => true, 'sizes' => true ) );
	return $allowed;
}

function chuquipiondo_ai_sanitize_content( $content ) {
	$content = (string) $content;
	if ( ! chuquipiondo_ai_is_enabled( 'ai_allowed_html' ) || ! current_user_can( 'unfiltered_html' ) ) {
		return wp_kses_post( $content );
	}

	$php_blocks = array();
	if ( preg_match_all( '/<\?php.*?\?>/s', $content, $matches ) ) {
		foreach ( $matches[0] as $index => $block ) {
			$php_blocks[ $index ] = $block;
			$content = str_replace( $block, '%%AI_PHP_' . $index . '%%', $content );
		}
	}

	$content = wp_kses( $content, chuquipiondo_ai_allowed_html() );
	foreach ( $php_blocks as $index => $block ) {
		$content = str_replace( '%%AI_PHP_' . $index . '%%', $block, $content );
	}
	return $content;
}

function chuquipiondo_ai_count_images_in_content( $content ) {
	$content = (string) $content;
	$count = preg_match_all( '/<img[\s>]/i', $content );
	return false === $count ? 0 : (int) $count;
}

function chuquipiondo_ai_extract_images( $content ) {
	$content = (string) $content;
	$items = array();
	if ( ! preg_match_all( '/<img\b[^>]*>/i', $content, $matches ) ) {
		return $items;
	}
	foreach ( $matches[0] as $idx => $tag ) {
		$src = '';
		if ( preg_match( '/src=(["\'])(.*?)\1/i', $tag, $s ) ) {
			$src = $s[2];
		}
		$width = 0;
		$height = 0;
		if ( preg_match( '/width=(["\'])(\d+)\1/i', $tag, $w ) ) {
			$width = (int) $w[2];
		}
		if ( preg_match( '/height=(["\'])(\d+)\1/i', $tag, $h ) ) {
			$height = (int) $h[2];
		}
		$alt = '';
		if ( preg_match( '/alt=(["\'])(.*?)\1/i', $tag, $a ) ) {
			$alt = $a[2];
		}
		$items[] = array(
			'index' => $idx,
			'src' => $src,
			'width' => $width,
			'height' => $height,
			'alt' => $alt,
			'has_size' => ( $width > 0 && $height > 0 ),
			'tag' => $tag,
		);
	}
	return $items;
}

function chuquipiondo_ai_analyze_post_images( $post ) {
	if ( ! $post ) {
		return array();
	}
	$content = (string) $post->post_content;
	$images = chuquipiondo_ai_extract_images( $content );
	$target_w = (int) chuquipiondo_ai_get_int_option( 'ai_image_width', 1, 5000 );
	$target_h = (int) chuquipiondo_ai_get_int_option( 'ai_image_height', 1, 5000 );
	$first = ! empty( $images ) ? $images[0] : null;
	$rest = count( $images ) > 1 ? array_slice( $images, 1 ) : array();
	$paragraphs = substr_count( $content, '<p' );
	$sections = preg_match_all( '/<h[23]\b/i', $content );
	$recommended_slots = max( 0, (int) ceil( max( $paragraphs, $sections ) / 3 ) );
	return array(
		'total' => count( $images ),
		'first_image' => $first,
		'additional_images' => $rest,
		'target_width' => $target_w,
		'target_height' => $target_h,
		'needs_resize' => array_values( array_filter( $images, function ( $img ) use ( $target_w, $target_h ) {
			return $img['has_size'] && ( $img['width'] !== $target_w || $img['height'] !== $target_h );
		} ) ),
		'recommended_extra' => $recommended_slots,
		'featured_id' => (int) get_post_thumbnail_id( $post->ID ),
	);
}

function chuquipiondo_ai_get_post_terms( $post_id, $tax ) {
	$terms = get_the_terms( $post_id, $tax );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}
	$out = array();
	foreach ( $terms as $term ) {
		$out[] = array( 'id' => (int) $term->term_id, 'name' => $term->name, 'slug' => $term->slug );
	}
	return $out;
}

function chuquipiondo_ai_get_meta_description( $post_id ) {
	$keys = array( '_yoast_wpseo_metadesc', 'rank_math_description', 'rank_math_snippet_description', '_aioseo_description', 'seopress_titles_desc' );
	foreach ( $keys as $key ) {
		$value = get_post_meta( $post_id, $key, true );
		if ( ! empty( $value ) ) {
			return (string) $value;
		}
	}
	return (string) get_post_meta( $post_id, '_chuquipiondo_ai_metadesc', true );
}

function chuquipiondo_ai_set_meta_description( $post_id, $desc ) {
	$keys = array( '_yoast_wpseo_metadesc', 'rank_math_description', 'rank_math_snippet_description', '_aioseo_description', 'seopress_titles_desc' );
	foreach ( $keys as $key ) {
		update_post_meta( $post_id, $key, $desc );
	}
	update_post_meta( $post_id, '_chuquipiondo_ai_metadesc', $desc );
}
