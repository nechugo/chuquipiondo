<?php
/**
 * CHUQUIPIONDO Welcome / Dashboard.
 *
 * A professional welcome screen with quick links, system status,
 * presets, and documentation. Shown after theme activation.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the welcome page.
 */
function chuquipiondo_welcome_menu() {
	add_menu_page(
		__( 'CHUQUIPIONDO', 'chuquipiondo' ),
		__( 'CHUQUIPIONDO', 'chuquipiondo' ),
		'manage_options',
		'chuquipiondo-welcome',
		'chuquipiondo_welcome_render',
		'dashicons-admin-customizer',
		3
	);
}
add_action( 'admin_menu', 'chuquipiondo_welcome_menu' );

/**
 * Redirect to welcome page after theme activation.
 */
function chuquipiondo_welcome_redirect() {
	if ( is_admin() && ! is_network_admin() ) {
		if ( ! get_transient( 'chuquipiondo_welcome_redirect' ) ) {
			return;
		}
		delete_transient( 'chuquipiondo_welcome_redirect' );
		wp_safe_redirect( admin_url( 'admin.php?page=chuquipiondo-welcome' ) );
		exit;
	}
}
add_action( 'admin_init', 'chuquipiondo_welcome_redirect' );

/**
 * Set transient on theme activation.
 */
function chuquipiondo_set_welcome_transient() {
	set_transient( 'chuquipiondo_welcome_redirect', 1, 30 );
	// Marcar como primera activacion si la opcion no existe (instalacion nueva).
	if ( ! get_option( 'chuquipiondo_theme_activated' ) ) {
		update_option( 'chuquipiondo_theme_activated', 'first', false );
	} else {
		// Ya hubo activaciones anteriores: marcar como reactivacion.
		update_option( 'chuquipiondo_theme_activated', 'reactivated', false );
	}
}
add_action( 'after_switch_theme', 'chuquipiondo_set_welcome_transient' );

/**
 * Enqueue welcome page assets.
 */
function chuquipiondo_welcome_assets( $hook ) {
	if ( 'toplevel_page_chuquipiondo-welcome' !== $hook ) {
		return;
	}
	wp_enqueue_style(
		'chuquipiondo-welcome',
		CHUQUIPONDO_URI . '/assets/css/admin.css',
		array(),
		chuquipiondo_asset_version( 'assets/css/admin.css' )
	);
}
add_action( 'admin_enqueue_scripts', 'chuquipiondo_welcome_assets' );

/**
 * Render the welcome page.
 */
function chuquipiondo_welcome_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$theme         = wp_get_theme();
	$option_count   = count( chuquipiondo_defaults() );
	$font_count     = count( chuquipiondo_fonts() );
	$icon_count     = count( chuquipiondo_button_icons() );
	$preset_count   = count( chuquipiondo_presets() );
	$ad_slot_count  = count( chuquipiondo_ad_slots() );
	?>
	<div class="wrap chuquipiondo-welcome">
		<div class="chuquipiondo-welcome__hero">
			<h1>CHUQUIPIONDO</h1>
			<p class="chuquipiondo-welcome__tagline"><?php esc_html_e( 'Liderazgo, Gestion y Formacion con proposito.', 'chuquipiondo' ); ?></p>
			<p class="chuquipiondo-welcome__version">v<?php echo esc_html( CHUQUIPIONDO_VERSION ); ?> · <?php esc_html_e( 'por Nelson Chuquipiondo', 'chuquipiondo' ); ?></p>
		</div>

		<?php
		// Asistente de configuracion: detecta primera instalacion vs reactivacion.
		$activation_state = get_option( 'chuquipiondo_theme_activated', 'first' );
		$setup_done = get_option( 'chuquipiondo_setup_done', false );
		if ( ! $setup_done ) :
			$is_first = ( 'first' === $activation_state );
			?>
			<div class="chuquipiondo-welcome__setup notice notice-info">
				<h2><span class="dashicons dashicons-welcome-add-page"></span>
					<?php
					if ( $is_first ) {
						esc_html_e( 'Bienvenido a CHUQUIPIONDO', 'chuquipiondo' );
					} else {
						esc_html_e( 'CHUQUIPIONDO reactivado', 'chuquipiondo' );
					}
					?>
				</h2>
				<p>
					<?php
					if ( $is_first ) {
						esc_html_e( 'Esta es la primera vez que activas el tema. Elige como quieres comenzar: instalar la demo completa con contenido de ejemplo, o solo la estructura del tema (sin contenido).', 'chuquipiondo' );
					} else {
						esc_html_e( 'Detectamos que ya habias activado CHUQUIPIONDO antes. Puedes importar la demo completa, o adaptar tu contenido existente a la arquitectura del tema (paginas, blog, articulos, menus y barras se reorganizaran automaticamente).', 'chuquipiondo' );
					}
					?>
				</p>
				<div class="chuquipiondo-welcome__setup-options">
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;max-width:48%;vertical-align:top;margin-right:1%;">
						<input type="hidden" name="action" value="chuquipiondo_setup_wizard">
						<input type="hidden" name="chuquipiondo_setup_mode" value="full_demo">
						<?php wp_nonce_field( 'chuquipiondo_setup_wizard', 'chuquipiondo_nonce' ); ?>
						<h3><?php esc_html_e( 'Instalar Demo Completa', 'chuquipiondo' ); ?></h3>
						<p><?php esc_html_e( 'Importa contenido de ejemplo (articulos con imagenes, paginas, musica), ads ficticios y configura todo el tema automaticamente. Ideal para empezar desde cero.', 'chuquipiondo' ); ?></p>
						<button type="submit" class="button button-primary button-large">
							<?php esc_html_e( 'Importar demo y estructura', 'chuquipiondo' ); ?>
						</button>
					</form>
					<?php if ( ! $is_first ) : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;max-width:48%;vertical-align:top;">
						<input type="hidden" name="action" value="chuquipiondo_setup_wizard">
						<input type="hidden" name="chuquipiondo_setup_mode" value="adapt">
						<?php wp_nonce_field( 'chuquipiondo_setup_wizard', 'chuquipiondo_nonce' ); ?>
						<h3><?php esc_html_e( 'Adaptar Contenido Existente', 'chuquipiondo' ); ?></h3>
						<p><?php esc_html_e( 'Reorganiza y adapta tu contenido actual (paginas, blog, articulos, menus, barras laterales) a la arquitectura del tema. No crea contenido nuevo, solo reestructura el existente.', 'chuquipiondo' ); ?></p>
						<button type="submit" class="button button-large">
							<?php esc_html_e( 'Solo adaptar estructura del tema', 'chuquipiondo' ); ?>
						</button>
					</form>
				<?php else : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;max-width:48%;vertical-align:top;">
						<input type="hidden" name="action" value="chuquipiondo_setup_wizard">
						<input type="hidden" name="chuquipiondo_setup_mode" value="structure_only">
						<?php wp_nonce_field( 'chuquipiondo_setup_wizard', 'chuquipiondo_nonce' ); ?>
						<h3><?php esc_html_e( 'Solo Estructura', 'chuquipiondo' ); ?></h3>
						<p><?php esc_html_e( 'Configura el tema (presets, menus, widgets, opciones) sin crear ningun contenido. Para cuando ya tienes tu propio contenido.', 'chuquipiondo' ); ?></p>
						<button type="submit" class="button button-large">
							<?php esc_html_e( 'Solo estructura de plantilla', 'chuquipiondo' ); ?>
						</button>
					</form>
				<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="chuquipiondo-welcome__grid">

			<!-- Quick Start -->
			<div class="chuquipiondo-welcome__card chuquipiondo-welcome__card--primary">
				<h2><span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e( 'Configuracion rapida', 'chuquipiondo' ); ?></h2>
				<p><?php esc_html_e( 'Personaliza todo el tema desde el Customizer.', 'chuquipiondo' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary button-large">
					<?php esc_html_e( 'Abrir Personalizador', 'chuquipiondo' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=chuquipiondo-options' ) ); ?>" class="button button-large">
					<?php esc_html_e( 'Opciones avanzadas', 'chuquipiondo' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=chuquipiondo-demo' ) ); ?>" class="button button-large">
					<?php esc_html_e( 'Importar demo', 'chuquipiondo' ); ?>
				</a>
			</div>

			<!-- System Status -->
			<div class="chuquipiondo-welcome__card">
				<h2><span class="dashicons dashicons-info"></span> <?php esc_html_e( 'Estado del sistema', 'chuquipiondo' ); ?></h2>
				<table class="chuquipiondo-welcome__status">
					<tr>
						<td><?php esc_html_e( 'Version del tema', 'chuquipiondo' ); ?></td>
						<td><strong>v<?php echo esc_html( CHUQUIPIONDO_VERSION ); ?></strong></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'WordPress', 'chuquipiondo' ); ?></td>
						<td><strong>v<?php echo esc_html( get_bloginfo( 'version' ) ); ?></strong></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'PHP', 'chuquipiondo' ); ?></td>
						<td><strong>v<?php echo esc_html( PHP_VERSION ); ?></strong></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Opciones configurables', 'chuquipiondo' ); ?></td>
						<td><strong><?php echo esc_html( $option_count ); ?></strong></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Fuentes disponibles', 'chuquipiondo' ); ?></td>
						<td><strong><?php echo esc_html( $font_count ); ?></strong></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Iconos de boton', 'chuquipiondo' ); ?></td>
						<td><strong><?php echo esc_html( $icon_count ); ?></strong></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Presets de color', 'chuquipiondo' ); ?></td>
						<td><strong><?php echo esc_html( $preset_count ); ?></strong></td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'Slots de anuncios', 'chuquipiondo' ); ?></td>
						<td><strong><?php echo esc_html( $ad_slot_count ); ?></strong></td>
					</tr>
				</table>
			</div>

			<!-- Presets -->
			<div class="chuquipiondo-welcome__card">
				<h2><span class="dashicons dashicons-color-picker"></span> <?php esc_html_e( 'Presets de color', 'chuquipiondo' ); ?></h2>
				<p><?php esc_html_e( 'Aplica un preset predefinido con un clic.', 'chuquipiondo' ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<select name="chuquipiondo_preset" style="width:100%;margin-bottom:8px;">
						<?php foreach ( chuquipiondo_presets() as $key => $preset ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $preset['label'] ); ?></option>
						<?php endforeach; ?>
					</select>
					<input type="hidden" name="action" value="chuquipiondo_apply_preset">
					<?php wp_nonce_field( 'chuquipiondo_apply_preset', 'chuquipiondo_nonce' ); ?>
					<button type="submit" class="button"><?php esc_html_e( 'Aplicar preset', 'chuquipiondo' ); ?></button>
				</form>
			</div>

			<!-- Features -->
			<div class="chuquipiondo-welcome__card chuquipiondo-welcome__card--wide">
				<h2><span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Caracteristicas del tema', 'chuquipiondo' ); ?></h2>
				<div class="chuquipiondo-welcome__features">
					<div class="chuquipiondo-welcome__feature">
						<span class="dashicons dashicons-layout"></span>
						<h3><?php esc_html_e( 'Cabecera Pro', 'chuquipiondo' ); ?></h3>
						<p>3 filas + sticky + pre-header + multiuso</p>
					</div>
					<div class="chuquipiondo-welcome__feature">
						<span class="dashicons dashicons-images-alt2"></span>
						<h3><?php esc_html_e( 'Hero / Slider', 'chuquipiondo' ); ?></h3>
						<p>6 modos, 3 efectos, slides administrables</p>
					</div>
					<div class="chuquipiondo-welcome__feature">
						<span class="dashicons dashicons-grid-view"></span>
						<h3><?php esc_html_e( 'Home Builder', 'chuquipiondo' ); ?></h3>
						<p>8 modulos reordenables</p>
					</div>
					<div class="chuquipiondo-welcome__feature">
						<span class="dashicons dashicons-format-aside"></span>
						<h3><?php esc_html_e( 'Blog Editorial', 'chuquipiondo' ); ?></h3>
						<p>5 estilos de tarjeta, grid configurable</p>
					</div>
					<div class="chuquipiondo-welcome__feature">
						<span class="dashicons dashicons-money"></span>
						<h3><?php esc_html_e( '30+ Ad Slots', 'chuquipiondo' ); ?></h3>
						<p>Master switch, 5 modos, insercion inteligente</p>
					</div>
					<div class="chuquipiondo-welcome__feature">
						<span class="dashicons dashicons-format-audio"></span>
						<h3><?php esc_html_e( 'Musica', 'chuquipiondo' ); ?></h3>
						<p>CPT, reproductor HTML5, mini player</p>
					</div>
					<div class="chuquipiondo-welcome__feature">
						<span class="dashicons dashicons-shield"></span>
						<h3><?php esc_html_e( 'Seguridad', 'chuquipiondo' ); ?></h3>
						<p>Headers, SSL, antibloqueo de ads</p>
					</div>
					<div class="chuquipiondo-welcome__feature">
						<span class="dashicons dashicons-performance"></span>
						<h3><?php esc_html_e( 'Ultra veloz', 'chuquipiondo' ); ?></h3>
						<p>CSS inline, JS defer, preload</p>
					</div>
				</div>
			</div>

			<!-- Documentation -->
			<div class="chuquipiondo-welcome__card">
				<h2><span class="dashicons dashicons-book"></span> <?php esc_html_e( 'Documentacion', 'chuquipiondo' ); ?></h2>
				<ul class="chuquipiondo-welcome__docs">
					<li><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Personalizador', 'chuquipiondo' ); ?> &rarr;</a></li>
					<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=chuquipiondo-options' ) ); ?>"><?php esc_html_e( 'Opciones avanzadas', 'chuquipiondo' ); ?> &rarr;</a></li>
					<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=chuquipiondo-demo' ) ); ?>"><?php esc_html_e( 'Importar demo', 'chuquipiondo' ); ?> &rarr;</a></li>
					<li><a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>"><?php esc_html_e( 'Configurar menus', 'chuquipiondo' ); ?> &rarr;</a></li>
					<li><a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Configurar widgets', 'chuquipiondo' ); ?> &rarr;</a></li>
					<li><a href="https://www.chuquipiondo.com" target="_blank"><?php esc_html_e( 'Sitio web', 'chuquipiondo' ); ?> &rarr;</a></li>
				</ul>
			</div>

			<!-- Reset -->
			<div class="chuquipiondo-welcome__card chuquipiondo-welcome__card--danger">
				<h2><span class="dashicons dashicons-warning"></span> <?php esc_html_e( 'Restablecer tema', 'chuquipiondo' ); ?></h2>
				<p><?php esc_html_e( 'Vuelve todos los valores a los de fabrica. No afecta articulos ni contenido.', 'chuquipiondo' ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="chuquipiondo_reset">
					<?php wp_nonce_field( 'chuquipiondo_reset', 'chuquipiondo_nonce' ); ?>
					<button type="submit" class="button button-link-delete" onclick="return confirm('<?php esc_attr_e( 'Esto restablecera todos los valores. Continuar?', 'chuquipiondo' ); ?>')"><?php esc_html_e( 'Restablecer todo', 'chuquipiondo' ); ?></button>
				</form>
			</div>

		</div>
	</div>
	<?php
}
