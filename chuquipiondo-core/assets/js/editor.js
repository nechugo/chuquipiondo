/**
 * CHUQUIPIONDO Core - Gutenberg editor blocks with live previews + inspectors.
 */
(function (blocks, element, components, data, editor) {
	'use strict';
	if (!blocks || !element) {
		return;
	}
	var el = element.createElement;
	var Fragment = element.Fragment;
	var useBlockProps = blocks.useBlockProps;
	var InspectorControls = editor && editor.InspectorControls ? editor.InspectorControls : null;
	var useSelect = data && data.useSelect ? data.useSelect : null;
	var RangeControl = components && components.RangeControl ? components.RangeControl : null;
	var SelectControl = components && components.SelectControl ? components.SelectControl : null;
	var TextControl = components && components.TextControl ? components.TextControl : null;

	function inspector(props, fields) {
		if (!InspectorControls) {
			return null;
		}
		return el(InspectorControls, {},
			el(components.PanelBody, {
				title: 'CHUQUIPIONDO',
				initialOpen: true
			}, fields.map(function (f) {
				var ctrl;
				if (f.type === 'range' && RangeControl) {
					ctrl = el(RangeControl, {
						key: f.attr,
						label: f.label,
						value: props.attributes[f.attr] || f.min,
						min: f.min,
						max: f.max,
						onChange: function (v) { props.setAttributes({ [f.attr]: v }); }
					});
				} else if (f.type === 'select' && SelectControl) {
					ctrl = el(SelectControl, {
						key: f.attr,
						label: f.label,
						value: props.attributes[f.attr] || f.options[0].value,
						options: f.options,
						onChange: function (v) { props.setAttributes({ [f.attr]: v }); }
					});
				} else if (TextControl) {
					ctrl = el(TextControl, {
						key: f.attr,
						label: f.label,
						value: props.attributes[f.attr] || '',
						onChange: function (v) { props.setAttributes({ [f.attr]: v }); }
					});
				}
				return ctrl;
			}))
		);
	}

	function postsPreview(props) {
		var attrs = props.attributes;
		var posts = [];
		if (useSelect) {
			posts = useSelect(function (select) {
				var q = select('core').getEntityRecords('postType', 'post', {
					per_page: attrs.count || 6,
					_embed: true
				});
				return q ? q.slice(0, attrs.count || 6) : [];
			}, [attrs.count]) || [];
		}
		if (!posts.length) {
			return el('div', { className: 'chuquipiondo-core-posts-placeholder' },
				el('p', null, 'Grid de articulos CHUQUIPIONDO (' + (attrs.count || 6) + ' posts, ' + (attrs.columns || 3) + ' columnas)')
			);
		}
		return el('div', {
			className: 'post-grid chuquipiondo-core-posts chuquipiondo-core-posts--cols-' + (attrs.columns || 3)
		}, posts.map(function (p) {
			var media = p._embedded && p._embedded['wp:featuredmedia'] && p._embedded['wp:featuredmedia'][0];
			return el('article', { key: p.id, className: 'post-card' },
				el('div', { className: 'post-card__media' },
					media ? el('img', { src: media.source_url, alt: p.title.rendered || '' }) : null
				),
				el('div', { className: 'post-card__body' },
					el('h3', { className: 'post-card__title' }, (p.title.rendered || '').replace(/<[^>]+>/g, ''))
				)
			);
		}));
	}

	var cardStyles = [
		{ value: 'editorial', label: 'Editorial' },
		{ value: 'elegant', label: 'Elegant' },
		{ value: 'magazine', label: 'Magazine' },
		{ value: 'minimal', label: 'Minimal' },
		{ value: 'image-focus', label: 'Image Focus' }
	];

	// Block: CHUQUIPIONDO Button
	blocks.registerBlockType('chuquipiondo/button', {
		edit: function (props) {
			var attrs = props.attributes;
			return el(Fragment, {},
				inspector(props, [
					{ type: 'text', attr: 'text', label: 'Texto del boton' },
					{ type: 'text', attr: 'url', label: 'URL (href)' }
				]),
				el('div', useBlockProps(),
					el('a', {
						href: '#',
						className: 'btn ' + (attrs.className || ''),
						onClick: function (e) { e.preventDefault(); }
					}, attrs.text || 'Boton')
				)
			);
		},
		save: function () {
			return null; // Dynamic block, rendered server-side.
		}
	});

	// Block: CHUQUIPIONDO Posts (live preview + inspector)
	blocks.registerBlockType('chuquipiondo/posts', {
		edit: function (props) {
			return el(Fragment, {},
				inspector(props, [
					{ type: 'range', attr: 'count', label: 'Articulos', min: 1, max: 12 },
					{ type: 'range', attr: 'columns', label: 'Columnas', min: 1, max: 4 },
					{ type: 'text', attr: 'category', label: 'Categoria (slug)' },
					{ type: 'select', attr: 'style', label: 'Estilo de tarjeta', options: cardStyles }
				]),
				el('div', useBlockProps(), postsPreview(props))
			);
		},
		save: function () {
			return null;
		}
	});

	// Block: CHUQUIPIONDO Music
	blocks.registerBlockType('chuquipiondo/music', {
		edit: function (props) {
			return el(Fragment, {},
				inspector(props, [
					{ type: 'range', attr: 'count', label: 'Canciones', min: 1, max: 12 },
					{ type: 'range', attr: 'columns', label: 'Columnas', min: 1, max: 4 }
				]),
				el('div', useBlockProps(),
					el('div', { className: 'chuquipiondo-core-music-placeholder' },
						el('p', null, 'Grid de musica CHUQUIPIONDO (' + (props.attributes.count || 4) + ' canciones)')
					)
				)
			);
		},
		save: function () {
			return null;
		}
	});

	// Block: CHUQUIPIONDO Categories
	blocks.registerBlockType('chuquipiondo/categories', {
		edit: function (props) {
			return el(Fragment, {},
				inspector(props, [
					{ type: 'range', attr: 'count', label: 'Categorias', min: 1, max: 12 }
				]),
				el('div', useBlockProps(),
					el('div', { className: 'chuquipiondo-core-categories-placeholder' },
						el('p', null, 'Categorias CHUQUIPIONDO (' + (props.attributes.count || 6) + ')')
					)
				)
			);
		},
		save: function () {
			return null;
		}
	});

	// Block: CHUQUIPIONDO Ad
	blocks.registerBlockType('chuquipiondo/ad', {
		edit: function (props) {
			return el(Fragment, {},
				inspector(props, [
					{ type: 'text', attr: 'slot', label: 'Slot de anuncio (ej. ads_blog_top)' }
				]),
				el('div', useBlockProps(),
					el('div', { className: 'chuquipiondo-core-ad-placeholder' },
						el('p', null, 'Slot de anuncio: ' + (props.attributes.slot || '(sin slot)'))
					)
				)
			);
		},
		save: function () {
			return null;
		}
	});
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.data, window.wp.editor);
