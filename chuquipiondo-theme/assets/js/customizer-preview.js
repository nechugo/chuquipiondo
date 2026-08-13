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

	// Font families.
	wp.customize('font_body', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--font-body', to);
		});
	});
	wp.customize('font_heading', function (value) {
		value.bind(function (to) {
			document.documentElement.style.setProperty('--font-heading', to);
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
