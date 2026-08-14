<?php
/**
 * The archive template (category, tag, author, date, custom tax).
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
