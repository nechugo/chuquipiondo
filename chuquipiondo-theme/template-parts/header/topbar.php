<?php
/**
 * Header 1: Top Bar.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="header-topbar">
	<div class="chuqui-container header-topbar__inner">
		<div class="header-topbar__left">
			<?php if ( chuquipiondo_is_enabled( 'header_topbar_date' ) ) : ?>
				<span class="topbar-date"><?php echo esc_html( wp_date( 'l, j F Y' ) ); ?></span>
			<?php endif; ?>
			<?php if ( chuquipiondo_is_enabled( 'header_topbar_time' ) ) : ?>
				<span class="topbar-time"><?php echo esc_html( wp_date( 'H:i' ) ); ?></span>
			<?php endif; ?>
			<?php
			$email = chuquipiondo_get_option( 'header_topbar_email' );
			if ( $email ) :
				?>
				<a class="topbar-email" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			<?php endif; ?>
		</div>

		<div class="header-topbar__right">
			<?php
			// Iconos sociales en el topbar (todos los que esten configurados).
			$profiles = array(
				'facebook'  => chuquipiondo_get_option( 'social_facebook' ),
				'x'         => chuquipiondo_get_option( 'social_x' ),
				'youtube'   => chuquipiondo_get_option( 'social_youtube' ),
				'instagram' => chuquipiondo_get_option( 'social_instagram' ),
				'linkedin'  => chuquipiondo_get_option( 'social_linkedin' ),
				'telegram'  => chuquipiondo_get_option( 'social_telegram' ),
				'tiktok'    => chuquipiondo_get_option( 'social_tiktok' ),
			);
			$has_any = false;
			foreach ( $profiles as $url ) {
				if ( ! empty( $url ) ) {
					$has_any = true;
					break;
				}
			}
			if ( $has_any ) :
			?>
				<div class="topbar-socials">
					<?php chuquipiondo_social_profiles_links(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
