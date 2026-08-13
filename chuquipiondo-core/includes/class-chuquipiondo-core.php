<?php
/**
 * Main CHUQUIPIONDO Core class.
 *
 * @package CHUQUIPIONDO_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core plugin singleton.
 */
final class Chuquipiondo_Core {

	/**
	 * Single instance.
	 *
	 * @var Chuquipiondo_Core|null
	 */
	private static $instance = null;

	/**
	 * Version.
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * Get the single instance.
	 *
	 * @return Chuquipiondo_Core
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
		$this->load_assets();
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
	}

	/**
	 * Load text domain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'chuquipiondo-core', false, dirname( CHUQUIPIONDO_CORE_BASENAME ) . '/languages' );
	}

	/**
	 * Check if the CHUQUIPIONDO theme is active.
	 *
	 * @return bool
	 */
	public function is_theme_active() {
		$theme = wp_get_theme();
		return ( 'CHUQUIPIONDO' === $theme->get( 'Name' ) || 'chuquipiondo-theme' === $theme->get_template() );
	}

	/**
	 * Load front-end assets.
	 */
	public function load_assets() {
		// Assets are enqueued conditionally.
	}

	/**
	 * Enqueue front-end styles and scripts.
	 */
	public function enqueue_front_assets() {
		wp_enqueue_style(
			'chuquipiondo-core',
			CHUQUIPIONDO_CORE_URL . 'assets/css/core.css',
			array(),
			CHUQUIPIONDO_CORE_VERSION
		);

		wp_enqueue_script(
			'chuquipiondo-core',
			CHUQUIPIONDO_CORE_URL . 'assets/js/core.js',
			array(),
			CHUQUIPIONDO_CORE_VERSION,
			true
		);
	}

	/**
	 * Enqueue editor assets for Gutenberg blocks.
	 */
	public function enqueue_editor_assets() {
		wp_enqueue_script(
			'chuquipiondo-core-editor',
			CHUQUIPIONDO_CORE_URL . 'assets/js/editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ),
			CHUQUIPIONDO_CORE_VERSION,
			true
		);
	}
}
