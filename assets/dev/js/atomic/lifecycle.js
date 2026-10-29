	/* ------------------------------------------------------------------ *
	 * Boot + lifecycle
	 * ------------------------------------------------------------------ */
	// Features that also run in the editor preview: Atomic element type, setting
	// prefix, runtime class and the node that gets animated.
	var EDITOR_FEATURES = [
		{
			key: 'appearing-image-animation',
			type: 'e-image',
			prefix: 'ha_aia_',
			Runtime: AppearingImage,
			target: function (node) {
				return node.querySelector('img');
			}
		},
		{
			key: 'text-animation',
			type: 'e-heading',
			prefix: 'ha_hta_',
			Runtime: TextAnimation,
			target: function (node) {
				return node.matches('[data-interaction-id]') ? node : node.querySelector('[data-interaction-id]');
			}
		}
	];

	// Features added by other plugins (Happy Addons Pro) through HappyAtomic.registerFeature().
	var external = [];
	var booted = false;

	function editorElementType(id) {
		try {
			var editor = (w.parent && w.parent.elementor) || w.elementor;
			var container = editor && editor.getContainer && editor.getContainer(id);

			return container ? (container.model.get('widgetType') || container.model.get('elType')) : null;
		} catch (e) {
			return null;
		}
	}

	function scanEditorFeatures(root) {
		var defaults = w.HappyAtomic && w.HappyAtomic.defaults;

		if (!defaults || !isEditor()) {
			return;
		}

		var features = EDITOR_FEATURES.concat(external.filter(function (feature) {
			return feature.type;
		})).filter(function (feature) {
			return defaults[feature.key];
		});

		if (!features.length) {
			return;
		}

		(root || d).querySelectorAll('[data-id]').forEach(function (node) {
			var id = node.getAttribute('data-id');
			var type = editorElementType(id);

			features.forEach(function (feature) {
				if ([].concat(feature.type).indexOf(type) === -1) {
					return;
				}

				var el = feature.target(node);

				if (!el) {
					return;
				}

				var settings = editorSettings(id);
				var config = settings ? editorFeatureConfig(settings, feature.prefix, defaults[feature.key]) : null;
				var existing = instances.get(el);

				// Features can share an element type: one feature must not destroy or
				// rebuild the runtime another feature put on the same element.
				if (existing && existing.featureKey && existing.featureKey !== feature.key) {
					return;
				}


				if (!config || !config.switcher) {
					if (existing) {
						destroy(el);
					}

					return;
				}

				var configJSON = JSON.stringify(config);
				var titleJSON = settings.title ? JSON.stringify(settings.title) : '';

				if (existing) {
					// Editing the text, or any change that re-renders the element, replaces
					// the markup the runtime built; the class it adds is then gone.
					var replaced = existing.isStale ? existing.isStale() : existing.built && !el.classList.contains('ha-hta-yes');
					var changed = existing.configJSON !== configJSON;

					if (!changed && existing.titleJSON === titleJSON && !replaced) {
						return;
					}

					if (replaced || existing.titleJSON !== titleJSON) {
						// Do not restore the old markup over what the editor rendered.
						if (existing.abandon) {
							existing.abandon();
						}

						instances.delete(el);
						live = live.filter(function (item) {
							return item !== el;
						});
					} else {
						destroy(el);
					}
				}

				var instance = new feature.Runtime(el, config);

				instance.featureKey = feature.key;
				instance.configJSON = configJSON;
				instance.titleJSON = titleJSON;
				register(el, instance);
			});
		});
	}

	function scan(root) {
		scanEditorFeatures(root);

		(root || d).querySelectorAll('[data-ha-atomic="image-cycle"]').forEach(function (el) {
			if (!instances.has(el)) {
				register(el, new ImageCycle(el));
			}
		});

		var features = (w.HappyAtomic && w.HappyAtomic.features) || {};

		[['appearing-image-animation', AppearingImage], ['text-animation', TextAnimation]].concat(external.map(function (feature) {
			// In the editor the settings scan owns these: a runtime built here from
			// the saved config is destroyed and rebuilt by that scan, over and over.
			return isEditor() && feature.type ? null : [feature.key, feature.Runtime];
		})).filter(Boolean).forEach(function (item) {
			var configs = features[item[0]] || {};

			Object.keys(configs).forEach(function (id) {
				var el = d.querySelector('[data-id="' + id.replace(/"/g, '') + '"]');

				if (el && !instances.has(el)) {
					register(el, new item[1](el, configs[id]));
				}
			});
		});
	}

	function sweep() {
		live.slice().forEach(function (el) {
			if (!d.body.contains(el)) {
				destroy(el);
				return;
			}

			var instance = instances.get(el);

			if (instance instanceof ImageCycle && instance.signature !== ImageCycle.signature(el)) {
				instance.update();
			}
		});

		scan(d);
	}

	var watching = false;

	function watchEditor() {
		if (watching) {
			return;
		}

		watching = true;

		var timer = null;
		var observer = new MutationObserver(function () {
			clearTimeout(timer);
			timer = setTimeout(sweep, 200);
		});

		observer.observe(d.body, {
			childList: true,
			subtree: true,
			attributes: true,
			attributeFilter: ['data-ha-config']
		});

		// A setting that does not change the markup (the animation settings) fires
		// no DOM mutation, so the editor settings are also checked on a timer.
		setInterval(function () {
			scanEditorFeatures(d);
		}, 500);
	}

	function boot() {
		booted = true;
		scan(d);

		if (isEditor()) {
			watchEditor();
		}

		w.addEventListener('pagehide', function () {
			live.slice().forEach(destroy);
		});
	}

	// Public surface for add-on features: shared helpers and a registration hook.
	w.HappyAtomic = w.HappyAtomic || {};
	w.HappyAtomic.api = {
		reducedMotion: reducedMotion,
		isEditor: isEditor,
		isMobile: isMobile,
		num: num
	};
	w.HappyAtomic.registerFeature = function (feature) {
		if (!feature || !feature.key || 'function' !== typeof feature.Runtime) {
			return;
		}

		external.push(feature);

		if (booted) {
			scan(d);
		}
	};

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
