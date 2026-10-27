<?php
namespace Happy_Addons\Elementor\Widget\Atomic;

use Elementor\Modules\AtomicWidgets\PropTypes\Base\Array_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Base\Object_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Contracts\Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Image_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Link_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\Boolean_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Primitives\String_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Query_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Union_Prop_Type;
use Elementor\Modules\AtomicWidgets\PropTypes\Url_Prop_Type;

defined( 'ABSPATH' ) || die();

/**
 * The same "link" value as the core Atomic link, without its dependencies:
 * those point at a top level "link" prop, which an image item does not have.
 */
class Image_Item_Link_Prop_Type extends Link_Prop_Type {

	protected function define_shape(): array {
		return [
			'destination'   => Union_Prop_Type::make()
				->add_prop_type( Url_Prop_Type::make()->skip_validation() )
				->add_prop_type( Query_Prop_Type::make() ),
			'isTargetBlank' => Boolean_Prop_Type::make(),
			'tag'           => String_Prop_Type::make()
				->enum( [ 'a', 'button' ] )
				->default( self::DEFAULT_TAG ),
		];
	}
}

/**
 * One Image Cycle item: an image (with its resolution) and an optional link.
 */
class Image_Item_Prop_Type extends Object_Prop_Type {

	public static function get_key(): string {
		return 'ha-image-item';
	}

	protected function define_shape(): array {
		return [
			'image' => Image_Prop_Type::make(),
			'link'  => Image_Item_Link_Prop_Type::make(),
		];
	}
}

/**
 * The Image Cycle items. The key is what the editor builds for a repeatable
 * control whose items are "ha-image-item" (<item key>-array).
 */
class Image_Items_Prop_Type extends Array_Prop_Type {

	public static function get_key(): string {
		return 'ha-image-item-array';
	}

	protected function define_item_type(): Prop_Type {
		return Image_Item_Prop_Type::make();
	}
}
