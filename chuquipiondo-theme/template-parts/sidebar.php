<?php
/**
 * The sidebar template-part.
 *
 * Renders the sidebar for the current view. Each view has its own
 * independent sidebar (blog, single, page); if that specific
 * sidebar is empty, the fallback `sidebar-1` is used.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sidebar_id = chuquipiondo_get_sidebar_id();
$has_specific = $sidebar_id && is_active_sidebar( $sidebar_id );
$has_fallback = is_active_sidebar( 'sidebar-1' );

// If neither the specific nor the fallback sidebar has widgets, render nothing.
if ( ! $has_specific && ! $has_fallback ) {
	return;
}
?>
<aside id="secondary" class="widget-area <?php echo esc_attr( chuquipiondo_get_layout_classes()['sidebar'] ); ?>" role="complementary">
	<?php
	// Ad slot: top of sidebar.
	chuquipiondo_ad_slot( 'ads_sidebar_top' );

	// Render the specific sidebar for this view, or fall back to the legacy one.
	if ( $has_specific ) {
		dynamic_sidebar( $sidebar_id );
	} else {
		dynamic_sidebar( 'sidebar-1' );
	}

	// Ad slot: middle of sidebar.
	chuquipiondo_ad_slot( 'ads_sidebar_middle' );

	// If no widgets at all, show a friendly placeholder.
	if ( ! $has_specific && ! $has_fallback ) {
		echo '<section class="widget widget-placeholder">';
		echo '<h2 class="widget-title">' . esc_html__( 'Barra lateral', 'chuquipiondo' ) . '</h2>';
		echo '<p>' . esc_html__( 'Anade widgets desde Apariencia > Widgets.', 'chuquipiondo' ) . '</p>';
		echo '</section>';
	}

	// Ad slot: bottom of sidebar.
	chuquipiondo_ad_slot( 'ads_sidebar_bottom' );
	?>
</aside>
