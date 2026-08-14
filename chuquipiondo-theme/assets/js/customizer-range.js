/**
 * Customizer Range Sliders Enhancement.
 *
 * Adds a live value display and a "Cancel" (reset) button
 * to every range slider in the Customizer.
 *
 * @package CHUQUIPIONDO
 */
(function( $, wp, defaults ) {
	'use strict';

	wp.customize.bind( 'ready', function() {
		$( '.customize-control input[type="range"]' ).each( function() {
			var $input   = $( this );
			var settingId = $input.closest( '.customize-control' ).attr( 'id' ) || '';
			settingId = settingId.replace( 'customize-control-', '' );

			var defaultVal = defaults[ settingId ] !== undefined ? defaults[ settingId ] : $input.attr( 'value' );

			// Create value display.
			var $value = $( '<span class="chuqui-range-value"></span>' )
				.text( $input.val() );

			// Create cancel button.
			var $cancel = $( '<button type="button" class="button button-secondary chuqui-range-cancel"></button>' )
				.text( wp.i18n.__( 'Cancelar', 'chuquipiondo' ) )
				.on( 'click', function( e ) {
					e.preventDefault();
					$input.val( defaultVal ).trigger( 'input' );
					$value.text( defaultVal );
					// Update the customizer setting.
					if ( settingId && wp.customize( settingId ) ) {
						wp.customize( settingId ).set( defaultVal );
					}
				});

			// Wrap input + value + cancel.
			var $wrap = $( '<div class="chuqui-range-wrap"></div>' );
			$input.after( $wrap );
			$wrap.append( $input, $value, $cancel );

			// Live update value on slide.
			$input.on( 'input', function() {
				$value.text( $input.val() );
			});
		});
	});

})( jQuery, wp, window.chuquipiondoRangeDefaults || {} );
