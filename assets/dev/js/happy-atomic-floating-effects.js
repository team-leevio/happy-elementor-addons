/**
 * Happy Addons — Floating Effects for Elementor V4 (Atomic) elements.
 *
 * An anime.js loop (translate / rotate / scale) on every enabled Atomic widget.
 * Widgets sit in a display:contents wrapper (data-atomic) that has no box, so
 * the widget's own element inside it is animated. Containers are not targeted.
 *
 * Frontend: the sanitized configs come from PHP (Atomic_Feature_Base::sanitize_config)
 * in window.HappyAtomic.features['floating-effects'], keyed by the element's data-id.
 * Editor preview: the canvas renders Atomic elements client side, so the config
 * is built from the editor settings instead, with
 * window.HappyAtomic.defaults['floating-effects'] as the fallback (keep both in sync).
 */
(function (w, d) {
	'use strict';

	var KEY = 'floating-effects';
	var PREFIX = 'ha_ffx_';
	var GROUPS = {
		translate: { axes: ['x', 'y'], prop: 'translate', min: -100, max: 100 },
		rotate: { axes: ['x', 'y', 'z'], prop: 'rotate', min: -180, max: 180 },
		scale: { axes: ['x', 'y'], prop: 'scale', min: 0, max: 5 }
	};

	var instances = {}; // data-id => { target, json, anime }

	function isEditor() {
		return d.body.classList.contains('elementor-editor-active') ||
			!!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
	}

	function num(value, fallback, min, max) {
		var parsed = parseFloat(value);

		if (isNaN(parsed)) {
			return fallback;
		}

		return Math.min(max, Math.max(min, parsed));
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

	function editorConfig(settings, defaults) {
		var config = {};

		Object.keys(defaults).forEach(function (key) {
			var value = unwrapProp(settings[PREFIX + key]);

			config[key] = (null === value || undefined === value || '' === value) ? defaults[key] : value;
		});

		return config;
	}

	function targetOf(id) {
		var safeId = String(id).replace(/[^a-zA-Z0-9_-]/g, '');
		var node = d.querySelector('[data-id="' + safeId + '"]');

		if (!node) {
			return null;
		}

		return node.hasAttribute('data-atomic') ? node.firstElementChild : node;
	}

	function animeConfig(target, config) {
		var anim = {
			targets: target,
			direction: 'alternate',
			easing: 'easeInOutSine',
			loop: true
		};
		var active = false;

		Object.keys(GROUPS).forEach(function (group) {
			var spec = GROUPS[group];
			var enabled = true === config[group] || 'true' === config[group] || 1 === config[group];

			if (!enabled) {
				return;
			}

			var duration = num(config[group + '_duration'], 1000, 0, 10000);
			var delay = num(config[group + '_delay'], 0, 0, 5000);

			spec.axes.forEach(function (axis) {
				var from = num(config[group + '_' + axis + '_from'], 0, spec.min, spec.max);
				var to = num(config[group + '_' + axis + '_to'], 0, spec.min, spec.max);

				if (from === to) {
					return;
				}

				anim[spec.prop + axis.toUpperCase()] = { value: [from, to], duration: duration, delay: delay };
				active = true;
			});
		});

		return active ? anim : null;
	}

	function destroy(id) {
		var instance = instances[id];

		if (!instance) {
			return;
		}

		if (instance.anime) {
			instance.anime.pause();
			w.anime.remove(instance.target);
		}

		if (instance.target) {
			instance.target.style.transform = '';
			instance.target.style.willChange = '';
		}

		delete instances[id];
	}

	function sync(id, config) {
		var current = instances[id];

		if (!config || !(true === config.switcher || 'true' === config.switcher)) {
			destroy(id);

			return;
		}

		var target = targetOf(id);
		var json = JSON.stringify(config);

		if (!target || !w.anime) {
			destroy(id);

			return;
		}

		// The editor replaces the markup on re-render: a stale target needs a new loop.
		if (current && current.target === target && current.json === json) {
			return;
		}

		destroy(id);

		var anim = animeConfig(target, config);

		if (!anim) {
			return;
		}

		target.style.willChange = 'transform';
		instances[id] = { target: target, json: json, anime: w.anime(anim) };
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

			// Only Atomic elements carry the floating effects props.
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

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
