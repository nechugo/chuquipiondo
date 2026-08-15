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
			<?php
			// Construir la lista de elementos visibles del topbar (hora, fecha, email).
			$topbar_items = array();
			if ( chuquipiondo_is_enabled( 'header_topbar_time' ) ) {
				$topbar_items[] = '<span class="topbar-time">' . esc_html( wp_date( 'g:i:s A' ) ) . '</span>';
			}
			if ( chuquipiondo_is_enabled( 'header_topbar_date' ) ) {
				$topbar_items[] = '<span class="topbar-date">' . esc_html( wp_date( 'l, j F Y' ) ) . '</span>';
			}
			$email = chuquipiondo_get_option( 'header_topbar_email' );
			if ( $email ) {
				$topbar_items[] = '<a class="topbar-email" href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
			}
			// Imprimir los elementos separados por una barra |.
			$sep = '<span class="topbar-sep" aria-hidden="true">|</span>';
			echo wp_kses_post( implode( ' ' . $sep . ' ', $topbar_items ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above.
			?>
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
