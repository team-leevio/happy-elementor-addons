<?php

namespace Happy_Addons\Elementor;

defined('ABSPATH') || die();

class Theme_Builder
{

    const CPT = 'ha_library';
    const TEMPLATE_TYPE = ['header' => 'Header', 'footer' => 'Footer', 'single' => 'Single Post'];
    const TAB_BASE = "edit.php?post_type=ha_library";

    public function __construct()
    {

        add_filter('query_vars', [$this, 'add_query_vars_filter']);
        add_filter('views_edit-' . self::CPT, [$this, 'admin_print_tabs']);
        add_action('init', [$this, 'create_themebuilder_cpt'], 0);
        add_action('admin_menu', [$this, 'modify_menu'], 90);

        add_action('pre_get_posts', [$this, 'add_role_filter_to_posts_query']);

        // Admin Actions
		add_action( 'admin_action_ha_library_new_post', [ $this, 'admin_action_new_post' ] );

        add_action('current_screen', function () {
            $current_screen = get_current_screen();

            // Only in elementor based pages.
            if (!$current_screen || !strstr($current_screen->post_type, 'ha_library')) {
                return;
            }

            add_action('in_admin_header', function () {
                $this->render_admin_top_bar();
            });

            // Allow plugins to add their templates on admin_head.
            add_action('admin_head', [$this, 'add_new_template_template']);
        });
    }

    public function add_query_vars_filter($vars)
    {
        $vars[] = "ha_library_type";
        return $vars;
    }

    public static function init()
    {
        new Theme_Builder();

        add_action('manage_' . self::CPT . '_posts_columns', [__CLASS__, 'admin_columns_headers']);
        add_action('manage_' . self::CPT . '_posts_custom_column', [__CLASS__, 'admin_columns_content'], 10, 2);
        // }
    }

    // Register Custom Post Type Theme Builder
    public function create_themebuilder_cpt()
    {

        $labels = array(
            'name' => _x('Theme Builder', 'Post Type General Name', 'textdomain'),
            'singular_name' => _x('Theme Builder', 'Post Type Singular Name', 'textdomain'),
            'menu_name' => _x('Theme Builder', 'Admin Menu text', 'textdomain'),
            'name_admin_bar' => _x('Theme Builder', 'Add New on Toolbar', 'textdomain'),
            'archives' => __('Theme Builder Archives', 'textdomain'),
            'attributes' => __('Theme Builder Attributes', 'textdomain'),
            'parent_item_colon' => __('Parent Theme Builder:', 'textdomain'),
            'all_items' => __('All Theme Builder', 'textdomain'),
            'add_new_item' => __('Add New Theme Builder', 'textdomain'),
            'add_new' => __('Add New', 'textdomain'),
            'new_item' => __('New Theme Builder', 'textdomain'),
            'edit_item' => __('Edit Theme Builder', 'textdomain'),
            'update_item' => __('Update Theme Builder', 'textdomain'),
            'view_item' => __('View Theme Builder', 'textdomain'),
            'view_items' => __('View Theme Builder', 'textdomain'),
            'search_items' => __('Search Theme Builder', 'textdomain'),
            'not_found' => __('Not found', 'textdomain'),
            'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
            'featured_image' => __('Featured Image', 'textdomain'),
            'set_featured_image' => __('Set featured image', 'textdomain'),
            'remove_featured_image' => __('Remove featured image', 'textdomain'),
            'use_featured_image' => __('Use as featured image', 'textdomain'),
            'insert_into_item' => __('Insert into Theme Builder', 'textdomain'),
            'uploaded_to_this_item' => __('Uploaded to this Theme Builder', 'textdomain'),
            'items_list' => __('Theme Builder list', 'textdomain'),
            'items_list_navigation' => __('Theme Builder list navigation', 'textdomain'),
            'filter_items_list' => __('Filter Theme Builder list', 'textdomain'),
        );
        $args = array(
            'label' => __('Theme Builder', 'textdomain'),
            'description' => __('', 'textdomain'),
            'labels' => $labels,
            'supports' => array('title','elementor'),
            'taxonomies' => array(),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => '',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'hierarchical' => false,
            'exclude_from_search' => true,
            'capability_type' => 'page',
        );
        register_post_type('ha_library', $args);
    }


    public function modify_menu()
    {
        add_submenu_page(
            Dashboard::PAGE_SLUG, // Parent slug
            'Theme Builder', // Page title
            'Theme Builder', // Menu title
            'manage_options', // Capability
            'edit.php?post_type=ha_library',  // Slug
            false // Function
        );
    }

    public function add_role_filter_to_posts_query($query)
    {
        /**
         * No use on front
         * pre get posts runs everywhere
         * even if you test $pagenow after, bail as soon as possible
         */
        if (!is_admin()) {
            return;
        }

        global $pagenow;

        /**
         * use $query parameter instead of global $post_type
         */
        if ('edit.php' === $pagenow && self::CPT === $query->query['post_type']) {

            if (isset($_GET['ha_library_type'])) {
                $meta_query = array(
                    array(
                        'key' => '_ha_library_type',
                        'value' => $_GET['ha_library_type'],
                        'compare' => '=='
                    )
                );
                $query->set( 'meta_query', $meta_query );
                $query->set( 'meta_key', '_ha_library_type' );
            }
        }

    }

    private function render_admin_top_bar()
    {
?>
        <div id="ha-admin-top-bar-root">
            <div class="ha-admin-top-bar">
                <div class="ha-admin-top-bar__main-area">
                    <div class="ha-admin-top-bar__heading">
                        <div class="ha-admin-top-bar__heading-logo">
                            <svg version="1.1" x="0px" y="0px" viewBox="0 0 110 118" enable-background="new 0 0 110 118" xml:space="preserve">
                                <g>
                                    <g>
                                        <path fill="#ffffff" d="M101.1,27.8c1,0,1.9-0.2,2.9-0.2c1.9-0.2,3.1-1.9,2.9-3.6c-0.2-1.9-1.9-3.2-3.5-2.9
			c-12.8,1.5-24.9-6.3-28.8-18.7c-0.6-1.7-2.5-2.7-4.1-2.1c-1.6,0.6-2.7,2.5-2.1,4.2C72.9,18.7,86.5,28.4,101.1,27.8z" />
                                        <path fill="#ffffff" d="M105.9,40.6c-1-2.3-3.3-3.8-5.8-3.8c-3.3,0.2-6.8,0-10.3-0.8C75.4,33,64.5,22.7,59.5,9.7
			c-0.8-2.3-3.3-4-5.8-3.8C27,6.5,3.7,26.9,0.4,55.5c-2.9,26.3,13,51.5,37.5,59.7c31.7,10.5,64.5-9.5,71.1-42.1
			C111.2,61.8,109.8,50.5,105.9,40.6z M63.9,44.8c0.4-1.7,2.1-2.9,3.9-2.5l13.6,2.9c1.6,0.4,2.9,2.1,2.5,4c-0.4,1.7-2.1,2.9-3.9,2.5
			l-13.6-2.9C64.7,48.2,63.4,46.5,63.9,44.8z M33.8,40.4c0.8-4.2,4.9-6.9,9.1-6.1c4.1,0.8,6.8,5,6,9.3c-0.8,4.2-4.9,6.9-9.1,6.1
			C35.6,48.8,33,44.6,33.8,40.4z M86.5,79.3C79.7,95.7,61.6,105,43.9,99.1c-13.2-4.4-22.5-16.8-23.7-30.5C20,65,22.9,62,26.4,62.7
			l56,9.3C85.7,72.6,87.8,76.1,86.5,79.3z" />
                                        <path fill="#ffffff" d="M58.9,83.9c-6.8-1.5-13.4,1.3-17.1,6.3c-0.8,1.1-0.4,2.7,0.8,3.2c2.1,1.1,4.5,1.9,7,2.5
			c6.6,1.5,13.2,0.2,18.5-2.7c1.2-0.6,1.4-2.3,0.6-3.4C66.3,86.9,62.8,84.8,58.9,83.9z" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <h1 class="ha-admin-top-bar__heading-title">Theme Builder</h1>
                    </div>
                    <div class="ha-admin-top-bar__main-area-buttons">
                        <a class="page-title-action" id="ha-template-library-add-new" href="http://ha.test/wp-admin/post-new.php?post_type=elementor_library">Add New</a>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    public static function admin_columns_headers($posts_columns)
    {
        $offset = 2;

        $posts_columns = array_slice($posts_columns, 0, $offset, true) + [
            'type' => __('Type', 'elementor-pro'),
            'condition' => __('Conditions', 'elementor-pro'),
        ] + array_slice($posts_columns, $offset, null, true);

        return $posts_columns;
    }

    public static function admin_columns_content($column_name, $post_id)
    {
        if('type' === $column_name){
            $type = get_post_meta($post_id,'_ha_library_type',true);

            echo ucfirst($type);
        }
        if ('condition' === $column_name) {
            //return;
            $instances = [];
    
            if (!empty($instances)) {
                echo implode('<br />', $instances);
            } else {
                echo __('None', 'elementor-pro');
            }
        }

    }

    public function admin_print_tabs($views)
    {
        $getActive = get_query_var('ha_library_type');
        // var_dump($getActive);
    ?>
        <div id="happyaddon-template-library-tabs-wrapper" class="nav-tab-wrapper">
            <a class="nav-tab <?= !($getActive) ? 'nav-tab-active' : ''; ?>" href="<?= admin_url(self::TAB_BASE) ?>">All</a>
            <?php
            foreach (self::TEMPLATE_TYPE as $key => $value) {
                $active = ($getActive == $key) ? 'nav-tab-active' : '';
                $admin_filter_url = admin_url(self::TAB_BASE . '&ha_library_type=' . $key);
                echo '<a class="nav-tab ' . $active . '" href="' . $admin_filter_url . '">' . $value . '</a>';
            }
            ?>
        </div>
        <br>
<?php
return $views;
    }

    /**
     * @since 2.3.0
     * @access public
     */
    public function add_new_template_template()
    {
        ob_start();
        include(HAPPY_ADDONS_DIR_PATH . 'templates/admin/new-template.php');
        $template = ob_get_clean();
        echo $template;
    }


    /**
	 * Admin action new post.
	 *
	 * When a new post action is fired the title is set to 'Elementor' and the post ID.
	 *
	 * Fired by `admin_action_elementor_new_post` action.
	 *
	 * @since 1.9.0
	 * @access public
	 */
	public function admin_action_new_post() {
		check_admin_referer( 'ha_library_new_post_action' );

		if ( empty( $_GET['post_type'] ) ) {
			$post_type = 'post';
		} else {
			$post_type = $_GET['post_type'];
		}

        $post_type_object = get_post_type_object( $post_type );

		if ( ! current_user_can( $post_type_object->cap->edit_posts ) ) {
			return;
		}

		if ( empty( $_GET['template_type'] ) ) {
			$type = 'post';
		} else {
			$type = sanitize_text_field( $_GET['template_type'] );
		}

		$post_data = isset( $_GET['post_data'] ) ? $_GET['post_data'] : [];

		$meta = [];

		/**
		 * Create new post meta data.
		 *
		 * Filters the meta data of any new post created.
		 *
		 * @since 2.0.0
		 *
		 * @param array $meta Post meta data.
		 */
		$meta = apply_filters( 'elementor/admin/create_new_post/meta', $meta );

		$post_data['post_type'] = $post_type;

		$document = $this->create_template_document( $type, $post_data, $meta );

		if ( is_wp_error( $document ) ) {
			wp_die( $document ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		wp_redirect( $this->get_edit_url($document) );

        // var_dump($this->get_edit_url($document));

		die;
	}

    protected function create_template_document($type, $post_data, $meta){
        if ( empty( $post_data['post_title'] ) ) {
			$post_data['post_title'] = esc_html__( 'Elementor', 'elementor' );
			$update_title = true;
		}

		$meta_data['_elementor_edit_mode'] = 'builder';

		// Save the type as-is for plugins that hooked at `wp_insert_post`.
		$meta_data[ '_ha_library_type' ]  = $type;
		$meta_data[ '_wp_page_template' ] = 'elementor_canvas';

		$post_data['meta_input'] = $meta_data;

		$post_id = wp_insert_post( $post_data );

		if ( ! empty( $update_title ) ) {
			$post_data['ID'] = $post_id;
			$post_data['post_title'] .= ' #' . $post_id;

			// The meta doesn't need update.
			unset( $post_data['meta_input'] );

			wp_update_post( $post_data );
		}

        return $post_id;
    }

    public function get_edit_url($id) {
		$url = add_query_arg(
			[
				'post' => $id,
				'action' => 'elementor',
			],
			admin_url( 'post.php' )
		);

		return $url;
	}
}

Theme_Builder::init();
