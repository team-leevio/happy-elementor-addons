/**
 * Happy Addons — Container Hover Effect, Elementor V4 editor preview.
 *
 * The canvas renders Atomic Flexbox / Div Block client side, so PHP never sees
 * them there: the rules are built from the editor settings instead. The frontend
 * gets the same rules from PHP (Container_Hover_Effect::build_css), keep both
 * in sync. window.HappyAtomic.defaults['container-hover-effect'] is printed by
 * Atomic_Manager::enqueue_preview_assets().
 */
(function (w, d) {
	'use strict';

	function isEditor() {
		return d.body.classList.contains('elementor-editor-active') ||
			!!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
	}

	function num(value, fallback) {
		var parsed = parseFloat(value);

		return isNaN(parsed) ? fallback : parsed;
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

	function hoverEffectCss(s, c) {
		var css = '';

		if (c.text_color && /^(#[0-9a-f]{3,8}|(rgb|hsl)a?\([0-9.%,\s\/-]+\)|[a-z]+)$/i.test(c.text_color)) {
			css += s + ':hover :where(h1,h2,h3,h4,h5,h6,p,a,span,li):not(.elementor-element-overlay *,.elementor-editor-element-settings,.elementor-editor-element-settings *){color:' + c.text_color + ';}';
		}

		if (!c.effects) {
			return css;
		}

		var time = num(c.duration, 0.5) + 's';
		var transforms = {grow: 'scale(1.08)', shrink: 'scale(1)', rotate: 'scale(1.08) rotate(2deg)'};
		var filter = 'brightness(' + num(c.brightness, 100) + '%) contrast(' + num(c.contrast, 100) + '%) saturate(' + num(c.saturate, 100) + '%) blur(' + num(c.blur, 0) + 'px) hue-rotate(' + num(c.hue, 0) + 'deg)';

		css += ':where(' + s + '){position:relative;}';
		// The editor keeps the edit handles inside the element, so overflow:hidden
		// (what the frontend uses) would hide them. The scaled layer is clipped to
		// the element box with a clip-path instead: the inset is 1 - 1/scale, so it
		// grows and shrinks with the transform.
		var GROWN = 'inset(3.7037%)';
		var rest = 'shrink' === c.animation ? GROWN : 'inset(0)';
		var hover = 'shrink' === c.animation ? 'inset(0)' : GROWN;

		css += s + '{isolation:isolate;}';
		css += s + '::before{content:"";position:absolute;inset:0;border-radius:inherit;background:inherit;z-index:-1;pointer-events:none;transition:transform ' + time + ' ease,clip-path ' + time + ' ease;}';
		css += s + '::after{content:"";position:absolute;inset:0;border-radius:inherit;z-index:-1;pointer-events:none;transition:-webkit-backdrop-filter ' + time + ' ease,backdrop-filter ' + time + ' ease;}';

		if (transforms[c.animation]) {
			css += s + '::before{clip-path:' + rest + ';' + ('shrink' === c.animation ? 'transform:scale(1.08);' : '') + '}';
			css += s + ':hover::before{clip-path:' + hover + ';transform:' + transforms[c.animation] + ';}';
		}

		return css + s + ':hover::after{-webkit-backdrop-filter:' + filter + ';backdrop-filter:' + filter + ';}';
	}

	function scanEditorHoverEffects() {
		var defaults = w.HappyAtomic && w.HappyAtomic.defaults && w.HappyAtomic.defaults['container-hover-effect'];

		if (!defaults || !isEditor()) {
			return;
		}

		d.querySelectorAll('[data-id].e-flexbox-base, [data-id].e-div-block-base, [data-id].e-grid-base, [data-id][data-element_type="e-flexbox"], [data-id][data-element_type="e-div-block"], [data-id][data-element_type="e-grid"]').forEach(function (el) {
			var id = el.getAttribute('data-id');
			var settings = editorSettings(id);
			var tag = d.getElementById('ha-che-' + id);

			if (!settings) {
				return;
			}

			var css = hoverEffectCss('[data-id="' + id.replace(/[^a-zA-Z0-9_-]/g, '') + '"]', editorFeatureConfig(settings, 'ha_che_', defaults));

			if (!css) {
				if (tag) {
					tag.remove();
				}

				return;
			}

			if (!tag) {
				tag = d.createElement('style');
				tag.id = 'ha-che-' + id;
				d.head.appendChild(tag);
			}

			if (tag.textContent !== css) {
				tag.textContent = css;
			}
		});
	}


	function boot() {
		scanEditorHoverEffects();

		// Settings changes fire no DOM mutation, so the editor is polled.
		setInterval(scanEditorHoverEffects, 500);
	}

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
