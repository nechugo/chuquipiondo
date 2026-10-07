<?php
/**
 * Iconos sociales SVG (sin dependencias, muy livianos) configurables
 * desde el personalizador. Solo muestran las redes con URL asignada.
 *
 * @package nechugo_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icons = array(
	'facebook'  => '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M13.5 9H16l.5-3h-3V4.5c0-.9.3-1.5 1.7-1.5H16V.2C15.4.1 14.4 0 13.3 0 10.9 0 9.5 1.5 9.5 4.2V6H7v3h2.5v9h4V9z"/></svg>',
	'twitter'   => '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M22 5.9c-.7.3-1.5.5-2.3.6.8-.5 1.5-1.3 1.8-2.2-.8.5-1.7.8-2.6 1a4.1 4.1 0 0 0-7 3.7A11.7 11.7 0 0 1 2.4 3a4.1 4.1 0 0 0 1.3 5.5c-.7 0-1.3-.2-1.9-.5v.1a4.1 4.1 0 0 0 3.3 4 4.2 4.2 0 0 1-1.9.1 4.1 4.1 0 0 0 3.8 2.8A8.2 8.2 0 0 1 1 16.9a11.6 11.6 0 0 0 6.3 1.8c7.5 0 11.7-6.3 11.7-11.7v-.5c.8-.6 1.5-1.3 2-2.1z"/></svg>',
	'instagram' => '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2.2c3.2 0 3.6 0 4.9.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 3.2-1.7 4.8-4.9 4.9-1.3.1-1.6.1-4.9.1s-3.6 0-4.8-.1c-3.3-.1-4.8-1.7-4.9-4.9C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8C2.4 4 4 2.4 7.2 2.3 8.4 2.2 8.8 2.2 12 2.2zm0 3.6a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4zm0 10.2a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.4-10.4a1.4 1.4 0 1 0 0 2.9 1.4 1.4 0 0 0 0-2.9z"/></svg>',
	'youtube'   => '<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4L15.8 12l-6.2 3.6z"/></svg>',
);

$keys = array( 'social_facebook', 'social_twitter', 'social_instagram', 'social_youtube' );
?>
<div class="nechugo-social">
	<?php foreach ( $keys as $key ) : ?>
		<?php $url = nechugo_get_option( $key ); ?>
		<?php $network = str_replace( 'social_', '', $key ); ?>
		<?php if ( ! empty( $url ) && isset( $icons[ $network ] ) ) : ?>
			<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="nechugo-social__link nechugo-social__link--<?php echo esc_attr( $network ); ?>" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
				<?php echo $icons[ $network ]; // phpcs:ignore -- SVG estatico del tema. ?>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
