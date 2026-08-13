/**
 * Customizer live preview.
 *
 * Updates site title / description / colors in real time without
 * a full page refresh.
 */

(function ($) {
	'use strict';

	if (!wp || !wp.customize) {
		return;
	}

	// Site title.
	wp.customize('blogname', function (value) {
		value.bind(function (to) {
			$('.site-title a').text(to);
		});
	});

	// Site description.
	wp.customize('blogdescription', function (value) {
		value.bind(function (to) {
			$('.site-description').text(to);
		});
	});

	// Colors -> CSS variables.
	var colorVars = {
		'color_navy': 'navy',
		'color_navy_dark': 'navy-dark',
		'color_sky': 'sky',
		'color_sky_soft': 'sky-soft',
		'color_background': 'background',
		'color_text': 'text',
		'color_muted': 'muted',
		'color_accent': 'accent',
		'button_bg': 'button-bg',
		'button_text': 'button-text',
		'button_hover_bg': 'button-hover-bg',
		'button_hover_text': 'button-hover',
		'footer_bg': 'footer-bg',
		'footer_text': 'footer-text'
	};

	$.each(colorVars, function (setting, varName) {
		wp.customize(setting, function (value) {
			value.bind(function (to) {
				document.documentElement.style.setProperty('--' + varName, to);
			});
		});
	});

	// Font families (resolve key to CSS stack via the embedded fontMap).
	var fontMap = {"inter": "\"Inter\", system-ui, -apple-system, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif", "plus-jakarta": "\"Plus Jakarta Sans\", \"Inter\", system-ui, sans-serif", "product-sans": "\"Product Sans\", \"Google Sans\", system-ui, sans-serif", "google-sans": "\"Google Sans\", \"Product Sans\", system-ui, sans-serif", "open-sans": "\"Open Sans\", system-ui, -apple-system, sans-serif", "roboto": "\"Roboto\", system-ui, -apple-system, sans-serif", "poppins": "\"Poppins\", system-ui, sans-serif", "montserrat": "\"Montserrat\", system-ui, sans-serif", "lato": "\"Lato\", system-ui, sans-serif", "raleway": "\"Raleway\", system-ui, sans-serif", "nunito": "\"Nunito\", system-ui, sans-serif", "playfair": "\"Playfair Display\", Georgia, \"Times New Roman\", serif", "merriweather": "\"Merriweather\", Georgia, serif", "lora": "\"Lora\", Georgia, serif", "libre-baskerville": "\"Libre Baskerville\", Georgia, serif", "source-serif": "\"Source Serif Pro\", Georgia, serif", "jetbrains-mono": "\"JetBrains Mono\", \"SF Mono\", \"Fira Code\", Consolas, monospace", "fira-code": "\"Fira Code\", \"SF Mono\", Consolas, monospace", "roboto-mono": "\"Roboto Mono\", \"SF Mono\", Consolas, monospace", "system-sans": "system-ui, -apple-system, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif", "system-serif": "Georgia, \"Times New Roman\", Times, serif", "system-mono": "\"SF Mono\", \"Fira Code\", Consolas, \"Liberation Mono\", monospace"};
	wp.customize('font_body', function (value) {
		value.bind(function (to) {
			var stack = fontMap[to] || fontMap['inter'];
			document.documentElement.style.setProperty('--font-body', stack);
		});
	});
	wp.customize('font_heading', function (value) {
		value.bind(function (to) {
			var stack = fontMap[to] || fontMap['plus-jakarta'];
			document.documentElement.style.setProperty('--font-heading', stack);
		});
	});

	// Base font size.
	wp.customize('font_size_base', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--font-size-base', to + 'px');
		});
	});

	// Container width.
	wp.customize('container_width', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--container-width', to + 'px');
		});
	});

	// Reading width.
	wp.customize('reading_width', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--reading-width', to + 'px');
		});
	});

	// Header height.
	wp.customize('header_main_height', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--header-height', to + 'px');
		});
	});

	// Hero height.
	wp.customize('hero_height', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--hero-height', to + 'px');
		});
	});

})(jQuery);
