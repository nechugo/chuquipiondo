/**
 * CHUQUIPIONDO Companion - Mega Menu interactions.
 *
 * Handles click-trigger mode (hover is CSS-driven) and closes the
 * panel on outside click / Escape for keyboard accessibility.
 */
(function () {
	'use strict';

	var trigger = (window.chuquiCompanion && window.chuquiCompanion.megaTrigger) || 'hover';

	function init() {
		var megaItems = document.querySelectorAll('.menu-item-mega');
		if (!megaItems.length) {
			return;
		}

		megaItems.forEach(function (item) {
			var panel = item.querySelector('.chuqui-mega-panel');
			if (!panel) {
				return;
			}
			// Keyboard: Enter/Space toggles the panel.
			var link = item.querySelector('a');
			if (link) {
				link.addEventListener('keydown', function (e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						var open = item.classList.contains('is-open');
						closeAll();
						if (!open) {
							item.classList.add('is-open');
							item.querySelector('.chuqui-mega-panel').style.display = 'grid';
							panel.setAttribute('aria-hidden', 'false');
						}
					}
				});
			}
		});

		// Close on Escape.
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				closeAll();
			}
		});

		// Close on outside click.
		document.addEventListener('click', function (e) {
			if (!e.target.closest('.menu-item-mega')) {
				closeAll();
			}
		});
	}

	function closeAll() {
		document.querySelectorAll('.menu-item-mega.is-open').forEach(function (item) {
			item.classList.remove('is-open');
			var panel = item.querySelector('.chuqui-mega-panel');
			if (panel) {
				panel.style.display = '';
				panel.setAttribute('aria-hidden', 'true');
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
