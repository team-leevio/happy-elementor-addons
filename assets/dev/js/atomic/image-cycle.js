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
