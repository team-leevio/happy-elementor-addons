/**
 * Happy Addons — Fixed Size Button, Elementor V4 editor preview.
 *
 * The canvas renders the Atomic Button client side, so PHP never sees it
 * there: the rule is built from the editor settings instead. The frontend gets
 * the same rule from PHP (Fixed_Size_Button::build_css), keep both in sync.
 * window.HappyAtomic.defaults['fixed-size-button'] is printed by
 * Atomic_Manager::enqueue_preview_assets().
 */
(function (w, d) {
	'use strict';

	var X = { left: 'flex-start', center: 'center', right: 'flex-end' };
	var Y = { top: 'flex-start', center: 'center', bottom: 'flex-end' };

	function isEditor() {
		return d.body.classList.contains('elementor-editor-active') ||
			!!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
	}

	function num(value, fallback) {
		var parsed = parseFloat(value);

		return isNaN(parsed) ? fallback : Math.min(500, Math.max(0, parsed));
	}

	function pick(map, value, fallback) {
		return Object.prototype.hasOwnProperty.call(map, value) ? map[value] : map[fallback];
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
		var value = unwrapProp(settings['ha_fsb_' + key]);

		return (null === value || undefined === value || '' === value) ? fallback : value;
	}

	function sizeCss(s, settings, defaults) {
		if (!setting(settings, 'switcher', false)) {
			return '';
		}

		var height = num(setting(settings, 'height', defaults.height), 0);
		var width = num(setting(settings, 'width', defaults.width), 0);
		var css = 'display:inline-flex;box-sizing:border-box;' +
			'justify-content:' + pick(X, setting(settings, 'align_x', defaults.align_x), 'center') + ';' +
			'align-items:' + pick(Y, setting(settings, 'align_y', defaults.align_y), 'center') + ';';

		if (height > 0) {
			css += 'height:' + height + 'px;';
		}

		if (width > 0) {
			css += 'width:' + width + 'px;';
		}

		return s + '{' + css + '}';
	}

	function scanEditorButtons() {
		var defaults = w.HappyAtomic && w.HappyAtomic.defaults && w.HappyAtomic.defaults['fixed-size-button'];

		if (!defaults || !isEditor()) {
			return;
		}

		d.querySelectorAll('[data-id]').forEach(function (el) {
			var id = el.getAttribute('data-id');
			var settings = editorSettings(id);
			var tag = d.getElementById('ha-fsb-' + id);

			// Only the Atomic Button carries the fixed size props.
			if (!settings || !('ha_fsb_switcher' in settings)) {
				return;
			}

			// Keep in sync with Fixed_Size_Button::selector().
			var safeId = id.replace(/[^a-zA-Z0-9_-]/g, '');
			var css = sizeCss('[data-id="' + safeId + '"]:not([data-atomic]),[data-id="' + safeId + '"][data-atomic]>*', settings, defaults);

			if (!css) {
				if (tag) {
					tag.remove();
				}

				return;
			}

			if (!tag) {
				tag = d.createElement('style');
				tag.id = 'ha-fsb-' + id;
				d.head.appendChild(tag);
			}

			if (tag.textContent !== css) {
				tag.textContent = css;
			}
		});
	}

	function boot() {
		scanEditorButtons();

		// Settings changes fire no DOM mutation, so the editor is polled.
		setInterval(scanEditorButtons, 500);
	}

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
