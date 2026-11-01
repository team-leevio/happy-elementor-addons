/**
 * Happy Addons — Text Stroke, Elementor V4 editor preview.
 *
 * The canvas renders the Atomic Heading client side, so PHP never sees it
 * there: the rule is built from the editor settings instead. The frontend gets
 * the same rule from PHP (Text_Stroke::build_css), keep both in sync.
 * window.HappyAtomic.defaults['text-stroke'] is printed by
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

	function setting(settings, key, fallback) {
		var value = unwrapProp(settings['ha_ts_' + key]);

		return (null === value || undefined === value || '' === value) ? fallback : value;
	}

	function strokeCss(s, settings, defaults) {
		var width = Math.max(0, num(setting(settings, 'width', defaults.width), 0));
		var stroke = color(setting(settings, 'color', defaults.color));

		if (width <= 0 || !stroke) {
			return '';
		}

		return s + '{-webkit-text-stroke:' + width + 'px ' + stroke + ';text-stroke:' + width + 'px ' + stroke + ';}';
	}

	function scanEditorHeadings() {
		var defaults = w.HappyAtomic && w.HappyAtomic.defaults && w.HappyAtomic.defaults['text-stroke'];

		if (!defaults || !isEditor()) {
			return;
		}

		d.querySelectorAll('[data-id]').forEach(function (el) {
			var id = el.getAttribute('data-id');
			var settings = editorSettings(id);
			var tag = d.getElementById('ha-ts-' + id);

			// Only the Atomic Heading carries the stroke props.
			if (!settings || !('ha_ts_width' in settings)) {
				return;
			}

			// Atomic widgets sit in a display:contents wrapper (data-atomic): the stroke
			// goes on the widget's own element inside it (keep in sync with Text_Stroke::selector()).
			var safeId = id.replace(/[^a-zA-Z0-9_-]/g, '');
			var css = strokeCss('[data-id="' + safeId + '"]:not([data-atomic]),[data-id="' + safeId + '"][data-atomic]>*', settings, defaults);

			if (!css) {
				if (tag) {
					tag.remove();
				}

				return;
			}

			if (!tag) {
				tag = d.createElement('style');
				tag.id = 'ha-ts-' + id;
				d.head.appendChild(tag);
			}

			if (tag.textContent !== css) {
				tag.textContent = css;
			}
		});
	}

	function boot() {
		scanEditorHeadings();

		// Settings changes fire no DOM mutation, so the editor is polled.
		setInterval(scanEditorHeadings, 500);
	}

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
