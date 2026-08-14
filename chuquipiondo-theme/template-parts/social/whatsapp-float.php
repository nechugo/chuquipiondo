<?php
/**
 * WhatsApp floating button template-part.
 *
 * @package CHUQUIPONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$position = chuquipiondo_get_option( 'whatsapp_position' );
$link     = chuquipiondo_whatsapp_link();
?>
<a href="<?php echo esc_url( $link ); ?>"
	class="chuqui-whatsapp chuqui-whatsapp--<?php echo esc_attr( sanitize_html_class( $position ) ); ?>"
	target="_blank" rel="noopener noreferrer"
	aria-label="<?php esc_attr_e( 'Escribir por WhatsApp', 'chuquipiondo' ); ?>">
	<span class="chuqui-whatsapp__icon">
		<?php chuquipiondo_social_icon( 'whatsapp' ); ?>
	</span>
</a>
