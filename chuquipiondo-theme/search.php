<?php
/**
 * The search results template.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div id="content" class="site-content">
	<?php chuquipiondo_blog(); ?>
</div>

<?php
get_footer();
