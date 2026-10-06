/**
 * Live preview del personalizador (postMessage).
 */
(function (wp, $) {
	'use strict';

	if (!wp || !wp.customize) return;

	var api = wp.customize;

	// Colores.
	['primary_color', 'secondary_color', 'accent_color', 'background_color', 'text_color',
	 'header_bg_color', 'header_text_color', 'footer_bg_color', 'footer_text_color'].forEach(function (key) {
		api(key, function (value) {
			value.bind(function (to) {
				document.documentElement.style.setProperty('--nn-' + key.replace('_color', ''), to);
			});
		});
	});

	// Tamano de texto e H1 en articulos.
	api('body_font_size', function (value) {
		value.bind(function (to) {
			var el = document.querySelector('.entry-content');
			if (el) el.style.fontSize = to + 'px';
		});
	});
	api('h1_font_size', function (value) {
		value.bind(function (to) {
			var el = document.querySelector('.entry-content h1');
			if (el) el.style.fontSize = to + 'px';
		});
	});

	// Ancho del contenedor.
	api('content_width', function (value) {
		value.bind(function (to) {
			to = Math.max(1200, parseInt(to, 10) || 1200);
			document.documentElement.style.setProperty('--nn-container', to + 'px');
		});
	});

	// Ancho de la barra lateral.
	api('sidebar_width', function (value) {
		value.bind(function (to) {
			to = Math.max(240, parseInt(to, 10) || 300);
			document.documentElement.style.setProperty('--nn-sidebar', to + 'px');
		});
	});

	// Nombre del sitio.
	api('blogname', function (value) {
		value.bind(function (to) {
			var el = document.querySelector('.site-title a');
			if (el) el.textContent = to;
		});
	});
})(window.wp, window.jQuery);
