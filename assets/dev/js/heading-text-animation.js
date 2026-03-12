(function ($, w) {

    "use strict";

    $(w).on("elementor/frontend/init", function () {

        if (typeof gsap === "undefined") return;
        if (typeof ScrollTrigger === "undefined") return;

        gsap.registerPlugin(ScrollTrigger);

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

                    textFlipDirection: settings.ha_hta_tv_rotation_direction || "horizontal",
                    textFlipValue: settings.ha_hta_tv_rotation_value || -80,
                    textFlipTransformOrigin: settings.ha_hta_tv_transform_origin || "top center -50",

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

                    // apply perspective on heading container
                    gsap.set(heading, {
                        perspective: 400
                    });

                    // FIXED direction mapping
                    const rotation_di = config.textFlipDirection === "horizontal" ? "y" : "x";

                    let flipConfig = {
                        duration: config.duration,
                        delay: config.delay,
                        opacity: 0,
                        force3D: true,
                        transformOrigin: config.textFlipTransformOrigin,
                        stagger: config.stagger
                    };

                    // Horizontal = rotationY
                    if (rotation_di === "y") {
                        flipConfig.rotationY = config.textFlipValue;
                    }

                    // Vertical = rotationX
                    if (rotation_di === "x") {
                        flipConfig.rotationX = config.textFlipValue;
                    }

                    tl.from(elements, flipConfig);

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

                    const start = config.triggerPoint || "top 85%";
                    const end = "bottom center";
                    const wrapper = this.$element;
                    const element = wrapper.find(".elementor-heading-title");

                    const RGBToHSL = (r, g, b) => {
                        r /= 255; g /= 255; b /= 255;
                        const l = Math.max(r, g, b);
                        const s = l - Math.min(r, g, b);
                        const h = s ? l === r ? (g - b) / s : l === g ? 2 + (b - r) / s : 4 + (r - g) / s : 0;
                        return [
                            60 * h < 0 ? 60 * h + 360 : 60 * h,
                            100 * (s ? (l <= 0.5 ? s / (2 * l - s) : s / (2 - (2 * l - s))) : 0),
                            100 * (2 * l - s) / 2
                        ];
                    };

                    let color = element.css("color").match(/(\d+)/g);
                    const hsl = RGBToHSL(color[ 0 ], color[ 1 ], color[ 2 ]);
                    element.css("--text-color", `${hsl[ 0 ].toFixed(1)}, ${hsl[ 1 ].toFixed(1)}%, ${hsl[ 2 ].toFixed(1)}%`);

                    const split = new SplitText(element[ 0 ], {
                        type: "lines",
                        linesClass: "invert-line"
                    });

                    split.lines.forEach((line) => {
                        gsap.to(line, {
                            backgroundPositionX: "-100%",
                            ease: "none",
                            scrollTrigger: {
                                trigger: line,
                                scrub: 1,
                                start: start,
                                end: end
                            }
                        });
                    });
                }

                else if (config.mode === "3dspin") {

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
                        ease: "power1"
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

                if (config.triggerMode === "hover") {
                    container.addEventListener("mouseenter", () => tl.restart());
                    return;
                }

                // PLAY WITH SCROLL
                if (config.triggerMode === "playwithscroll") {

                    const st = ScrollTrigger.create({
                        trigger: container,
                        start: "top bottom",
                        end: "bottom top",
                        scrub: 1,
                        animation: tl
                    });

                    this.scrollTriggers.push(st);
                    return;
                }

                // NORMAL SCROLL (play once)
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

                const lines = el.innerHTML.split(/<br\s*\/?>/i);
                el.innerHTML = "";

                const arr = [];

                lines.forEach(line => {

                    if (isTextInvert) {

                        const wrapper = document.createElement("div");
                        wrapper.className = "ha-invert-line";

                        const text = document.createElement("span");
                        text.className = "ha-invert-text";
                        text.innerHTML = line;

                        const mask = document.createElement("span");
                        mask.className = "ha-invert-mask";

                        wrapper.appendChild(text);
                        wrapper.appendChild(mask);

                        el.appendChild(wrapper);
                        arr.push(mask);

                    } else {

                        const div = document.createElement("div");
                        div.className = "ha-flip";
                        div.innerHTML = line;

                        el.appendChild(div);
                        arr.push(div);

                    }

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