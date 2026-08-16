<?php
/**
 * Custom Customizer controls.
 *
 * Currently ships a lightweight slides repeater control used by
 * the Hero / Slider section. Rendered as a JS-free textarea-encoded
 * JSON so it works without extra Customizer scripts.
 *
 * @package CHUQUIPIONDO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slides repeater control.
 *
 * Stores an array of slides as JSON in a textarea. This keeps the
 * Customizer dependency-free (no custom JS/Backbone needed) while
 * still being fully administrable.
 */
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	// WP_Customize_Control solo esta disponible en el Customizer.
	// No cargar la clase en el front-end.
	return;
}

if ( ! class_exists( 'Chuquipiondo_Slides_Control' ) ) {
class Chuquipiondo_Slides_Control extends WP_Customize_Control {

	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'chuquipiondo_slides';

	/**
	 * Enqueue control assets.
	 */
	public function enqueue() {
		wp_enqueue_script(
			'chuquipiondo-customizer-slides',
			CHUQUIPONDO_URI . '/assets/js/customizer-slides.js',
			array( 'jquery', 'customize-controls' ),
			chuquipiondo_asset_version( 'assets/js/customizer-slides.js' ),
			true
		);
		wp_enqueue_style(
			'chuquipiondo-customizer-slides',
			CHUQUIPONDO_URI . '/assets/css/customizer.css',
			array(),
			chuquipiondo_asset_version( 'assets/css/customizer.css' )
		);
	}

	/**
	 * Export the setting to JS.
	 */
	public function to_json() {
		parent::to_json();
		$value = $this->value();
		if ( ! is_array( $value ) ) {
			$value = array();
		}
		$this->json['slides'] = array_values( $value );
		$this->json['label'] = $this->label;
	}

	/**
	 * Render the control's content.
	 */
	public function content_template() {
		?>
		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>
		<div class="chuquipiondo-slides" id="chuquipiondo-slides-{{ data.id }}">
			<ul class="chuquipiondo-slides-list"></ul>
			<button type="button" class="button chuquipiondo-add-slide"><?php esc_html_e( 'Anadir slide', 'chuquipiondo' ); ?></button>
			<textarea class="chuquipiondo-slides-json" {{{ data.input_attrs }}} style="display:none;" data-customize-setting-link="{{ data.settings.default }}"></textarea>
		</div>
		<?php
	}
}
} // end if class_exists

/**
 * Enqueue the range-slider enhancement for all range controls.
 *
 * Shows the live numeric value and a "Cancelar" (reset to default)
 * button next to every range input in the Customizer.
 */
function chuquipiondo_enqueue_range_controls_script() {
	wp_enqueue_script(
		'chuquipiondo-customizer-range',
		CHUQUIPONDO_URI . '/assets/js/customizer-range.js',
		array( 'jquery', 'customize-controls' ),
		chuquipiondo_asset_version( 'assets/js/customizer-range.js' ),
		true
	);

	// Pass the default values so the "Cancelar" button can restore them.
	$defaults = chuquipiondo_defaults();
	$range_defaults = array();
	foreach ( $defaults as $key => $value ) {
		if ( is_numeric( $value ) ) {
			$range_defaults[ $key ] = $value;
		}
	}
	wp_localize_script(
		'chuquipiondo-customizer-range',
		'chuquipiondoRangeDefaults',
		$range_defaults
	);
}
add_action( 'customize_controls_enqueue_scripts', 'chuquipiondo_enqueue_range_controls_script' );

