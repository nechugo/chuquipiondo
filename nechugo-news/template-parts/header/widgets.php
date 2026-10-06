<?php
/**
 * Zona de 4 widgets bajo el encabezado (solo muestra las zonas con widgets).
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$zones = array();
for ( $i = 1; $i <= 4; $i++ ) {
	if ( is_active_sidebar( 'header-widgets-' . $i ) ) {
		$zones[] = $i;
	}
}
if ( empty( $zones ) ) {
	return;
}
?>
<div class="nechugo-header-widgets">
	<div class="nechugo-container header-widgets__grid header-widgets__grid--<?php echo esc_attr( count( $zones ) ); ?>">
		<?php foreach ( $zones as $i ) : ?>
			<div class="header-widgets__zone">
				<?php dynamic_sidebar( 'header-widgets-' . $i ); ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
