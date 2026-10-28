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
