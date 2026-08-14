/**
 * Customizer: slides repeater control.
 *
 * Renders and manages the slide list in the Customizer, serializing
 * the data as JSON into the hidden textarea bound to the setting.
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
	});

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
				$item.append('<input type="number" class="slide-overlay" min="0" max="90" step="5" value="' + (slide.overlay || 30) + '">');

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
			$item.find('input').on('input change', function () {
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
