/**
 * Happy Addons — Happy Tooltip for Elementor V4 (Atomic) elements.
 *
 * One floating tooltip per element, appended to <body> and placed from the
 * target's bounding box (Atomic elements cannot take a child: images are void
 * and the editor re-renders them client side). Styled by the --ha-att-*
 * variables set here; the rest is in css/widgets/atomic/tooltip.css.
 *
 * Frontend: the sanitized configs come from PHP (Tooltip::sanitize_config) in
 * window.HappyAtomic.features['tooltip'], keyed by the element's data-id.
 * Editor preview: the canvas renders Atomic elements client side, so the config
 * is built from the editor settings instead, with window.HappyAtomic.defaults['tooltip']
 * as the fallback (keep both in sync).
 */
(function (w, d) {
	'use strict';

	var KEY = 'tooltip';
	var PREFIX = 'ha_tt_';
	var COLOR = /^(#[0-9a-f]{3,8}|(rgb|hsl)a?\([0-9.%,\s\/-]+\)|[a-z]+)$/i;
	var POSITIONS = ['top', 'bottom', 'left', 'right'];
	var ANIMATIONS = ['none', 'fadeIn', 'zoomIn', 'rollIn', 'bounce', 'slideInDown', 'slideInLeft', 'slideInRight', 'slideInUp'];
	var ALIGNS = ['left', 'center', 'right'];
	var VIEWPORT_GAP = 8;

	var instances = {}; // data-id => Tooltip
	var openTooltip = null;
	var counter = 0;

	function isEditor() {
		return d.body.classList.contains('elementor-editor-active') ||
			!!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
	}

	function num(value, fallback, min, max) {
		var parsed = parseFloat(value);

		if (isNaN(parsed)) {
			return fallback;
		}

		if (undefined !== min) {
			parsed = Math.max(min, parsed);
		}

		if (undefined !== max) {
			parsed = Math.min(max, parsed);
		}

		return parsed;
	}

	function pick(value, list, fallback) {
		return list.indexOf(value) > -1 ? value : fallback;
	}

	function color(value, fallback) {
		return COLOR.test(String(value || '').trim()) ? String(value).trim() : fallback;
	}

	function truthy(value) {
		return true === value || 'true' === value || 1 === value || '1' === value;
	}

	function unwrapProp(value) {
		while (value && 'object' === typeof value && '$$type' in value) {
			value = value.value;
		}

		return value;
	}

	function editorSettings(id) {
		try {
			var editor = (w.parent && w.parent.elementor) || w.elementor;
			var container = editor && editor.getContainer && editor.getContainer(id);
			var settings = container && (container.settings || (container.model && container.model.get('settings')));

			return settings ? (settings.attributes || (settings.toJSON && settings.toJSON())) : null;
		} catch (e) {
			return null;
		}
	}

	// Settings as the frontend config: prefix stripped, defaults filled in.
	function editorConfig(settings, defaults) {
		var config = {};

		Object.keys(defaults).forEach(function (key) {
			var value = unwrapProp(settings[PREFIX + key]);

			// An emptied content field means "no tooltip", not the default text.
			config[key] = (null === value || undefined === value || ('' === value && 'content' !== key)) ? defaults[key] : value;
		});

		return config;
	}

	// Validates a config (frontend or editor) into the values the tooltip renders with.
	function normalize(config) {
		return {
			content: String(config.content || ''),
			position: pick(config.position, POSITIONS, 'top'),
			animation: pick(config.animation, ANIMATIONS, 'none'),
			duration: num(config.duration, 1000, 100, 5000),
			arrow: truthy(config.arrow),
			trigger: 'click' === config.trigger ? 'click' : 'hover',
			distance: num(config.distance, 0, -100, 500),
			align: pick(config.align, ALIGNS, 'center'),
			width: num(config.width, 120, 1, 800),
			arrowSize: num(config.arrow_size, 5, 1, 100),
			fontSize: num(config.font_size, 14, 8, 100),
			fontWeight: num(config.font_weight, 500, 100, 900),
			bg: color(config.bg_color, '#000000'),
			color: color(config.color, '#ffffff'),
			radius: num(config.radius, 6, 0, 100),
			paddingY: num(config.padding_y, 5, 0, 100),
			paddingX: num(config.padding_x, 10, 0, 100)
		};
	}

	// The widget's own element: Atomic widgets sit in a display:contents wrapper (data-atomic).
	function resolveTarget(id) {
		var node = d.querySelector('[data-id="' + id.replace(/[^a-zA-Z0-9_-]/g, '') + '"]');

		if (!node) {
			return null;
		}

		return node.hasAttribute('data-atomic') ? node.firstElementChild : node;
	}

	function setContent(el, html) {
		if (w.DOMPurify) {
			el.innerHTML = w.DOMPurify.sanitize(html);
		} else {
			el.textContent = html;
		}
	}

	function Tooltip(id, target, config) {
		this.id = id;
		this.target = target;
		this.c = normalize(config);
		this.visible = false;
		this.tip = null;
		this.onScroll = this.place.bind(this);
		this.listeners = [];

		if ('' === this.c.content.trim()) {
			return;
		}

		this.build();
		this.bind();
	}

	Tooltip.prototype.build = function () {
		var c = this.c;
		var tip = d.createElement('div');
		var style = tip.style;

		tip.className = 'ha-atomic-tooltip';
		tip.id = 'ha-att-' + (++counter);
		tip.setAttribute('role', 'tooltip');
		tip.setAttribute('data-position', c.position);
		tip.setAttribute('data-animation', c.animation);
		tip.setAttribute('data-trigger', c.trigger);
		tip.setAttribute('data-arrow', c.arrow ? 'true' : 'false');

		style.setProperty('--ha-att-width', c.width + 'px');
		style.setProperty('--ha-att-padding-y', c.paddingY + 'px');
		style.setProperty('--ha-att-padding-x', c.paddingX + 'px');
		style.setProperty('--ha-att-radius', c.radius + 'px');
		style.setProperty('--ha-att-bg', c.bg);
		style.setProperty('--ha-att-color', c.color);
		style.setProperty('--ha-att-font-size', c.fontSize + 'px');
		style.setProperty('--ha-att-font-weight', c.fontWeight);
		style.setProperty('--ha-att-align', c.align);
		style.setProperty('--ha-att-duration', c.duration + 'ms');
		style.setProperty('--ha-att-arrow-size', c.arrowSize + 'px');

		setContent(tip, c.content);
		d.body.appendChild(tip);
		this.tip = tip;
	};

	Tooltip.prototype.on = function (node, type, handler, options) {
		node.addEventListener(type, handler, options);
		this.listeners.push([node, type, handler, options]);
	};

	Tooltip.prototype.bind = function () {
		var self = this;
		var show = function () { self.show(); };
		var hide = function () { self.hide(); };

		if ('click' === this.c.trigger) {
			this.on(this.target, 'click', function () {
				self.visible ? self.hide() : self.show();
			});
			this.on(d, 'click', function (event) {
				if (self.visible && !self.target.contains(event.target) && !self.tip.contains(event.target)) {
					self.hide();
				}
			});
		} else {
			this.on(this.target, 'mouseenter', show);
			this.on(this.target, 'mouseleave', hide);
			this.on(this.target, 'focusin', show);
			this.on(this.target, 'focusout', hide);
		}

		this.on(d, 'keydown', function (event) {
			if ('Escape' === event.key && self.visible) {
				self.hide();
			}
		});
	};

	// Places the tooltip next to the target, kept inside the viewport; the arrow keeps pointing at the target.
	Tooltip.prototype.place = function () {
		if (!this.tip || !this.target.isConnected) {
			return;
		}

		var c = this.c;
		var rect = this.target.getBoundingClientRect();
		var tipW = this.tip.offsetWidth;
		var tipH = this.tip.offsetHeight;
		var gap = c.distance + (c.arrow ? c.arrowSize : 0);
		var inset = c.arrowSize + c.radius;
		var position = c.position;
		var x;
		var y;
		var arrow;

		// Flip to the opposite side when the chosen one does not fit in the viewport.
		var fits = {
			top: rect.top - tipH - gap >= VIEWPORT_GAP,
			bottom: rect.bottom + tipH + gap <= w.innerHeight - VIEWPORT_GAP,
			left: rect.left - tipW - gap >= VIEWPORT_GAP,
			right: rect.right + tipW + gap <= w.innerWidth - VIEWPORT_GAP
		};
		var opposite = { top: 'bottom', bottom: 'top', left: 'right', right: 'left' };

		if (!fits[position] && fits[opposite[position]]) {
			position = opposite[position];
		}

		if ('top' === position || 'bottom' === position) {
			x = Math.min(Math.max(rect.left + rect.width / 2 - tipW / 2, VIEWPORT_GAP), Math.max(VIEWPORT_GAP, w.innerWidth - tipW - VIEWPORT_GAP));
			y = 'top' === position ? rect.top - tipH - gap : rect.bottom + gap;
			arrow = rect.left + rect.width / 2 - x;
			arrow = Math.min(Math.max(arrow, inset), Math.max(inset, tipW - inset));
		} else {
			y = Math.min(Math.max(rect.top + rect.height / 2 - tipH / 2, VIEWPORT_GAP), Math.max(VIEWPORT_GAP, w.innerHeight - tipH - VIEWPORT_GAP));
			x = 'left' === position ? rect.left - tipW - gap : rect.right + gap;
			arrow = rect.top + rect.height / 2 - y;
			arrow = Math.min(Math.max(arrow, inset), Math.max(inset, tipH - inset));
		}

		// Neither side fits (element spans the viewport): keep it on screen.
		x = Math.min(Math.max(x, VIEWPORT_GAP), Math.max(VIEWPORT_GAP, w.innerWidth - tipW - VIEWPORT_GAP));
		this.tip.setAttribute('data-position', position);
		this.tip.style.left = Math.round(x) + 'px';
		this.tip.style.top = Math.round(y) + 'px';
		this.tip.style.setProperty('--ha-att-arrow-pos', Math.round(arrow) + 'px');
	};

	Tooltip.prototype.show = function () {
		if (!this.tip || this.visible || !this.target.isConnected) {
			return;
		}

		if (openTooltip && openTooltip !== this) {
			openTooltip.hide();
		}

		this.place();
		this.tip.classList.add('is-visible');
		this.target.setAttribute('aria-describedby', this.tip.id);
		w.addEventListener('scroll', this.onScroll, true);
		w.addEventListener('resize', this.onScroll);
		this.visible = true;
		openTooltip = this;
	};

	Tooltip.prototype.hide = function () {
		if (!this.tip || !this.visible) {
			return;
		}

		this.tip.classList.remove('is-visible');

		if (this.target.getAttribute('aria-describedby') === this.tip.id) {
			this.target.removeAttribute('aria-describedby');
		}

		w.removeEventListener('scroll', this.onScroll, true);
		w.removeEventListener('resize', this.onScroll);
		this.visible = false;

		if (openTooltip === this) {
			openTooltip = null;
		}
	};

	Tooltip.prototype.destroy = function () {
		this.hide();

		this.listeners.forEach(function (item) {
			item[0].removeEventListener(item[1], item[2], item[3]);
		});
		this.listeners = [];

		if (this.tip) {
			this.tip.remove();
			this.tip = null;
		}
	};

	function destroy(id) {
		if (instances[id]) {
			instances[id].destroy();
			delete instances[id];
		}
	}

	// Creates, rebuilds or removes the tooltip of an element so it matches `config` (null/disabled = none).
	function sync(id, config) {
		var current = instances[id];
		var target = config && truthy(config.switcher) ? resolveTarget(id) : null;

		if (!target) {
			destroy(id);

			return;
		}

		var json = JSON.stringify(config);

		// The editor replaces the markup on re-render: a stale target needs a new tooltip.
		if (current && current.target === target && current.json === json) {
			return;
		}

		destroy(id);

		instances[id] = new Tooltip(id, target, config);
		instances[id].json = json;
	}

	function scanFrontend() {
		var configs = (w.HappyAtomic && w.HappyAtomic.features && w.HappyAtomic.features[KEY]) || {};

		Object.keys(configs).forEach(function (id) {
			sync(id, configs[id]);
		});
	}

	function scanEditor() {
		var defaults = w.HappyAtomic && w.HappyAtomic.defaults && w.HappyAtomic.defaults[KEY];
		var seen = {};

		if (!defaults) {
			return;
		}

		d.querySelectorAll('[data-id]').forEach(function (node) {
			var id = node.getAttribute('data-id');
			var settings = editorSettings(id);

			seen[id] = true;

			// Only Atomic elements carry the tooltip props.
			sync(id, settings && (PREFIX + 'switcher') in settings ? editorConfig(settings, defaults) : null);
		});

		Object.keys(instances).forEach(function (id) {
			if (!seen[id]) {
				destroy(id);
			}
		});
	}

	function boot() {
		if (isEditor()) {
			scanEditor();

			// Settings changes fire no DOM mutation, so the editor is polled.
			setInterval(scanEditor, 500);

			return;
		}

		scanFrontend();
	}

	w.addEventListener('pagehide', function () {
		Object.keys(instances).forEach(destroy);
	});

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
