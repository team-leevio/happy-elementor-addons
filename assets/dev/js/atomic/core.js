/**
 * Happy Addons — Elementor V4 (Atomic) runtime.
 *
 * Atomic elements do not fire frontend/element_ready/*, so every component
 * here is found in the DOM and tracked in a single WeakMap:
 *  - Image Cycle   [data-ha-atomic="image-cycle"] (settings in data-ha-config)
 *  - Appearing Image Animation / Text Animation: elements listed in
 *    window.HappyAtomic.features (keyed by the element's data-id).
 *  - In the Elementor editor the canvas renders Atomic elements client side, so
 *    PHP never sees them: Appearing Image Animation reads its settings from the
 *    editor instead (window.HappyAtomic.defaults tells which features are on).
 */
(function (w, d) {
	'use strict';

	if (typeof gsap === 'undefined') {
		return;
	}

	if (typeof ScrollTrigger !== 'undefined') {
		gsap.registerPlugin(ScrollTrigger);
	}

	var MOBILE_QUERY = '(max-width: 767px)';
	var instances = new WeakMap();
	var live = []; // elements with an instance, so detached ones can be cleaned up

	function reducedMotion() {
		return w.matchMedia && w.matchMedia('(prefers-reduced-motion: reduce)').matches;
	}

	function isEditor() {
		return d.body.classList.contains('elementor-editor-active') ||
			!!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
	}

	function isMobile() {
		if (w.elementorFrontend && typeof w.elementorFrontend.getCurrentDeviceMode === 'function') {
			var mode = w.elementorFrontend.getCurrentDeviceMode();
			return 'mobile' === mode || 'mobile_extra' === mode;
		}

		return w.matchMedia && w.matchMedia(MOBILE_QUERY).matches;
	}

	function num(value, fallback) {
		var parsed = parseFloat(value);

		return isNaN(parsed) ? fallback : parsed;
	}

	function parseJSON(text) {
		try {
			return JSON.parse(text) || {};
		} catch (e) {
			return {};
		}
	}

	function triggerStart(config) {
		if ('custom' === config.trigger_point) {
			return config.custom_trigger_start || 'top 80%';
		}

		return String(config.trigger_point || 'top-center').replace('-', ' ');
	}

	/* ------------------------------------------------------------------ *
	 * Editor: settings of an element come from the editor, not from PHP
	 * ------------------------------------------------------------------ */
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

	// Settings of a feature as the frontend config: prefix stripped, defaults filled in.
	function editorFeatureConfig(settings, prefix, defaults) {
		var config = {};

		Object.keys(defaults).forEach(function (key) {
			var value = unwrapProp(settings[prefix + key]);

			config[key] = (null === value || undefined === value || '' === value) ? defaults[key] : value;
		});

		return config;
	}

	function register(el, instance) {
		instances.set(el, instance);
		live.push(el);
	}

	function destroy(el) {
		var instance = instances.get(el);

		if (instance && instance.destroy) {
			try {
				instance.destroy();
			} catch (e) {}
		}

		instances.delete(el);
		live = live.filter(function (item) {
			return item !== el;
		});
	}
