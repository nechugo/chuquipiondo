/**
 * CHUQUIPIONDO Core - Gutenberg editor blocks.
 */

(function (blocks, element) {
	'use strict';

	if (!blocks || !element) {
		return;
	}

	var el = element.createElement;
	var Fragment = element.Fragment;
	var useBlockProps = blocks.useBlockProps;

	// Block: CHUQUIPIONDO Button
	blocks.registerBlockType('chuquipiondo/button', {
		edit: function (props) {
			var attrs = props.attributes;
			return el('div', useBlockProps(),
				el('a', {
					href: '#',
					className: 'btn ' + (attrs.className || ''),
					onClick: function (e) { e.preventDefault(); }
				}, attrs.text || 'Boton')
			);
		},
		save: function () {
			return null; // Dynamic block, rendered server-side.
		}
	});

	// Block: CHUQUIPIONDO Posts
	blocks.registerBlockType('chuquipiondo/posts', {
		edit: function (props) {
			return el('div', useBlockProps(),
				el('div', { className: 'chuquipiondo-core-posts-placeholder' },
					el('p', null, 'Grid de articulos CHUQUIPIONDO (' + (props.attributes.count || 6) + ' posts, ' + (props.attributes.columns || 3) + ' columnas)')
				)
			);
		},
		save: function () {
			return null;
		}
	});

	// Block: CHUQUIPIONDO Music
	blocks.registerBlockType('chuquipiondo/music', {
		edit: function (props) {
			return el('div', useBlockProps(),
				el('div', { className: 'chuquipiondo-core-music-placeholder' },
					el('p', null, 'Grid de musica CHUQUIPIONDO')
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
			return el('div', useBlockProps(),
				el('div', { className: 'chuquipiondo-core-categories-placeholder' },
					el('p', null, 'Categorias CHUQUIPIONDO')
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
			return el('div', useBlockProps(),
				el('div', { className: 'chuquipiondo-core-ad-placeholder' },
					el('p', null, 'Slot de anuncio: ' + (props.attributes.slot || '(sin slot)'))
				)
			);
		},
		save: function () {
			return null;
		}
	});

})(window.wp.blocks, window.wp.element);
