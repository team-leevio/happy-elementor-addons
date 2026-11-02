<?php
namespace Happy_Addons\Elementor\Extensions\Atomic;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/atomic-feature-base.php';

/**
 * Happy Tooltip for every Atomic element: the containers (Flexbox, Div Block,
 * Grid) and the Atomic widgets (heading, image, button, ...). The V4
 * counterpart of extensions/advanced-tooltip.php.
 *
 * The V3 tooltip appends a child to the element; that does not work here
 * (images cannot have children, widgets sit in a `display: contents` wrapper and
 * the editor re-renders them client side). Instead a single floating element is
 * appended to <body> and positioned from the target's bounding box, by
 * happy-atomic-tooltip.js. The sanitized config is handed over through
 * Atomic_Manager::register_frontend_config(); the editor preview builds the same
 * config from the editor settings (keep both in sync).
 */
class Tooltip extends Atomic_Feature_Base {

	protected function prefix() {
		return 'ha_tt_';
	}

	/** Not a fixed list: see targets_element(). */
	protected function target_type() {
		return [];
	}

	protected function targets_element( $element ) {
		return $element instanceof \Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base
			|| $element instanceof \Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Element_Base;
	}

	protected function is_target( $element ) {
		return $this->targets_element( $element );
	}

	protected function feature_key() {
		return 'tooltip';
	}

	protected function section_label() {
		return __( 'Happy Tooltip', 'happy-elementor-addons' );
	}

	protected function matches_schema( array $schema ) {
		// Every Atomic element has the Classes prop.
		return isset( $schema['classes'] );
	}

	protected function fields() {
		$on    = [ 'switcher' => true ];
		$arrow = [ 'switcher' => true, 'arrow' => true ];

		$slider = function ( $default, $label, $min, $max, $show = null ) use ( $on ) {
			return [
				'type' => 'slider', 'default' => $default, 'min' => $min, 'max' => $max, 'step' => 1,
				'label' => $label,
				'show'  => $show ?: $on,
			];
		};

		$num = function ( $default, $label, $min, $max, $step = 1 ) use ( $on ) {
			return [
				'type' => 'number', 'default' => $default, 'min' => $min, 'max' => $max, 'step' => $step,
				'label' => $label,
				'show'  => $on,
			];
		};

		return [
			'switcher'    => [ 'type' => 'bool', 'default' => false, 'label' => __( 'Enable Happy Tooltip?', 'happy-elementor-addons' ) ],
			'content'     => [
				'type'        => 'string',
				'default'     => __( 'I am a tooltip', 'happy-elementor-addons' ),
				'label'       => __( 'Content', 'happy-elementor-addons' ),
				'description' => ha_get_allowed_html_desc( 'intermediate' ),
				'show'        => $on,
			],
			'position'    => [
				'type'    => 'enum',
				'default' => 'top',
				'label'   => __( 'Position', 'happy-elementor-addons' ),
				'options' => [
					'top'    => __( 'Top', 'happy-elementor-addons' ),
					'bottom' => __( 'Bottom', 'happy-elementor-addons' ),
					'left'   => __( 'Left', 'happy-elementor-addons' ),
					'right'  => __( 'Right', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'animation'   => [
				'type'    => 'enum',
				'default' => 'none',
				'label'   => __( 'Animation', 'happy-elementor-addons' ),
				'options' => [
					'none'         => __( 'None', 'happy-elementor-addons' ),
					'fadeIn'       => __( 'fadeIn', 'happy-elementor-addons' ),
					'zoomIn'       => __( 'zoomIn', 'happy-elementor-addons' ),
					'rollIn'       => __( 'rollIn', 'happy-elementor-addons' ),
					'bounce'       => __( 'bounce', 'happy-elementor-addons' ),
					'slideInDown'  => __( 'slideInDown', 'happy-elementor-addons' ),
					'slideInLeft'  => __( 'slideInLeft', 'happy-elementor-addons' ),
					'slideInRight' => __( 'slideInRight', 'happy-elementor-addons' ),
					'slideInUp'    => __( 'slideInUp', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'duration'    => $num( 1000, __( 'Animation Duration (ms)', 'happy-elementor-addons' ), 100, 5000, 50 ),
			'arrow'       => [ 'type' => 'bool', 'default' => true, 'label' => __( 'Arrow', 'happy-elementor-addons' ), 'show' => $on ],
			'trigger'     => [
				'type'    => 'enum',
				'default' => 'hover',
				'label'   => __( 'Trigger', 'happy-elementor-addons' ),
				'options' => [
					'hover' => __( 'Hover', 'happy-elementor-addons' ),
					'click' => __( 'Click', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'distance'    => $slider( 0, __( 'Distance (px)', 'happy-elementor-addons' ), -100, 500 ),
			'align'       => [
				'type'    => 'enum',
				'default' => 'center',
				'label'   => __( 'Text Alignment', 'happy-elementor-addons' ),
				'options' => [
					'left'   => __( 'Left', 'happy-elementor-addons' ),
					'center' => __( 'Center', 'happy-elementor-addons' ),
					'right'  => __( 'Right', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'width'       => $slider( 120, __( 'Width (px)', 'happy-elementor-addons' ), 1, 800 ),
			'arrow_size'  => $slider( 5, __( 'Arrow Size (px)', 'happy-elementor-addons' ), 1, 100, $arrow ),
			'font_size'   => $num( 14, __( 'Font Size (px)', 'happy-elementor-addons' ), 8, 100 ),
			'font_weight' => [
				'type'    => 'enum',
				'default' => '500',
				'label'   => __( 'Font Weight', 'happy-elementor-addons' ),
				'options' => [
					'300' => __( 'Light (300)', 'happy-elementor-addons' ),
					'400' => __( 'Normal (400)', 'happy-elementor-addons' ),
					'500' => __( 'Medium (500)', 'happy-elementor-addons' ),
					'600' => __( 'Semi Bold (600)', 'happy-elementor-addons' ),
					'700' => __( 'Bold (700)', 'happy-elementor-addons' ),
					'800' => __( 'Extra Bold (800)', 'happy-elementor-addons' ),
				],
				'show'    => $on,
			],
			'bg_color'    => [
				'type'    => 'color',
				'default' => '#000000',
				'label'   => __( 'Background Color', 'happy-elementor-addons' ),
				'show'    => $on,
			],
			'color'       => [
				'type'    => 'color',
				'default' => '#ffffff',
				'label'   => __( 'Text Color', 'happy-elementor-addons' ),
				'show'    => $on,
			],
			'radius'      => $num( 6, __( 'Border Radius (px)', 'happy-elementor-addons' ), 0, 100 ),
			'padding_y'   => $num( 5, __( 'Padding Top / Bottom (px)', 'happy-elementor-addons' ), 0, 100 ),
			'padding_x'   => $num( 10, __( 'Padding Left / Right (px)', 'happy-elementor-addons' ), 0, 100 ),
		];
	}

	/**
	 * The content may carry the same inline HTML the V3 tooltip allows, so it is
	 * filtered with the allow-list instead of being stripped to plain text.
	 */
	protected function sanitize_config( array $settings ) {
		$config  = parent::sanitize_config( $settings );
		$content = $settings[ $this->prefix() . 'content' ] ?? null;

		// An emptied field means "no tooltip", not "fall back to the default text".
		$config['content'] = is_string( $content ) ? ha_kses_intermediate( $content ) : ha_kses_intermediate( $this->fields()['content']['default'] );

		return $config;
	}
}
