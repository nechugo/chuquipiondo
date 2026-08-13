<?php
/**
 * The single post template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div id="content" class="site-content">
	<?php chuquipiondo_single(); ?>
</div>

<?php
get_footer();
