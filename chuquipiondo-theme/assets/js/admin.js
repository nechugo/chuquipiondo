/**
 * Admin panel: confirm dialogs for reset/import.
 */

(function ($) {
	'use strict';

	$(function () {
		$('#chuquipiondo-reset-btn').on('click', function (e) {
			var msg = (window.chuquipiondoAdmin && chuquipiondoAdmin.strings.confirmReset) || 'Continue?';
			if (!window.confirm(msg)) {
				e.preventDefault();
			}
		});

		$('#chuquipiondo-import-form').on('submit', function (e) {
			var msg = (window.chuquipiondoAdmin && chuquipiondoAdmin.strings.confirmImport) || 'Continue?';
			if (!window.confirm(msg)) {
				e.preventDefault();
			}
		});
	});

})(jQuery);
