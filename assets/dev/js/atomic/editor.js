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

	register('ha-field', Field, 'custom');
	register('ha-color', controls.ColorControl, 'full', props.colorPropTypeUtil);
	register('ha-slider', Slider, 'full', props.numberPropTypeUtil);

	if (itemUtil) {
		register('ha-image-item', ImageItem, 'full', itemUtil);
	}
}(window));
