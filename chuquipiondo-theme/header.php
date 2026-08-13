<?php
/**
 * The header template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Saltar al contenido', 'chuquipiondo' ); ?></a>

	<?php
	/**
	 * Header system (3 rows: top bar, main, multiuse).
	 */
	// Pre-header: 2 invisible columns above the header.
	chuquipiondo_preheader();

	do_action( 'chuquipiondo_header' );
	?>
