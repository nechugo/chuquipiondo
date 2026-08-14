/**
 * Customizer: slides repeater control.
 *
 * Renders and manages the slide list in the Customizer, serializing
 * the data as JSON into the hidden textarea bound to the setting.
 * Includes percentage display for sliders and cancel/reset buttons.
 */

(function ($) {
	'use strict';

	if (!wp || !wp.customize) {
		return;
	}

	wp.customize.bind('ready', function () {
		$('.chuquipiondo-slides').each(function () {
			initRepeater($(this));
		});
		
		// Add percentage display and cancel button to all range controls
		initRangeControls();
	});
	
	function initRangeControls() {
		// Wait for controls to be rendered
		setTimeout(function() {
			$('.customize-control input[type="range"]').each(function() {
				var $input = $(this);
				if ($input.closest('.chuquipiondo-slides').length > 0) {
					return; // Skip slider overlay controls, they have their own display
				}
				
				var $control = $input.closest('.customize-control');
				
				// Skip if already initialized
				if ($control.find('.range-value-display').length > 0) {
					return;
				}
				
				// Create value display
				var $display = $('<span class="range-value-display">' + $input.val() + '</span>');
				$input.after($display);
				
				// Create cancel/reset button
				var $cancelBtn = $('<button type="button" class="button range-cancel-btn" title="Restaurar valor por defecto">Cancelar</button>');
				$display.after($cancelBtn);
				
				// Update display on change
				$input.on('input change', function() {
					$display.text($(this).val());
				});
				
				// Reset to default on cancel
				$cancelBtn.on('click', function() {
					var defaultValue = $input.attr('data-customize-setting-link');
					if (defaultValue) {
						var setting = wp.customize(defaultValue);
						if (setting && setting._default !== undefined) {
							$input.val(setting._default).trigger('change');
							$display.text(setting._default);
						} else {
							// Try to get default from input attrs or use min value
							var minVal = $input.attr('min') || '0';
							$input.val(minVal).trigger('change');
							$display.text(minVal);
						}
					}
				});
			});
		}, 500);
	}

	function initRepeater($wrapper) {
		var $list = $wrapper.find('.chuquipiondo-slides-list');
		var $json = $wrapper.find('.chuquipiondo-slides-json');
		var $addBtn = $wrapper.find('.chuquipiondo-add-slide');

		var setting = wp.customize($json.data('customize-setting-link'));
		var slides = [];
		try {
			var val = setting();
			if (val) {
				slides = typeof val === 'string' ? JSON.parse(val) : val;
				if (!Array.isArray(slides)) {
					slides = [];
				}
			}
		} catch (e) {
			slides = [];
		}

		function render() {
			$list.empty();
			slides.forEach(function (slide, index) {
				var $item = $('<div class="chuquipiondo-slide-item" data-index="' + index + '"></div>');

				$item.append('<label>Imagen Desktop (URL)</label>');
				$item.append('<input type="url" class="slide-image-desktop" value="' + escAttr(slide.image_desktop) + '">');

				$item.append('<label>Imagen Tablet (URL)</label>');
				$item.append('<input type="url" class="slide-image-tablet" value="' + escAttr(slide.image_tablet) + '">');

				$item.append('<label>Imagen Movil (URL)</label>');
				$item.append('<input type="url" class="slide-image-mobile" value="' + escAttr(slide.image_mobile) + '">');

				$item.append('<label>Titulo</label>');
				$item.append('<input type="text" class="slide-title" value="' + escAttr(slide.title) + '">');

				$item.append('<label>Subtitulo</label>');
				$item.append('<input type="text" class="slide-subtitle" value="' + escAttr(slide.subtitle) + '">');

				$item.append('<label>Texto del boton</label>');
				$item.append('<input type="text" class="slide-button-text" value="' + escAttr(slide.button_text) + '">');

				$item.append('<label>URL del boton</label>');
				$item.append('<input type="url" class="slide-button-url" value="' + escAttr(slide.button_url) + '">');

				$item.append('<label>Overlay (%)</label>');
				var $overlayInput = $('<input type="number" class="slide-overlay" min="0" max="90" step="5" value="' + (slide.overlay || 30) + '">');
				var $overlayDisplay = $('<span class="range-value-display">' + (slide.overlay || 30) + '%</span>');
				var $overlayCancel = $('<button type="button" class="button range-cancel-btn" title="Restaurar valor por defecto">Cancelar</button>');
				
				$item.append($overlayInput);
				$item.append($overlayDisplay);
				$item.append($overlayCancel);
				
				// Update display on change
				$overlayInput.on('input change', function() {
					$overlayDisplay.text($(this).val() + '%');
				});
				
				// Reset to default (30%)
				$overlayCancel.on('click', function() {
					$overlayInput.val(30).trigger('change');
				});

				var $actions = $('<div></div>');
				$actions.append('<button type="button" class="button chuquipiondo-slide-remove">Eliminar</button>');
				if (index > 0) {
					$actions.append('<button type="button" class="button chuquipiondo-slide-up">Subir</button>');
				}
				if (index < slides.length - 1) {
					$actions.append('<button type="button" class="button chuquipiondo-slide-down">Bajar</button>');
				}
				$item.append($actions);

				bindItem($item, index);
				$list.append($item);
			});
		}

		function bindItem($item, index) {
			$item.find('input').not('.slide-overlay').on('input change', function () {
				var cls = $(this).attr('class').replace('slide-', '').replace('image-', 'image_').replace('button-', 'button_');
				var key = cls;
				// Normalize keys.
				var keyMap = {
					'image_desktop': 'image_desktop',
					'image_tablet': 'image_tablet',
					'image_mobile': 'image_mobile',
					'title': 'title',
					'subtitle': 'subtitle',
					'button_text': 'button_text',
					'button_url': 'button_url',
					'overlay': 'overlay'
				};
				var mapped = keyMap[cls] || cls;
				var val = $(this).val();
				if (mapped === 'overlay') {
					val = parseInt(val, 10) || 0;
				}
				slides[index][mapped] = val;
				save();
			});

			$item.find('.chuquipiondo-slide-remove').on('click', function () {
				slides.splice(index, 1);
				save();
				render();
			});

			$item.find('.chuquipiondo-slide-up').on('click', function () {
				if (index > 0) {
					var tmp = slides[index];
					slides[index] = slides[index - 1];
					slides[index - 1] = tmp;
					save();
					render();
				}
			});

			$item.find('.chuquipiondo-slide-down').on('click', function () {
				if (index < slides.length - 1) {
					var tmp = slides[index];
					slides[index] = slides[index + 1];
					slides[index + 1] = tmp;
					save();
					render();
				}
			});
		}

		function save() {
			setting(JSON.stringify(slides));
		}

		$addBtn.on('click', function () {
			slides.push({
				image_desktop: '',
				image_tablet: '',
				image_mobile: '',
				title: '',
				subtitle: '',
				button_text: '',
				button_url: '',
				overlay: 30
			});
			save();
			render();
		});

		render();
	}

	function escAttr(s) {
		return String(s || '').replace(/"/g, '&quot;').replace(/</g, '&lt;');
	}

})(jQuery);
