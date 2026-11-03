/**
 * Happy Addons — Happy Mouse Cursor, Elementor V4 (Atomic).
 *
 * Registers the feature with the Atomic runtime (happy-addons-atomic.js): the
 * runtime hands every element that has the cursor enabled (frontend config or,
 * in the editor, the editor settings) to CustomMouseCursor. The cursor itself is
 * the same MouseFollower the Classic feature uses, so the markup and the base
 * CSS (custom-mouse-cursor.css) are shared; only the per-element rules differ
 * and are built here from the config (keep in sync with the Classic selectors).
 */
(function (w, d) {
	'use strict';

	if (!w.HappyAtomic || 'function' !== typeof w.HappyAtomic.registerFeature) {
		return;
	}

	var cursor = null;

	function getCursor() {
		if (cursor) {
			return cursor;
		}

		if ('undefined' === typeof MouseFollower) {
			return null;
		}

		// Classic's script (loaded when a Classic element on the page has the cursor)
		// keeps its own cursor in a global; a second one would draw over this one
		// with the default style, so both share it.
		if ('undefined' !== typeof haCursor && haCursor) {
			cursor = haCursor;

			return cursor;
		}

		cursor = new MouseFollower({
			el: null,
			container: d.body,
			className: 'mf-cursor ha-cursor ha-init-hide',
			innerClassName: 'mf-cursor-inner ha-cursor-inner',
			textClassName: 'mf-cursor-text ha-cursor-text',
			mediaClassName: 'mf-cursor-media ha-cursor-media',
			mediaBoxClassName: 'mf-cursor-media-box ha-cursor-media-box',
			iconSvgClassName: 'mf-svgsprite ha-svgsprite',
			iconSvgNamePrefix: '-',
			iconSvgSrc: '',
			dataAttr: 'cursor',
			hiddenState: '-hidden',
			textState: '-text ha-text',
			iconState: '-icon ha-icon',
			activeState: false,
			mediaState: '-media ha-media',
			stateDetection: {
				'-hidden': 'iframe'
			},
			visible: true,
			visibleOnState: false,
			speed: 0.2,
			ease: 'expo.out',
			overwrite: true,
			skewing: 0,
			skewingIcon: 0,
			skewingDelta: 0,
			skewingDeltaMax: 0,
			stickDelta: 0,
			showTimeout: 20,
			hideOnLeave: true,
			hideTimeout: 500,
			hideMediaTimeout: 500,
			rotation: false
		});

		if ('undefined' !== typeof haCursor && !haCursor) {
			haCursor = cursor;
		}

		return cursor;
	}

	/* ------------------------------------------------------------------ *
	 * Helpers
	 * ------------------------------------------------------------------ */
	function unwrap(value) {
		while (value && 'object' === typeof value && '$$type' in value) {
			value = value.value;
		}

		return value;
	}

	var attachmentUrls = {};

	// Attachment url from the editor's media library (an image picked there is stored by id).
	function attachmentUrl(id, done) {
		var media = (w.parent && w.parent.wp && w.parent.wp.media) || (w.wp && w.wp.media);

		if (!id || !media) {
			return '';
		}

		if (attachmentUrls[id]) {
			return attachmentUrls[id];
		}

		var model = media.attachment(id);
		var url = model.get('url');

		if (url) {
			attachmentUrls[id] = url;

			return url;
		}

		model.fetch().then(function () {
			attachmentUrls[id] = model.get('url') || '';

			if (attachmentUrls[id] && done) {
				done();
			}
		});

		return '';
	}

	function mediaUrl(value, done) {
		value = unwrap(value);

		if (!value) {
			return '';
		}

		if ('string' === typeof value) {
			return value;
		}

		if ('object' === typeof value) {
			var url = '';

			if (undefined !== value.url) {
				url = mediaUrl(value.url, done);
			}

			if (!url && undefined !== value.src) {
				url = mediaUrl(value.src, done);
			}

			if (!url && undefined !== value.id) {
				url = attachmentUrl(unwrap(value.id), done);
			}

			return url;
		}

		return '';
	}

	function num(value, fallback) {
		var parsed = parseFloat(value);

		return isNaN(parsed) ? fallback : parsed;
	}

	function str(value) {
		return String(null === value || undefined === value ? '' : value);
	}

	function color(value) {
		value = str(value).trim();

		return /^(#[0-9a-f]{3,8}|(rgb|hsl)a?\(\s*[0-9.%,\s\/-]+\)|[a-z]+)$/i.test(value) ? value : '';
	}

	function safeToken(value) {
		return str(value).replace(/[^a-zA-Z0-9 ,'_-]/g, '');
	}

	function cssUrl(value) {
		return 'url("' + str(value).replace(/["\\()\s<>]/g, function (ch) {
			return encodeURIComponent(ch);
		}) + '")';
	}

	function escapeHTML(value) {
		return str(value).replace(/[&<>"']/g, function (ch) {
			return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[ch];
		});
	}

	function safeId(id) {
		return str(id).replace(/[^a-zA-Z0-9_-]/g, '');
	}

	function unit(value) {
		return ['px', 'em', 'rem', '%', 'vw', 'vh'].indexOf(value) > -1 ? value : 'px';
	}

	// Edges / corners of the native Atomic multi-size props, in CSS shorthand order.
	var EDGES = ['block-start', 'inline-end', 'block-end', 'inline-start'];
	var CORNERS = ['start-start', 'start-end', 'end-end', 'end-start'];

	// A multi-size value as four CSS lengths: the frontend config already holds them
	// (PHP), the editor holds the Atomic prop. Null when nothing is set.
	function lengths(value, binds) {
		value = unwrap(value);

		if (!value || 'object' !== typeof value) {
			return null;
		}

		if (Array.isArray(value)) {
			return 4 === value.length ? value.map(function (item) {
				return /^-?[0-9.]+(px|em|rem|%|vw|vh)?$/.test(item) ? item : '0';
			}) : null;
		}

		// Linked mode: one size for every side.
		if (undefined !== value.size && '' !== value.size && null !== value.size) {
			var one = num(unwrap(value.size), 0) + unit(unwrap(value.unit));

			return [one, one, one, one];
		}

		var any = false;
		var out = binds.map(function (bind) {
			var side = unwrap(value[bind]);

			if (side && 'object' === typeof side && undefined !== side.size && '' !== side.size && null !== side.size) {
				any = true;

				return num(unwrap(side.size), 0) + unit(unwrap(side.unit));
			}

			return '0';
		});

		return any ? out : null;
	}

	function box(value, binds, fallback) {
		var list = lengths(value, binds);

		return list ? list.join(' ') : fallback;
	}

	function borderRule(prefix, config) {
		var style = config[prefix + 'border_style'];

		if (!style || 'none' === style) {
			return '';
		}

		return 'border-style:' + safeToken(style) + ';border-width:' + box(config[prefix + 'border_sizes'], EDGES, '1px') + ';border-color:' + color(config[prefix + 'border_color']) + ';';
	}

	// Font-icon class of an icon-library value ({value, library}).
	function iconClass(value) {
		value = unwrap(value);

		if (value && 'object' === typeof value && undefined !== value.library) {
			var cls = unwrap(value.value);

			return 'string' === typeof cls ? cls.replace(/[^a-zA-Z0-9 _-]/g, '') : '';
		}

		return '';
	}

	/* ------------------------------------------------------------------ *
	 * CSS of an element's cursor. P is the cursor while the element is hovered.
	 * ------------------------------------------------------------------ */
	function buildCSS(id, config) {
		var P = '.ha-cursor.ha-cmc-' + id;
		var type = config.type;
		var css = '';
		var shadowColor;

		if (config.default_cursor && 'default' !== config.default_cursor) {
			css += '[data-id="' + id + '"]{cursor:' + safeToken(config.default_cursor) + ' !important;}';
		}

		if ('text' === type || 'icon' === type) {
			css += P + ' .mf-cursor-text{color:' + color('text' === type ? config.text_color : config.icon_color) + ';';

			if ('icon' === type) {
				css += 'font-size:' + num(config.icon_size, 22) + 'px;';
			} else {
				if (num(config.text_font_size, 0) > 0) {
					css += 'font-size:' + num(config.text_font_size, 0) + 'px;';
				}

				if (str(unwrap(config.text_font))) {
					css += 'font-family:' + safeToken(unwrap(config.text_font)) + ';';
				}

				if (config.text_font_weight && 'default' !== config.text_font_weight) {
					css += 'font-weight:' + safeToken(config.text_font_weight) + ';';
				}

				if (config.text_transform && 'none' !== config.text_transform) {
					css += 'text-transform:' + safeToken(config.text_transform) + ';';
				}

				if (num(config.text_line_height, 0) > 0) {
					css += 'line-height:' + num(config.text_line_height, 0) + ';';
				}

				if (0 !== num(config.text_letter_spacing, 0)) {
					css += 'letter-spacing:' + num(config.text_letter_spacing, 0) + 'px;';
				}
			}

			css += '}';

			if ('icon' === type && config.media_svg && 'string' === typeof config.media_svg) {
				css += P + ' .mf-cursor-text .ha-cmc-svg{display:block;width:' + num(config.icon_size, 22) + 'px;height:' + num(config.icon_size, 22) + 'px;background-color:' + color(config.icon_color) + ';-webkit-mask:' + cssUrl(config.media_svg) + ' center/contain no-repeat;mask:' + cssUrl(config.media_svg) + ' center/contain no-repeat;}';
			}

			if ('icon' === type && config.icon_html) {
				css += P + ' .mf-cursor-text svg{display:block;width:' + num(config.icon_size, 22) + 'px;height:' + num(config.icon_size, 22) + 'px;fill:currentColor;}';
			}
		}

		if ('image' === type) {
			var imgBox = P + ' .mf-cursor-inner .mf-cursor-media-box';
			var radius = box(config.img_radius_sizes, CORNERS, '0');

			css += imgBox + ' img{object-fit:' + safeToken(config.img_fit) + ';}';
			css += imgBox + ',' + imgBox + ' img{width:' + num(config.img_width, 150) + 'px;height:' + num(config.img_height, 150) + 'px;border-radius:' + radius + ';}';

			if (borderRule('img_', config)) {
				css += imgBox + ' img{' + borderRule('img_', config) + '}';
			}

			css += P + '.-media .mf-cursor-media-box{transform:rotate(' + num(config.img_rotate, 0) + 'deg);}';
			css += P + '.-media .mf-cursor-media-box img{opacity:' + num(config.img_opacity, 1) + ';}';
			css += P + '.ha-media:before{background:transparent;}';

			if (config.img_rotation) {
				css += P + '.-media .ha-cursor-media .ha-cursor-media-box{animation:haImageRotation' + ('anti-clockwise' === config.img_rotation_direction ? 'AntiClockwise' : 'Clockwise') + ' ' + num(config.img_rotation_delay, 3) + 's linear infinite;}';
			}

			if (config.img_offset) {
				css += P + '.-media.ha-media .mf-cursor-inner.ha-cursor-inner{position:fixed;left:' + num(config.img_offset_x, 0) + 'px;top:' + num(config.img_offset_y, 0) + 'px;}';
			}

			css += P + '.-media .ha-cursor-media .ha-cursor-media-box img{filter:blur(' + num(config.img_blur, 0) + 'px) brightness(' + num(config.img_brightness, 100) + '%) contrast(' + num(config.img_contrast, 100) + '%) saturate(' + num(config.img_saturate, 100) + '%) hue-rotate(' + num(config.img_hue, 0) + 'deg);}';
		}

		if ('video' === type) {
			var vbox = P + ' .mf-cursor-inner .mf-cursor-media-box';
			var vradius = box(config.video_radius_sizes, CORNERS, '50%');

			css += vbox + ' video{object-fit:' + safeToken(config.video_fit) + ';border-radius:' + vradius + ';}';
			css += P + ' .mf-cursor-inner .mf-cursor-media{width:' + num(config.video_width, 150) + 'px;height:' + num(config.video_height, 150) + 'px;}';
			css += vbox + '{border-radius:' + vradius + ';' + borderRule('video_', config) + '}';
			css += P + '.-media .mf-cursor-media-box{opacity:' + num(config.video_opacity, 1) + ';}';
			css += P + '.ha-media:before{background:transparent;}';

			if (config.video_offset) {
				css += P + '.-media.ha-media .mf-cursor-inner.ha-cursor-inner{position:fixed;left:' + num(config.video_offset_x, 0) + 'px;top:' + num(config.video_offset_y, 0) + 'px;}';
			}
		}

		if ('video' !== type && 'image' !== type) {
			if (config.blur_switcher) {
				css += P + '.mf-cursor.-text:before{backdrop-filter:blur(' + num(config.blur, 6) + 'px);}';
				css += P + '.mf-cursor:before{background:' + color(config.blur_bg) + ';}';
			} else {
				css += P + '.mf-cursor:before{background:' + color(config.bg) + ';}';
			}

			css += P + '.mf-cursor.-text:before,' + P + '.-media:before,' + P + ' .ha-cursor-text{width:' + num(config.box_width, 80) + 'px;height:' + num(config.box_height, 80) + 'px;}';
			css += P + '.mf-cursor:before{' + borderRule('', config) + 'border-radius:' + box(config.radius_sizes, CORNERS, '50%') + ';}';
		}

		css += P + '.mf-cursor .ha-cursor-text,' + P + '.mf-cursor .ha-cursor-media-box{padding:' + box(config.padding_sizes, EDGES, '0') + ';}';

		if (lengths(config.margin_sizes, EDGES)) {
			css += P + '.mf-cursor .ha-cursor-text,' + P + '.mf-cursor .ha-cursor-media-box{margin:' + box(config.margin_sizes, EDGES, '0') + ';}';
		}

		if ('video' !== type && config.shadow) {
			shadowColor = color(config.shadow_color);
			css += P + '.mf-cursor.-text:before,' + P + '.mf-cursor.-media:before{box-shadow:' + num(config.shadow_x, 0) + 'px ' + num(config.shadow_y, 0) + 'px ' + num(config.shadow_blur, 10) + 'px ' + num(config.shadow_spread, 0) + 'px ' + shadowColor + ';}';
		}

		return css;
	}

	/* ------------------------------------------------------------------ *
	 * Runtime
	 * ------------------------------------------------------------------ */
	var instances = [];

	function refreshAll() {
		instances.slice().forEach(function (instance) {
			try {
				instance.apply();
			} catch (e) {
				if (w.console) {
					w.console.error('[Happy Mouse Cursor]', e);
				}
			}
		});
	}

	function CustomMouseCursor(el, config) {
		var self = this;

		this.el = el;
		this.config = config;
		this.id = safeId(el.getAttribute('data-id'));
		this.cls = 'ha-cmc-' + this.id;
		this.style = null;
		this.destroyed = false;
		this.enter = this.onEnter.bind(this);
		this.leave = this.onLeave.bind(this);

		this.expected = {};
		this.guard = null;

		el.addEventListener('mouseenter', this.enter);
		el.addEventListener('mouseleave', this.leave);

		// Classic's global handler strips the cursor attributes of every element it
		// does not know when that element becomes ready (also when the editor
		// re-renders it); put them back.
		if (w.MutationObserver) {
			this.guard = new MutationObserver(function () {
				if (!self.destroyed && CustomMouseCursor.ATTRS.some(function (name) {
					return (self.expected[name] === undefined ? null : self.expected[name]) !== el.getAttribute(name);
				})) {
					self.apply();
				}
			});
			this.guard.observe(el, {attributes: true, attributeFilter: CustomMouseCursor.ATTRS});
		}

		instances.push(this);

		try {
			this.build();
		} catch (e) {
			if (w.console) {
				w.console.error('[Happy Mouse Cursor]', e);
			}
		}
	}

	CustomMouseCursor.ATTRS = ['data-cursor-text', 'data-cursor-img', 'data-cursor-video'];

	CustomMouseCursor.prototype.onEnter = function () {
		var instance = getCursor();

		if (instance && instance.el) {
			instance.el.classList.add(this.cls);
		}
	};

	CustomMouseCursor.prototype.onLeave = function () {
		if (cursor && cursor.el) {
			cursor.el.classList.remove(this.cls);
		}
	};

	CustomMouseCursor.prototype.clearAttrs = function () {
		var el = this.el;

		CustomMouseCursor.ATTRS.forEach(function (attr) {
			el.removeAttribute(attr);
		});
	};

	// Computes the attributes and rules of the cursor; apply() puts them on the element.
	CustomMouseCursor.prototype.build = function () {
		var self = this;
		var config = this.config;
		var done = function () {
			if (!self.destroyed) {
				self.build();
			}
		};
		var type = config.type;
		var attrs = {};

		if ('color' === type) {
			attrs['data-cursor-text'] = ' ';
		} else if ('text' === type) {
			attrs['data-cursor-text'] = str(config.text) ? escapeHTML(config.text) : ' ';
		} else if ('icon' === type) {
			var cls = iconClass(config.media_svg);

			if (cls) {
				attrs['data-cursor-text'] = '<i class="' + cls + '"></i>';
			} else if (config.icon_html) {
				attrs['data-cursor-text'] = config.icon_html;
			} else {
				config.media_svg = mediaUrl(config.media_svg, done);
				attrs['data-cursor-text'] = config.media_svg ? '<i class="ha-cmc-svg"></i>' : ' ';
			}
		} else if ('image' === type) {
			config.media_image = mediaUrl(config.media_image, done);
			attrs['data-cursor-img'] = config.media_image || ' ';
		} else if ('video' === type) {
			config.media_video = mediaUrl(config.media_video, done);
			attrs['data-cursor-video'] = config.media_video || ' ';
		}

		this.attrs = attrs;
		this.css = buildCSS(this.id, config);

		refreshAll();
	};

	// A parent with its own cursor wins over its children, as in Classic. It is
	// worked out from the live instances, so it holds whatever the order they are
	// built or rebuilt in.
	CustomMouseCursor.prototype.apply = function () {
		var el = this.el;
		var self = this;
		var suppressed = instances.some(function (other) {
			return other !== self && !other.destroyed && other.attrs && other.el !== el && other.el.contains(el);
		});

		this.clearAttrs();
		this.expected = {};

		if (suppressed || !this.attrs || !getCursor()) {
			this.removeStyle();

			return;
		}

		this.expected = this.attrs;

		Object.keys(this.attrs).forEach(function (name) {
			el.setAttribute(name, self.attrs[name]);
		});

		this.writeStyle(this.css);

		// The element is rebuilt while the pointer may already be over it.
		if (el.matches && el.matches(':hover')) {
			this.onEnter();
		}
	};

	CustomMouseCursor.prototype.writeStyle = function (css) {
		if (!this.style) {
			this.style = d.createElement('style');
			this.style.id = 'ha-cmc-style-' + this.id;
			d.head.appendChild(this.style);
		}

		this.style.textContent = css;
	};

	CustomMouseCursor.prototype.removeStyle = function () {
		if (this.style && this.style.parentNode) {
			this.style.parentNode.removeChild(this.style);
		}

		this.style = null;
	};

	CustomMouseCursor.prototype.destroy = function () {
		this.destroyed = true;
		this.expected = {};

		if (this.guard) {
			this.guard.disconnect();
		}

		this.el.removeEventListener('mouseenter', this.enter);
		this.el.removeEventListener('mouseleave', this.leave);
		this.onLeave();
		this.clearAttrs();
		this.removeStyle();

		instances = instances.filter(function (instance) {
			return instance !== this;
		}, this);

		refreshAll();
	};

	/* ------------------------------------------------------------------ *
	 * Editor: the canvas renders Atomic elements client side, so the settings
	 * are read from the editor. This has its own watcher (the shared one keys
	 * its runtimes by element, which another feature on the same element would
	 * block) and rebuilds the cursor whenever a setting changes.
	 * ------------------------------------------------------------------ */
	var TYPES = ['e-flexbox', 'e-div-block', 'e-heading', 'e-paragraph', 'e-image', 'e-button', 'e-divider', 'e-svg'];
	var PREFIX = 'ha_cmc_';
	var editorNodes = new Map();

	function isEditor() {
		return d.body.classList.contains('elementor-editor-active') ||
			!!(w.elementorFrontend && w.elementorFrontend.isEditMode && w.elementorFrontend.isEditMode());
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
			var value = unwrap(attributes[PREFIX + key]);

			config[key] = (null === value || undefined === value || '' === value) ? defaults[key] : value;
		});

		return config;
	}

	function dropEditorNode(node) {
		var entry = editorNodes.get(node);

		if (entry) {
			entry.instance.destroy();
			editorNodes.delete(node);
		}
	}

	function scanEditor() {
		if (!isEditor()) {
			return;
		}

		var defaults = (w.HappyAtomic.defaults && w.HappyAtomic.defaults['custom-mouse-cursor']) || {};

		editorNodes.forEach(function (entry, node) {
			if (!d.body.contains(node)) {
				dropEditorNode(node);
			}
		});

		d.querySelectorAll('[data-id]').forEach(function (node) {
			var container = editorContainer(node.getAttribute('data-id'));
			var type = container && container.model ? (container.model.get('widgetType') || container.model.get('elType')) : null;
			var entry = editorNodes.get(node);

			if (!container || TYPES.indexOf(type) === -1) {
				return;
			}

			var config = editorConfig(container, defaults);

			if (!config || !config.switcher) {
				dropEditorNode(node);

				return;
			}

			var json = JSON.stringify(config);

			if (entry && entry.json === json) {
				return;
			}

			dropEditorNode(node);

			editorNodes.set(node, {json: json, instance: new CustomMouseCursor(node, config)});
		});
	}

	// The editor is only known once its frontend runs, so this always starts; on a
	// public page it stops by itself.
	var ticks = 0;
	var timer = setInterval(function () {
		scanEditor();

		if (!isEditor() && ++ticks > 100) {
			clearInterval(timer);
		}
	}, 300);

	// Frontend: the config PHP prints. An empty type keeps the shared scan from
	// building a second, stale runtime on the same node in the editor, where the
	// watcher above owns the cursor.
	w.HappyAtomic.registerFeature({
		key: 'custom-mouse-cursor',
		type: [],
		Runtime: CustomMouseCursor
	});
}(window, document));
