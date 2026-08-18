/* CHUQUIPIONDO AI Studio - admin UI (vanilla JS, talks to the REST API). */
(function () {
	'use strict';

	var root = document.getElementById('chuquipiondo-ai-root');
	var nonce = root ? root.getAttribute('data-nonce') : (window.CAI && window.CAI.nonce) || '';
	var base = (root && root.getAttribute('data-rest')) || (window.CAI && window.CAI.rest) || '';
	var i18n = (window.CAI && window.CAI.i18n) || {};
	var currentId = 0;
	var currentType = 'post';
	var page = 1;

	function t(key, fallback) {
		return i18n[key] || fallback || key;
	}

	function api(method, path, body) {
		var opts = { method: method, headers: { 'X-WP-Nonce': nonce, 'Content-Type': 'application/json' }, credentials: 'same-origin' };
		if (body) { opts.body = JSON.stringify(body); }
		return fetch(base + path, opts).then(function (r) {
			return r.json().then(function (data) {
				if (!r.ok) {
					throw new Error((data && data.message) || ('HTTP ' + r.status));
				}
				return data;
			});
		});
	}

	function el(id) { return document.getElementById(id); }
	function status(node, msg, kind) {
		if (!node) { return; }
		node.textContent = msg || '';
		node.className = 'cai-status-msg ' + (kind || '');
	}

	/* ----------------------------- LIST ----------------------------- */
	function loadList() {
		var type = el('cai-post-type') ? el('cai-post-type').value : 'post';
		var s = el('cai-search') ? el('cai-search').value : '';
		currentType = type;
		var list = el('cai-list');
		if (list) { list.innerHTML = '<p>' + t('loading', 'Cargando...') + '</p>'; }
		api('GET', '/posts?post_type=' + encodeURIComponent(type) + '&s=' + encodeURIComponent(s) + '&paged=' + page).then(function (data) {
			renderList(data);
		}).catch(function (e) {
			if (list) { list.innerHTML = '<p class="cai-status-msg err">' + t('error', 'Error: ') + e.message + '</p>'; }
		});
	}

	function renderList(data) {
		var list = el('cai-list');
		var pager = el('cai-pager');
		if (!list) { return; }
		if (!data.items || !data.items.length) {
			list.innerHTML = '<p>' + t('loading', 'Sin resultados.') + '</p>';
			if (pager) { pager.innerHTML = ''; }
			return;
		}
		list.innerHTML = data.items.map(function (it) {
			var thumb = it.thumbnail ? '<img src="' + escAttr(it.thumbnail) + '" alt="">' : '<span class="cai-item__noimg"></span>';
			return '<div class="cai-item" data-id="' + it.id + '"><span>' + thumb + '</span>' +
				'<div><div>' + escHtml(it.title) + '</div>' +
				'<div class="cai-item__meta">' + escHtml(it.type) + ' &middot; ' + escHtml(it.status) + ' &middot; ' + it.image_count + ' imgs</div></div></div>';
		}).join('');
		[].forEach.call(list.querySelectorAll('.cai-item'), function (node) {
			node.addEventListener('click', function () {
				var id = parseInt(node.getAttribute('data-id'), 10);
				[].forEach.call(list.querySelectorAll('.cai-item'), function (n) { n.classList.remove('is-active'); });
				node.classList.add('is-active');
				loadPost(id);
			});
		});
		if (pager) {
			pager.innerHTML = '';
			for (var p = 1; p <= data.pages; p++) {
				var b = document.createElement('button');
				b.type = 'button'; b.className = 'button' + (p === data.pages ? '' : '');
				b.textContent = p;
				if (p === page) { b.classList.add('button-primary'); }
				(function (pp) { b.addEventListener('click', function () { page = pp; loadList(); }); })(p);
				pager.appendChild(b);
			}
		}
	}

	/* ----------------------------- POST ----------------------------- */
	function loadPost(id) {
		currentId = id;
		var empty = el('cai-empty'); var editor = el('cai-editor');
		if (empty) { empty.hidden = true; }
		if (editor) { editor.hidden = false; }
		status(el('cai-status-msg'), t('loading', 'Cargando...'), '');
		api('GET', '/post/' + id).then(function (p) {
			if (el('cai-title')) { el('cai-title').value = p.title; }
			if (el('cai-content')) { el('cai-content').value = p.content; }
			if (el('cai-meta-desc')) { el('cai-meta-desc').value = p.meta_desc || ''; }
			if (el('cai-tags')) { el('cai-tags').value = (p.tags || []).map(function (x) { return x.name; }).join(', '); }
			if (el('cai-slug')) { el('cai-slug').value = p.slug; }
			if (el('cai-excerpt')) { el('cai-excerpt').value = p.excerpt; }
			if (el('cai-status')) { el('cai-status').value = (p.status === 'publish') ? 'publish' : 'draft'; }
			renderImages(p.images);
			status(el('cai-status-msg'), '', '');
		}).catch(function (e) { status(el('cai-status-msg'), t('error', 'Error: ') + e.message, 'err'); });
	}

	function renderImages(info) {
		var box = el('cai-images-report');
		if (!box || !info) { return; }
		var html = '<p><strong>' + t('firstImg', 'Primera imagen') + ':</strong> ' + (info.first_image ? info.first_image.width + 'x' + info.first_image.height : t('noImage', 'Sin imagen destacada')) + '</p>';
		html += '<p>' + t('extra', 'Imagenes adicionales') + ': ' + (info.additional_images ? info.additional_images.length : 0) + '</p>';
		html += '<p>' + t('recommended', 'Espacios recomendados para mas imagenes') + ': ' + info.recommended_extra + '</p>';
		if (info.needs_resize && info.needs_resize.length) {
			html += '<p>' + t('needsResize', 'Imagenes que no respetan 500x900') + ': ' + info.needs_resize.length + '</p>';
		}
		html += '<p>Objetivo: ' + info.target_width + 'x' + info.target_height + ' px</p>';
		box.innerHTML = html;
	}

	function save(view) {
		if (!currentId) { return; }
		var body = {
			id: currentId,
			title: el('cai-title') ? el('cai-title').value : null,
			content: el('cai-content') ? el('cai-content').value : null,
			excerpt: el('cai-excerpt') ? el('cai-excerpt').value : null,
			slug: el('cai-slug') ? el('cai-slug').value : null,
			status: el('cai-status') ? el('cai-status').value : null,
			meta_desc: el('cai-meta-desc') ? el('cai-meta-desc').value : null,
			tags: el('cai-tags') ? el('cai-tags').value.split(',').map(function (s) { return s.trim(); }).filter(Boolean) : null
		};
		status(el('cai-status-msg'), t('loading', 'Guardando...'), '');
		api('POST', '/update', body).then(function (r) {
			status(el('cai-status-msg'), t('saved', 'Guardado.'), 'ok');
			if (r && r.images) { renderImages(r.images); }
			if (view) {
				var url = (window.location.origin + '/?p=' + currentId);
				window.open(url, '_blank');
			}
		}).catch(function (e) { status(el('cai-status-msg'), t('error', 'Error: ') + e.message, 'err'); });
	}

	/* ----------------------------- TASKS ----------------------------- */
	function runTask(task, ctxParam, promptParam, extra) {
		var body = { task: task, context: ctxParam, prompt: promptParam };
		if (extra) { for (var k in extra) { body[k] = extra[k]; } }
		status(el('cai-status-msg'), t('generating', 'Generando con IA...'), '');
		return api('POST', '/generate', body).then(function (r) {
			status(el('cai-status-msg'), t('saved', 'Listo.'), 'ok');
			return r;
		}).catch(function (e) {
			status(el('cai-status-msg'), t('error', 'Error: ') + e.message, 'err');
			throw e;
		});
	}

	function onContentTask(task) {
		var ctx = el('cai-content') ? el('cai-content').value : '';
		var prompt = el('cai-prompt') ? el('cai-prompt').value : '';
		runTask(task, ctx, prompt).then(function (r) {
			if (el('cai-content') && r && r.content) {
				if (task === 'generate_paragraphs') {
					el('cai-content').value += '\n' + r.content;
				} else {
					el('cai-content').value = r.content;
				}
			}
		}).catch(function () {});
	}

	/* ----------------------------- IMAGES ----------------------------- */
	function analyzeImages() {
		if (!currentId) { return; }
		status(el('cai-status-msg'), t('analyzing', 'Analizando imagenes...'), '');
		api('POST', '/analyze-images', { id: currentId }).then(function (info) {
			renderImages(info); status(el('cai-status-msg'), t('saved', 'Analizado.'), 'ok');
		}).catch(function (e) { status(el('cai-status-msg'), t('error', 'Error: ') + e.message, 'err'); });
	}

	function forceResize() {
		var c = el('cai-content');
		if (!c) { return; }
		// Server-side normalization happens on save; here we just flag the content.
		var content = c.value;
		// Replace or add width/height on <img> client-side (matches server target 500x900).
		content = content.replace(/<img\b([^>]*?)(\swidth=(["'])\d+\3)?([^>]*?)(\sheight=(["'])\d+\6)?([^>]*?)>/gi, function (m, a, w, wq, b, h, hq, c2) {
			return '<img' + a + ' width="900"' + b + ' height="500"' + c2 + '>';
		});
		c.value = content;
		status(el('cai-status-msg'), t('saved', 'Marcadas a 500x900. Guarda para aplicar.'), 'ok');
	}

	function addImage() {
		var desc = window.prompt('Describe la imagen que quieres anadir (500x900):');
		if (!desc) { return; }
		runTask('image_alt', desc, '').then(function () {
			// Use the AI generation flow via the publish image endpoint is heavy; instead inject marker.
			var c = el('cai-content');
			if (c) {
				c.value += '\n<!--AI_IMAGE:' + desc.replace(/-->/g, '') + '-->';
			}
			status(el('cai-status-msg'), 'Marcador de imagen IA insertado. Guarda para generarla.', 'ok');
		}).catch(function () {});
	}

	/* ----------------------------- CODE ----------------------------- */
	function generateCode() {
		var lang = el('cai-code-lang') ? el('cai-code-lang').value : 'html';
		var desc = el('cai-code-desc') ? el('cai-code-desc').value : '';
		runTask('generate_code', desc, '', { language: lang }).then(function (r) {
			if (el('cai-code-out') && r && r.content) { el('cai-code-out').textContent = r.content; }
		}).catch(function () {});
	}
	function insertCode() {
		var c = el('cai-content'); var out = el('cai-code-out');
		if (c && out && out.textContent) { c.value += '\n' + out.textContent; status(el('cai-status-msg'), t('saved', 'Codigo insertado.'), 'ok'); }
	}

	/* ----------------------------- SEO ----------------------------- */
	function generateSeo() {
		var ctx = el('cai-content') ? el('cai-content').value : '';
		runTask('seo_meta', ctx, '').then(function (r) {
			if (!r || !r.content) { return; }
			var parts = r.content.split(/\nKEYWORDS:\s*/i);
			if (el('cai-meta-desc')) { el('cai-meta-desc').value = (parts[0] || '').trim(); }
			if (el('cai-tags') && parts[1]) { el('cai-tags').value = parts[1].trim(); }
			if (el('cai-excerpt') && !el('cai-excerpt').value) { el('cai-excerpt').value = (parts[0] || '').trim(); }
		}).catch(function () {});
	}

	/* ----------------------------- GENERATE PAGE ----------------------------- */
	function runGenerate() {
		var body = {
			topic: el('cai-gen-topic') ? el('cai-gen-topic').value : '',
			title: el('cai-gen-title') ? el('cai-gen-title').value : '',
			post_type: el('cai-gen-type') ? el('cai-gen-type').value : 'post',
			words: el('cai-gen-words') ? parseInt(el('cai-gen-words').value, 10) : 800,
			images: el('cai-gen-images') ? parseInt(el('cai-gen-images').value, 10) : 3,
			prompt: el('cai-gen-prompt') ? el('cai-gen-prompt').value : '',
			force_publish: (document.querySelector('input[name="cai-gen-status"]:checked') || {}).value === 'publish'
		};
		var box = el('cai-gen-result'); var st = el('cai-gen-status');
		if (box) { box.innerHTML = ''; }
		status(st, t('generating', 'Generando con IA... (puede tardar 30-60s)'), '');
		api('POST', '/publish', body).then(function (r) {
			status(st, t('saved', 'Articulo creado.'), 'ok');
			if (box && r) {
				box.innerHTML = '<div class="ok"><p><strong>Titulo:</strong> ' + escHtml(r.title) + '</p>' +
					'<p><strong>Slug:</strong> ' + escHtml(r.slug) + '</p>' +
					'<p><strong>Estado:</strong> ' + escHtml(r.status) + '</p>' +
					'<p><strong>SEO:</strong> ' + escHtml((r.seo && r.seo.meta_description) || '') + '</p>' +
					'<p><strong>Etiquetas:</strong> ' + escHtml(((r.seo && r.seo.tags) || []).join(', ')) + '</p>' +
					'<p><a class="button button-primary" href="' + escAttr(r.edit_url) + '">Editar</a> ' +
					'<a class="button" href="' + escAttr(r.post_url) + '" target="_blank">Ver</a></p></div>';
			}
		}).catch(function (e) {
			status(st, t('error', 'Error: ') + e.message, 'err');
			if (box) { box.innerHTML = '<div class="err">' + escHtml(e.message) + '</div>'; }
		});
	}

	/* ----------------------------- WIRING ----------------------------- */
	function escHtml(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }
	function escAttr(s) { return String(s == null ? '' : s).replace(/"/g, '&quot;'); }

	function wireTabs() {
		var tabs = document.querySelectorAll('.cai-tab');
		tabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				var target = tab.getAttribute('data-tab');
				tabs.forEach(function (x) { x.classList.toggle('is-active', x === tab); });
				document.querySelectorAll('.cai-tabpanel').forEach(function (panel) {
					panel.hidden = panel.getAttribute('data-panel') !== target;
				});
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		wireTabs();
		if (el('cai-refresh')) { el('cai-refresh').addEventListener('click', loadList); }
		if (el('cai-search')) { var to; el('cai-search').addEventListener('input', function () { clearTimeout(to); to = setTimeout(loadList, 350); }); }
		if (el('cai-post-type')) { el('cai-post-type').addEventListener('change', function () { page = 1; loadList(); }); }
		document.querySelectorAll('[data-task]').forEach(function (b) {
			b.addEventListener('click', function () { onContentTask(b.getAttribute('data-task')); });
		});
		if (el('cai-analyze-images')) { el('cai-analyze-images').addEventListener('click', analyzeImages); }
		if (el('cai-resize-images')) { el('cai-resize-images').addEventListener('click', forceResize); }
		if (el('cai-add-image')) { el('cai-add-image').addEventListener('click', addImage); }
		if (el('cai-seo-generate')) { el('cai-seo-generate').addEventListener('click', generateSeo); }
		if (el('cai-generate-code')) { el('cai-generate-code').addEventListener('click', generateCode); }
		if (el('cai-insert-code')) { el('cai-insert-code').addEventListener('click', insertCode); }
		if (el('cai-save')) { el('cai-save').addEventListener('click', function () { save(false); }); }
		if (el('cai-save-view')) { el('cai-save-view').addEventListener('click', function () { save(true); }); }
		if (el('cai-gen-run')) { el('cai-gen-run').addEventListener('click', runGenerate); }
		if (document.querySelector('.chuquipiondo-ai-editor')) { loadList(); }
	});
})();
