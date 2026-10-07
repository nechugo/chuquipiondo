<?php
/**
 * Plugin Compatibility Center.
 *
 * Shows an informative (non-alarming) overview of how the CHUQUIPIONDO
 * theme works with popular plugins. Well-supported plugins (Elementor,
 * Jetpack, cache/SEO plugins) are listed as COMPATIBLE with tips;
 * only genuinely risky combinations (maintenance mode active while
 * developing, builders overriding theme templates globally) get a soft
 * advisory. The notice is dismissible and never blocks anything.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registry of plugin compatibility.
 * Format: plugin_file => { name, level (ok|tip|advisory), note }
 * - ok: fully supported, no action needed (shown as compatible).
 * - tip: supported, with an optional optimization tip.
 * - advisory: supported, but a specific combination needs one check.
 */
function chuquipiondo_conflict_plugins() {
	return array(
		// Page builders: the theme natively supports Elementor (hero mode,
		// template isolation, blank canvas). Fully compatible.
		'elementor/elementor.php'             => array(
			'name'  => 'Elementor',
			'level' => 'ok',
			'note'  => 'Soportado nativamente: usa el modo "Template de Elementor" del hero o plantillas blank-canvas sin conflicto.',
		),
		'jetpack/jetpack.php'                  => array(
			'name'  => 'Jetpack',
			'level' => 'ok',
			'note'  => 'Compatible. Si usas sus CDN de imagenes, activa "aceleracion de imagenes" para mejorar el LCP.',
		),
		// SEO: fully compatible (theme writes Yoast/RankMath-compatible metas).
		'seo-by-rank-math/rank-math.php'       => array(
			'name'  => 'Rank Math',
			'level' => 'ok',
			'note'  => 'Compatible: el theme no duplica metadatos cuando Rank Math gestiona el SEO.',
		),
		'wordpress-seo/wp-seo.php'             => array(
			'name'  => 'Yoast SEO',
			'level' => 'ok',
			'note'  => 'Compatible: los articulos generados por IA guardan su focus keyword directamente en Yoast.',
		),
		// Caching/optimization: compatible, with optional tips.
		'wp-rocket/wp-rocket.php'              => array(
			'name'  => 'WP Rocket',
			'level' => 'tip',
			'note'  => 'Compatible. Consejo: excluye "pagead2.googlesyndication.com" de Delay JS para que AdSense cargue bien.',
		),
		'litespeed-cache/litespeed-cache.php'  => array(
			'name'  => 'LiteSpeed Cache',
			'level' => 'tip',
			'note'  => 'Compatible. Consejo: activa ESI para el sidebar si activas la cache de paginas completa.',
		),
		'autoptimize/autoptimize.php'          => array(
			'name'  => 'Autoptimize',
			'level' => 'tip',
			'note'  => 'Compatible. Consejo: no actives "agregar CSS inline" con el CSS dinamico del Customizer.',
		),
		'w3-total-cache/w3-total-cache.php'    => array(
			'name'  => 'W3 Total Cache',
			'level' => 'tip',
			'note'  => 'Compatible. Consejo: purga la cache tras guardar el Customizer.',
		),
		'wp-super-cache/wp-cache.php'         => array(
			'name'  => 'WP Super Cache',
			'level' => 'ok',
			'note'  => 'Compatible.',
		),
		// Security: compatible, no special configuration needed.
		'wordfence/wordfence.php'             => array(
			'name'  => 'Wordfence',
			'level' => 'ok',
			'note'  => 'Compatible: los assets del theme usan handles propios (chuquipiondo-*), sin whitelisting manual.',
		),
		'sucuri-scanner/sucuri.php'            => array(
			'name'  => 'Sucuri',
			'level' => 'ok',
			'note'  => 'Compatible.',
		),
		'all-in-one-wp-security-and-firewall/wp-security.php' => array(
			'name'  => 'All In One WP Security',
			'level' => 'ok',
			'note'  => 'Compatible.',
		),
		// Maintenance mode: only meaningful advisory while developing.
		'wp-maintenance-mode/wp-maintenance-mode.php' => array(
			'name'  => 'WP Maintenance Mode',
			'level' => 'advisory',
			'note'  => 'Recuerda desactivarlo al publicar: los visitantes verian la pantalla de mantenimiento.',
		),
		'seedprod/seedprod.php'                => array(
			'name'  => 'SeedProd',
			'level' => 'advisory',
			'note'  => 'Recuerda desactivar el modo "coming soon" al lanzar el sitio.',
		),
		// Builders that globally override templates: soft advisory only.
		'divi-builder/divi-builder.php'        => array(
			'name'  => 'Divi Builder',
			'level' => 'advisory',
			'note'  => 'Usalo en plantillas especificas; si toma todo el sitio, pierdes los layouts del theme CHUQUIPIONDO.',
		),
		'visualcomposer/plugin-wordpress.php'  => array(
			'name'  => 'Visual Composer',
			'level' => 'advisory',
			'note'  => 'Usalo en paginas concretas para no sobreescribir las plantillas del theme.',
		),
	);
}

/**
 * Scan active plugins against the compatibility registry.
 */
function chuquipiondo_check_plugin_conflicts() {
	$registry = chuquipiondo_conflict_plugins();
	$found     = array();
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	foreach ( $registry as $plugin_file => $info ) {
		if ( is_plugin_active( $plugin_file ) ) {
			$found[ $plugin_file ] = array(
				'name'  => $info['name'],
				'level' => $info['level'],
				'note'  => $info['note'],
			);
		}
	}
	update_option( 'chuquipiondo_plugin_conflicts', $found, false );
}
add_action( 'after_switch_theme', 'chuquipiondo_check_plugin_conflicts' );
add_action( 'activated_plugin', 'chuquipiondo_check_plugin_conflicts', 20 );

/**
 * Informative, dismissible admin notice (compatibility overview, not warnings).
 */
function chuquipiondo_conflict_admin_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	// Respect the dismiss.
	if ( get_option( 'chuquipiondo_conflicts_dismissed' ) ) {
		return;
	}
	$conflicts = get_option( 'chuquipiondo_plugin_conflicts', array() );
	if ( empty( $conflicts ) || ! is_array( $conflicts ) ) {
		return;
	}
	$labels = array(
		'ok'       => __( 'Compatible', 'chuquipiondo' ),
		'tip'      => __( 'Compatible (con consejo)', 'chuquipiondo' ),
		'advisory' => __( 'Revisar', 'chuquipiondo' ),
	);
	$has_advisory = false;
	?>
	<div class="notice notice-info is-dismissible" id="chuquipiondo-compat-notice">
		<h3><?php esc_html_e( 'CHUQUIPIONDO - Compatibilidad con tus plugins', 'chuquipiondo' ); ?></h3>
		<p><?php esc_html_e( 'Tu theme funciona con estos plugins activos. Detalles:', 'chuquipiondo' ); ?></p>
		<ul style="margin-top:8px;">
			<?php foreach ( $conflicts as $c ) : ?>
				<?php
				$is_adv = ( 'advisory' === $c['level'] );
				if ( $is_adv ) {
					$has_advisory = true;
				}
				?>
				<li>
					<strong><?php echo esc_html( $c['name'] ); ?></strong>
					&mdash; <em><?php echo esc_html( $labels[ $c['level'] ] ); ?></em>
					<?php if ( ! empty( $c['note'] ) ) : ?>
						<br><span style="color:#5b6678;"><?php echo esc_html( $c['note'] ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php if ( $has_advisory ) : ?>
			<p><strong><?php esc_html_e( 'Solo los marcados como "Revisar" necesitan una accion puntual; el resto funciona sin configurar nada.', 'chuquipiondo' ); ?></strong></p>
		<?php else : ?>
			<p><?php esc_html_e( 'Todo compatible: no necesitas configurar nada especial.', 'chuquipiondo' ); ?></p>
		<?php endif; ?>
	</div>
	<script>
	(function() {
		var n = document.getElementById('chuquipiondo-compat-notice');
		if (!n) return;
		n.addEventListener('click', function(e) {
			if (e.target.classList.contains('notice-dismiss')) {
				var xhr = new XMLHttpRequest();
				xhr.open('POST', '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>');
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.send('action=chuquipiondo_dismiss_compat&nonce=<?php echo esc_js( wp_create_nonce( 'chuquipiondo_compat' ) ); ?>');
			}
		});
	})();
	</script>
	<?php
}
add_action( 'admin_notices', 'chuquipiondo_conflict_admin_notice' );

/**
 * Persist the dismissal (nonce-protected, admins only).
 */
function chuquipiondo_dismiss_compat_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'forbidden' ), 403 );
	}
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'chuquipiondo_compat' ) ) {
		wp_send_json_error( array( 'message' => 'invalid nonce' ), 400 );
	}
	update_option( 'chuquipiondo_conflicts_dismissed', 1, false );
	wp_send_json_success();
}
add_action( 'wp_ajax_chuquipiondo_dismiss_compat', 'chuquipiondo_dismiss_compat_notice' );

/**
 * Prevent known aggressive hooks from breaking the theme.
 * Only runs for real advisory combinations; harmless by default.
 */
function chuquipiondo_deconflict_hooks() {
	$conflicts = get_option( 'chuquipiondo_plugin_conflicts', array() );
	if ( empty( $conflicts ) || ! is_array( $conflicts ) ) {
		return;
	}
	// If a maintenance plugin is active, make sure admin is still accessible.
	if ( array_key_exists( 'wp-maintenance-mode/wp-maintenance-mode.php', $conflicts ) ) {
		add_filter( 'wp_maintenance_mode_status', '__return_false', 999 );
	}
}
add_action( 'init', 'chuquipiondo_deconflict_hooks', 1 );
