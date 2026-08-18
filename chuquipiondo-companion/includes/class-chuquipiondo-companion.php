<?php
/**
 * Main CHUQUIPIONDO Companion class.
 *
 * Singleton bootstrap that wires the plugin into WordPress and
 * loads the front-end/admin assets. Mirrors the Chuquipiondo_Core
 * pattern (modular includes + singleton).
 *
 * @package CHUQUIPIONDO_Companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Companion plugin singleton.
 */
final class Chuquipiondo_Companion {

	/**
	 * Single instance.
	 *
	 * @var Chuquipiondo_Companion|null
	 */
	private static $instance = null;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $version = CHUQUIPIONDO_COMPANION_VERSION;

	/**
	 * Get the single instance.
	 *
	 * @return Chuquipiondo_Companion
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'admin_notices', array( $this, 'maybe_render_dependency_notice' ) );

		// Module bootstrap is handled inside each module file.
	}

	/**
	 * Load text domain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'chuquipiondo-companion', false, dirname( CHUQUIPIONDO_COMPANION_BASENAME ) . '/languages' );
	}

	/**
	 * Render a notice when the required theme is not active.
	 *
	 * The companion can be activated without the theme, but its front-end
	 * modules (builders, mega menu, blog pro) rely on theme helpers.
	 */
	public function maybe_render_dependency_notice() {
		if ( chuquipiondo_companion_is_theme_active() ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || false === strpos( $screen->id, 'chuquipiondo' ) ) {
			return;
		}
		?>
		<div class="notice notice-warning">
			<p>
				<?php
				printf(
					/* translators: %s: theme name */
					esc_html__( 'CHUQUIPIONDO Companion funciona mejor con el tema %s activado. Algunos modulos pueden no renderizarse sin el tema.', 'chuquipiondo-companion' ),
					'<strong>CHUQUIPIONDO</strong>'
				);
				?>
			</p>
		</div>
		<?php
	}
}
