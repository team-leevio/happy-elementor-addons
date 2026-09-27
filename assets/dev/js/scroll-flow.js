(function ($, w) {

    "use strict";

    $(w).on("elementor/frontend/init", function () {

        if (typeof gsap === "undefined" || typeof Observer === "undefined") {
            return;
        }

        gsap.registerPlugin(Observer);

        const MIN_PANELS = 2;
        const REDUCED_MOTION = "(prefers-reduced-motion: reduce)";

        // Every live Scroll Flow on the page. Used to hand a gesture to the flow
        // that currently owns the viewport instead of whichever wrapper the
        // pointer happens to be over.
        const flows = [];

        /*
         * The section takes over the wheel as soon as a gesture starts over it.
         * If it is not aligned with the viewport yet, the page is scrolled into
         * place first so every transition runs full-screen, exactly like the
         * reference demo (which relies on `position: fixed` sections).
         */
        const ALIGN_EPS = 2;

        function toNumber(value, fallback) {
            const number = parseFloat(value);
            return isNaN(number) ? fallback : number;
        }

        function clamp(value, min, max) {
            return Math.max(min, Math.min(max, value));
        }

        function toBool(value, fallback) {
            if (value === "yes" || value === true || value === 1 || value === "1") {
                return true;
            }

            if (value === "no" || value === false || value === 0 || value === "0" || value === "") {
                return false;
            }

            return fallback;
        }

        /*
         * Themes often set `scroll-behavior: smooth`, which would turn the
         * internal realignment/release into an animation. Force an instant jump.
         */
        function scrollInstantly(top) {
            const root = document.documentElement;
            const previous = root.style.scrollBehavior;

            root.style.scrollBehavior = "auto";
            w.scrollTo(0, top);
            root.style.scrollBehavior = previous;
        }

        function scrollByInstantly(delta) {
            const root = document.documentElement;
            const previous = root.style.scrollBehavior;
            const current = w.pageYOffset || root.scrollTop || 0;

            root.style.scrollBehavior = "auto";
            w.scrollTo(0, current + delta);
            root.style.scrollBehavior = previous;
        }

        const HappyScrollFlow = elementorModules.frontend.handlers.Base.extend({

            currentIndex: -1,
            animating: false,
            observer: null,
            timeline: null,
            panels: [],
            hostEl: null,
            config: null,
            lastDevice: null,
            resizeTimer: null,
            animTimer: null,
            captureTimer: null,
            released: false,
            stageTop: 0,

            onInit: function () {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                this.build();
            },

            bindEvents: function () {
                elementorModules.frontend.handlers.Base.prototype.bindEvents.apply(this, arguments);

                this.lastDevice = this.getDeviceMode();
                this.eventNamespace = ".haSf" + (this.$element.attr("data-id") || Math.random().toString(36).slice(2, 8));

                const ns = this.eventNamespace;

                $(w).on("resize" + ns + " orientationchange" + ns, () => {
                    clearTimeout(this.resizeTimer);

                    this.resizeTimer = setTimeout(() => {
                        const device = this.getDeviceMode();

                        if (device !== this.lastDevice) {
                            this.lastDevice = device;
                            this.build();
                            return;
                        }

                        this.updateStageMetrics();
                    }, 250);
                });

                // Only used to re-arm the observer after a boundary release.
                $(w).on("scroll" + ns, () => this.onScroll());
            },

            onElementChange: function () {
                this.build();
            },

            onDestroy: function () {
                clearTimeout(this.resizeTimer);
                clearTimeout(this.animTimer);
                clearTimeout(this.captureTimer);
                $(w).off(this.eventNamespace || ".haSf");
                this.destroy();
                elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);
            },

            /**
             * Resolve a (possibly responsive) setting for the current device.
             */
            getResponsiveSetting: function (settings, key) {
                const device = this.getDeviceMode();

                if ("desktop" !== device) {
                    const deviceKey = key + "_" + device;

                    if (settings[ deviceKey ] !== undefined && settings[ deviceKey ] !== "") {
                        return settings[ deviceKey ];
                    }
                }

                return settings[ key ];
            },

            getDeviceMode: function () {
                if (typeof elementorFrontend !== "undefined" && typeof elementorFrontend.getCurrentDeviceMode === "function") {
                    return elementorFrontend.getCurrentDeviceMode();
                }

                return "desktop";
            },

            isEditMode: function () {
                return typeof elementorFrontend !== "undefined" && elementorFrontend.isEditMode();
            },

            isDisabledOnMobile: function () {
                const settings = this.getElementSettings() || {};

                if ("yes" === settings.ha_sf_enable_on_mobile) {
                    return false;
                }

                const device = this.getDeviceMode();

                return device === "mobile" || device === "mobile_extra";
            },

            prefersReducedMotion: function () {
                return !!(w.matchMedia && w.matchMedia(REDUCED_MOTION).matches);
            },

            /**
             * The element that directly holds the panels. Boxed Elementor
             * containers wrap their children in `.e-con-inner` and legacy
             * sections in `.elementor-container`, so resolve that first.
             */
            getHost: function () {
                const $boxed = this.$element.children(".e-con-inner");

                if ($boxed.length) {
                    return $boxed.first();
                }

                const $container = this.$element.children(".elementor-container");

                if ($container.length) {
                    return $container.first();
                }

                return this.$element;
            },

            /**
             * Sanitized settings payload rendered by PHP, used as a fallback.
             */
            getServerSettings: function () {
                const raw = this.$element.attr("data-ha-sf-data");

                if (!raw) {
                    return {};
                }

                try {
                    const parsed = JSON.parse(raw);

                    return parsed && "object" === typeof parsed ? parsed : {};
                } catch (error) {
                    return {};
                }
            },

            getConfig: function (settings) {
                const server = this.getServerSettings();

                const mode = String(this.getResponsiveSetting(settings, "ha_sf_mode") || server.mode || "slide");
                const ease = String(this.getResponsiveSetting(settings, "ha_sf_easing_function") || server.ease || "power1.inOut");

                return {
                    mode: mode,
                    ease: ease,
                    duration: clamp(toNumber(this.getResponsiveSetting(settings, "ha_sf_duration"), toNumber(server.duration, 1.25)), 0.1, 5),
                    bgMove: clamp(toNumber(this.getResponsiveSetting(settings, "ha_sf_bg_move"), toNumber(server.bgMove, 15)), 0, 60),
                    wheelSpeed: clamp(toNumber(this.getResponsiveSetting(settings, "ha_sf_wheel_speed"), toNumber(server.wheelSpeed, -1)), -3, 3),
                    tolerance: clamp(toNumber(this.getResponsiveSetting(settings, "ha_sf_tolerance"), toNumber(server.tolerance, 10)), 0, 50),
                    loop: toBool(this.getResponsiveSetting(settings, "ha_sf_loop"), toBool(server.loop, true))
                };
            },

            build: function () {
                const settings = this.getElementSettings() || {};

                if ("yes" !== settings.ha_sf_switcher || this.isEditMode() || this.isDisabledOnMobile() || this.prefersReducedMotion()) {
                    this.destroy();
                    return;
                }

                const $host = this.getHost();
                const panels = $host.length ? Array.prototype.slice.call($host[ 0 ].children) : [];

                if (panels.length < MIN_PANELS) {
                    this.destroy();
                    return;
                }

                this.destroy();

                this.config = this.getConfig(settings);
                this.panels = panels;
                this.hostEl = $host[ 0 ];
                this.currentIndex = -1;
                this.animating = false;
                this.released = false;
                this.lastScrollTop = null;

                this.$element.addClass("ha-sf-active");
                this.hostEl.classList.add("ha-sf-host");

                this.panels.forEach((panel) => panel.classList.add("ha-sf-panel"));

                gsap.set(this.panels, { autoAlpha: 0, zIndex: 0 });

                this.updateStageMetrics();
                this.createObserver();
                this.gotoSection(0, 1);

                if (flows.indexOf(this) === -1) {
                    flows.push(this);
                }

                /*
                 * Happy Addons keeps its animation timelines paused until their
                 * own ScrollTrigger fires. Capture them while they are still in
                 * GSAP's cache so they can be replayed later, even after they
                 * have finished once.
                 */
                clearTimeout(this.captureTimer);
                this.captureTimer = setTimeout(() => this.capturePanelAnimations(), 600);
            },

            destroy: function () {
                this.killObserver();

                clearTimeout(this.captureTimer);

                const registered = flows.indexOf(this);

                if (registered !== -1) {
                    flows.splice(registered, 1);
                }

                if (this.timeline) {
                    this.timeline.kill();
                    this.timeline = null;
                }

                const panels = this.panels || [];

                if (panels.length) {
                    gsap.set(panels, { clearProps: "transform,opacity,visibility,zIndex,backgroundPositionY" });
                }

                panels.forEach((panel) => {
                    if (panel && panel.classList) {
                        panel.classList.remove("ha-sf-panel");
                    }
                });

                if (this.hostEl && this.hostEl.classList) {
                    this.hostEl.classList.remove("ha-sf-host");
                }

                this.$element.removeClass("ha-sf-active");

                this.panels = [];
                this.hostEl = null;
                this.config = null;
                this.currentIndex = -1;
                this.animating = false;
                this.released = false;
                this.lastScrollTop = null;
            },

            createObserver: function () {
                this.killObserver();

                const config = this.config;

                this.observer = Observer.create({
                    target: this.$element[ 0 ],
                    type: "wheel,touch",
                    wheelSpeed: config.wheelSpeed,
                    tolerance: config.tolerance,
                    preventDefault: true,
                    onDown: (self) => this.onGesture(self, false),
                    onUp: (self) => this.onGesture(self, true)
                });
            },

            killObserver: function () {
                if (this.observer) {
                    this.observer.kill();
                    this.observer = null;
                }
            },

            onGesture: function (self, forward) {
                if (!this.config || !this.panels.length) {
                    return;
                }

                const engaged = this.getEngagedFlow();

                if (engaged && engaged !== this) {
                    const mine = this.$element[ 0 ];
                    const theirs = engaged.$element[ 0 ];

                    /*
                     * Nested flows receive the same bubbled gesture; the flow that
                     * already owns it must not process it twice.
                     */
                    if (mine && theirs && (mine.contains(theirs) || theirs.contains(mine))) {
                        return;
                    }

                    /*
                     * Only a sliver of this flow is under the pointer while another
                     * flow owns the viewport. Hand the gesture over instead of
                     * aligning/transitioning the wrong flow.
                     */
                    engaged.handleGesture(self, forward, true);
                    return;
                }

                this.handleGesture(self, forward, false);
            },

            handleGesture: function (self, forward, delegated) {
                if (!this.config || !this.panels.length || this.animating) {
                    return;
                }

                if (!delegated && this.shouldIgnore(self)) {
                    return;
                }

                const step = forward ? 1 : -1;

                /*
                 * At a boundary (Loop off) the flow already handed the page
                 * over; keep feeding it the gesture instead of re-aligning and
                 * snapping back to the section.
                 */
                if (this.released) {
                    this.release(step > 0 ? this.panels.length : -1, self);
                    return;
                }

                /*
                 * Make sure the section fills the viewport before transitioning
                 * so the animation matches the demo. The gesture that aligns the
                 * page is consumed; the next one transitions.
                 */
                if (!this.isAligned()) {
                    this.align();
                    return;
                }

                const next = this.currentIndex + step;

                if (this.canGo(next)) {
                    this.gotoSection(next, step);
                } else {
                    this.release(next, self);
                }
            },

            /**
             * The flow that currently owns the viewport (its box straddles the
             * vertical center). With several Scroll Flows on a page this is the
             * one a gesture should drive.
             */
            getEngagedFlow: function () {
                const viewportHeight = w.innerHeight || document.documentElement.clientHeight || 0;
                const center = viewportHeight / 2;
                let engaged = null;
                let closest = Infinity;

                flows.forEach((flow) => {
                    const element = flow.$element && flow.$element[ 0 ];

                    if (!element || !flow.config || !flow.panels.length) {
                        return;
                    }

                    const rect = element.getBoundingClientRect();

                    if (rect.top > center || rect.bottom < center) {
                        return;
                    }

                    const distance = Math.abs((rect.top + rect.bottom) / 2 - center);

                    if (distance < closest) {
                        closest = distance;
                        engaged = flow;
                    }
                });

                return engaged;
            },

            /**
             * Ignore gestures that originate from a nested Scroll Flow wrapper
             * so only the innermost instance reacts.
             */
            shouldIgnore: function (self) {
                const event = self && self.event;
                const target = event && event.target;

                if (!target || !target.closest) {
                    return false;
                }

                const owner = target.closest("[data-ha-sf]");

                return owner !== null && owner !== this.$element[ 0 ];
            },

            canGo: function (index) {
                if (!this.panels.length) {
                    return false;
                }

                if (this.config.loop) {
                    return true;
                }

                return index >= 0 && index < this.panels.length;
            },

            getScrollTop: function () {
                return w.pageYOffset || document.documentElement.scrollTop || 0;
            },

            updateStageMetrics: function () {
                const element = this.$element[ 0 ];

                if (!element) {
                    return;
                }

                const rect = element.getBoundingClientRect();

                this.stageTop = rect.top + this.getScrollTop();
            },

            /**
             * The document position at which the stage fills the viewport. A
             * stage near the bottom of a short page may not be able to reach the
             * viewport top, so the furthest reachable position is used instead.
             */
            alignTarget: function () {
                const doc = document.documentElement;
                const body = document.body;
                const height = Math.max(
                    doc ? doc.scrollHeight : 0,
                    body ? body.scrollHeight : 0
                );
                const maxScroll = Math.max(0, height - (w.innerHeight || doc.clientHeight || 0));

                return Math.min(this.stageTop, maxScroll);
            },

            isAligned: function () {
                this.updateStageMetrics();

                return Math.abs(this.getScrollTop() - this.alignTarget()) <= ALIGN_EPS;
            },

            align: function () {
                scrollInstantly(this.alignTarget());
            },

            /**
             * True when there is page content left to scroll to in that direction.
             */
            hasScrollRoom: function (down) {
                const doc = document.documentElement;
                const body = document.body;
                const height = Math.max(
                    doc ? doc.scrollHeight : 0,
                    body ? body.scrollHeight : 0
                );
                const maxScroll = Math.max(0, height - (w.innerHeight || doc.clientHeight || 0));

                if (down) {
                    return this.stageTop < maxScroll - ALIGN_EPS;
                }

                return this.stageTop > ALIGN_EPS;
            },

            /**
             * Hand the gesture back to the page so the remaining content can be
             * reached once the first/last panel is reached with loop disabled.
             */
            release: function (nextIndex, self) {
                const down = nextIndex >= this.panels.length;

                if (!this.hasScrollRoom(down)) {
                    return;
                }

                const event = self && self.event;
                let delta = (down ? 1 : -1) * 120;

                if (event && "number" === typeof event.deltaY && 0 !== event.deltaY) {
                    delta = event.deltaY;
                }

                this.released = true;

                if (this.observer) {
                    this.observer.disable();
                }

                scrollByInstantly(delta);
            },

            /**
             * Re-arm the observer once the page is scrolled back onto the stage.
             */
            onScroll: function () {
                if (!this.config || !this.panels.length) {
                    return;
                }

                const scrollTop = this.getScrollTop();
                const previous = "number" === typeof this.lastScrollTop ? this.lastScrollTop : scrollTop;

                this.lastScrollTop = scrollTop;

                if (!this.released) {
                    return;
                }

                const target = this.alignTarget();
                const crossed = (previous < target && scrollTop >= target) ||
                    (previous > target && scrollTop <= target);

                if (!crossed && Math.abs(scrollTop - target) > ALIGN_EPS) {
                    return;
                }

                this.released = false;

                if (this.observer) {
                    this.observer.enable();
                }
            },

            normalizeIndex: function (index) {
                const total = this.panels.length;

                if (this.config.loop) {
                    return gsap.utils.wrap(0, total)(index);
                }

                return clamp(index, 0, total - 1);
            },

            hasBackgroundImage: function (panel) {
                const background = w.getComputedStyle(panel).backgroundImage || "";

                return background.indexOf("url(") !== -1;
            },

            gotoSection: function (rawIndex, direction) {
                if (this.animating || !this.panels.length) {
                    return;
                }

                const config = this.config;
                const total = this.panels.length;

                if (!config.loop && (rawIndex < 0 || rawIndex >= total)) {
                    return;
                }

                const index = this.normalizeIndex(rawIndex);

                if (index === this.currentIndex) {
                    return;
                }

                const panels = this.panels;
                const fromTop = -1 === direction;
                const distanceFactor = fromTop ? -1 : 1;

                this.animating = true;

                if (this.timeline) {
                    this.timeline.kill();
                }

                const timeline = gsap.timeline({
                    defaults: { duration: config.duration, ease: config.ease },
                    onComplete: () => {
                        this.animating = false;
                    }
                });

                if (this.currentIndex >= 0 && panels[ this.currentIndex ]) {
                    gsap.set(panels[ this.currentIndex ], { zIndex: 0 });
                    timeline.set(panels[ this.currentIndex ], { autoAlpha: 0 }, config.duration);
                }

                gsap.set(panels[ index ], { autoAlpha: 1, zIndex: 1 });

                if ("fade" === config.mode) {
                    timeline.fromTo(
                        panels[ index ],
                        { yPercent: 0, autoAlpha: 0 },
                        { yPercent: 0, autoAlpha: 1 },
                        0
                    );

                    if (config.bgMove && this.hasBackgroundImage(panels[ index ])) {
                        timeline.fromTo(
                            panels[ index ],
                            { backgroundPositionY: (50 + config.bgMove * distanceFactor) + "%" },
                            { backgroundPositionY: "50%" },
                            0
                        );
                    }
                } else if ("zoom" === config.mode) {
                    timeline.fromTo(
                        panels[ index ],
                        { scale: 1.15, yPercent: 0 },
                        { scale: 1, yPercent: 0 },
                        0
                    );
                } else {
                    timeline.fromTo(
                        panels[ index ],
                        { yPercent: 100 * distanceFactor },
                        { yPercent: 0 },
                        0
                    );

                    if (config.bgMove && this.hasBackgroundImage(panels[ index ])) {
                        timeline.fromTo(
                            panels[ index ],
                            { backgroundPositionY: (50 + config.bgMove * distanceFactor) + "%" },
                            { backgroundPositionY: "50%" },
                            0
                        );
                    }
                }

                this.timeline = timeline;
                this.currentIndex = index;

                this.queuePanelAnimation(index);
            },

            /**
             * Replay the entrance animations of the panel that just became
             * active. Scroll based animations (Elementor entrance effects and
             * Happy Addons Appearing Image / Heading Text) never receive a real
             * scroll inside the section, so they would otherwise stay in their
             * initial (hidden) state.
             */
            queuePanelAnimation: function (index) {
                const panel = this.panels[ index ];

                if (!panel) {
                    return;
                }

                clearTimeout(this.animTimer);

                const delay = this.animationBootstrapped ? 60 : 350;
                this.animationBootstrapped = true;

                this.animTimer = setTimeout(() => {
                    if (!this.panels.length || this.panels[ index ] !== panel) {
                        return;
                    }

                    this.animatePanel(panel);
                }, delay);
            },

            animatePanel: function (panel) {
                if (!panel) {
                    return;
                }

                this.replayElementorAnimations(panel);
                this.replayHappyAnimations(panel);
            },

            readSettings: function (element) {
                const raw = element && element.getAttribute ? element.getAttribute("data-settings") : "";

                if (!raw) {
                    return {};
                }

                try {
                    const parsed = JSON.parse(raw);

                    return parsed && "object" === typeof parsed ? parsed : {};
                } catch (error) {
                    return {};
                }
            },

            resolveResponsive: function (settings, key) {
                const device = this.getDeviceMode();

                if ("desktop" !== device) {
                    const deviceValue = settings[ key + "_" + device ];

                    if (deviceValue !== undefined && deviceValue !== "") {
                        return deviceValue;
                    }
                }

                const value = settings[ key ];

                if (value && "object" === typeof value) {
                    return value[ device ] !== undefined ? value[ device ] : value.desktop;
                }

                return value;
            },

            replayElementorAnimations: function (panel) {
                const nodes = panel.querySelectorAll(".elementor-invisible, .animated");

                Array.prototype.forEach.call(nodes, (element) => {
                    const settings = this.readSettings(element);
                    let animation = settings._animation || settings.animation || "";

                    if (animation && "object" === typeof animation) {
                        animation = this.resolveResponsive({ _animation: animation }, "_animation") || "";
                    }

                    if (!animation || "none" === animation) {
                        element.classList.remove("elementor-invisible");
                        return;
                    }

                    let animationDelay = settings._animation_delay || settings.animation_delay || 0;

                    if (animationDelay && "object" === typeof animationDelay) {
                        animationDelay = this.resolveResponsive({ _animation_delay: animationDelay }, "_animation_delay") || 0;
                    }

                    animationDelay = toNumber(animationDelay, 0);

                    clearTimeout(element.__haSfAnimTimer);
                    element.classList.remove("animated");
                    element.classList.remove(animation);
                    element.classList.add("elementor-invisible");

                    // Force a reflow so the animation can restart.
                    void element.offsetWidth;

                    element.__haSfAnimTimer = setTimeout(() => {
                        element.classList.remove("elementor-invisible");
                        element.classList.add("animated", animation);
                    }, animationDelay);
                });
            },

            replayHappyAnimations: function (panel) {
                if (typeof gsap === "undefined") {
                    return;
                }

                const nodes = panel.querySelectorAll('[data-settings*="ha_hta_switcher"], [data-settings*="ha_aia_switcher"]');

                Array.prototype.forEach.call(nodes, (element) => {
                    const settings = this.readSettings(element);
                    const isHeading = "yes" === settings.ha_hta_switcher;
                    const isImage = "yes" === settings.ha_aia_switcher;

                    if (!isHeading && !isImage) {
                        return;
                    }

                    if (isHeading) {
                        const triggerMode = this.resolveResponsive(settings, "ha_hta_trigger_mode");

                        // Hover is mouse driven and scrubbed triggers follow the
                        // page scroll, so neither should be replayed here.
                        if ("hover" === triggerMode || "playwithscroll" === triggerMode) {
                            return;
                        }
                    }

                    let timelines = this.collectTimelines(element);

                    if (timelines.length) {
                        element.__haSfTimelines = timelines;
                    } else if (element.__haSfTimelines && element.__haSfTimelines.length) {
                        timelines = element.__haSfTimelines;
                    }

                    timelines.forEach((timeline) => {
                        if (!timeline || "function" !== typeof timeline.restart) {
                            return;
                        }

                        try {
                            timeline.restart(true);
                        } catch (error) {
                            // Timeline was rebuilt/killed elsewhere; ignore.
                        }
                    });
                });
            },

            /**
             * Capture the animations of every panel early, before Happy Addons'
             * timelines have a chance to finish and leave GSAP's cache.
             */
            capturePanelAnimations: function () {
                if (!this.panels.length) {
                    return;
                }

                this.panels.forEach((panel) => {
                    const nodes = panel.querySelectorAll('[data-settings*="ha_hta_switcher"], [data-settings*="ha_aia_switcher"]');

                    Array.prototype.forEach.call(nodes, (element) => {
                        const timelines = this.collectTimelines(element);

                        if (timelines.length) {
                            element.__haSfTimelines = timelines;
                        }
                    });
                });
            },

            /**
             * Collect GSAP timelines belonging to an element: ScrollTrigger
             * animations (Appearing Image / Heading Text "on scroll") plus paused
             * timelines found through the element's tweens (Heading Text, which
             * attaches its timeline to a bare ScrollTrigger).
             */
            collectTimelines: function (element) {
                const timelines = [];

                if (typeof ScrollTrigger !== "undefined" && ScrollTrigger.getAll) {
                    ScrollTrigger.getAll().forEach((trigger) => {
                        const target = trigger.trigger;

                        if (!target) {
                            return;
                        }

                        if (!(target === element || element.contains(target) || target.contains(element))) {
                            return;
                        }

                        if (trigger.vars && (trigger.vars.scrub || trigger.scrub)) {
                            return;
                        }

                        if (trigger.animation && timelines.indexOf(trigger.animation) === -1) {
                            timelines.push(trigger.animation);
                        }
                    });
                }

                if (gsap.getTweensOf) {
                    const targets = [ element ];
                    const descendants = element.querySelectorAll("*");

                    for (let i = 0; i < descendants.length; i++) {
                        targets.push(descendants[ i ]);
                    }

                    targets.forEach((target) => {
                        let tweens = [];

                        try {
                            tweens = gsap.getTweensOf(target) || [];
                        } catch (error) {
                            tweens = [];
                        }

                        tweens.forEach((tween) => {
                            const parent = tween.parent;

                            if (parent && parent !== gsap.globalTimeline && timelines.indexOf(parent) === -1) {
                                timelines.push(parent);
                            }
                        });
                    });
                }

                return timelines;
            }

        });

        elementorFrontend.hooks.addAction("frontend/element_ready/container", function ($scope) {
            elementorFrontend.elementsHandler.addHandler(HappyScrollFlow, { $element: $scope });
        });

        elementorFrontend.hooks.addAction("frontend/element_ready/section", function ($scope) {
            elementorFrontend.elementsHandler.addHandler(HappyScrollFlow, { $element: $scope });
        });

    });

})(jQuery, window);
