/**
 * WhatsApp float: lightweight, handles pre-filled message.
 * The pulse effect is CSS-driven and respects prefers-reduced-motion.
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var btn = document.querySelector('.chuqui-whatsapp');
		if (!btn) {
			return;
		}

		// Pre-fill the message from the current page title.
		var url = btn.getAttribute('href');
		if (url && url.indexOf('wa.me') !== -1 && url.indexOf('text=') === -1) {
			var title = encodeURIComponent(document.title);
			btn.setAttribute('href', url + '?text=' + title);
		}

		// Track click (optional analytics hook).
		btn.addEventListener('click', function () {
			if (typeof window.gtag === 'function') {
				window.gtag('event', 'whatsapp_click', {
					event_category: 'engagement',
					event_label: 'float_button',
				});
			}
		});
	});
})();
