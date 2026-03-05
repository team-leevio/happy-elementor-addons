(function ($, w) {
    "use strict";

    $(w).on("elementor/frontend/init", function () {

        if (typeof gsap === "undefined" || typeof ScrollTrigger === "undefined") {
            console.warn("GSAP or ScrollTrigger not loaded");
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        const HappyHTA = elementorModules.frontend.handlers.Base.extend({

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
                this.timelines.forEach(tl => tl.kill());
                this.scrollTriggers.forEach(st => st.kill());
                this.timelines = [];
                this.scrollTriggers = [];

                const el = this.$element.find(".elementor-heading-title");
                if (el.data("original")) el.html(el.data("original"));
            },

            build: function () {
                const settings = this.getElementSettings();
                if (settings.ha_hta_switcher !== "yes") return;

                const $heading = this.$element.find(".elementor-heading-title");
                if (!$heading.length) return;
                // if (!$heading.data("original")) $heading.data("original", $heading.html());

                const config = this.getConfig(settings);
                this.applyAnimation(this.$element[ 0 ], $heading[ 0 ], config);
            },

            getConfig: function (settings) {
                return {
                    mode: settings.ha_hta_mode || "reveal",
                    charsWordsMode: settings.ha_hta_chars_words_mode || "chars",
                    transformX: settings.ha_hta_cw_transform_x || 25,
                    transformY: settings.ha_hta_cw_transform_y || 0,
                    textMoveDirection: settings.ha_hta_tv_rotation_direction || "horizontal",
                    textMoveValue: settings.ha_hta_tv_rotation_value || -80,
                    revealOrientation: settings.ha_hta_tr_orientation || "bottom",
                    scaleValue: parseFloat(settings.ha_hta_scale) || 1.5,
                    scaleBreak: settings.ha_hta_scale_text_break || "lines",
                    triggerMode: settings.ha_hta_trigger_mode || "scroll",
                    triggerPoint: (settings.ha_hta_trigger_point || "top-center").replace("-", " "),
                    customTrigger: settings.ha_hta_custom_trigger_start || "top 80%",
                    delay: parseFloat(settings.ha_hta_delay) || 0.15,
                    duration: parseFloat(settings.ha_hta_duration) || 0.8,
                    stagger: parseFloat(settings.ha_hta_stagger_delay) || 0.05,
                    easing: settings.ha_hta_easing_function || "power2.out"
                };
            },

            applyAnimation: function (container, heading, config) {
                let elements;
                let actualMode = config.mode;

                // Handle slide mode - delegate to chars or words based on charsWordsMode
                if (config.mode === "slide") {
                    actualMode = config.charsWordsMode || "chars";
                }

                switch (actualMode) {
                    case "chars": elements = this.splitChars(heading); break;
                    case "words": elements = this.splitWords(heading); break;
                    case "text_move": elements = this.splitLines(heading); break;
                    case "reveal": elements = this.splitWords(heading); break;
                    case "scale": elements = this.splitDynamic(heading, config.scaleBreak); break;
                    case "3dspin": elements = this.splitChars(heading); break;
                    default: elements = [ heading ];
                }

                const tl = gsap.timeline({ paused: true });

                switch (actualMode) {
                    case "chars":
                    case "words":
                        tl.from(elements, {
                            x: config.transformX,
                            y: config.transformY,
                            opacity: 0,
                            duration: config.duration,
                            stagger: config.stagger,
                            ease: config.easing
                        });
                        break;

                    case "text_move":
                        tl.from(elements, {
                            x: config.textMoveDirection === "horizontal" ? config.textMoveValue : 0,
                            y: config.textMoveDirection === "vertical" ? config.textMoveValue : 0,
                            opacity: 0,
                            duration: config.duration,
                            stagger: config.stagger,
                            ease: config.easing
                        });
                        break;

                    case "reveal":
                        tl.from(elements, {
                            yPercent: config.revealOrientation === "top" ? -100 : 100,
                            opacity: 0,
                            duration: config.duration,
                            stagger: config.stagger,
                            ease: config.easing
                        });
                        break;

                    case "scale":
                        tl.from(elements, {
                            scale: config.scaleValue,
                            opacity: 0,
                            duration: config.duration,
                            stagger: config.stagger,
                            ease: config.easing
                        });
                        break;

                    case "invert":
                        heading.style.backgroundSize = "200% 100%";
                        heading.style.backgroundImage = "linear-gradient(to right,#000 50%,#fff 50%)";
                        heading.style.webkitBackgroundClip = "text";
                        heading.style.color = "transparent";
                        tl.fromTo(
                            heading,
                            { backgroundPosition: "100% 0" },
                            { backgroundPosition: "0% 0", duration: 1.2, ease: "power2.out" }
                        );
                        break;

                    case "3dspin":
                        gsap.set(container, { perspective: 800 });
                        tl.from(elements, {
                            rotationX: -90,
                            transformOrigin: "50% 50% -50",
                            opacity: 0,
                            duration: config.duration,
                            stagger: config.stagger,
                            ease: config.easing
                        });
                        break;
                }

                this.attachTrigger(container, tl, config);
                this.timelines.push(tl);
                setTimeout(() => ScrollTrigger.refresh(), 100);
            },

            attachTrigger: function (container, tl, config) {
                if (config.triggerMode === "pageload") {
                    gsap.delayedCall(config.delay, () => tl.play());
                    return;
                }

                if (config.triggerMode === "hover") {
                    container.addEventListener("mouseenter", () => tl.restart());
                    return;
                }

                const startPoint = config.triggerPoint === "custom" ? config.customTrigger : config.triggerPoint;

                this.scrollTriggers.push(ScrollTrigger.create({
                    trigger: container,
                    start: startPoint,
                    toggleActions: "play none none none",
                    onEnter: () => { if (config.triggerMode !== "playwithscroll") gsap.delayedCall(config.delay, () => tl.play()); },
                    scrub: config.triggerMode === "playwithscroll",
                }));
            },

            splitChars: function (el) {
                const text = el.textContent.trim();
                el.innerHTML = "";
                const chars = [];
                text.split("").forEach(char => {
                    const span = document.createElement("span");
                    span.className = "ha-char";
                    span.textContent = char === " " ? "\u00A0" : char;
                    el.appendChild(span);
                    chars.push(span);
                });
                return chars;
            },

            splitWords: function (el) {
                const words = el.textContent.trim().split(" ");
                el.innerHTML = "";
                const spans = [];
                words.forEach((word, i) => {
                    const span = document.createElement("span");
                    span.className = "ha-word";
                    span.textContent = word;
                    el.appendChild(span);
                    spans.push(span);
                    if (i !== words.length - 1) el.appendChild(document.createTextNode(" "));
                });
                return spans;
            },

            splitLines: function (el) {
                const lines = el.innerHTML.split("<br>");
                el.innerHTML = "";
                const arr = [];
                lines.forEach(line => {
                    const div = document.createElement("div");
                    div.className = "ha-line";
                    div.innerHTML = line;
                    el.appendChild(div);
                    arr.push(div);
                });
                return arr;
            },

            splitDynamic: function (el, type) {
                if (type === "chars") return this.splitChars(el);
                if (type === "words") return this.splitWords(el);
                return this.splitLines(el);
            }

        });

        elementorFrontend.hooks.addAction(
            "frontend/element_ready/heading.default",
            function ($scope) {
                elementorFrontend.elementsHandler.addHandler(HappyHTA, { $element: $scope });
            }
        );
    });

})(jQuery, window);