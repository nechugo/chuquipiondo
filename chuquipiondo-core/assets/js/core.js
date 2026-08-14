/**
 * CHUQUIPIONDO Core - Front-end JavaScript.
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initReadingProgress();
		initBackToTop();
		initTabsWidget();
	});

	/**
	 * Reading progress bar.
	 */
	function initReadingProgress() {
		var bar = document.querySelector('.chuqui-reading-progress__bar');
		if (!bar) {
			return;
		}

		function update() {
			var scrollTop = window.scrollY;
			var docHeight = document.documentElement.scrollHeight - window.innerHeight;
			var pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
			bar.style.width = pct + '%';
		}

		window.addEventListener('scroll', update, { passive: true });
		update();
	}

	/**
	 * Back to top button.
	 */
	function initBackToTop() {
		var btn = document.getElementById('chuqui-back-to-top');
		if (!btn) {
			return;
		}

		var visible = false;

		function check() {
			var shouldShow = window.scrollY > 400;
			if (shouldShow !== visible) {
				visible = shouldShow;
				if (shouldShow) {
					btn.removeAttribute('hidden');
				} else {
					btn.setAttribute('hidden', '');
				}
			}
		}

		window.addEventListener('scroll', check, { passive: true });

		btn.addEventListener('click', function () {
			var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			window.scrollTo({
				top: 0,
				behavior: reduce ? 'auto' : 'smooth'
			});
		});

		check();
	}

	/**
	 * Tabs widget.
	 */
	function initTabsWidget() {
		var containers = document.querySelectorAll('.chuqui-tabs-widget');
		containers.forEach(function (container) {
			var tabs = container.querySelectorAll('.chuqui-tabs__nav a');
			var panels = container.querySelectorAll('.chuqui-tab');

			tabs.forEach(function (tab) {
				tab.addEventListener('click', function (e) {
					e.preventDefault();
					var target = tab.getAttribute('href');

					// Deactivate all.
					tabs.forEach(function (t) { t.parentElement.classList.remove('active'); });
					panels.forEach(function (p) { p.style.display = 'none'; p.classList.remove('active'); });

					// Activate clicked.
					tab.parentElement.classList.add('active');
					var panel = container.querySelector(target);
					if (panel) {
						panel.style.display = 'block';
						panel.classList.add('active');
					}
				});
			});
		});
	}
})();
