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

	/* ------------------------------------------------------------------ *
	 * Image Cycle
	 * ------------------------------------------------------------------ */
	function ImageCycle(wrapper) {
		this.wrapper = wrapper;
		this.config = parseJSON(wrapper.getAttribute('data-ha-config'));
		this.signature = ImageCycle.signature(wrapper);
		this.mm = null;
		this.tweens = [];
		this.runToken = 0;
		this.hasEntered = false;
		this.resumeTimer = null;
		this.io = null;
		this.pending = false;
		this.timeline = null;
		this.hoverHandlers = [];
		this.safetyTimer = null;
		this.run();
	}

	ImageCycle.signature = function (wrapper) {
		return (wrapper.getAttribute('data-ha-config') || '') + '|' + wrapper.querySelectorAll('.ha-ic-group > .ha-ic-card').length;
	};

	ImageCycle.prototype.cards = function () {
		return Array.prototype.slice.call(this.wrapper.querySelectorAll('.ha-ic-group > .ha-ic-card'), 0, 20);
	};

	ImageCycle.prototype.parts = function () {
		var wrapper = this.wrapper;

		return {
			group: wrapper.querySelector('.ha-ic-group'),
			container: wrapper.querySelector('.ha-ic-container'),
			headings: wrapper.querySelector('.ha-ic-headings')
		};
	};

	ImageCycle.prototype.reset = function (cards) {
		var parts = this.parts();
		var targets = cards.concat([parts.group, parts.container, parts.headings]).filter(Boolean);

		gsap.killTweensOf(targets);
		gsap.set(targets, {clearProps: 'all'});
	};

	ImageCycle.prototype.clearPlayOnAppear = function () {
		if (this.io) {
			this.io.disconnect();
			this.io = null;
		}

		this.pending = false;
		this.timeline = null;
	};

	ImageCycle.prototype.setRotationPaused = function (paused) {
		this.tweens.forEach(function (tween) {
			if (paused) {
				tween.pause();
			} else {
				tween.play();
			}
		});
	};

	ImageCycle.prototype.observePlayOnAppear = function () {
		var self = this;

		if (!('IntersectionObserver' in w)) {
			self.resumePlayOnAppear();
			return;
		}

		self.io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					self.resumePlayOnAppear();
				}
			});
		}, {root: null, rootMargin: '-50% 0px -50% 0px', threshold: 0});

		self.io.observe(self.wrapper);
	};

	ImageCycle.prototype.resumePlayOnAppear = function () {
		if (this.io) {
			this.io.disconnect();
			this.io = null;
		}

		if (!this.pending) {
			return;
		}

		this.pending = false;
		this.hasEntered = true;

		if (this.timeline) {
			this.timeline.play();
			this.timeline = null;
		}
	};

	ImageCycle.prototype.run = function () {
		var self = this;
		var cards = self.cards();

		self.teardownTimeline();

		if (!cards.length) {
			return;
		}

		self.wrapper.classList.add('ha-ic--loading');
		self.reset(cards);

		self.runToken++;
		var token = self.runToken;
		var started = false;

		var start = function () {
			if (started || token !== self.runToken) {
				return;
			}

			started = true;
			clearTimeout(self.safetyTimer);
			self.wrapper.classList.remove('ha-ic--loading');
			self.build(cards);
		};

		// Never leave the headings hidden if an image stalls.
		self.safetyTimer = setTimeout(start, 5000);

		if (typeof imagesLoaded !== 'undefined') {
			try {
				imagesLoaded(cards, start);
			} catch (e) {
				start();
			}
		} else {
			start();
		}
	};

	ImageCycle.prototype.teardownTimeline = function () {
		clearTimeout(this.safetyTimer);
		clearTimeout(this.resumeTimer);

		if (this.mm) {
			this.mm.revert();
			this.mm = null;
		}

		this.clearPlayOnAppear();
		this.unbindHover();
		this.tweens = [];
	};

	ImageCycle.prototype.unbindHover = function () {
		this.hoverHandlers.forEach(function (item) {
			item.el.removeEventListener(item.type, item.fn);
		});

		this.hoverHandlers = [];
	};

	ImageCycle.prototype.build = function (cards) {
		var self = this;
		var config = self.config;
		var parts = self.parts();
		var wrapper = self.wrapper;
		var count = cards.length;
		var mode = config.animation_mode || 'variation-1';
		var direction = config.direction || 'bottom';
		var rotationDir = 'counter-clockwise' === config.rotation_direction ? -1 : 1;
		var pauseOnHover = !!config.pause_on_hover;
		var playOnAppear = !!config.play_on_appear;
		var reduced = reducedMotion();
		var spinOffset = 1 === rotationDir ? 0 : -360;

		var stagger = num(config.stagger, 0.1);

		if ('variation-3' === mode) {
			stagger = num(config.stagger_v3, 0.15);
		}

		var entranceDuration = num(config.entrance_duration, 0.5);
		var headingDuration = num(config.heading_duration, 1);
		var groupDuration = num(config.group_duration, 20);
		var flipInterval = num(config.flip_interval, 2);
		var initialScale = num(config.initial_scale, 3);
		var initialOpacity = num(config.initial_opacity, 0.8);
		var headings = parts.headings ? [parts.headings] : [];

		self.mm = gsap.matchMedia();
		self.tweens = [];

		self.mm.add({isDesktop: '(min-width: 53em)', isMobile: '(max-width: 53em)'}, function (context) {
			var small = context.conditions.isMobile && !context.conditions.isDesktop;
			var radius = num(small ? config.radius_mobile : config.radius, small ? 180 : 250);
			var radius1Base = num(small ? config.radius1_mobile : config.radius1, 50);
			var image = cards[0].querySelector('.ha-ic-card__img') || cards[0];
			var imageH = image ? image.clientHeight : 0;
			var imageW = image ? image.clientWidth : 0;
			var sliceAngle = (2 * Math.PI) / count;
			var tl = gsap.timeline();
			var entranceEnd = 0;

			gsap.set(cards, {clearProps: 'all'});

			var flip = function () {
				gsap.to(cards[Math.floor(Math.random() * count)], {rotateY: '+=180'});
			};

			var addRotation = function (target, position, vars) {
				var tween = gsap.to(target, vars);

				tl.add(tween, position);
				self.tweens.push(tween);
			};

			if ('variation-1' === mode) {
				var radius1 = radius1Base + (image ? imageH / 2 : 60);
				var radius2 = radius - radius1;
				var wrapW = wrapper.offsetWidth || w.innerWidth;
				var wrapH = wrapper.offsetHeight || w.innerHeight;
				var offsetY = wrapH / 2 + (image ? imageH * 1.5 : 120);
				var offsetX = wrapW / 2 + (image ? imageW * 1.5 : 120);
				var from = {stagger: stagger, duration: 0.5, opacity: initialOpacity, scale: initialScale};

				if ('top' === direction) {
					from.y = -offsetY;
					from.rotateX = 180 * rotationDir;
				} else if ('left' === direction) {
					from.x = -offsetX;
					from.rotateY = -180 * rotationDir;
				} else if ('right' === direction) {
					from.x = offsetX;
					from.rotateY = 180 * rotationDir;
				} else {
					from.y = offsetY;
					from.rotateX = -180 * rotationDir;
				}

				tl.from(cards, from)
					.set(cards, {transformOrigin: 'center ' + (radius1 + (image ? imageH / 2 : 60)) + 'px'})
					.set(parts.group, {transformStyle: 'preserve-3d'})
					.to(cards, {y: -radius1, duration: entranceDuration, ease: 'power1.out'})
					.to(cards, {
						rotation: function (i) {
							return (i * 360) / count + spinOffset;
						},
						rotateY: 15,
						duration: 1,
						ease: 'power1.out'
					}, '<')
					.to(cards, {
						x: function (i) {
							return Math.round(radius2 * Math.cos(sliceAngle * i - Math.PI / 4));
						},
						y: function (i) {
							return Math.round(radius2 * Math.sin(sliceAngle * i - Math.PI / 4)) - radius1;
						},
						rotation: function (i) {
							return (i + 1) * (360 / count) + spinOffset;
						}
					})
					.to(cards, {rotateY: 180, opacity: initialOpacity, duration: 1}, '<');

				if (headings.length) {
					tl.from(headings, {opacity: 0, filter: 'blur(60px)', duration: headingDuration}, '<');
				}

				entranceEnd = tl.duration();

				tl.to(cards, {repeat: -1, duration: flipInterval, onRepeat: flip});
				addRotation(parts.group, '<-=' + flipInterval, {
					rotation: 360 * rotationDir,
					duration: groupDuration,
					repeat: -1,
					ease: 'none'
				});
			} else if ('variation-2' === mode) {
				tl.from(cards, {
					x: function (i) {
						var cardW = image ? imageW : 80;

						return i % 2 ? -w.innerWidth / 2 - cardW * 4 : w.innerWidth / 2 + cardW * 4;
					},
					rotation: function (i) {
						return i % 2 ? -90 * rotationDir : 90 * rotationDir;
					},
					delay: function (i) {
						return Math.floor(i / 2) * stagger;
					},
					duration: 1,
					opacity: initialOpacity,
					scale: initialScale,
					ease: 'power4.out'
				})
					.set(cards, {
						scale: function (i) {
							return i > count / 2 - 1 ? -1 : 1;
						}
					})
					.to(cards, {
						y: function (i) {
							return (i >= Math.floor(count / 2) ? 1 : -1) * radius;
						},
						duration: entranceDuration,
						ease: 'power2.out'
					})
					.set(cards, {
						transformOrigin: 'center ' + (radius + (image ? imageH / 2 : 60)) + 'px',
						y: function (i) {
							if (i >= Math.floor(count / 2)) {
								return -radius;
							}
						}
					})
					.to(cards, {
						rotation: function (i) {
							return i > count / 2 - 1 ? ((count - i - 1) * 360) / count + spinOffset : (i * 360) / count + spinOffset;
						},
						opacity: initialOpacity,
						duration: 1,
						ease: 'power2.out'
					});

				if (headings.length) {
					tl.from(headings, {opacity: 0, filter: 'blur(60px)', duration: 1.5}, '<-=1');
				}

				entranceEnd = tl.duration();

				tl.to(cards, {repeat: -1, duration: flipInterval, onRepeat: flip});
				addRotation(parts.group, '<-=1.5', {
					rotation: 360 * rotationDir,
					duration: groupDuration,
					repeat: -1,
					ease: 'none'
				});
			} else {
				var ringX = function (i) {
					return Math.round(radius * Math.cos(sliceAngle * i - Math.PI / 4));
				};
				var ringY = function (i) {
					return Math.round(radius * Math.sin(sliceAngle * i - Math.PI / 4));
				};

				gsap.set(cards, {
					x: ringX,
					y: ringY,
					rotation: function (i) {
						return (i + 1) * (360 / count);
					}
				});

				tl.set(cards, {opacity: 0, scale: 0, x: 0, y: 0})
					.to(cards, {
						stagger: stagger,
						opacity: 1,
						scale: 1,
						duration: 1,
						x: ringX,
						y: ringY,
						rotation: function (i) {
							return (i + 1) * (360 / count) + 360 * rotationDir;
						}
					})
					.to(parts.group, {rotation: rotationDir * 360 - 90, duration: 3, ease: 'power4.out'}, 0);

				if (headings.length) {
					tl.from(headings, {opacity: 0, filter: 'blur(60px)', duration: headingDuration}, 1);
				}

				entranceEnd = tl.duration();

				tl.to(cards, {repeat: -1, duration: flipInterval, onRepeat: flip});
				addRotation(parts.container || parts.group, 0, {
					rotation: 1 === rotationDir ? '+=360' : '-=360',
					duration: groupDuration,
					ease: 'none',
					repeat: -1
				});
			}

			if (reduced) {
				// Show the final layout without motion.
				tl.time(entranceEnd + 0.001);
				tl.pause();
				self.tweens.forEach(function (tween) {
					tween.pause();
				});
				self.hasEntered = true;
			} else if (playOnAppear && !self.hasEntered && entranceEnd > 0) {
				tl.pause(0);
				self.pending = true;
				self.timeline = tl;
				self.observePlayOnAppear();
			} else {
				if (self.hasEntered && !self.replay && entranceEnd > 0) {
					tl.time(entranceEnd + 0.001);
				}

				self.hasEntered = true;
			}

			self.replay = false;

			return function () {
				tl.kill();
			};
		});

		if (pauseOnHover && !reduced) {
			self.bindHover(cards);
		}
	};

	ImageCycle.prototype.bindHover = function (cards) {
		var self = this;
		var hovered = function () {
			return cards.some(function (card) {
				return card.matches(':hover');
			});
		};
		var on = function (el, type, fn) {
			el.addEventListener(type, fn);
			self.hoverHandlers.push({el: el, type: type, fn: fn});
		};

		cards.forEach(function (card) {
			on(card, 'mouseenter', function () {
				clearTimeout(self.resumeTimer);
				self.setRotationPaused(true);
			});

			on(card, 'mouseleave', function () {
				if (hovered()) {
					return;
				}

				clearTimeout(self.resumeTimer);
				self.resumeTimer = setTimeout(function () {
					self.setRotationPaused(false);
				}, 60);
			});
		});

		if (hovered()) {
			self.setRotationPaused(true);
		}
	};

	ImageCycle.prototype.update = function () {
		this.config = parseJSON(this.wrapper.getAttribute('data-ha-config'));
		this.signature = ImageCycle.signature(this.wrapper);
		this.replay = true;
		this.run();
	};

	ImageCycle.prototype.destroy = function () {
		this.runToken++;
		this.teardownTimeline();
		this.reset(this.cards());
		this.wrapper.classList.remove('ha-ic--loading');
	};

	/* ------------------------------------------------------------------ *
	 * Appearing Image Animation (e-image)
	 * ------------------------------------------------------------------ */
	function AppearingImage(img, config) {
		this.img = img;
		this.config = config;
		this.wrap = null;
		this.timelines = [];
		this.triggers = [];
		this.build();
	}

	AppearingImage.CLIP = {
		left: 'inset(0 0 0 100%)',
		right: 'inset(0 100% 0 0)',
		top: 'inset(0 0 100% 0)',
		bottom: 'inset(100% 0 0 0)'
	};

	AppearingImage.CORNER = {
		'top-left': 'inset(0 100% 100% 0)',
		'top-right': 'inset(0 0 100% 100%)',
		'bottom-left': 'inset(100% 100% 0 0)',
		'bottom-right': 'inset(100% 0 0 100%)',
		center: 'inset(50% 50% 50% 50%)'
	};

	AppearingImage.ORIGIN = {
		left: 'left center',
		right: 'right center',
		top: 'center top',
		bottom: 'center bottom'
	};

	AppearingImage.prototype.build = function () {
		var img = this.img;
		var config = this.config;

		if (reducedMotion() || (isMobile() && !config.enable_on_mobile) || typeof ScrollTrigger === 'undefined') {
			img.style.visibility = 'visible';
			return;
		}

		var wrap = d.createElement('span');

		wrap.className = 'ha-aia-wrap';
		img.parentNode.insertBefore(wrap, img);
		wrap.appendChild(img);
		img.style.visibility = 'visible';
		this.wrap = wrap;

		gsap.set(wrap, {autoAlpha: 0});

		if ('stretch' === config.mode) {
			this.stretch();
		} else {
			this.animate();
		}

		setTimeout(function () {
			ScrollTrigger.refresh();
		}, 50);
	};

	AppearingImage.prototype.track = function (tl) {
		this.timelines.push(tl);

		if (tl.scrollTrigger) {
			this.triggers.push(tl.scrollTrigger);
		}
	};

	AppearingImage.prototype.stretch = function () {
		var wrap = this.wrap;
		var img = this.img;

		gsap.set(wrap, {autoAlpha: 1});

		if (isEditor()) {
			return;
		}

		wrap.style.transition = 'none';
		wrap.style.paddingBottom = '395px';

		var tl = gsap.timeline({
			scrollTrigger: {
				trigger: wrap,
				start: 'top top',
				pin: true,
				scrub: 1,
				pinSpacing: false,
				end: 'bottom bottom+=100'
			}
		});

		tl.to(img, {width: '100%', borderRadius: '0px'});
		this.track(tl);
	};

	AppearingImage.prototype.animate = function () {
		var wrap = this.wrap;
		var img = this.img;
		var config = this.config;
		var duration = num(config.animation_duration, 1.2);
		var easing = config.easing_function || 'power4.inOut';
		var tl = gsap.timeline({
			delay: num(config.delay, 0),
			scrollTrigger: {
				trigger: wrap,
				start: triggerStart(config),
				toggleActions: 'play none none none'
			}
		});

		this.track(tl);
		tl.set(wrap, {autoAlpha: 1}, 0);

		switch (config.mode) {
			case 'corner-reveal':
				tl.from(wrap, {clipPath: AppearingImage.CORNER[config.corner_direction] || AppearingImage.CORNER['top-right'], duration: duration, ease: easing}, 0);
				tl.fromTo(img, {scale: 1.15}, {scale: 1, duration: duration, ease: 'power2.out'}, 0);
				break;

			case 'scale':
				tl.fromTo(img, {
					scale: num(config.scale_from, 0.5),
					transformOrigin: AppearingImage.ORIGIN[config.rs_direction] || AppearingImage.ORIGIN.left
				}, {scale: num(config.scale_to, 1), duration: duration, ease: easing}, 0);
				break;

			case 'tiles-reveal':
				this.tiles(tl, duration, easing);
				break;

			default:
				tl.from(wrap, {clipPath: AppearingImage.CLIP[config.rs_direction] || AppearingImage.CLIP.left, duration: duration, ease: easing}, 0);
				tl.fromTo(img, {scale: 1.2}, {scale: 1, duration: duration, ease: 'power2.out'}, 0);
		}
	};

	AppearingImage.prototype.tiles = function (tl, duration, easing) {
		var wrap = this.wrap;
		var img = this.img;
		var config = this.config;
		var rect = wrap.getBoundingClientRect();
		var count = Math.max(2, Math.round(num(config.tiles_count, 5)));
		var horizontal = 'horizontal' === config.tiles_orientation;
		var dir = horizontal ? config.tiles_horizontal_direction : config.tiles_vertical_direction;
		var axis = 'scaleY';
		var origin = 'center top';
		var slices = [];

		switch (dir) {
			case 'left-to-right': axis = 'scaleX'; origin = 'left center'; break;
			case 'right-to-left': axis = 'scaleX'; origin = 'right center'; break;
			case 'top-to-bottom': axis = 'scaleY'; origin = 'center top'; break;
			case 'bottom-to-top': axis = 'scaleY'; origin = 'center bottom'; break;
		}

		var totalW = Math.round(rect.width);
		var totalH = Math.round(rect.height);
		var size = Math.ceil((horizontal ? totalW : totalH) / count);

		wrap.style.position = 'relative';
		wrap.style.overflow = 'hidden';

		for (var i = 0; i < count; i++) {
			var offset = i * size;
			var slice = d.createElement('span');

			slice.className = 'ha-aia-slice';

			Object.assign(slice.style, {
				position: 'absolute',
				top: horizontal ? '0px' : offset + 'px',
				left: horizontal ? offset + 'px' : '0px',
				width: horizontal ? size + 0.4 + 'px' : totalW + 'px',
				height: horizontal ? totalH + 'px' : size + 0.4 + 'px',
				backgroundImage: 'url("' + (img.currentSrc || img.src) + '")',
				backgroundSize: totalW + 'px ' + totalH + 'px',
				backgroundPosition: horizontal ? '-' + offset + 'px 0px' : '0px -' + offset + 'px',
				backgroundRepeat: 'no-repeat',
				transformOrigin: origin,
				backfaceVisibility: 'hidden',
				willChange: 'transform'
			});

			wrap.appendChild(slice);
			slices.push(slice);
		}

		img.style.opacity = 0;

		var from = {duration: duration, ease: easing, stagger: {each: num(config.tiles_stagger_delay, 0.08), from: 'start'}};

		from[axis] = 0;
		tl.from(slices, from);
		tl.to(img, {
			opacity: 1,
			duration: 0.2,
			onComplete: function () {
				slices.forEach(function (slice) {
					slice.remove();
				});
			}
		});
	};

	AppearingImage.prototype.destroy = function () {
		this.triggers.forEach(function (trigger) {
			trigger.kill();
		});
		this.timelines.forEach(function (tl) {
			tl.kill();
		});

		this.triggers = [];
		this.timelines = [];

		var img = this.img;
		var wrap = this.wrap;

		gsap.set(img, {clearProps: 'all'});

		if (wrap && wrap.parentNode) {
			wrap.parentNode.insertBefore(img, wrap);
			wrap.remove();
		}

		this.wrap = null;
		img.style.visibility = 'visible';
	};

	/* ------------------------------------------------------------------ *
	 * Text Animation (e-heading)
	 * ------------------------------------------------------------------ */
	function TextAnimation(el, config) {
		this.el = el;
		this.config = config;
		this.timelines = [];
		this.triggers = [];
		this.listeners = [];
		this.original = el.innerHTML;
		this.built = false;
		this.build();
	}

	TextAnimation.prototype.build = function () {
		var el = this.el;
		var config = this.config;

		if (reducedMotion() || (isMobile() && !config.enable_on_mobile) || typeof ScrollTrigger === 'undefined') {
			el.style.visibility = 'visible';
			return;
		}

		this.highlight(el);

		var mode = config.mode;

		if ('slide' === mode) {
			mode = 'words' === config.chars_words_mode ? 'words' : 'chars';
		}

		var classes = {
			chars: 'ha-hta-chars',
			words: 'ha-hta-words',
			scale: 'ha-hta-text-scale',
			invert: 'ha-hta-text-invert',
			text_flip: 'ha-hta-text-flip',
			smoky_reveal: 'ha-tm-smoky-reveal',
			alt_reveal: 'ha-tm-alt-reveal',
			popup_reveal: 'ha-tm-popup-reveal',
			mixing_reveal: 'ha-tm-mixing-reveal'
		};

		this.modeClass = classes[mode] || 'ha-hta-chars';
		el.classList.add('ha-hta-yes', this.modeClass);
		this.built = true;

		this.apply(mode);
		el.style.visibility = 'visible';
	};

	TextAnimation.prototype.apply = function (mode) {
		var el = this.el;
		var config = this.config;
		var duration = num(config.duration, 0.8);
		var stagger = num(config.stagger_delay, 0.05);
		var easing = config.easing_function || 'power2.out';
		var from = config.stagger_from || 'random';
		var targets;
		var tl = gsap.timeline({paused: true});

		switch (mode) {
			case 'chars':
			case 'words':
				targets = 'chars' === mode ? this.split(el, 'ha-char') : this.splitWords(el, 'ha-word');
				tl.from(targets, {x: num(config.cw_transform_x, 25), y: num(config.cw_transform_y, 0), opacity: 0, duration: duration, stagger: stagger, ease: easing});
				break;

			case 'reveal':
				targets = this.split(el, 'ha-char ha-reveal-char');
				tl.from(targets, {yPercent: 'top' === config.tr_orientation ? -100 : 100, opacity: 0, duration: duration, stagger: stagger, ease: easing});
				break;

			case 'text_flip':
				targets = this.splitLines(el, false);
				gsap.set(el, {perspective: 400});

				var flip = {
					duration: duration,
					delay: num(config.delay, 0.15),
					opacity: 0,
					force3D: true,
					transformOrigin: 'custom' === config.tv_transform_origin ? config.tv_transform_origin_custom : config.tv_transform_origin,
					stagger: stagger
				};

				flip['horizontal' === config.tv_rotation_direction ? 'rotationY' : 'rotationX'] = num(config.tv_rotation_value, -80);
				tl.from(targets, flip);
				break;

			case 'scale':
				if ('chars' === config.scale_text_break) {
					targets = this.split(el, 'ha-char');
				} else if ('words' === config.scale_text_break) {
					targets = this.splitWords(el, 'ha-word');
				} else {
					targets = this.splitLines(el, false);
				}

				tl.from(targets, {scale: num(config.scale, 1.5), opacity: 0, duration: duration, stagger: stagger, ease: easing});
				break;

			case 'invert':
				this.invert();
				return;

			case 'smoky_reveal':
				targets = this.split(el, 'letter');
				gsap.set(targets, {y: num(config.smoky_y_offset, 80), scale: num(config.smoky_scale, 2.5), filter: 'blur(' + num(config.smoky_blur, 15) + 'px)', opacity: 0});
				tl.to(targets, {y: 0, scale: 1, filter: 'blur(0px)', opacity: 1, duration: duration, ease: easing, stagger: {each: stagger, from: from}});
				break;

			case 'alt_reveal':
				targets = this.split(el, 'letter');
				targets.forEach(function (letter, i) {
					gsap.set(letter, {y: i % 2 === 0 ? num(config.alt_even_reveal_y_offset, -60) : num(config.alt_odd_reveal_y_offset, 60), opacity: 0});
				});
				tl.to(targets, {y: 0, opacity: 1, duration: duration, ease: easing, stagger: stagger});
				break;

			case 'popup_reveal':
				targets = this.split(el, 'letter');
				gsap.set(targets, {y: num(config.popup_y_offset, 50), rotation: num(config.popup_rotation_deg, 40), scale: 0, opacity: 0, transformOrigin: '50% 50%'});
				tl.to(targets, {y: 0, rotation: 0, scale: 1, opacity: 1, duration: duration, ease: 'back.out(1.7)', stagger: {each: stagger, from: from}});
				break;

			case 'mixing_reveal':
				targets = this.splitWords(el, 'ha-word word');
				gsap.set(targets, {opacity: 0, scale: 0, filter: 'blur(' + num(config.mixing_blur, 4) + 'px)'});
				tl.to(targets, {opacity: 1, scale: 1, filter: 'blur(0px)', duration: duration, ease: easing, stagger: {each: stagger, from: from}});
				break;

			default:
				return;
		}

		this.attach(tl);
		this.timelines.push(tl);
	};

	TextAnimation.prototype.attach = function (tl) {
		var config = this.config;
		var el = this.el;

		if ('playwithscroll' === config.trigger_mode) {
			this.triggers.push(ScrollTrigger.create({trigger: el, start: 'top bottom', end: 'bottom top', scrub: 1, animation: tl}));
			return;
		}

		this.triggers.push(ScrollTrigger.create({
			trigger: el,
			start: triggerStart(config),
			onEnter: function () {
				gsap.delayedCall(num(config.delay, 0.15), function () {
					tl.play();
				});
			}
		}));
	};

	TextAnimation.prototype.invert = function () {
		var el = this.el;
		var config = this.config;

		if (typeof SplitText === 'undefined') {
			return;
		}

		var rgb = (w.getComputedStyle(el).color.match(/(\d+)/g) || [0, 0, 0]).map(Number);
		var r = rgb[0] / 255;
		var g = rgb[1] / 255;
		var b = rgb[2] / 255;
		var l = Math.max(r, g, b);
		var s = l - Math.min(r, g, b);
		var h = s ? (l === r ? (g - b) / s : l === g ? 2 + (b - r) / s : 4 + (r - g) / s) : 0;
		var hue = 60 * h < 0 ? 60 * h + 360 : 60 * h;
		var sat = 100 * (s ? (l <= 0.5 ? s / (2 * l - s) : s / (2 - (2 * l - s))) : 0);
		var light = (100 * (2 * l - s)) / 2;

		el.style.setProperty('--text-color', hue.toFixed(1) + ', ' + sat.toFixed(1) + '%, ' + light.toFixed(1) + '%');
		el.style.setProperty('--ha-opacity', String(num(config.invert_opacity, 0.45)));

		this.splitText = new SplitText(el, {type: 'lines', linesClass: 'invert-line'});

		var start = triggerStart(config);
		var self = this;

		this.splitText.lines.forEach(function (line) {
			var tween = gsap.to(line, {
				backgroundPositionX: '-100%',
				ease: 'none',
				scrollTrigger: {trigger: line, scrub: 1, start: start, end: 'bottom center'}
			});

			self.timelines.push(tween);

			if (tween.scrollTrigger) {
				self.triggers.push(tween.scrollTrigger);
			}
		});
	};

	// Split the text of a node into per-character spans, keeping highlight spans.
	TextAnimation.prototype.split = function (el, className) {
		return this.splitBy(el, className, '');
	};

	TextAnimation.prototype.splitWords = function (el, className) {
		return this.splitBy(el, className, ' ');
	};

	TextAnimation.prototype.splitBy = function (el, className, separator) {
		var nodes = Array.prototype.slice.call(el.childNodes);
		var parts = [];

		el.textContent = '';

		var emit = function (text, highlight, color) {
			var pieces = text.split(separator);

			pieces.forEach(function (piece, i) {
				var span = d.createElement('span');

				span.className = className;

				if (highlight) {
					span.classList.add('ha-tm-highlight');

					if (color) {
						span.style.color = color;
					}
				}

				span.textContent = '' === separator && ' ' === piece ? ' ' : piece;
				el.appendChild(span);
				parts.push(span);

				if (separator && i !== pieces.length - 1) {
					el.appendChild(d.createTextNode(' '));
				}
			});
		};

		nodes.forEach(function (node) {
			if (3 === node.nodeType) {
				emit(node.textContent, false);
			} else if (1 === node.nodeType) {
				emit(node.textContent, 'true' === node.getAttribute('data-highlight'), node.getAttribute('data-color'));
			}
		});

		return parts;
	};

	TextAnimation.prototype.splitLines = function (el, mask) {
		var lines = el.innerHTML.split(/<br\s*\/?>/i);
		var out = [];

		el.textContent = '';

		lines.forEach(function (line) {
			var div = d.createElement('div');

			div.className = mask ? 'ha-invert-line' : 'ha-flip';
			div.innerHTML = line; // content that was already in the element
			el.appendChild(div);
			out.push(div);
		});

		return out;
	};

	// Wrap {text} in a highlight span.
	TextAnimation.prototype.highlight = function (el) {
		var color = this.config.highlight_color || '';

		if (color) {
			el.style.setProperty('--ha-highlight-color', color);
		} else {
			el.style.removeProperty('--ha-highlight-color');
		}

		var walk = function (node) {
			if (3 === node.nodeType) {
				var parts = node.textContent.split(/(\{.*?\})/g);

				if (parts.length < 2) {
					return;
				}

				var fragment = d.createDocumentFragment();

				parts.forEach(function (part) {
					if (part.length > 1 && '{' === part.charAt(0) && '}' === part.charAt(part.length - 1)) {
						var span = d.createElement('span');

						span.className = 'ha-tm-highlight';
						span.setAttribute('data-highlight', 'true');
						span.setAttribute('data-color', color);

						if (color) {
							span.style.color = color;
						}

						span.textContent = part.slice(1, -1);
						fragment.appendChild(span);
					} else if (part) {
						fragment.appendChild(d.createTextNode(part));
					}
				});

				node.parentNode.replaceChild(fragment, node);
			} else if (1 === node.nodeType) {
				Array.prototype.slice.call(node.childNodes).forEach(walk);
			}
		};

		walk(el);
	};

	// Stop without touching the markup: the editor has already replaced it.
	TextAnimation.prototype.abandon = function () {
		this.triggers.forEach(function (trigger) {
			trigger.kill();
		});
		this.timelines.forEach(function (tl) {
			tl.kill();
		});

		if (this.splitText) {
			this.splitText.revert();
		}

		this.triggers = [];
		this.timelines = [];
	};

	TextAnimation.prototype.destroy = function () {
		this.triggers.forEach(function (trigger) {
			trigger.kill();
		});
		this.timelines.forEach(function (tl) {
			tl.kill();
		});

		if (this.splitText) {
			this.splitText.revert();
		}

		this.triggers = [];
		this.timelines = [];

		var el = this.el;

		el.innerHTML = this.original;
		el.style.removeProperty('--ha-highlight-color');
		el.classList.remove('ha-hta-yes');

		if (this.modeClass) {
			el.classList.remove(this.modeClass);
		}

		gsap.set(el, {clearProps: 'perspective'});
		el.style.visibility = 'visible';
	};

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

		var features = EDITOR_FEATURES.filter(function (feature) {
			return defaults[feature.key];
		});

		if (!features.length) {
			return;
		}

		(root || d).querySelectorAll('[data-id]').forEach(function (node) {
			var id = node.getAttribute('data-id');
			var type = editorElementType(id);

			features.forEach(function (feature) {
				if (feature.type !== type) {
					return;
				}

				var el = feature.target(node);

				if (!el) {
					return;
				}

				var settings = editorSettings(id);
				var config = settings ? editorFeatureConfig(settings, feature.prefix, defaults[feature.key]) : null;
				var existing = instances.get(el);

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
					var replaced = existing.built && !el.classList.contains('ha-hta-yes');
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

		[['appearing-image-animation', AppearingImage], ['text-animation', TextAnimation]].forEach(function (item) {
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
		scan(d);

		if (isEditor()) {
			watchEditor();
		}

		w.addEventListener('pagehide', function () {
			live.slice().forEach(destroy);
		});
	}

	if ('loading' === d.readyState) {
		d.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
}(window, document));
