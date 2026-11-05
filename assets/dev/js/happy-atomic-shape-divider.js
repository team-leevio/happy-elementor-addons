/**
 * Happy Addons — Shape Divider, Elementor V4 (Atomic).
 *
 * Registers the feature with the Atomic runtime (happy-addons-atomic.js): the
 * runtime hands every Flexbox / Div Block / Grid that has the divider enabled
 * (frontend config) to ShapeDivider. In the editor the canvas renders Atomic
 * elements client side, so this script reads the settings from the editor and
 * rebuilds the shapes whenever one changes. The markup and styles follow
 * Elementor's own shape divider (inline SVG in an `elementor-shape` wrapper).
 */
(function (w, d) {
	'use strict';

	if (!w.HappyAtomic || 'function' !== typeof w.HappyAtomic.registerFeature) {
		return;
	}

	var SIDES = ['top', 'bottom'];
	var cache = {};

	function loadSvg(url) {
		if (!cache[url]) {
			cache[url] = fetch(url, {credentials: 'same-origin'}).then(function (response) {
				if (!response.ok) {
					throw new Error('Shape not found');
				}

				return response.text();
			}).then(function (text) {
				var svg = new DOMParser().parseFromString(text, 'image/svg+xml').documentElement;

				if (!svg || 'svg' !== svg.localName || svg.querySelector('parsererror')) {
					throw new Error('Invalid shape');
				}

				// The shapes are plugin files; drop anything executable regardless.
				svg.querySelectorAll('script, foreignObject').forEach(function (node) {
					node.remove();
				});

				return svg;
			}).catch(function () {
				delete cache[url];

				return null;
			});
		}

		return cache[url];
	}

	function ShapeDivider(el, config) {
		this.el = el;
		this.config = config || {};
		this.nodes = [];
		this.dead = false;
		this.saved = {position: el.style.position, isolation: el.style.isolation};
		this.build();
	}

	ShapeDivider.prototype.build = function () {
		var self = this;
		var c = this.config;
		var any = false;

		SIDES.forEach(function (side) {
			var url = c[side + '_url'];

			if (!c[side + '_shape'] || !url) {
				return;
			}

			any = true;

			var node = d.createElement('div');
			var width = parseFloat(c[side + '_width']);
			var height = parseFloat(c[side + '_height']);

			node.className = 'ha-sd-shape ha-sd-shape--' + side +
				(c[side + '_above_content'] ? ' ha-sd-shape--front' : '') +
				(c[side + '_flip'] ? ' ha-sd-shape--flip' : '');
			node.setAttribute('aria-hidden', 'true');
			node.setAttribute('data-negative', c[side + '_negative'] ? 'true' : 'false');

			if (c[side + '_color']) {
				node.style.setProperty('--ha-sd-color', c[side + '_color']);
			}

			if (width > 0) {
				node.style.setProperty('--ha-sd-width', width + '%');
			}

			if (height > 0) {
				node.style.setProperty('--ha-sd-height', height + 'px');
			}

			self.nodes.push(node);
			self.el.insertBefore(node, self.el.firstChild);

			loadSvg(url).then(function (svg) {
				if (svg && !self.dead && node.parentNode) {
					node.appendChild(d.importNode(svg, true));
				}
			});
		});

		if (!any) {
			return;
		}

		// The shapes are positioned against the container; behind its content they need its own stacking context.
		if ('static' === w.getComputedStyle(this.el).position) {
			this.el.style.position = 'relative';
		}

		this.el.style.isolation = 'isolate';
	};

	// The editor re-renders an element without notice, which drops the shapes with its markup.
	ShapeDivider.prototype.isStale = function () {
		var el = this.el;

		return this.nodes.some(function (node) {
			return node.parentNode !== el;
		});
	};

	ShapeDivider.prototype.destroy = function () {
		this.dead = true;

		this.nodes.forEach(function (node) {
			if (node.parentNode) {
				node.parentNode.removeChild(node);
			}
		});

		this.nodes = [];
		this.el.style.position = this.saved.position;
		this.el.style.isolation = this.saved.isolation;
	};

	/* ------------------------------------------------------------------ *
	 * Editor
	 * ------------------------------------------------------------------ */
	var TYPES = ['e-flexbox', 'e-div-block', 'e-grid'];
	var PREFIX = 'ha_sd_';
	var editorNodes = new Map();

	function unwrap(value) {
		while (value && 'object' === typeof value && '$$type' in value) {
			value = value.value;
		}

		return value;
	}

	function isEditor() {
		return d.body.classList.contains('elementor-editor-active') ||
			!!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
	}

	function editorContainer(id) {
		try {
			var editor = (w.parent && w.parent.elementor) || w.elementor;

			return editor && editor.getContainer ? editor.getContainer(id) : null;
		} catch (e) {
			return null;
		}
	}

	// Same rules as the PHP sanitizer: only keep what the chosen shape supports, and resolve its file.
	function editorConfig(container, defaults) {
		var settings = container.settings || (container.model && container.model.get('settings'));
		var attributes = settings ? (settings.attributes || (settings.toJSON && settings.toJSON())) : null;

		if (!attributes) {
			return null;
		}

		var shapes = defaults.shapes || {};
		var config = {};

		Object.keys(defaults).forEach(function (key) {
			if ('shapes' === key) {
				return;
			}

			var value = unwrap(attributes[PREFIX + key]);

			config[key] = (null === value || undefined === value || '' === value) ? defaults[key] : value;
		});

		SIDES.forEach(function (side) {
			var info = shapes[config[side + '_shape']];

			if (!info) {
				config[side + '_shape'] = '';

				return;
			}

			config[side + '_flip'] = !!info.flip && !!config[side + '_flip'];
			config[side + '_negative'] = !!info.invert && !!config[side + '_negative'];

			if (info.height_only) {
				config[side + '_width'] = 100;
			}

			config[side + '_url'] = config[side + '_negative'] ? info.negative : info.url;
		});

		return config;
	}

	function dropEditorNode(node) {
		var entry = editorNodes.get(node);

		if (entry) {
			entry.instance.destroy();
			editorNodes.delete(node);
		}
	}

	function scanEditor() {
		if (!isEditor()) {
			return;
		}

		var defaults = (w.HappyAtomic.defaults && w.HappyAtomic.defaults['shape-divider']) || null;

		if (!defaults) {
			return;
		}

		editorNodes.forEach(function (entry, node) {
			if (!d.body.contains(node)) {
				dropEditorNode(node);
			}
		});

		d.querySelectorAll('[data-id]').forEach(function (node) {
			var container = editorContainer(node.getAttribute('data-id'));
			var type = container && container.model ? (container.model.get('widgetType') || container.model.get('elType')) : null;

			if (!container || TYPES.indexOf(type) === -1) {
				return;
			}

			var config = editorConfig(container, defaults);
			var entry = editorNodes.get(node);

			if (!config || !config.switcher) {
				dropEditorNode(node);

				return;
			}

			var json = JSON.stringify(config);

			if (entry && entry.json === json && !entry.instance.isStale()) {
				return;
			}

			dropEditorNode(node);

			editorNodes.set(node, {json: json, instance: new ShapeDivider(node, config)});
		});
	}

	// The editor is only known once its frontend runs, so this always starts; on a
	// public page it stops by itself.
	var ticks = 0;
	var timer = setInterval(function () {
		scanEditor();

		if (!isEditor() && ++ticks > 100) {
			clearInterval(timer);
		}
	}, 300);

	// Frontend: the config PHP prints. An empty type keeps the shared scan from
	// building a second runtime on the same node in the editor, where the watcher
	// above owns the shapes.
	w.HappyAtomic.registerFeature({
		key: 'shape-divider',
		type: [],
		Runtime: ShapeDivider
	});
}(window, document));
