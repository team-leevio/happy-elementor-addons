(function ($, w) {
	"use strict";

	$(w).on("elementor/frontend/init", function () {

		if (typeof gsap === "undefined") return;

		if (typeof ScrollTrigger === "undefined") {
			console.warn("ScrollTrigger not loaded");
			return;
		}

		gsap.registerPlugin(ScrollTrigger);

		const HappyAIA = elementorModules.frontend.handlers.Base.extend({

			scrollTriggers: [],
			timelines: [],

			onInit: function () {
				elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
				this.build();
			},

			onElementChange: function () {
				this.destroyAnimation();
				this.build();
			},

			onDestroy: function () {
				this.destroyAnimation();
				elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);
			},

			destroyAnimation: function () {
				this.scrollTriggers.forEach(st => st && st.kill && st.kill());
				this.timelines.forEach(tl => tl && tl.kill && tl.kill());

				this.scrollTriggers = [];
				this.timelines = [];

				this.$element.find(".ha-aia-slice").remove();
				this.$element.removeClass("ha-aia-animating ha-aia-complete");

				// RESET
				var container = this.$element[0];
				var image = this.$element.find("img")[0];

				if (container) {
					gsap.set(container, { clearProps: "clipPath,overflow,transition" });
				}

				if (image) {
					gsap.set(image, { clearProps: "all", opacity: 1 });
					var wrap = image.parentElement;
					if (wrap) {
						gsap.set(wrap, { clearProps: "transition,paddingBottom" });
					}
				}
			},

		build: function () {
			const settings = this.getElementSettings();
			if (settings.ha_aia_switcher !== "yes") return;

			if ('mobile' === elementorFrontend.getCurrentDeviceMode() && settings.ha_aia_enable_on_mobile !== 'yes') {
				gsap.set(this.$element[0], { autoAlpha: 1 });
				return;
			}

			const $container = this.$element;
			const $image = $container.find("img").first();
			if (!$image.length) return;

			const config = this.getConfig(settings);
			this.applyAnimation($container[0], $image[0], config);
		},

		getResponsiveSetting: function (settings, key, respectEmpty) {
			var deviceMode = elementorFrontend.getCurrentDeviceMode();
			if (deviceMode !== 'desktop') {
				var deviceKey = key + '_' + deviceMode;
				if (
					settings[deviceKey] !== undefined &&
					(respectEmpty || settings[deviceKey] !== '')
				) {
					return settings[deviceKey];
				}
			}
			return settings[key];
		},

		getConfig: function (settings) {
			return {
				mode: this.getResponsiveSetting(settings, 'ha_aia_mode') || 'reveal',
				direction: this.getResponsiveSetting(settings, 'ha_aia_rs_direction') || 'left',
				cornerDirection: this.getResponsiveSetting(settings, 'ha_aia_corner_direction') || 'top-left',
				scaleFrom: parseFloat(this.getResponsiveSetting(settings, 'ha_aia_scale_from')) || 0.5,
				scaleTo: parseFloat(this.getResponsiveSetting(settings, 'ha_aia_scale_to')) || 1,
				duration: parseFloat(this.getResponsiveSetting(settings, 'ha_aia_animation_duration')) || 1,
				delay: parseFloat(this.getResponsiveSetting(settings, 'ha_aia_delay')) || 0,
				easing: this.getResponsiveSetting(settings, 'ha_aia_easing_function') || 'power2.out',
				trigger: (this.getResponsiveSetting(settings, 'ha_aia_trigger_point') || 'top-center').replace('-', ' '),
				tiles: parseInt(this.getResponsiveSetting(settings, 'ha_aia_tiles_count')) || 5,
				tilesOrientation: this.getResponsiveSetting(settings, 'ha_aia_tiles_orientation') || 'horizontal',
				tilesHorizontalDirection: this.getResponsiveSetting(settings, 'ha_aia_tiles_horizontal_direction') || 'bottom-to-top',
				tilesVerticalDirection: this.getResponsiveSetting(settings, 'ha_aia_tiles_vertical_direction') || 'left-to-right',
				tilesStaggerDelay: this.getResponsiveSetting(settings, 'ha_aia_tiles_stagger_delay') || 0.08,
			};
		},

			getClipPath: function (direction) {
				return {
					left: "inset(0 0 0 100%)",
					right: "inset(0 100% 0 0)",
					top: "inset(0 0 100% 0)",
					bottom: "inset(100% 0 0 0)"
				}[direction];
			},

			getCornerClipPath: function (direction) {
				return {
					"top-left": "inset(0 100% 100% 0)",
					"top-right": "inset(0 0 100% 100%)",
					"bottom-left": "inset(100% 100% 0 0)",
					"bottom-right": "inset(100% 0 0 100%)",
					"center": "inset(50% 50% 50% 50%)"
				}[direction];
			},

			getTransformOrigin: function (direction) {
				return {
					left: "left center",
					right: "right center",
					top: "center top",
					bottom: "center bottom"
				}[direction];
			},

			createTiles: function (container, image, config) {
				const rect = container.getBoundingClientRect();
				const slices = [];
				const count = config.tiles;
				const orientation = config.tilesOrientation;
				const direction = orientation === 'horizontal'
					? config.tilesHorizontalDirection
					: config.tilesVerticalDirection;

				container.style.position = "relative";
				container.style.overflow = "hidden";

				let axis, origin;

				switch (direction) {
					case "left-to-right": axis = "scaleX"; origin = "left center"; break;
					case "right-to-left": axis = "scaleX"; origin = "right center"; break;
					case "top-to-bottom": axis = "scaleY"; origin = "center top"; break;
					case "bottom-to-top": axis = "scaleY"; origin = "center bottom"; break;
					default: axis = "scaleY"; origin = "center top";
				}

				if (orientation === "horizontal") {
					const totalWidth = Math.round(rect.width);
					const sliceWidth = Math.ceil(totalWidth / count); // avoid fractions

					for (let i = 0; i < count; i++) {

						const left = i * sliceWidth;

						const slice = document.createElement("div");
						slice.classList.add("ha-aia-slice");

						Object.assign(slice.style, {
							position: "absolute",
							top: "0px",
							left: left + "px",
							width: (sliceWidth + 0.4) + "px", // adjust overlap
							height: Math.round(rect.height) + "px",
							backgroundImage: `url(${image.src})`,
							backgroundSize: totalWidth + "px " + Math.round(rect.height) + "px",
							backgroundPosition: `-${left}px 0px`,
							backgroundRepeat: "no-repeat",
							transformOrigin: origin,
							backfaceVisibility: "hidden",
							willChange: "transform",
						});

						container.appendChild(slice);
						slices.push(slice);
					}
				} else {
					const totalHeight = Math.round(rect.height);
					const sliceHeight = Math.ceil(totalHeight / count);

					for (let i = 0; i < count; i++) {

						const top = i * sliceHeight;

						const slice = document.createElement("div");
						slice.classList.add("ha-aia-slice");

						Object.assign(slice.style, {
							position: "absolute",
							top: top + "px",
							left: "0px",
							width: Math.round(rect.width) + "px",
							height: (sliceHeight + 0.4) + "px", // adjust overlap fix
							backgroundImage: `url(${image.src})`,
							backgroundSize: Math.round(rect.width) + "px " + totalHeight + "px",
							backgroundPosition: `0px -${top}px`,
							backgroundRepeat: "no-repeat",
							transformOrigin: origin,
							backfaceVisibility: "hidden",
							willChange: "transform",
						});

						container.appendChild(slice);
						slices.push(slice);
					}
				}

				image.style.opacity = 0;
				return { slices, axis };
			},

			applyStretchAnimation: function (container, image, config) {
				var wrap = image.parentElement;

				gsap.set(container, { autoAlpha: 1 });
				wrap.style.transition = "none";
				wrap.style.paddingBottom = "395px";

				var tl = gsap.timeline({
					scrollTrigger: {
						trigger: wrap,
						start: "top top",
						pin: true,
						scrub: 1,
						pinSpacing: false,
						end: "bottom bottom+=100"
					}
				});

				tl.to(image, {
					width: "100%",
					borderRadius: "0px"
				});

				this.timelines.push(tl);
				if (tl.scrollTrigger) this.scrollTriggers.push(tl.scrollTrigger);
			},

			applyAnimation: function (container, image, config) {

				if (config.mode === "stretch") {
					if (!elementorFrontend.isEditMode()) {
						this.applyStretchAnimation(container, image, config);
						setTimeout(() => {
							if (typeof ScrollTrigger !== "undefined") ScrollTrigger.refresh();
						}, 50);
					} else {
						gsap.set(container, { autoAlpha: 1 });
					}
					return;
				}

				const tl = gsap.timeline({
					delay: config.delay,
					scrollTrigger: {
						trigger: container,
						start: config.trigger,
						toggleActions: "play none none none"
					}
				});

				this.timelines.push(tl);
				if (tl.scrollTrigger) this.scrollTriggers.push(tl.scrollTrigger);

				tl.set(container, { autoAlpha: 1 }, 0);

				switch (config.mode) {

					case "reveal":

						if (elementorFrontend.isEditMode()) {
							// Editor safe fallback like reveal
							gsap.set(container, { clipPath: "inset(0 0 0 0)" });
							gsap.fromTo(image,
								{ scale: 1.2 },
								{ scale: 1, duration: config.duration, delay: config.delay, ease: "power2.out" }
							);
						}
						// Actual Reveal
						tl.from(container, {
							clipPath: this.getClipPath(config.direction),
							duration: config.duration,
							ease: config.easing
						}, 0);

						tl.fromTo(image,
							{ scale: 1.2 },
							{ scale: 1, duration: config.duration, ease: "power2.out" },
							0
						);
						break;

					case "corner-reveal":

						if (elementorFrontend.isEditMode()) {
							// Editor safe fallback like reveal
							gsap.set(container, { clipPath: "inset(0 0 0 0)" });
							gsap.fromTo(image,
								{ scale: 1.15 },
								{ scale: 1, duration: config.duration, delay: config.delay, ease: "power2.out" }
							);
						}

						tl.from(container, {
							clipPath: this.getCornerClipPath(config.cornerDirection),
							duration: config.duration,
							ease: config.easing
						}, 0);

						tl.fromTo(image,
							{ scale: 1.15 },
							{ scale: 1, duration: config.duration, ease: "power2.out" },
							0
						);
						break;

					case "scale":
						tl.fromTo(image,
							{ scale: config.scaleFrom, transformOrigin: this.getTransformOrigin(config.direction) },
							{ scale: config.scaleTo, duration: config.duration, ease: config.easing },
							0
						);
						break;

					case "tiles-reveal":
						const { slices, axis } = this.createTiles(container, image, config);
						tl.from(slices, {
							[axis]: 0,
							duration: config.duration,
							// stagger: 0.08,
							ease: config.easing,
							stagger: {
								each: config.tilesStaggerDelay,
								from: 'start'
							}
						});
						tl.to(image, {
							opacity: 1,
							duration: 0.2,
							onComplete: () => slices.forEach(s => s.remove())
						});
						break;
				}

				setTimeout(() => {
					if (typeof ScrollTrigger !== "undefined") ScrollTrigger.refresh();
				}, 50);
			}

		});

		elementorFrontend.hooks.addAction(
			"frontend/element_ready/image.default",
			function ($scope) {
				elementorFrontend.elementsHandler.addHandler(HappyAIA, { $element: $scope });
			}
		);

	});

})(jQuery, window);