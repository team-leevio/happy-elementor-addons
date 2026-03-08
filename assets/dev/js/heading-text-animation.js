(function ($, w) {

    "use strict";

    $(w).on("elementor/frontend/init", function () {

        if (typeof gsap === "undefined") return;
        if (typeof ScrollTrigger === "undefined") return;

        gsap.registerPlugin(ScrollTrigger);
        console.log('mount ');


        const HappyHTA = elementorModules.frontend.handlers.Base.extend({

            timelines: [],
            scrollTriggers: [],

            onInit() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                this.build();
            },

            onElementChange() {
                this.destroyAnimation();
                this.build();
            },

            onDestroy() {
                this.destroyAnimation();
            },

            destroyAnimation() {

                this.timelines.forEach(t => t.kill());
                this.scrollTriggers.forEach(s => s.kill());

                this.timelines = [];
                this.scrollTriggers = [];

                const heading = this.$element.find(".elementor-heading-title");

                if (heading.data("original")) {
                    heading.html(heading.data("original"));
                }

            },

            build() {

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
                    "ha-hta-chars ha-hta-words ha-hta-lines ha-hta-text-scale ha-hta-text-invert ha-hta-3d-spin ha-hta-text-flip"
                );

                let modeClass = "ha-hta-chars";

                switch (actualMode) {
                    case "chars": modeClass = "ha-hta-chars"; break;
                    case "words": modeClass = "ha-hta-words"; break;
                    case "lines": modeClass = "ha-hta-lines"; break;
                    case "scale": modeClass = "ha-hta-text-scale"; break;
                    case "invert": modeClass = "ha-hta-text-invert"; break;
                    case "3dspin": modeClass = "ha-hta-3d-spin"; break;
                    case "text_flip": modeClass = "ha-hta-text-flip"; break;
                }

                wrapper.addClass(modeClass);

                config.mode = actualMode;

                this.applyAnimation(wrapper[ 0 ], heading[ 0 ], config);

            },

            getConfig(settings) {

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

            applyAnimation(container, heading, config) {

                let elements;

                switch (config.mode) {

                    case "chars":
                        elements = this.splitChars(heading);
                        break;

                    case "words":
                        elements = this.splitWords(heading);
                        break;

                    case "reveal":
                        elements = this.splitReveal(heading);
                        break;

                    case "text_flip":
                        elements = this.splitLines(heading);
                        break;

                    case "scale":
                        elements = this.splitDynamic(heading, config.scaleBreak);
                        break;

                    case "invert":
                        elements = this.splitLines(heading, true);
                        break;

                    case "3dspin":
                        elements = this.build3D(heading);
                        break;

                    default:
                        elements = [ heading ];

                }

                const tl = gsap.timeline({ paused: true });

                if (config.mode === "chars" || config.mode === "words") {

                    tl.from(elements, {
                        x: config.transformX,
                        y: config.transformY,
                        opacity: 0,
                        duration: config.duration,
                        stagger: config.stagger,
                        ease: config.easing
                    });

                }

                else if (config.mode === "reveal") {

                    const offset = config.revealOrientation === "top" ? -100 : 100;

                    tl.from(elements, {
                        yPercent: offset,
                        opacity: 0,
                        duration: config.duration,
                        stagger: config.stagger,
                        ease: config.easing
                    });

                }

                else if (config.mode === "text_flip") {

                    // tl.from(elements, {
                    //     x: config.textMoveDirection === "horizontal" ? config.textMoveValue : 0,
                    //     y: config.textMoveDirection === "vertical" ? config.textMoveValue : 0,
                    //     opacity: 0,
                    //     duration: config.duration,
                    //     stagger: config.stagger,
                    //     ease: config.easing
                    // });

                    // gsap.set(container, {
                    //     perspective: 400
                    // });

                    // const flipConfig = {
                    //     opacity: 0,
                    //     duration: config.duration,
                    //     delay: config.delay,
                    //     stagger: config.stagger,
                    //     force3D: true,
                    //     transformOrigin: "top center -50",
                    //     // ease: config.easing
                    // };

                    // // rotation direction
                    // if (config.textMoveDirection === "horizontal") {
                    //     flipConfig.rotationX = config.textMoveValue;
                    // }

                    // if (config.textMoveDirection === "vertical") {
                    //     flipConfig.rotationY = config.textMoveValue;
                    // }

                    // tl.from(elements, flipConfig);

                    gsap.set(container, {
                        perspective: 400
                    });

                    const flipConfig = {
                        opacity: 0,
                        duration: config.duration,
                        delay: config.delay,
                        stagger: config.stagger,
                        ease: config.easing,
                        transformOrigin: "top center -80",
                        force3D: true,

                        // depth illusion
                        z: -120,
                        rotationX: config.textMoveDirection === "horizontal" ? config.textMoveValue : 0,
                        rotationY: config.textMoveDirection === "vertical" ? config.textMoveValue : 0,

                        filter: "blur(8px)"
                    };

                    tl.from(elements, flipConfig);

                    // remove blur smoothly
                    tl.to(elements, {
                        filter: "blur(0px)",
                        duration: config.duration * 0.6,
                        stagger: config.stagger
                    }, 0);

                }

                else if (config.mode === "scale") {

                    tl.from(elements, {
                        scale: config.scaleValue,
                        opacity: 0,
                        duration: config.duration,
                        stagger: config.stagger,
                        ease: config.easing
                    });

                }

                else if (config.mode === "invert") {

                    elements.forEach((line) => {

                        const st = ScrollTrigger.create({
                            trigger: line,
                            start: "top 85%",
                            end: "bottom center",
                            scrub: 1,
                            animation: gsap.to(line, {
                                backgroundPositionX: "0%",
                                ease: "none"
                            })
                        });

                        this.scrollTriggers.push(st);

                    });

                }

                else if (config.mode === "3dspin") {

                    // gsap.set(container, { perspective: 600 });

                    // tl.from(elements, {
                    //     rotationX: -90,
                    //     opacity: 0,
                    //     transformOrigin: "50% 50% -50",
                    //     duration: 0.8,
                    //     stagger: 0.04
                    // });

                    const original = heading;
                    const clone = heading.cloneNode(true);

                    clone.classList.add("duplicate-text");

                    heading.parentNode.appendChild(clone);

                    const originalChars = this.splitChars(original);
                    const cloneChars = this.splitChars(clone);

                    gsap.set(cloneChars, { opacity: 0 });

                    const height = heading.offsetHeight;
                    const origin = `50% 50% -${height / 2}`;

                    const spinTL = gsap.timeline({ paused: true });

                    spinTL.set(cloneChars, {
                        rotationX: -90,
                        transformOrigin: origin
                    });

                    spinTL.to(originalChars, {
                        rotationX: 90,
                        opacity: 0,
                        transformOrigin: origin,
                        duration: config.duration,
                        stagger: config.stagger,
                        ease: "power2.in"
                    });

                    spinTL.to(cloneChars, {
                        opacity: 1,
                        duration: 0.01,
                        stagger: config.stagger
                    }, 0);

                    spinTL.to(cloneChars, {
                        rotationX: 0,
                        duration: config.duration,
                        stagger: config.stagger,
                        ease: "power2.out"
                    }, 0);

                    this.attachTrigger(container, spinTL, config);

                    this.timelines.push(spinTL);

                    return;

                }

                this.attachTrigger(container, tl, config);

                this.timelines.push(tl);

            },

            attachTrigger(container, tl, config) {

                // if (config.triggerMode === "pageload") {
                //     gsap.delayedCall(config.delay, () => tl.play());
                //     return;
                // }

                if (config.triggerMode === "hover") {
                    container.addEventListener("mouseenter", () => tl.restart());
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

            splitChars(el) {

                const text = el.textContent.trim();
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

            splitWords(el) {

                const words = el.textContent.trim().split(" ");
                el.innerHTML = "";

                const spans = [];

                words.forEach((w, i) => {

                    const span = document.createElement("span");
                    span.className = "ha-word";
                    span.textContent = w;

                    el.appendChild(span);
                    spans.push(span);

                    if (i !== words.length - 1) {
                        el.appendChild(document.createTextNode(" "));
                    }

                });

                return spans;

            },

            splitReveal(el) {

                const chars = this.splitChars(el);

                chars.forEach(c => c.classList.add("ha-reveal-char"));

                return chars;

            },

            splitLines(el, isTextInvert = false) {

                const lines = el.innerHTML.split("<br>");
                el.innerHTML = "";

                const arr = [];

                lines.forEach(line => {

                    const div = document.createElement("div");
                    div.className = isTextInvert ? "ha-invert" : "ha-flip";
                    div.innerHTML = line;

                    el.appendChild(div);
                    arr.push(div);

                });

                return arr;

            },

            splitDynamic(el, type) {

                if (type === "chars") return this.splitChars(el);
                if (type === "words") return this.splitWords(el);

                return this.splitLines(el);

            },

            build3D(el) {

                const chars = this.splitChars(el);

                chars.forEach(c => c.classList.add("ha-spin-char"));

                return chars;

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