<?php
/**
 * The main template file (blog index / fallback).
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div id="content" class="site-content">
	<?php
	// On the front page, render the home builder (hero + modules).
	if ( is_front_page() ) {
		do_action( 'chuquipiondo_home' );
	} else {
		chuquipiondo_blog();
	}
	?>
</div>

<?php
get_footer();
