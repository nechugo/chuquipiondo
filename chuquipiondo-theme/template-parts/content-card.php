<?php
/**
 * Post card template-part dispatcher.
 *
 * The variant is selected by the $style argument (or the
 * blog_card_style option). Each preset lives in its own file:
 *  - content-card-minimal.php
 *  - content-card-editorial.php
 *  - content-card-elegant.php
 *  - content-card-magazine.php
 *  - content-card-image-focus.php
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$style = isset( $args['style'] ) ? $args['style'] : chuquipiondo_get_option( 'blog_card_style' );
$style = sanitize_html_class( $style );

$variants = array( 'minimal', 'editorial', 'elegant', 'magazine', 'image-focus' );
if ( ! in_array( $style, $variants, true ) ) {
	$style = 'editorial';
}

chuquipiondo_get_template_part( 'template-parts/content-card-' . $style, null, array( 'style' => $style ) );
