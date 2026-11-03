/**
 * Happy Addons — Elementor V4 (Atomic) editor controls.
 *
 * Registers the panel controls the Atomic components use on top of the core
 * ones (see Atomic_Field_Control and the Image Cycle widget):
 *  - ha-field       a core control with a fractional step / icon buttons and the
 *                   helper text under it
 *  - ha-slider      slider with a number input
 *  - ha-color       color picker (swatch, hex/rgba field)
 *  - ha-image-item  one Image Cycle item: image, resolution and link
 */
(function (w) {
	'use strict';

	var v2 = w.elementorV2 || {};
	var panel = v2.editorEditingPanel;
	var controls = v2.editorControls;
	var props = v2.editorProps;
	var ui = v2.ui;
	var z = v2.schema && v2.schema.z;
	var React = w.React;

	if (!panel || !panel.controlsRegistry || !controls || !props || !ui || !React) {
		return;
	}

	var h = React.createElement;
	var registry = panel.controlsRegistry;

	function register(type, component, layout, propTypeUtil) {
		if (!registry.get(type)) {
			registry.register(type, component, layout, propTypeUtil);
		}
	}

	/* ---------------------------------------------------------------- *
	 * ha-field
	 * ---------------------------------------------------------------- */
	var Field = controls.createControl(function (fieldProps) {
		var config = fieldProps.childControlConfig || {};
		var Child = config.component;

		if (!Child) {
			return null;
		}

		var child = h(Child, config.props || {});
		var label = fieldProps.label ? h(panel.ControlLabel, null, fieldProps.label) : null;
		var description = fieldProps.description
			? h(ui.Typography, {
				variant: 'caption',
				color: 'text.tertiary',
				sx: {display: 'block', fontSize: '11px', fontStyle: 'italic', lineHeight: 1.4}
			}, fieldProps.description)
			: null;

		var body = fieldProps.inline
			? h(ui.Box, {
				sx: {
					display: 'grid',
					gridTemplateColumns: 'repeat(2, minmax(0, 1fr))',
					alignItems: 'center',
					justifyContent: 'space-between',
					gap: 1
				}
			}, label, child)
			: h(ui.Stack, {gap: 1}, label, child);

		return h(ui.Stack, {gap: 0.5}, body, description);
	});

	/* ---------------------------------------------------------------- *
	 * ha-slider
	 * ---------------------------------------------------------------- */
	var Slider = controls.createControl(function (sliderProps) {
		var bound = controls.useBoundProp(props.numberPropTypeUtil);
		var min = sliderProps.min;
		var max = sliderProps.max;
		var step = sliderProps.step || 1;
		var current = Number(bound.value);

		if (null === bound.value || undefined === bound.value || isNaN(current)) {
			current = Number(bound.placeholder);
		}

		if (isNaN(current)) {
			current = min;
		}

		return h(ui.Stack, {direction: 'row', alignItems: 'center', gap: 2},
			h(ui.Box, {sx: {flex: 1, px: 1}},
				h(ui.Slider, {
					size: 'small',
					min: min,
					max: max,
					step: step,
					value: Math.min(Math.max(current, min), max),
					onChange: function (event, value) {
						bound.setValue(+(Math.round(value / step) * step).toFixed(4));
					}
				})
			),
			h(ui.Box, {sx: {width: '76px', flexShrink: 0}},
				h(controls.NumberControl, {min: min, max: max, step: step})
			)
		);
	});

	/* ---------------------------------------------------------------- *
	 * ha-image-item
	 * ---------------------------------------------------------------- */
	var itemUtil = z && props.createPropUtils('ha-image-item', z.strictObject({
		image: z.any().nullable().optional(),
		link: z.any().nullable().optional()
	}));

	// The link control refuses a link while the element already holds an anchor,
	// and the cards of the other items are anchors. An id that matches no element
	// keeps every item free to take a link.
	var NO_LINK_RESTRICTION_ID = 'ha-image-cycle-item';

	var ImageItem = controls.createControl(function (itemProps) {
		var bound = controls.useBoundProp(itemUtil);
		var context = Object.assign({}, bound);

		delete context.value;
		delete context.setValue;

		return h(controls.PropProvider, Object.assign({}, context, {value: bound.value, setValue: bound.setValue}),
			h(ui.Stack, {gap: 2},
				h(controls.PropKeyProvider, {bind: 'image'},
					h(controls.ImageControl, {sizes: itemProps.sizes})
				),
				h(controls.PropKeyProvider, {bind: 'link'},
					h(controls.LinkControl, Object.assign({}, itemProps.linkOptions, {context: {elementId: NO_LINK_RESTRICTION_ID}}))
				)
			)
		);
	});

	/* ---------------------------------------------------------------- *
	 * Native Atomic controls the settings registry does not expose:
	 * font family, spacing and the border width / radius of Happy Mouse Cursor
	 * (the same controls the Style tab uses). Each sits inside an "ha-field".
	 * ---------------------------------------------------------------- */
	var icons = v2.icons || {};
	var editorUi = v2.editorUi || {};

	function icon(name) {
		return icons[name] ? h(icons[name], {fontSize: 'tiny'}) : null;
	}

	// Sub-heading that groups the controls below it (Layout & Style).
	if (props.stringPropTypeUtil) {
		register('ha-heading', controls.createControl(function (headingProps) {
			return h(ui.Stack, {gap: 1, sx: {pt: 1}},
				h(ui.Divider, null),
				h(ui.Typography, {variant: 'subtitle2', sx: {fontWeight: 600}}, headingProps.text)
			);
		}), 'full', props.stringPropTypeUtil);
	}

	// The native spacing controls clear a prop by setting it to null. A null setting
	// breaks Elementor's own style-id handling (isClassesProp reads `.$$type` of every
	// setting), so a cleared prop is stored as an empty value of its type instead.
	function noNull(Inner, type) {
		return controls.createControl(function (innerProps) {
			var bound = controls.useBoundProp();
			var context = Object.assign({}, bound);

			delete context.value;
			delete context.setValue;

			return h(controls.PropProvider, Object.assign({}, context, {
				value: bound.value,
				setValue: function (value, options, meta) {
					bound.setValue(null === value || undefined === value ? {$$type: type, value: {}} : value, options, meta);
				}
			}), h(Inner, innerProps));
		});
	}

	if (controls.FontFamilyControl && controls.useFontFamilies && props.fontFamilyPropTypeUtil) {
		register('ha-font-family', controls.createControl(function () {
			var fonts = controls.useFontFamilies();
			var width = editorUi.useSectionWidth ? editorUi.useSectionWidth() : 320;

			return h(controls.FontFamilyControl, {fontFamilies: fonts, sectionWidth: width, ariaLabel: 'Font family'});
		}), 'full', props.fontFamilyPropTypeUtil);
	}

	if (controls.LinkedDimensionsControl && props.dimensionsPropTypeUtil) {
		register('ha-dimensions', noNull(function (dimProps) {
			return h(controls.LinkedDimensionsControl, {label: dimProps.label, isSiteRtl: false, min: dimProps.min});
		}, 'dimensions'), 'full', props.dimensionsPropTypeUtil);
	}

	if (controls.EqualUnequalSizesControl && props.borderWidthPropTypeUtil && props.borderRadiusPropTypeUtil) {
		register('ha-border-width', noNull(function (sizeProps) {
			return h(controls.EqualUnequalSizesControl, {
				items: [
					{label: 'Top', ariaLabel: 'Border top width', icon: icon('SideTopIcon'), bind: 'block-start'},
					{label: 'Right', ariaLabel: 'Border right width', icon: icon('SideRightIcon'), bind: 'inline-end'},
					{label: 'Bottom', ariaLabel: 'Border bottom width', icon: icon('SideBottomIcon'), bind: 'block-end'},
					{label: 'Left', ariaLabel: 'Border left width', icon: icon('SideLeftIcon'), bind: 'inline-start'}
				],
				label: sizeProps.label,
				icon: icon('SideAllIcon'),
				tooltipLabel: 'Adjust borders',
				multiSizePropTypeUtil: props.borderWidthPropTypeUtil
			});
		}, 'border-width-v2'), 'full', props.borderWidthPropTypeUtil);

		register('ha-border-radius', noNull(function (sizeProps) {
			return h(controls.EqualUnequalSizesControl, {
				items: [
					{label: 'Top left', ariaLabel: 'Border top left radius', icon: icon('RadiusTopLeftIcon'), bind: 'start-start'},
					{label: 'Top right', ariaLabel: 'Border top right radius', icon: icon('RadiusTopRightIcon'), bind: 'start-end'},
					{label: 'Bottom right', ariaLabel: 'Border bottom right radius', icon: icon('RadiusBottomRightIcon'), bind: 'end-end'},
					{label: 'Bottom left', ariaLabel: 'Border bottom left radius', icon: icon('RadiusBottomLeftIcon'), bind: 'end-start'}
				],
				label: sizeProps.label,
				icon: icon('BorderCornersIcon'),
				tooltipLabel: 'Adjust corners',
				multiSizePropTypeUtil: props.borderRadiusPropTypeUtil
			});
		}, 'border-radius-v2'), 'full', props.borderRadiusPropTypeUtil);
	}

	register('ha-field', Field, 'custom');
	register('ha-color', controls.ColorControl, 'full', props.colorPropTypeUtil);
	register('ha-slider', Slider, 'full', props.numberPropTypeUtil);

	// The core color control is not in the settings registry (only the style tab uses it).
	if (controls.ColorControl && props.colorPropTypeUtil) {
		register('ha-color', controls.ColorControl, 'custom', props.colorPropTypeUtil);
	}

	if (itemUtil) {
		register('ha-image-item', ImageItem, 'full', itemUtil);
	}

	/* ---------------------------------------------------------------- *
	 * Happy icon on the section of every Happy Atomic feature, like the
	 * Classic panel. PHP ends those section labels with a run of zero-width characters.
	 * ---------------------------------------------------------------- */
	var MARK = '\u200B\u200C\u200B';

	function addSectionIcons() {
		var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
			acceptNode: function (node) {
				return node.nodeValue.slice(-MARK.length) === MARK ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
			}
		});
		var nodes = [];

		while (walker.nextNode()) {
			nodes.push(walker.currentNode);
		}

		nodes.forEach(function (node) {
			var parent = node.parentNode;

			if (!parent || parent.querySelector('.ha-section-icon')) {
				return;
			}

			var icon = document.createElement('i');

			icon.className = 'hm hm-happyaddons ha-section-icon';
			icon.style.float = 'right';
			parent.appendChild(icon);
		});
	}

	if (w.MutationObserver && document.body) {
		var timer = null;

		new MutationObserver(function () {
			clearTimeout(timer);
			timer = setTimeout(addSectionIcons, 100);
		}).observe(document.body, {childList: true, subtree: true});
	}
}(window));
