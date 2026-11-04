/**
 * Happy Addons — Background Parallax, Elementor V4 (Atomic).
 *
 * Registers the feature with the Atomic runtime (happy-addons-atomic.js): the
 * runtime hands every Flexbox / Div Block that has the parallax enabled
 * (frontend config) to BackgroundParallax. In the editor the canvas renders
 * Atomic elements client side, so this script reads the settings from the editor
 * and rebuilds the effect whenever one changes. The effect is the Classic one
 * (background-parallax.js): Jarallax for the scroll types, a moving background
 * position for Auto Moving.
 */
(function (w, d) {
	'use strict';

	if (!w.HappyAtomic || 'function' !== typeof w.HappyAtomic.registerFeature) {
		return;
	}

	function num(value, fallback) {
		var parsed = parseFloat(value);

		return isNaN(parsed) ? fallback : parsed;
	}

	function deviceFilter(android, ios) {
		// Same table as Classic: the devices the parallax is NOT enabled on.
		if (android && ios) {
			return /iPad|iPhone|iPod|Android/;
		}

		if (android) {
			return /Android/;
		}

		return ios ? /iPad|iPhone|iPod/ : null;
	}

	function BackgroundParallax(el, config) {
		this.el = el;
		this.config = config || {};
		this.observer = null;
		this.timer = null;
		this.jarallax = false;
		this.saved = el.style.backgroundPosition;
		this.start();
	}

	BackgroundParallax.prototype.start = function () {
		var self = this;
		var c = this.config;

		if ('automove' === c.type) {
			this.autoMove();

			return;
		}

		var style = w.getComputedStyle(this.el);
		var options = {
			type: c.type,
			speed: num(c.speed, 0.5) || 0.5,
			keepImg: true,
			imgSize: style.backgroundSize,
			imgPosition: style.backgroundPosition,
			imgRepeat: style.backgroundRepeat,
			// A setting of "Enable on ..." turns the parallax on for that device.
			disableParallax: deviceFilter(!c.enable_on_android, !c.enable_on_ios)
		};

		// Start once the element is on screen, like Classic.
		this.observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting && 'function' === typeof w.jarallax) {
					self.observer.disconnect();
					self.observer = null;
					w.jarallax(self.el, options);
					self.jarallax = true;
				}
			});
		});
		this.observer.observe(this.el);
	};

	BackgroundParallax.prototype.autoMove = function () {
		var el = this.el;
		var speed = parseInt(this.config.speed, 10) || 0.1;
		var direction = this.config.direction || 'left';
		var position = 0;

		if (/^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
			el.classList.add('ha-bg-parallax-no-trans');
		}

		el.style.backgroundPosition = '0px 0px';

		this.timer = setInterval(function () {
			position += ('left' === direction || 'top' === direction) ? speed : -speed;
			el.style.backgroundPosition = ('left' === direction || 'right' === direction) ? position + 'px 0' : '0 ' + position + 'px';
		}, 70);
	};

	BackgroundParallax.prototype.destroy = function () {
		if (this.observer) {
			this.observer.disconnect();
			this.observer = null;
		}

		if (this.jarallax && 'function' === typeof w.jarallax) {
			try {
				w.jarallax(this.el, 'destroy');
			} catch (e) {}

			this.jarallax = false;
		}

		if (this.timer) {
			clearInterval(this.timer);
			this.timer = null;
		}

		this.el.classList.remove('ha-bg-parallax-no-trans');
		this.el.style.backgroundPosition = this.saved;
	};

	/* ------------------------------------------------------------------ *
	 * Editor
	 * ------------------------------------------------------------------ */
	var TYPES = ['e-flexbox', 'e-div-block', 'e-grid'];
	var PREFIX = 'ha_bgp_';
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

	function editorConfig(container, defaults) {
		var settings = container.settings || (container.model && container.model.get('settings'));
		var attributes = settings ? (settings.attributes || (settings.toJSON && settings.toJSON())) : null;

		if (!attributes) {
			return null;
		}

		var config = {};

		Object.keys(defaults).forEach(function (key) {
			var value = unwrap(attributes[PREFIX + key]);

			config[key] = (null === value || undefined === value || '' === value) ? defaults[key] : value;
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

		var defaults = (w.HappyAtomic.defaults && w.HappyAtomic.defaults['background-parallax']) || {};

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

			if (entry && entry.json === json) {
				return;
			}

			dropEditorNode(node);

			editorNodes.set(node, {json: json, instance: new BackgroundParallax(node, config)});
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
	// above owns the effect.
	w.HappyAtomic.registerFeature({
		key: 'background-parallax',
		type: [],
		Runtime: BackgroundParallax
	});
}(window, document));
