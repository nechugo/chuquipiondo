/**
 * CHUQUIPIONDO Companion - Admin interactions.
 *
 * - Mega menu meta box: persists the toggle on the selected nav item
 *   via the WP REST/AJAX menu endpoint.
 */
(function () {
	'use strict';

	function init() {
		var saveBtn = document.getElementById('chuqui-mega-save');
		if (!saveBtn) {
			return;
		}

		saveBtn.addEventListener('click', function () {
			var checkbox = document.getElementById('chuqui-mega-enable');
			var enabled = checkbox && checkbox.checked ? 1 : 0;

			// Gather selected menu items from the nav-menus screen.
			var ids = [];
			document.querySelectorAll('#menu-to-edit .menu-item-checkbox:checked').forEach(function (cb) {
				ids.push(cb.value);
			});

			if (!ids.length) {
				window.alert(chuquiCompanionAdmin.i18n.noItem);
				return;
			}

			saveBtn.disabled = true;
			var prev = saveBtn.textContent;
			saveBtn.textContent = chuquiCompanionAdmin.i18n.saving;

			fetch(chuquiCompanionAdmin.ajaxUrl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: new URLSearchParams({
					action: 'chuquipiondo_mega_save',
					nonce: chuquiCompanionAdmin.nonce,
					ids: ids.join(','),
					enabled: enabled
				})
			})
				.then(function (r) { return r.json(); })
				.then(function (res) {
					saveBtn.disabled = false;
					saveBtn.textContent = prev;
					if (res && res.success) {
						window.alert(chuquiCompanionAdmin.i18n.saved);
					} else {
						window.alert(chuquiCompanionAdmin.i18n.error);
					}
				})
				.catch(function () {
					saveBtn.disabled = false;
					saveBtn.textContent = prev;
					window.alert(chuquiCompanionAdmin.i18n.error);
				});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
