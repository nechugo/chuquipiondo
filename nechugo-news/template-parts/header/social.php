<?php
/**
 * Iconos sociales (enlaces configurables desde el personalizador).
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$keys = array( 'social_facebook', 'social_twitter', 'social_instagram', 'social_youtube' );
?>
<div class="nechugo-social">
	<?php foreach ( $keys as $key ) : ?>
		<?php $url = nechugo_get_option( $key ); ?>
		<?php if ( ! empty( $url ) ) : ?>
			<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="nechugo-social__link nechugo-social__link--<?php echo esc_attr( str_replace( 'social_', '', $key ) ); ?>">
				<?php echo esc_html( ucfirst( str_replace( 'social_', '', $key ) ) ); ?>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
