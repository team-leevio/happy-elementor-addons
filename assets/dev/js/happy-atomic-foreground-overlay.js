/**
 * Happy Addons — Foreground Overlay, Elementor V4 editor preview.
 *
 * The canvas renders Atomic Flexbox / Div Block / Grid client side, so PHP never
 * sees them there: the rules are built from the editor settings instead. The
 * frontend gets the same rules from PHP (Foreground_Overlay::build_css), keep
 * both in sync. window.HappyAtomic.defaults['foreground-overlay'] is printed by
 * Atomic_Manager::enqueue_preview_assets().
 */
(function (w, d) {
	'use strict';

	var COLOR = /^(#[0-9a-f]{3,8}|(rgb|hsl)a?\([0-9.%,\s\/-]+\)|[a-z]+)$/i;

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
	function editorFeatureConfig(settings, prefix, defaults) {
		var config = {};

		Object.keys(defaults).forEach(function (key) {
			var value = unwrapProp(settings[prefix + key]);

			config[key] = (null === value || undefined === value || '' === value) ? defaults[key] : value;
		});

		return config;
	}

	function colorMix(position, opacity) {
		return 'color-mix(in srgb, var(--fg-' + position + '-color, #ffffff) calc(var(--fg-' + position + '-opacity, ' + opacity + ') * 100%), transparent)';
	}

	function gradient() {
		var left = colorMix('left', 1);
		var center = colorMix('center', 0);
		var right = colorMix('right', 1);

		return 'linear-gradient( var(--fg-angle, 90deg), ' +
			left + ' 0%, ' +
			left + ' clamp(0%, (var(--fg-left-end, 5%) + var(--fg-center-start, 5%) - var(--fg-blend, 20%)) / 2, 100%), ' +
			center + ' clamp(0%, (var(--fg-left-end, 5%) + var(--fg-center-start, 5%) + var(--fg-blend, 20%)) / 2, 100%), ' +
			center + ' clamp(0%, (var(--fg-center-end, 95%) + var(--fg-right-start, 95%) - var(--fg-blend, 20%)) / 2, 100%), ' +
			right + ' clamp(0%, (var(--fg-center-end, 95%) + var(--fg-right-start, 95%) + var(--fg-blend, 20%)) / 2, 100%), ' +
			right + ' 100% )';
	}

	function overlayCss(s, c) {
		if ('classic' !== c.type && 'gradient' !== c.type) {
			return '';
		}

		// The editor keeps the edit handles inside the element, so overflow:hidden
		// (what the frontend uses) would hide them; the layer follows the element's
		// border-radius instead.
		var css = ':where(' + s + '){position:relative;}';

		css += s + '{isolation:isolate;}';
		css += s + '::after{content:"";position:absolute;inset:0;z-index:9999;pointer-events:none;display:block;border-radius:inherit;';

		if ('classic' === c.type) {
			if (color(c.color)) {
				css += 'background:' + color(c.color) + ';';
			}

			css += 'opacity:' + num(c.opacity, 0.5) + ';';
		} else {
			css += 'background:' + gradient() + ';';
		}

		var filters = [];

		[['blur', 0, 'px', 'blur'], ['brightness', 100, '%', 'brightness'], ['contrast', 100, '%', 'contrast'], ['saturate', 100, '%', 'saturate'], ['hue', 0, 'deg', 'hue-rotate']].forEach(function (filter) {
			var value = num(c[filter[0]], filter[1]);

			if (value !== filter[1]) {
				filters.push(filter[3] + '(' + value + filter[2] + ')');
			}
		});

		if (filters.length) {
			css += 'filter:' + filters.join(' ') + ';';
		}

		if (c.blend_mode && 'normal' !== c.blend_mode && /^[a-z-]+$/.test(c.blend_mode)) {
			css += 'mix-blend-mode:' + c.blend_mode + ';';
		}

		css += '}';

		if ('gradient' === c.type) {
			var vars = '';

			[['left', 'start'], ['center', 'center'], ['right', 'end']].forEach(function (area) {
				var key = area[1];

				if (color(c[key + '_color'])) {
					vars += '--fg-' + area[0] + '-color:' + color(c[key + '_color']) + ';';
				}

				vars += '--fg-' + area[0] + '-opacity:' + num(c[key + '_opacity'], 1) + ';';
				vars += '--fg-' + area[0] + '-start:' + num(c[key + '_from'], 0) + '%;';
				vars += '--fg-' + area[0] + '-end:' + num(c[key + '_to'], 100) + '%;';
			});

			vars += '--fg-blend:' + num(c.blend, 20) + '%;--fg-angle:' + num(c.angle, 90) + 'deg;';

			css += s + '{' + vars + '}';
		}

		return css;
	}

	function scanEditorOverlays() {
		var defaults = w.HappyAtomic && w.HappyAtomic.defaults && w.HappyAtomic.defaults['foreground-overlay'];

		if (!defaults || !isEditor()) {
			return;
		}

		d.querySelectorAll('[data-id].e-flexbox-base, [data-id].e-div-block-base, [data-id].e-grid-base, [data-id][data-element_type="e-flexbox"], [data-id][data-element_type="e-div-block"], [data-id][data-element_type="e-grid"]').forEach(function (el) {
			var id = el.getAttribute('data-id');
			var settings = editorSettings(id);
			var tag = d.getElementById('ha-fo-' + id);

			if (!settings) {
				return;
			}

			var css = overlayCss('[data-id="' + id.replace(/[^a-zA-Z0-9_-]/g, '') + '"]', editorFeatureConfig(settings, 'ha_fo_', defaults));

			if (!css) {
				if (tag) {
					tag.remove();
				}

				return;
			}

			if (!tag) {
				tag = d.createElement('style');
				tag.id = 'ha-fo-' + id;
				d.head.appendChild(tag);
			}

			if (tag.textContent !== css) {
				tag.textContent = css;
			}
		});
	}

	function boot() {
		scanEditorOverlays();

		// Settings changes fire no DOM mutation, so the editor is polled.
		setInterval(scanEditorOverlays, 500);
	}

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
