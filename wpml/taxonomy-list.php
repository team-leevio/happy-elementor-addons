<?php
/**
 * Taxonomy List integration
 */
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Taxonomy_List_Selected_List_Category extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'selected_list_category';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Taxonomy List: Title', 'happy-elementor-addons' );
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';
			default:
				return '';
		}
	}
}

class WPML_Taxonomy_List_Selected_List_Post_Tag extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'selected_list_post_tag';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Taxonomy List: Title', 'happy-elementor-addons' );
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';
			default:
				return '';
		}
	}
}

class WPML_Taxonomy_List_Selected_List_Post_Format extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'selected_list_post_format';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Taxonomy List: Title', 'happy-elementor-addons' );
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';
			default:
				return '';
		}
	}
}

class WPML_Taxonomy_List_Selected_List_Product_Cat extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'selected_list_product_cat';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Taxonomy List: Title', 'happy-elementor-addons' );
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';
			default:
				return '';
		}
	}
}

class WPML_Taxonomy_List_Selected_List_Product_Tag extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'selected_list_product_tag';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Taxonomy List: Title', 'happy-elementor-addons' );
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';
			default:
				return '';
		}
	}
}

class WPML_Taxonomy_List_Selected_List_Product_Shipping_Class extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'selected_list_product_shipping_class';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Taxonomy List: Title', 'happy-elementor-addons' );
			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
				return 'LINE';
			default:
				return '';
		}
	}
}
