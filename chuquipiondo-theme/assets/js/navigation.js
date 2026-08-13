/**
 * Navigation: mobile menu toggle, sticky header, search toggle.
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initMobileMenu();
		initStickyHeader();
		initSearchToggle();
		initSmoothAnchor();
	});

	/**
	 * Mobile menu open/close.
	 */
	function initMobileMenu() {
		var toggle = document.querySelector('.menu-toggle');
		var nav = document.querySelector('.header-main__nav');
		if (!toggle || !nav) {
			return;
		}

		toggle.addEventListener('click', function () {
			var isOpen = toggle.getAttribute('aria-expanded') === 'true';
			toggle.setAttribute('aria-expanded', String(!isOpen));
			nav.classList.toggle('is-open', !isOpen);
			document.body.classList.toggle('menu-open', !isOpen);
		});

		// Close on link click.
		nav.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () {
				toggle.setAttribute('aria-expanded', 'false');
				nav.classList.remove('is-open');
				document.body.classList.remove('menu-open');
			});
		});

		// Close on overlay click.
		document.addEventListener('click', function (e) {
			if (document.body.classList.contains('menu-open') &&
				!nav.contains(e.target) &&
				!toggle.contains(e.target)) {
				toggle.setAttribute('aria-expanded', 'false');
				nav.classList.remove('is-open');
				document.body.classList.remove('menu-open');
			}
		});

		// Close on Escape.
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && document.body.classList.contains('menu-open')) {
				toggle.setAttribute('aria-expanded', 'false');
				nav.classList.remove('is-open');
				document.body.classList.remove('menu-open');
				toggle.focus();
			}
		});
	}

	/**
	 * Sticky header (Astra-style): fixed on scroll, PC + mobile.
	 * Toggles the .scrolled class on the .site-header element.
	 */
	function initStickyHeader() {
		var header = document.querySelector('.site-header.is-sticky-header');
		if (!header) {
			header = document.querySelector('.header-main.is-sticky');
			if (!header) {
				return;
			}
		}

		var scrolled = false;
		var scrollThreshold = 10;

		function checkScroll() {
			var shouldScroll = window.scrollY > scrollThreshold;
			if (shouldScroll !== scrolled) {
				scrolled = shouldScroll;
				header.classList.toggle('scrolled', scrolled);
				// Also toggle on the main header row for mode-specific styling.
				var mainRow = header.querySelector('.header-main');
				if (mainRow) {
					mainRow.classList.toggle('scrolled', scrolled);
				}
			}
		}

		window.addEventListener('scroll', checkScroll, { passive: true });
		checkScroll();
	}

	/**
	 * Header search toggle.
	 */
	function initSearchToggle() {
		var btn = document.querySelector('.search-toggle');
		var form = document.querySelector('.header-search-form');
		if (!btn || !form) {
			return;
		}

		btn.addEventListener('click', function () {
			var isHidden = form.hasAttribute('hidden');
			if (isHidden) {
				form.removeAttribute('hidden');
				var input = form.querySelector('input[type="search"]');
				if (input) {
					input.focus();
				}
				btn.setAttribute('aria-expanded', 'true');
			} else {
				form.setAttribute('hidden', '');
				btn.setAttribute('aria-expanded', 'false');
			}
		});

		// Close on outside click.
		document.addEventListener('click', function (e) {
			if (!form.hasAttribute('hidden') &&
				!form.contains(e.target) &&
				!btn.contains(e.target)) {
				form.setAttribute('hidden', '');
				btn.setAttribute('aria-expanded', 'false');
			}
		});
	}

	/**
	 * Smooth scroll for in-page anchors (respects reduced motion).
	 */
	function initSmoothAnchor() {
		var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
		if (reduce) {
			return;
		}
		document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
			anchor.addEventListener('click', function (e) {
				var id = anchor.getAttribute('href');
				if (id.length < 2) {
					return;
				}
				var target = document.querySelector(id);
				if (target) {
					e.preventDefault();
					target.scrollIntoView({ behavior: 'smooth', block: 'start' });
				}
			});
		});
	}
})();
