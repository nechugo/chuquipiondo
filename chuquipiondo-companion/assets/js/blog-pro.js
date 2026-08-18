/**
 * CHUQUIPIONDO Companion - Blog Pro interactions.
 *
 * - Category filters: fetch posts via AJAX and swap the grid.
 * - Load more: append the next page without a full reload.
 */
(function () {
	'use strict';

	function init() {
		var container = document.querySelector('.chuqui-blog-pro-filters') || document.body;
		var config = window.chuquiBlogPro || { ajaxUrl: '', nonce: '', i18n: {} };

		// Filter buttons.
		var filterBtns = document.querySelectorAll('.blog-pro-filter');
		filterBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				filterBtns.forEach(function (b) { b.classList.remove('is-active'); });
				btn.classList.add('is-active');
				loadPosts(btn.getAttribute('data-cat'), 1);
			});
		});

		// Load more button (delegated because it may be replaced).
		document.addEventListener('click', function (e) {
			var btn = e.target.closest('.blog-pro-loadmore__btn');
			if (!btn) {
				return;
			}
			var grid = document.querySelector('.post-grid');
			if (!grid) {
				return;
			}
			btn.disabled = true;
			var label = btn.querySelector('.blog-pro-loadmore__label');
			var prev = label ? label.textContent : '';
			if (label) { label.textContent = config.i18n.loading || '...'; }

			var cat = container.getAttribute('data-current') || 0;
			var page = parseInt(btn.getAttribute('data-page'), 10) + 1;
			var style = document.querySelector('.chuqui-blog-pro');
			style = style ? style.getAttribute('data-style') : 'editorial';
			var columns = document.querySelector('.chuqui-blog-pro');
			columns = columns ? columns.getAttribute('data-columns') : 3;

			fetch(config.ajaxUrl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: new URLSearchParams({
					action: 'chuquipiondo_blog_pro',
					nonce: config.nonce,
					page: page,
					cat: cat,
					style: style,
					columns: columns
				})
			})
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if (res && res.html) {
						var tmp = document.createElement('div');
						tmp.innerHTML = res.html;
						var newGrid = tmp.querySelector('.post-grid');
						if (newGrid) {
							while (newGrid.firstChild) {
								grid.appendChild(newGrid.firstChild);
							}
						}
					}
					if (!res || !res.has_more) {
						btn.remove();
					} else {
						btn.setAttribute('data-page', res.next_page);
						btn.disabled = false;
						if (label) { label.textContent = prev; }
					}
				})
				.catch(function () {
					btn.disabled = false;
					if (label) { label.textContent = config.i18n.error || 'Error'; }
				});
		});
	}

	function loadPosts(cat, page) {
		var grid = document.querySelector('.post-grid');
		if (!grid) { return; }
		var config = window.chuquiBlogPro || { ajaxUrl: '', nonce: '' };
		var wrapper = document.querySelector('.chuqui-blog-pro');
		var style = wrapper ? wrapper.getAttribute('data-style') : 'editorial';
		var columns = wrapper ? wrapper.getAttribute('data-columns') : 3;

		grid.style.opacity = '0.5';

		fetch(config.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams({
				action: 'chuquipiondo_blog_pro',
				nonce: config.nonce,
				page: page,
				cat: cat,
				style: style,
				columns: columns
			})
		})
			.then(function (r) { return r.json(); })
			.then(function (res) {
				if (res && res.html) {
					var tmp = document.createElement('div');
					tmp.innerHTML = res.html;
					var newGrid = tmp.querySelector('.post-grid');
					if (newGrid) {
						grid.innerHTML = newGrid.innerHTML;
					}
				}
				grid.style.opacity = '';
			})
			.catch(function () { grid.style.opacity = ''; });
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
