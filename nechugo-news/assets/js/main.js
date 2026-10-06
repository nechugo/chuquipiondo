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

		// Hero slider (hero / grid / carrusel, autoplay configurable).
		var slider = document.querySelector('[data-slider]');
		if (slider) {
			var slides = Array.prototype.slice.call(slider.querySelectorAll('.nechugo-hero-slide'));
			var current = 0;
			var timer = null;
			var autoplay = slider.getAttribute('data-autoplay') !== 'false';
			var interval = parseInt(slider.getAttribute('data-interval'), 10) || 5000;
			var style = slider.getAttribute('data-style') || 'hero';
			var track = slider.querySelector('.nechugo-hero-track');

			var show = function (index) {
				current = (index + slides.length) % slides.length;
				if (style === 'carousel') {
					var w = slides[0].offsetWidth + 16;
					track.style.transform = 'translateX(-' + (current * w) + 'px)';
				} else if (style === 'grid') {
					slides.forEach(function (s, i) {
						s.classList.toggle('is-active', i === current);
					});
				} else {
					slides.forEach(function (s, i) {
						s.classList.toggle('is-active', i === current);
					});
					track.style.transform = 'translateX(-' + (current * 100) + '%)';
				}
			};

			var next = function () { show(current + 1); };
			var prev = function () { show(current - 1); };

			var start = function () {
				if (!autoplay || slides.length < 2) return;
				timer = setInterval(next, interval);
			};
			var stop = function () { if (timer) clearInterval(timer); };

			var nextBtn = slider.querySelector('.nechugo-hero-next');
			var prevBtn = slider.querySelector('.nechugo-hero-prev');
			if (nextBtn) nextBtn.addEventListener('click', function () { stop(); next(); start(); });
			if (prevBtn) prevBtn.addEventListener('click', function () { stop(); prev(); start(); });
			slider.addEventListener('mouseenter', stop);
			slider.addEventListener('mouseleave', start);
			if (style === 'carousel') {
				slides.forEach(function (s) { s.classList.add('is-active'); });
			}
			show(0);
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
