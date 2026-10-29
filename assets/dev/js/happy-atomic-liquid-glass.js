/**
 * Happy Addons — Liquid Glass, Elementor V4 editor preview.
 *
 * The canvas renders Atomic elements (containers and widgets) client side, so PHP never
 * sees them there: the rules and the SVG filter are built from the editor
 * settings instead. The frontend gets the same from PHP (Liquid_Glass::build_css
 * / ::svg), keep both in sync. window.HappyAtomic.defaults['liquid-glass'] and
 * window.HappyAtomic.liquidGlass (preset blur + filter markup) are printed by
 * Atomic_Manager::enqueue_preview_assets() / Liquid_Glass::print_editor_presets().
 */
(function (w, d) {
	'use strict';

	var COLOR = /^(#[0-9a-f]{3,8}|(rgb|hsl)a?\(\s*[0-9.%,\s\/-]+\)|[a-z]+)$/i;

	var SHADOWS = {
		'soft-glow': '0 0 15px 0 rgba(255, 255, 255, 0.6) inset',
		'warm-glow': '0 0 20px 0 rgba(255, 255, 255, 0.65) inset',
		'bright-glow': '0 0 15px 0 rgba(255, 255, 255, 0.7) inset',
		'wide-bloom': '0 0 30px 1px rgba(255, 255, 255, 0.7) inset',
		'deep-bloom': '0 0 40px 5px rgba(255, 255, 255, 0.6) inset',
		'top-light': '0 20px 15px -5px rgba(255, 255, 255, 0.5) inset',
		'bottom-light': '0 -20px 25px -15px rgba(255, 255, 255, 0.5) inset',
		'top-fade': '0 10px 25px -10px rgba(255, 255, 255, 0.4) inset',
		'bottom-fade': '0 -10px 20px -5px rgba(255, 255, 255, 0.55) inset',
		'top-edge': '0 15px 15px -10px rgba(255, 255, 255, 0.45) inset',
		'rim-light': '0 0 10px 0 rgba(255, 255, 255, 0.8) inset, 0 0 2px 0 rgba(255, 255, 255, 0.9) inset',
		'inner-shadow': 'inset 0 2px 8px 0 rgba(0, 0, 0, 0.15), inset 0 0 12px 0 rgba(255, 255, 255, 0.3)',
		'spotlight': '0 -5px 30px -10px rgba(255, 255, 255, 0.7) inset',
		'frost-edge': '0 0 6px 0 rgba(255, 255, 255, 0.9) inset, 0 0 20px -5px rgba(200, 220, 255, 0.4) inset',
		'vignette': 'inset 0 0 50px 10px rgba(0, 0, 0, 0.08)'
	};

	var BORDERS = ['solid', 'dashed', 'dotted', 'double', 'groove'];
	var CHANNELS = ['R', 'G', 'B', 'A'];

	function isEditor() {
		return d.body.classList.contains('elementor-editor-active') ||
			!!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
	}

	function num(value, fallback) {
		var parsed = parseFloat(value);

		return isNaN(parsed) ? fallback : parsed;
	}

	function color(value) {
		return COLOR.test(String(value || '').trim()) ? String(value).trim() : '';
	}

	function unwrapProp(value) {
		while (value && 'object' === typeof value && '$$type' in value) {
			value = value.value;
		}

		return value;
	}

	function editorContainer(id) {
		try {
			var editor = (w.parent && w.parent.elementor) || w.elementor;

			return (editor && editor.getContainer && editor.getContainer(id)) || null;
		} catch (e) {
			return null;
		}
	}

	// Atomic elements only: the V3 elements share the ha_lg_ settings (as 'yes').
	function isAtomic(container) {
		var model = container && container.model;
		var type = model && (model.get('widgetType') || model.get('elType'));

		return !!type && /^(e-|ha-a-)/.test(type);
	}

	function editorSettings(container) {
		try {
			var settings = container && (container.settings || (container.model && container.model.get('settings')));

			return settings ? (settings.attributes || (settings.toJSON && settings.toJSON())) : null;
		} catch (e) {
			return null;
		}
	}

	// Settings as the frontend config: prefix stripped, defaults filled in.
	function editorFeatureConfig(settings, prefix, defaults) {
		var config = {};

		Object.keys(defaults).forEach(function (key) {
			var value = unwrapProp(settings[prefix + key]);

			config[key] = (null === value || undefined === value || '' === value) ? defaults[key] : value;
		});

		return config;
	}

	function radius(value) {
		var parts = String(value || '').trim().split(/\s+/).filter(Boolean);

		if (!parts.length || parts.length > 4) {
			return '';
		}

		for (var i = 0; i < parts.length; i++) {
			if (!/^\d*\.?\d+(px|%|em|rem)?$/.test(parts[i])) {
				return '';
			}

			parts[i] = /^\d*\.?\d+$/.test(parts[i]) ? parts[i] + 'px' : parts[i];
		}

		return parts.join(' ');
	}

	function pick(value, list, fallback) {
		return list.indexOf(value) > -1 ? value : fallback;
	}

	function filterId(c, id) {
		return 'custom' === c.preset ? 'ha-glass-distortion-custom-' + id : 'ha-glass-distortion-' + c.preset;
	}

	function presetData(c) {
		var presets = w.HappyAtomic && w.HappyAtomic.liquidGlass;

		return (presets && presets[c.preset]) || null;
	}

	// Inner markup of the SVG filter, '' when the preset is unknown.
	function filterMarkup(c) {
		if ('custom' === c.preset) {
			return '<feTurbulence type="fractalNoise" baseFrequency="' + num(c.frequency_x, 0.006) + ' ' + num(c.frequency_y, 0.006) + '" numOctaves="' + num(c.octaves, 2) + '" seed="' + num(c.seed, 92) + '" result="noise" />' +
				'<feGaussianBlur in="noise" stdDeviation="' + num(c.std_deviation, 2) + '" result="blurred" />' +
				'<feDisplacementMap in="SourceGraphic" in2="blurred" scale="' + num(c.scale, 80) + '" xChannelSelector="' + pick(c.x_channel, CHANNELS, 'R') + '" yChannelSelector="' + pick(c.y_channel, CHANNELS, 'G') + '" />';
		}

		var preset = presetData(c);

		return preset ? preset.markup : '';
	}

	function glassCss(s, c, id) {
		if (!c.switcher) {
			return '';
		}

		var preset = presetData(c);
		var custom = 'custom' === c.preset;
		var blur = num(c.blur, 0) > 0 ? num(c.blur, 0) : (preset ? preset.blur : 8);
		var filter = 'blur(' + blur + 'px)' + (custom || preset ? ' url(#' + filterId(c, id) + ')' : '');
		var rules = '-webkit-backdrop-filter:' + filter + ';backdrop-filter:' + filter + ';';

		if ('customs' === c.shadow) {
			rules += 'box-shadow:' + (c.shadow_inset ? 'inset ' : '') + num(c.shadow_x, 0) + 'px ' + num(c.shadow_y, 0) + 'px ' + num(c.shadow_blur, 15) + 'px ' + num(c.shadow_spread, 0) + 'px ' + (color(c.shadow_color) || 'rgba(255, 255, 255, 0.5)') + ';';
		} else if (SHADOWS[c.shadow]) {
			rules += 'box-shadow:' + SHADOWS[c.shadow] + ';';
		}

		if (BORDERS.indexOf(c.border_style) > -1) {
			rules += 'border:' + num(c.border_width, 1) + 'px ' + c.border_style + (color(c.border_color) ? ' ' + color(c.border_color) : '') + ';';
		}

		if (radius(c.radius)) {
			rules += 'border-radius:' + radius(c.radius) + ';';
		}

		return s + '{' + rules + '}';
	}

	// The filter is shared per preset, so the SVG is only (re)written when it changes.
	function syncSvg(c, id) {
		var name = 'custom' === c.preset ? 'custom-' + id : c.preset;
		var svgClass = 'ha-lg-svg-' + name;
		var markup = c.switcher ? filterMarkup(c) : '';
		var svg = d.querySelector('svg.' + svgClass);

		if (!markup) {
			if (svg && 'custom' === c.preset) {
				svg.remove();
			}

			return;
		}

		var html = '<defs><filter id="' + filterId(c, id) + '" x="0%" y="0%" width="100%" height="100%">' + markup + '</filter></defs>';

		if (!svg) {
			svg = d.createElementNS('http://www.w3.org/2000/svg', 'svg');
			svg.setAttribute('class', svgClass);
			svg.setAttribute('width', '0');
			svg.setAttribute('height', '0');
			svg.setAttribute('style', 'position:absolute;overflow:hidden');
			d.body.appendChild(svg);
		}

		if (svg.getAttribute('data-ha-lg') !== html) {
			svg.innerHTML = html;
			svg.setAttribute('data-ha-lg', html);
		}
	}

	function scanEditorGlass() {
		var defaults = w.HappyAtomic && w.HappyAtomic.defaults && w.HappyAtomic.defaults['liquid-glass'];

		if (!defaults || !isEditor()) {
			return;
		}

		d.querySelectorAll('[data-id]').forEach(function (el) {
			var id = el.getAttribute('data-id');
			var container = editorContainer(id);
			var settings = isAtomic(container) && editorSettings(container);
			var tag = d.getElementById('ha-lg-' + id);

			if (!settings) {
				return;
			}

			var safeId = id.replace(/[^a-zA-Z0-9_-]/g, '');
			var config = editorFeatureConfig(settings, 'ha_lg_', defaults);
			// Atomic widgets sit in a display:contents wrapper (data-atomic) with no box:
			// the effect goes on their own element inside it (keep in sync with Liquid_Glass::selector()).
			var css = glassCss('[data-id="' + safeId + '"]:not([data-atomic]),[data-id="' + safeId + '"][data-atomic]>*', config, safeId);

			syncSvg(config, safeId);

			if (!css) {
				if (tag) {
					tag.remove();
				}

				return;
			}

			if (!tag) {
				tag = d.createElement('style');
				tag.id = 'ha-lg-' + id;
				d.head.appendChild(tag);
			}

			if (tag.textContent !== css) {
				tag.textContent = css;
			}
		});
	}

	function boot() {
		scanEditorGlass();

		// Settings changes fire no DOM mutation, so the editor is polled.
		setInterval(scanEditorGlass, 500);
	}

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
