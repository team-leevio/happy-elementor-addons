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
			// An empty value rests on 0 when the range holds it (translate, rotate, skew).
			current = min > 0 ? min : (max < 0 ? max : 0);
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

	/* ---------------------------------------------------------------- *
	 * ha-tabs
	 * Style tabs (Normal / Hover) whose groups open as popovers, like the
	 * Classic panel. Bound to the feature's switcher: nothing shows while it is off.
	 * Every inner control is rendered by the panel itself (SettingsControl), so
	 * dependencies, history and prop wrapping behave as for any other control.
	 * ---------------------------------------------------------------- */
	var icons = v2.icons || {};

	function PopoverRow(rowProps) {
		var bound = controls.useBoundProp(props.booleanPropTypeUtil);
		var anchor = React.useRef(null);
		var state = React.useState(false);
		var open = state[0];
		var on = true === bound.value;
		var Popover = ui.Popover;

		return h(ui.Stack, {gap: 0},
			h(ui.Stack, {direction: 'row', alignItems: 'center', justifyContent: 'space-between'},
				h(panel.ControlLabel, null, rowProps.label),
				h(ui.Stack, {direction: 'row', alignItems: 'center', gap: 0.5},
					on && icons.ArrowBackUpIcon ? h(ui.IconButton, {
						size: 'tiny',
						'aria-label': 'Reset',
						onClick: function () {
							state[1](false);
							bound.setValue(false);
						}
					}, h(icons.ArrowBackUpIcon, {fontSize: 'tiny'})) : null,
					h(ui.IconButton, {
						size: 'tiny',
						ref: anchor,
						color: on ? 'primary' : 'default',
						'aria-label': rowProps.label,
						onClick: function () {
							if (!on) {
								bound.setValue(true);
							}

							state[1](true);
						}
					}, icons.PencilIcon ? h(icons.PencilIcon, {fontSize: 'tiny'}) : '✎')
				)
			),
			Popover ? h(Popover, {
				open: open && on,
				anchorEl: anchor.current,
				onClose: function () {
					state[1](false);
				},
				anchorOrigin: {vertical: 'bottom', horizontal: 'center'},
				transformOrigin: {vertical: 'top', horizontal: 'center'},
				slotProps: {paper: {sx: {width: 280, maxWidth: '90vw', p: 2}}}
			}, h(ui.Stack, {gap: 1.5}, rowProps.controls.map(function (control) {
				return h(panel.SettingsControl, {key: control.value.bind, control: control});
			}))) : null
		);
	}

	var Tabs = controls.createControl(function (tabsProps) {
		var bound = controls.useBoundProp(props.booleanPropTypeUtil);
		var state = React.useState(0);
		var tabs = tabsProps.tabs || [];

		if (true !== bound.value || !tabs.length) {
			return null;
		}

		var tab = tabs[Math.min(state[0], tabs.length - 1)];

		return h(ui.Stack, {gap: 1.5},
			h(ui.Tabs, {
				value: Math.min(state[0], tabs.length - 1),
				variant: 'fullWidth',
				size: 'small',
				onChange: function (event, value) {
					state[1](value);
				}
			}, tabs.map(function (item, index) {
				return h(ui.Tab, {key: index, label: item.label, value: index, sx: {px: 1}});
			})),
			h(ui.Stack, {gap: 1.5}, tab.items.map(function (item, index) {
				if ('popover' === item.kind) {
					return h(panel.SettingsField, {key: item.toggle + index, bind: item.toggle, propDisplayName: item.label},
						h(PopoverRow, {label: item.label, controls: item.controls}));
				}

				return h(panel.SettingsControl, {key: item.control.value.bind, control: item.control});
			}))
		);
	});

	register('ha-field', Field, 'custom');
	register('ha-color', controls.ColorControl, 'full', props.colorPropTypeUtil);
	register('ha-slider', Slider, 'full', props.numberPropTypeUtil);
	register('ha-tabs', Tabs, 'custom', props.booleanPropTypeUtil);

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
	var MARK = '​‌​';
	var DOT_COLOR = '#fd85d4';

	function unwrapValue(value) {
		while (value && 'object' === typeof value && '$$type' in value) {
			value = value.value;
		}

		return value;
	}

	// Whether one prop counts as "on": a switch, a non-empty text (Wrapper Link's URL,
	// a Foreground type other than "none") or a number above zero (Text Stroke width).
	function propActive(settings, prop) {
		var raw = settings.get(prop);

		if (undefined === raw && settings.attributes) {
			raw = settings.attributes[prop];
		}

		var active = unwrapValue(raw);

		if ('string' === typeof active && '' !== active.trim() && !isNaN(Number(active))) {
			active = Number(active);
		}

		if ('number' === typeof active) {
			return active > 0;
		}

		if ('string' === typeof active) {
			active = active.trim();

			return '' !== active && 'none' !== active && 'no' !== active && '0' !== active;
		}

		return true === active || 'yes' === active;
	}

	// Whether the selected element has the feature of this section turned on
	// (any of the section's switcher props, when several are given).
	function sectionActive(switcher) {
		try {
			var editor = w.elementor;
			var selected = editor && editor.selection && editor.selection.getElements ? editor.selection.getElements() : [];
			var settings = selected.length && selected[0].settings;

			if (!settings || !settings.get) {
				return false;
			}

			return [].concat(switcher).some(function (prop) {
				return propActive(settings, prop);
			});
		} catch (e) {
			return false;
		}
	}

	function addSectionIcons() {
		var sections = (w.HappyAtomicEditor && w.HappyAtomicEditor.sections) || {};
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

			if (!parent) {
				return;
			}

			var icon = parent.querySelector('.ha-section-icon');
			var dot = parent.querySelector('.ha-section-dot');

			if (!icon) {
				icon = document.createElement('i');
				icon.className = 'hm hm-happyaddons ha-section-icon';
				parent.appendChild(icon);
			}

			// Icon and indicator sit on the label's middle line.
			parent.style.display = 'inline-flex';
			parent.style.alignItems = 'center';
			parent.style.gap = '6px';
			icon.style.cssText = 'float:none;display:inline-block;line-height:1;vertical-align:middle;flex:none;';

			var switcher = sections[node.nodeValue.slice(0, -MARK.length)];
			var active = switcher ? sectionActive(switcher) : false;

			if (active && !dot) {
				dot = document.createElement('span');
				dot.className = 'ha-section-dot';
				dot.style.cssText = 'display:inline-block;flex:none;width:6px;height:6px;border-radius:50%;background:' + DOT_COLOR + ';';
				parent.appendChild(dot);
			} else if (!active && dot) {
				dot.remove();
			}
		});
	}

	if (w.MutationObserver && document.body) {
		var timer = null;

		new MutationObserver(function () {
			clearTimeout(timer);
			timer = setTimeout(addSectionIcons, 100);
		}).observe(document.body, {childList: true, subtree: true});

		// A setting change does not always touch the DOM around the section header.
		setInterval(addSectionIcons, 500);
	}
}(window));
