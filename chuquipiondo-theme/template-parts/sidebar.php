<?php
/**
 * The sidebar template-part.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<aside id="secondary" class="widget-area <?php echo esc_attr( chuquipiondo_get_layout_classes()['sidebar'] ); ?>" role="complementary">
	<?php
	chuquipiondo_ad_slot( 'ads_sidebar_top' );

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		dynamic_sidebar( 'sidebar-1' );
	}

	chuquipiondo_ad_slot( 'ads_sidebar_middle' );

	// If no widgets, show a friendly placeholder.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		echo '<section class="widget widget-placeholder">';
		echo '<h2 class="widget-title">' . esc_html__( 'Barra lateral', 'chuquipiondo' ) . '</h2>';
		echo '<p>' . esc_html__( 'Anade widgets desde Apariencia > Widgets.', 'chuquipiondo' ) . '</p>';
		echo '</section>';
	}

	chuquipiondo_ad_slot( 'ads_sidebar_bottom' );
	?>
</aside>
