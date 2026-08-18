<?php
/**
 * Settings page for the CHUQUIPIONDO Companion plugin.
 *
 * Centralized master switches for every module, plus a status panel.
 * Stored as options (not theme_mods) so it persists across theme changes.
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the companion admin menu.
 */
function chuquipiondo_companion_admin_menu() {
	add_menu_page(
		__( 'CHUQUIPIONDO Companion', 'chuquipiondo-companion' ),
		__( 'Companion', 'chuquipiondo-companion' ),
		'manage_options',
		'chuquipiondo-companion',
		'chuquipiondo_companion_settings_page_render',
		'dashicons-superhero',
		59
	);
}
add_action( 'admin_menu', 'chuquipiondo_companion_admin_menu' );

/**
 * Register companion settings.
 */
function chuquipiondo_companion_register_settings() {
	$option_keys = array_keys( chuquipiondo_companion_defaults() );
	foreach ( $option_keys as $key ) {
		register_setting( 'chuquipiondo_companion_group', $key, array(
			'type'              => 'string',
			'sanitize_callback' => 'chuquipiondo_companion_sanitize_option',
			'default'           => '',
		) );
	}
}
add_action( 'admin_init', 'chuquipiondo_companion_register_settings' );

/**
 * Sanitize a companion option (loose: accepts scalar or array).
 *
 * @param mixed $value Raw value.
 * @return mixed
 */
function chuquipiondo_companion_sanitize_option( $value ) {
	if ( is_array( $value ) ) {
		return array_map( 'sanitize_text_field', $value );
	}
	return sanitize_text_field( $value );
}

/**
 * Render the settings page.
 */
function chuquipiondo_companion_settings_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$theme_active = chuquipiondo_companion_is_theme_active();
	?>
	<div class="wrap chuquipiondo-companion-admin">
		<h1><?php esc_html_e( 'CHUQUIPIONDO Companion', 'chuquipiondo-companion' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Modulos premium del tema CHUQUIPIONDO. Activa o desactiva cada modulo segun lo necesites.', 'chuquipiondo-companion' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'chuquipiondo_companion_group' ); ?>

			<h2 class="title"><?php esc_html_e( 'Estado', 'chuquipiondo-companion' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><?php esc_html_e( 'Version del companion', 'chuquipiondo-companion' ); ?></th>
					<td>v<?php echo esc_html( CHUQUIPIONDO_COMPANION_VERSION ); ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Tema CHUQUIPIONDO', 'chuquipiondo-companion' ); ?></th>
					<td>
						<?php if ( $theme_active ) : ?>
							<span class="chuqui-badge chuqui-badge--ok"><?php esc_html_e( 'Activo', 'chuquipiondo-companion' ); ?></span>
						<?php else : ?>
							<span class="chuqui-badge chuqui-badge--warn"><?php esc_html_e( 'No activo (modulos limitados)', 'chuquipiondo-companion' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'Modulos', 'chuquipiondo-companion' ); ?></h2>
			<table class="form-table">
				<?php
				$modules = array(
					'companion_header_builder_enable' => __( 'Header Builder (cabecera avanzada)', 'chuquipiondo-companion' ),
					'companion_footer_builder_enable' => __( 'Footer Builder (pie de pagina avanzado)', 'chuquipiondo-companion' ),
					'companion_mega_menu_enable'      => __( 'Mega Menu (menus desplegables ricos)', 'chuquipiondo-companion' ),
					'companion_blog_pro_enable'       => __( 'Blog / Revista Pro (grid avanzado, timeline, relacionados)', 'chuquipiondo-companion' ),
					'companion_ads_pro_enable'        => __( 'Ads Pro (rotacion, A/B, analitica)', 'chuquipiondo-companion' ),
				);
				foreach ( $modules as $key => $label ) :
					?>
					<tr>
						<th><?php echo esc_html( $label ); ?></th>
						<td>
							<label>
								<input type="hidden" name="<?php echo esc_attr( $key ); ?>" value="0">
								<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="1" <?php checked( chuquipiondo_companion_is_enabled( $key ) ); ?>>
								<?php esc_html_e( 'Activar modulo', 'chuquipiondo-companion' ); ?>
							</label>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>

			<?php submit_button( __( 'Guardar cambios', 'chuquipiondo-companion' ) ); ?>
		</form>

		<?php do_action( 'chuquipiondo_companion_after_settings_form' ); ?>
	</div>
	<?php
}
