<?php
/**
 * Post Info integration
 */
namespace Happy_Addons\Elementor;

defined( 'ABSPATH' ) || die();

class WPML_Post_Info_Icon_List extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'icon_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'text_prefix',
			'string_no_comments',
			'string_one_comment',
			'string_comments',
			'custom_text',
			'custom_url' => ['url']
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'text_prefix':
				return __( 'Post Info: Before', 'happy-elementor-addons' );
			case 'string_no_comments':
				return __( 'Post Info: No Comments', 'happy-elementor-addons' );
			case 'string_one_comment':
				return __( 'Post Info: One Comment', 'happy-elementor-addons' );
			case 'string_comments':
				return __( 'Post Info: Comments', 'happy-elementor-addons' );
			case 'custom_text':
				return __( 'Post Info: Custom', 'happy-elementor-addons' );
			case 'url':
				return __( 'Post Info: Custom URL', 'happy-elementor-addons' );
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
			case 'text_prefix':
				return 'LINE';
			case 'string_no_comments':
				return 'LINE';
			case 'string_one_comment':
				return 'LINE';
			case 'string_comments':
				return 'LINE';
			case 'custom_text':
				return 'LINE';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}

class WPML_Comparison_Table_Rows_Data extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'rows_data';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'column_text',
			'row_content'
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'column_text':
				return __( 'Comparison Table: Title', 'happy-elementor-addons' );
			case 'row_content':
				return __( 'Comparison Table: Show Content', 'happy-elementor-addons' );
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
			case 'column_text':
				return 'LINE';
			case 'row_content':
				return 'VISUAL';
			default:
				return '';
		}
	}
}

class WPML_Comparison_Table_Table_Btns extends \WPML_Elementor_Module_With_Items  {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'table_btns';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return [
			'btn_title',
			'link' => ['url']
		];
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'btn_title':
				return __( 'Comparison Table: Title', 'happy-elementor-addons' );
			case 'url':
				return __( 'Comparison Table: Link', 'happy-elementor-addons' );
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
			case 'btn_title':
				return 'LINE';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}
