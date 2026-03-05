(function ($, w) {
    "use strict";

    $(w).on("elementor/frontend/init", function () {

        if (typeof gsap === "undefined") return;

        if (typeof ScrollTrigger === "undefined") {
            console.warn("ScrollTrigger not loaded");
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

                if (el.data("original")) {
                    el.html(el.data("original"));
                }
            },

            build: function () {

                const settings = this.getElementSettings();
                if (settings.ha_hta_switcher !== "yes") return;

                const $container = this.$element;
                const $heading = $container.find(".elementor-heading-title");
                console.log("heading ", $heading);
                
                if (!$heading.length) return;

                if (!$heading.data("original")) {
                    $heading.data("original", $heading.html());
                }

                const config = this.getConfig(settings);

                this.applyAnimation($container[ 0 ], $heading[ 0 ], config);
            },

            getConfig: function (settings) {

                return {

                    mode: settings.ha_hta_mode || "reveal",

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

                switch (config.mode) {

                    case "chars":
                        elements = this.splitChars(heading);
                        break;

                    case "words":
                        elements = this.splitWords(heading);
                        break;

                    case "text_move":
                        elements = this.splitLines(heading);
                        break;

                    case "reveal":
                        elements = this.splitWords(heading);
                        break;

                    case "scale":
                        elements = this.splitDynamic(heading, config.scaleBreak);
                        break;

                    case "3dspin":
                        elements = this.splitChars(heading);
                        break;

                    default:
                        elements = [ heading ];
                }

                const tl = gsap.timeline({ paused: true });

                switch (config.mode) {

                    case "chars":

                        tl.from(elements, {
                            x: config.transformX,
                            y: config.transformY,
                            opacity: 0,
                            duration: config.duration,
                            stagger: config.stagger,
                            ease: config.easing
                        });

                        break;

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

                        if (config.textMoveDirection === "horizontal") {

                            tl.from(elements, {
                                x: config.textMoveValue,
                                opacity: 0,
                                duration: config.duration,
                                stagger: config.stagger,
                                ease: config.easing
                            });

                        } else {

                            tl.from(elements, {
                                y: config.textMoveValue,
                                opacity: 0,
                                duration: config.duration,
                                stagger: config.stagger,
                                ease: config.easing
                            });

                        }

                        break;

                    case "reveal":

                        const fromY = config.revealOrientation === "top" ? -100 : 100;

                        tl.from(elements, {
                            yPercent: fromY,
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
                        heading.style.backgroundImage =
                            "linear-gradient(to right,#000 50%,#fff 50%)";
                        heading.style.webkitBackgroundClip = "text";
                        heading.style.color = "transparent";

                        tl.fromTo(
                            heading,
                            { backgroundPosition: "100% 0" },
                            {
                                backgroundPosition: "0% 0",
                                duration: 1.2,
                                ease: "power2.out"
                            }
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

                switch (config.triggerMode) {

                    case "pageload":

                        gsap.delayedCall(config.delay, () => tl.play());

                        break;

                    case "hover":

                        container.addEventListener("mouseenter", () => tl.restart());

                        break;

                    case "playwithscroll":

                        const st = ScrollTrigger.create({
                            trigger: container,
                            start: config.triggerPoint,
                            scrub: true,
                            animation: tl
                        });

                        this.scrollTriggers.push(st);

                        break;

                    default:

                        const st2 = ScrollTrigger.create({
                            trigger: container,
                            start: config.triggerPoint,
                            onEnter: () => {
                                gsap.delayedCall(config.delay, () => tl.play());
                            }
                        });

                        this.scrollTriggers.push(st2);

                }

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

                const words = el.textContent.split(" ");
                el.innerHTML = "";

                const elements = [];

                words.forEach(word => {

                    const span = document.createElement("span");
                    span.className = "ha-word";
                    span.textContent = word + " ";

                    el.appendChild(span);
                    elements.push(span);

                });

                return elements;

            },

            splitLines: function (el) {

                const words = el.innerHTML.split("<br>");
                el.innerHTML = "";

                const lines = [];

                words.forEach(line => {

                    const div = document.createElement("div");
                    div.className = "ha-line";
                    div.innerHTML = line;

                    el.appendChild(div);
                    lines.push(div);

                });

                return lines;

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