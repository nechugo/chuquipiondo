/**
 * Social share: copy-to-clipboard + share popup.
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initCopyLink();
		initSharePopup();
	});

	/**
	 * Copy-link buttons.
	 */
	function initCopyLink() {
		var copyLinks = document.querySelectorAll('.social-share__link--copy');
		copyLinks.forEach(function (link) {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				var url = link.getAttribute('data-copy-url') || window.location.href;
				copyToClipboard(url).then(function () {
					showCopiedFeedback(link);
				}).catch(function () {
					// Fallback: open a prompt.
					window.prompt(chuquipiondoSocial && chuquipiondoSocial.copyLabel || 'Copy', url);
				});
			});
		});
	}

	/**
	 * Copy text to clipboard (modern API with fallback).
	 */
	function copyToClipboard(text) {
		if (navigator.clipboard && navigator.clipboard.writeText) {
			return navigator.clipboard.writeText(text);
		}
		return new Promise(function (resolve, reject) {
			try {
				var textarea = document.createElement('textarea');
				textarea.value = text;
				textarea.style.position = 'fixed';
				textarea.style.opacity = '0';
				document.body.appendChild(textarea);
				textarea.select();
				document.execCommand('copy');
				document.body.removeChild(textarea);
				resolve();
			} catch (err) {
				reject(err);
			}
		});
	}

	/**
	 * Show a temporary "copied" feedback.
	 */
	function showCopiedFeedback(link) {
		var original = link.innerHTML;
		var label = (window.chuquipiondoSocial && window.chuquipiondoSocial.copied) || 'Copied';
		link.innerHTML = '<span style="font-size:10px;font-weight:700">' + label + '</span>';
		setTimeout(function () {
			link.innerHTML = original;
		}, 1500);
	}

	/**
	 * Open share links in a centered popup.
	 */
	function initSharePopup() {
		var links = document.querySelectorAll('.social-share__link');
		links.forEach(function (link) {
			if (link.classList.contains('social-share__link--copy') ||
				link.classList.contains('social-share__link--email')) {
				return;
			}
			link.addEventListener('click', function (e) {
				var href = link.getAttribute('href');
				if (!href || href.charAt(0) === '#') {
					return;
				}
				e.preventDefault();
				window.open(href, 'chuquipiondo-share', 'width=600,height=500,menubar=no,toolbar=no,resizable=yes,scrollbars=yes');
			});
		});
	}
})();
