<?php
/**
 * Panel control that shows a feature's settings as Normal / Hover style tabs,
 * with popover groups (a toggle + the settings it opens), like the Classic panel.
 * It is rendered by the "ha-tabs" control registered in happy-atomic-editor.js
 * and is bound to the feature's switcher prop: it only shows while that is on.
 *
 * A tab is [ 'label' => string, 'items' => item[] ] where an item is either
 * [ 'kind' => 'popover', 'label' => string, 'toggle' => prop, 'controls' => control[] ]
 * or [ 'kind' => 'control', 'control' => control ], with every control already serialized.
 */
namespace Happy_Addons\Elementor\Classes;

use Elementor\Modules\AtomicWidgets\Controls\Base\Atomic_Control_Base;

defined( 'ABSPATH' ) || die();

class Atomic_Tabs_Control extends Atomic_Control_Base {

	private $tabs = [];

	public function get_type(): string {
		return 'ha-tabs';
	}

	public function set_tabs( array $tabs ): self {
		$this->tabs = $tabs;

		return $this;
	}

	public function get_props(): array {
		return [ 'tabs' => $this->tabs ];
	}
}
