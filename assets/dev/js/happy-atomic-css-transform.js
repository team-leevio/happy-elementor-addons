/**
 * Happy Addons — CSS Transform, Elementor V4 editor preview.
 *
 * The canvas renders Atomic elements client side, so PHP never sees them there:
 * the rules are built from the editor settings instead. The frontend gets the
 * same rules from PHP (CSS_Transform::build_css), keep both in sync.
 * window.HappyAtomic.defaults['css-transform'] is printed by
 * Atomic_Manager::enqueue_preview_assets().
 */
(function (w, d) {
	'use strict';

	var TYPES = ['e-flexbox', 'e-div-block', 'e-grid', 'e-heading', 'e-paragraph', 'e-button', 'e-image', 'e-svg', 'e-divider', 'e-youtube'];
	var PREFIX = 'ha_tfx_';

	function isEditor() {
		return d.body.classList.contains('elementor-editor-active') ||
			!!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
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
			var value = unwrapProp(attributes[PREFIX + key]);

			config[key] = (null === value || undefined === value || '' === value) ? defaults[key] : value;
		});

		return config;
	}

	function fmt(value) {
		var number = parseFloat(value);

		return isNaN(number) ? 0 : +number.toFixed(4);
	}

	function filled(value) {
		return null !== value && undefined !== value && '' !== value;
	}

	function buildCss(s, c) {
		if (!c.switcher) {
			return '';
		}

		var vars = '';

		[['', ''], ['_hover', '-hover']].forEach(function (state) {
			var sx = state[0];
			var cs = state[1];

			function set(name, key, unit) {
				var value = c[key + sx];

				if (filled(value)) {
					vars += '--ha-tfx-' + name + cs + ':' + fmt(value) + unit + ';';
				}
			}

			if (c['translate_toggle' + sx]) {
				set('translate-x', 'translate_x', 'px');
				set('translate-y', 'translate_y', 'px');
			}

			if (c['rotate_toggle' + sx]) {
				if ('loose' === c['rotate_mode' + sx]) {
					set('rotate-x', 'rotate_x', 'deg');
					set('rotate-y', 'rotate_y', 'deg');
				}

				set('rotate-z', 'rotate_z', 'deg');
			}

			if (c['scale_toggle' + sx]) {
				var x = fmt(c['scale_x' + sx]);

				vars += '--ha-tfx-scale-x' + cs + ':' + x + ';--ha-tfx-scale-y' + cs + ':' + x + ';';

				if ('loose' === c['scale_mode' + sx]) {
					vars += '--ha-tfx-scale-y' + cs + ':' + fmt(c['scale_y' + sx]) + ';';
				}
			}

			if (c['skew_toggle' + sx]) {
				set('skew-x', 'skew_x', 'deg');
				set('skew-y', 'skew_y', 'deg');
			}
		});

		vars += '--ha-tfx-transition-duration:' + fmt(c.transition_duration) + 's;';

		function transform(h) {
			function v(name, fallback) {
				return h ? 'var(--ha-tfx-' + name + h + ',var(--ha-tfx-' + name + ',' + fallback + '))' : 'var(--ha-tfx-' + name + ',' + fallback + ')';
			}

			return 'translate(' + v('translate-x', 0) + ',' + v('translate-y', 0) + ') scale(' + v('scale-x', 1) + ',' + v('scale-y', 1) + ') skew(' + v('skew-x', 0) + ',' + v('skew-y', 0) + ') rotateX(' + v('rotate-x', 0) + ') rotateY(' + v('rotate-y', 0) + ') rotateZ(' + v('rotate-z', 0) + ')';
		}

		return s + '{' + vars + 'transform:' + transform('') + ';transition-property:transform;transition-duration:var(--ha-tfx-transition-duration,.2s);}' +
			s + ':hover{transform:' + transform('-hover') + ';}';
	}

	function scan() {
		var defaults = w.HappyAtomic && w.HappyAtomic.defaults && w.HappyAtomic.defaults['css-transform'];

		if (!defaults || !isEditor()) {
			return;
		}

		d.querySelectorAll('[data-id]').forEach(function (el) {
			var id = el.getAttribute('data-id').replace(/[^a-zA-Z0-9_-]/g, '');
			var container = editorContainer(id);
			var type = container && container.model ? (container.model.get('widgetType') || container.model.get('elType')) : null;

			if (!container || TYPES.indexOf(type) === -1) {
				return;
			}

			var config = editorConfig(container, defaults);
			var tag = d.getElementById('ha-tfx-' + id);

			// A wrapper with `display: contents` has no box to transform: use the element it renders.
			var target = el;

			for (var depth = 0; depth < 3 && target.firstElementChild && 'contents' === w.getComputedStyle(target).display; depth++) {
				target = target.firstElementChild;
			}

			if (target !== el) {
				el.removeAttribute('data-ha-tfx');
			}

			target.setAttribute('data-ha-tfx', id);

			var css = config ? buildCss('[data-ha-tfx="' + id + '"]', config) : '';

			if (!css) {
				if (tag) {
					tag.remove();
				}

				return;
			}

			if (!tag) {
				tag = d.createElement('style');
				tag.id = 'ha-tfx-' + id;
				d.head.appendChild(tag);
			}

			if (tag.textContent !== css) {
				tag.textContent = css;
			}
		});
	}

	function boot() {
		scan();

		// Settings changes fire no DOM mutation, so the editor is polled.
		setInterval(scan, 500);
	}

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
