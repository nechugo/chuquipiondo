<?php
/**
 * Admin utilities for the CHUQUIPIONDO Companion plugin.
 *
 * Activation notices and helpers shared across the admin UI.
 * The main settings page lives in settings.php.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Show a one-time notice after activation pointing to the settings page.
 */
function chuquipiondo_companion_activation_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( '1' !== get_transient( 'chuquipiondo_companion_just_activated' ) ) {
		return;
	}
	delete_transient( 'chuquipiondo_companion_just_activated' );
	?>
	<div class="notice notice-success is-dismissible">
		<p>
			<?php esc_html_e( 'CHUQUIPIONDO Companion activado.', 'chuquipiondo-companion' ); ?>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=chuquipiondo-companion' ) ); ?>">
				<?php esc_html_e( 'Configurar modulos', 'chuquipiondo-companion' ); ?>
			</a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'chuquipiondo_companion_activation_notice' );
