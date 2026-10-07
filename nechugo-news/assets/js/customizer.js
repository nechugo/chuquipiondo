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

	// Colores del encabezado (postMessage).
	['header_bg_color', 'header_text_color'].forEach(function (key) {
		api(key, function (value) {
			value.bind(function (to) {
				document.documentElement.style.setProperty('--nn-' + key.replace('_color', ''), to);
			});
		});
	});

	// Altura del encabezado.
	api('header_height', function (value) {
		value.bind(function (to) {
			to = Math.max(48, parseInt(to, 10) || 64);
			document.documentElement.style.setProperty('--nn-header-h', to + 'px');
			document.querySelectorAll('.header__inner').forEach(function (el) {
				el.style.minHeight = to + 'px';
			});
		});
	});

	// Tamano de texto y menu del encabezado.
	api('header_font_size', function (value) {
		value.bind(function (to) {
			document.querySelector('.site-header').style.fontSize = parseInt(to, 10) + 'px';
		});
	});
	api('header_menu_size', function (value) {
		value.bind(function (to) {
			document.querySelectorAll('.nechugo-nav a').forEach(function (el) {
				el.style.fontSize = parseInt(to, 10) + 'px';
			});
		});
	});

	// Logo: ancho y alto.
	api('logo_width', function (value) {
		value.bind(function (to) {
			document.querySelectorAll('.custom-logo').forEach(function (el) {
				el.style.width = Math.max(120, parseInt(to, 10) || 220) + 'px';
				el.style.minWidth = el.style.width;
			});
		});
	});
	api('logo_height', function (value) {
		value.bind(function (to) {
			document.querySelectorAll('.custom-logo').forEach(function (el) {
				el.style.height = Math.max(40, parseInt(to, 10) || 120) + 'px';
			});
		});
	});

	// Ancho de la barra de busqueda.
	api('search_bar_width', function (value) {
		value.bind(function (to) {
			var w = Math.max(140, parseInt(to, 10) || 200) + 'px';
			document.querySelectorAll('.nechugo-search-field').forEach(function (el) {
				el.style.width = w;
				el.style.minWidth = w;
				el.style.maxWidth = w;
			});
		});
	});

	// Tipografia por contexto.
	['h1_font_size', 'post_title_font_size', 'page_title_font_size', 'widget_title_font_size'].forEach(function (key) {
		api(key, function (value) {
			value.bind(function (to) {
				var size = parseInt(to, 10);
				if (key === 'h1_font_size') {
					document.querySelectorAll('h1, .entry-title').forEach(function (el) {
						el.style.fontSize = size + 'px';
					});
				}
			});
		});
	});

	// Separacion entre cajas del encabezado.
	api('header_gap', function (value) {
		value.bind(function (to) {
			var gap = Math.max(0, parseInt(to, 10) || 5) + 'px';
			document.querySelectorAll('.header__inner, .topbar__inner, .topbar__left, .topbar__right, .nechugo-menu').forEach(function (el) {
				el.style.gap = gap;
			});
		});
	});

	// Espaciados estructurales.
	api('spacing_header_body', function (value) {
		value.bind(function (to) {
			document.getElementById('content').style.paddingTop = Math.max(0, parseInt(to, 10)) + 'px';
		});
	});
	api('spacing_body_footer', function (value) {
		value.bind(function (to) {
			document.querySelector('.site-footer').style.marginTop = Math.max(0, parseInt(to, 10)) + 'px';
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
