/**
 * nechugo News - JS principal (vanilla, ~2KB, sin dependencias).
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		// Menu movil.
		var toggle = document.querySelector('.nechugo-menu-toggle');
		if (toggle) {
			toggle.addEventListener('click', function () {
				var open = document.body.classList.toggle('nechugo-nav-open');
				toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			});
		}

		// Buscador desplegable.
		var searchToggle = document.querySelector('.nechugo-search-toggle');
		if (searchToggle) {
			searchToggle.addEventListener('click', function () {
				var form = searchToggle.parentElement.querySelector('.nechugo-search-form');
				if (!form) return;
				var hidden = form.hasAttribute('hidden');
				if (hidden) {
					form.removeAttribute('hidden');
					var field = form.querySelector('input[type="search"]');
					if (field) field.focus();
				} else {
					form.setAttribute('hidden', '');
				}
				searchToggle.setAttribute('aria-expanded', hidden ? 'true' : 'false');
			});
		}

		// Hero slider.
		var slider = document.querySelector('[data-slider]');
		if (slider) {
			var slides = slider.querySelectorAll('.nechugo-hero-slide');
			var current = 0;
			var timer = null;

			var show = function (index) {
				slides.forEach(function (s, i) {
					s.classList.toggle('is-active', i === index);
				});
				current = index;
			};

			var next = function () { show((current + 1) % slides.length); };
			var prev = function () { show((current - 1 + slides.length) % slides.length); };

			var start = function () { timer = setInterval(next, 5000); };
			var stop = function () { if (timer) clearInterval(timer); };

			var nextBtn = slider.querySelector('.nechugo-hero-next');
			var prevBtn = slider.querySelector('.nechugo-hero-prev');
			if (nextBtn) nextBtn.addEventListener('click', function () { stop(); next(); start(); });
			if (prevBtn) prevBtn.addEventListener('click', function () { stop(); prev(); start(); });
			slider.addEventListener('mouseenter', stop);
			slider.addEventListener('mouseleave', start);
			start();
		}

		// Volver arriba.
		var backTop = document.querySelector('.nechugo-back-top');
		if (backTop) {
			var onScroll = function () {
				backTop.classList.toggle('is-visible', window.scrollY > 400);
			};
			window.addEventListener('scroll', onScroll, { passive: true });
			onScroll();
			backTop.addEventListener('click', function () {
				window.scrollTo({ top: 0, behavior: 'smooth' });
			});
		}
	});
})();
