/**
 * Hero / Slider: fade, slide, ken burns effects with autoplay.
 * Only loaded when the hero has 2+ slides (see enqueue.php).
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var hero = document.querySelector('.hero--slider-active');
		if (!hero) {
			return;
		}

		var config = window.chuquipiondoHero || {};
		var slides = hero.querySelectorAll('.hero__slide');
		var dotsContainer = hero.querySelector('.hero__dots');
		var prevBtn = hero.querySelector('.hero__arrow--prev');
		var nextBtn = hero.querySelector('.hero__arrow--next');

		if (slides.length < 2) {
			return;
		}

		var current = 0;
		var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		var effect = config.effect || 'fade';
		var autoplay = config.autoplay && !reduce;
		var speed = parseInt(config.speed, 10) || 5000;
		var timer = null;

		// Build dots.
		if (dotsContainer) {
			slides.forEach(function (_, i) {
				var dot = document.createElement('button');
				dot.className = 'hero__dot' + (i === 0 ? ' hero__dot--active' : '');
				dot.setAttribute('role', 'tab');
				dot.setAttribute('aria-label', 'Slide ' + (i + 1));
				dot.addEventListener('click', function () {
					goTo(i);
				});
				dotsContainer.appendChild(dot);
			});
		}

		/**
		 * Switch to a slide.
		 */
		function goTo(index) {
			slides[current].classList.remove('hero__slide--active');
			if (dotsContainer) {
				var oldDot = dotsContainer.children[current];
				if (oldDot) {
					oldDot.classList.remove('hero__dot--active');
				}
			}

			current = (index + slides.length) % slides.length;

			slides[current].classList.add('hero__slide--active');
			if (dotsContainer) {
				var newDot = dotsContainer.children[current];
				if (newDot) {
					newDot.classList.add('hero__dot--active');
				}
			}

			// Re-trigger ken burns animation.
			if (effect === 'kenburns') {
				var img = slides[current].querySelector('.hero__img--kenburns');
				if (img) {
					img.style.animation = 'none';
					// Force reflow.
					void img.offsetWidth;
					img.style.animation = '';
				}
			}

			restartAutoplay();
		}

		function next() { goTo(current + 1); }
		function prev() { goTo(current - 1); }

		/**
		 * Restart the autoplay timer.
		 */
		function restartAutoplay() {
			stopAutoplay();
			if (autoplay) {
				timer = setInterval(next, speed);
			}
		}

		function stopAutoplay() {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		}

		// Controls.
		if (nextBtn) {
			nextBtn.addEventListener('click', next);
		}
		if (prevBtn) {
			prevBtn.addEventListener('click', prev);
		}

		// Pause on hover / focus.
		hero.addEventListener('mouseenter', stopAutoplay);
		hero.addEventListener('mouseleave', restartAutoplay);
		hero.addEventListener('focusin', stopAutoplay);
		hero.addEventListener('focusout', restartAutoplay);

		// Keyboard navigation.
		hero.addEventListener('keydown', function (e) {
			if (e.key === 'ArrowLeft') {
				prev();
			} else if (e.key === 'ArrowRight') {
				next();
			}
		});

		// Swipe support.
		var startX = 0;
		hero.addEventListener('touchstart', function (e) {
			startX = e.touches[0].clientX;
		}, { passive: true });

		hero.addEventListener('touchend', function (e) {
			var endX = e.changedTouches[0].clientX;
			var diff = startX - endX;
			if (Math.abs(diff) > 50) {
				if (diff > 0) {
					next();
				} else {
					prev();
				}
			}
		}, { passive: true });

		// Respect visibility (pause when tab hidden).
		document.addEventListener('visibilitychange', function () {
			if (document.hidden) {
				stopAutoplay();
			} else {
				restartAutoplay();
			}
		});

		restartAutoplay();
	});
})();
