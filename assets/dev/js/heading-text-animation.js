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

            timelines: [],
            scrollTriggers: [],

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
            },

            destroyAnimation: function () {

                this.timelines.forEach(t => t.kill());
                this.scrollTriggers.forEach(s => s.kill());

                this.timelines = [];
                this.scrollTriggers = [];

                const heading = this.$element.find(".elementor-heading-title");

                if (heading.data("original")) {
                    heading.html(heading.data("original"));
                }

            },

            build: function () {

                const settings = this.getElementSettings();

                if (settings.ha_hta_switcher !== "yes") return;

                const wrapper = this.$element;
                const heading = wrapper.find(".elementor-heading-title");

                if (!heading.length) return;

                if (!heading.data("original")) {
                    heading.data("original", heading.html());
                }

                const config = this.getConfig(settings);

                let actualMode = config.mode;

                if (config.mode === "slide") {
                    actualMode = config.charsWordsMode === "words" ? "words" : "chars";
                }

                wrapper.removeClass(
                    "ha-hta-reveal ha-hta-chars ha-hta-words ha-hta-lines ha-hta-text-scale ha-hta-text-invert ha-hta-3d-spin ha-hta-text-move"
                );

                let modeClass = "ha-hta-reveal";

                switch (actualMode) {
                    case "chars": modeClass = "ha-hta-chars"; break;
                    case "words": modeClass = "ha-hta-words"; break;
                    case "lines": modeClass = "ha-hta-lines"; break;
                    case "scale": modeClass = "ha-hta-text-scale"; break;
                    case "invert": modeClass = "ha-hta-text-invert"; break;
                    case "3dspin": modeClass = "ha-hta-3d-spin"; break;
                    case "text_move": modeClass = "ha-hta-text-move"; break;
                }

                wrapper.addClass(modeClass);

                config.mode = actualMode;

                this.applyAnimation(wrapper[ 0 ], heading[ 0 ], config);

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

                    case "lines":
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

                tl.from(elements, {
                    y: 80,
                    opacity: 0,
                    duration: config.duration,
                    stagger: config.stagger,
                    ease: config.easing
                });

                this.attachTrigger(container, tl, config);

                this.timelines.push(tl);

            },

            attachTrigger: function (container, tl, config) {

                if (config.triggerMode === "pageload") {
                    gsap.delayedCall(config.delay, () => tl.play());
                    return;
                }

                const st = ScrollTrigger.create({
                    trigger: container,
                    start: config.triggerPoint,
                    toggleActions: "play none none none",
                    onEnter: () => {
                        gsap.delayedCall(config.delay, () => tl.play());
                    }
                });

                this.scrollTriggers.push(st);

            },

            splitChars: function (el) {

                const text = el.textContent;

                el.innerHTML = "";

                const chars = [];

                text.split("").forEach(c => {

                    const span = document.createElement("span");

                    span.className = "ha-char";

                    span.textContent = c === " " ? "\u00A0" : c;

                    el.appendChild(span);

                    chars.push(span);

                });

                return chars;

            },

            splitWords: function (el) {

                const words = el.textContent.split(" ");

                el.innerHTML = "";

                const spans = [];

                words.forEach((word, i) => {

                    const span = document.createElement("span");

                    span.className = "ha-word";

                    span.textContent = word;

                    el.appendChild(span);

                    spans.push(span);

                    if (i !== words.length - 1) {
                        el.appendChild(document.createTextNode(" "));
                    }

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

                elementorFrontend.elementsHandler.addHandler(
                    HappyHTA,
                    { $element: $scope }
                );

            }
        );

    });

})(jQuery, window);