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
