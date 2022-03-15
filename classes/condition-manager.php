<?php

namespace Happy_Addons\Elementor;

defined('ABSPATH') || die();

use Exception;
use Happy_Addons\Elementor\Conditions_Cache;

class Condition_Manager {
    public static $instance = null;

    private $cache;
    private $all_conds;
    private $location_cache = [];

    public function __construct() {
        $this->cache = new Conditions_Cache();

        add_action('wp_ajax_ha_condition_autocomplete', [$this, 'process_autocomplete']);
        add_action('wp_ajax_ha_condition_update', [$this, 'process_condition_update']);
        add_action('wp_ajax_ha_cond_template_type', [$this, 'ha_get_template_type']);

        $this->process_condition();
    }

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function initial_conditions() {
        $conditions = [
            'general' => [
                'title' => __('General', 'happy-elementor-addons'),
                'all_label' => __('',''),
                'is_pro' => false,
            ],
            'archive' => [
                'title' => __('Archives', 'happy-elementor-addons'),
                'is_pro' => false,
            ],
            'singular' => [
                'title' => __('Singular', 'happy-elementor-addons'),
                'is_pro' => false,
            ],
        ];

        return $conditions;
    }

    private function archive_conditions() {
        $conditions = [
            'all' => [
                'title' => __('All Archives', 'happy-elementor-addons'),
                'is_pro' => false,
            ],
            'author' => [
                'title' => __('Author Archive', 'happy-elementor-addons'),
                'is_pro' => true,
            ],
            'date' => [
                'title' => __('Date Archive', 'happy-elementor-addons'),
                'is_pro' => true,
            ],
            'search' => [
                'title' => __('Search Results', 'happy-elementor-addons'),
                'is_pro' => true,
            ],
            'post_archive' => [
                'title' => __('Posts Archive', 'happy-elementor-addons'),
                'is_pro' => true,
            ],
        ];

        return apply_filters('happyaddons/conditions/archive', $conditions);
    }


    private function singular_conditions() {
        $conditions = [
            'all' => [
                'title' => __('All Singular', 'happy-elementor-addons'),
                'is_pro' => false,
            ],
            'front_page' => [
                'title' => __('Front Page', 'happy-elementor-addons'),
                'is_pro' => true,
            ],
            'post_group' => [
                'title' => __('Posts', 'happy-elementor-addons'),
                'type' => 'condition-group',
                'conditions' => [
                    'post' => [
                        'title' => __('Posts', 'happy-elementor-addons'),
                        'is_pro' => false,
                    ],
                    'in_category' => [
                        'title' => __('In Category', 'happy-elementor-addons'),
                        'is_pro' => false,
                    ],
                    'in_category_children' => [
                        'title' => __('In Child Categories', 'happy-elementor-addons'),
                        'is_pro' => false,
                    ],
                    'in_post_tag' => [
                        'title' => __('In Tag', 'happy-elementor-addons'),
                        'is_pro' => false,
                    ],
                    'post_by_author' => [
                        'title' => __('Posts By Author', 'happy-elementor-addons'),
                        'is_pro' => false,
                    ],
                ]
            ],
            'page_group' => [
                'title' => __('Page', 'happy-elementor-addons'),
                'type' => 'condition-group',
                'is_pro' => true,
                'conditions' => [
                    'page' => [
                        'title' => __('Pages', 'happy-elementor-addons'),
                        'is_pro' => false,
                    ],
                    'page_by_author' => [
                        'title' => __('Pages By Author', 'happy-elementor-addons'),
                        'is_pro' => false,
                    ],
                ]
            ],
            'child_of' => [
                'title' => __('Direct Child Of', 'happy-elementor-addons'),
                'is_pro' => true,
            ],
            'any_child_of' => [
                'title' => __('Any Child Of', 'happy-elementor-addons'),
                'is_pro' => true,
            ],
            'by_author' => [
                'title' => __('By Author', 'happy-elementor-addons'),
                'is_pro' => true,
            ],
            'not_found404' => [
                'title' => __('404 Page', 'happy-elementor-addons'),
                'is_pro' => true,
            ],
        ];

        return $conditions;
    }

    protected function process_condition() {
        $conditions = array(
            'name' => array_keys($this->initial_conditions()),
            'sub_name' => array(
                'archive' => array_keys($this->archive_conditions()),
                'singular' => $this->flatten_singular_array($this->singular_conditions())
            )
        );

        $this->all_conds = $conditions;
    }

    protected function flatten_singular_array($array) {
        $postSubCond = array_keys($array['post_group']['conditions']);
        $pageSubCond = array_keys($array['page_group']['conditions']);

        unset($array['post_group']);
        unset($array['page_group']);

        $keys = array_keys($array);
        $keys = array_merge($keys, $postSubCond, $pageSubCond);

        return $keys;
    }

    protected function validate_reqeust() {
        $nonce = !empty($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';

        if (!wp_verify_nonce($nonce, 'ha_editor_nonce')) {
            throw new Exception('Invalid request');
        }

        if (!current_user_can('edit_posts')) {
            throw new Exception('Unauthorized request');
        }
    }

    public function ha_get_template_type() {
        try {
            $this->validate_reqeust();

            $id = isset($_REQUEST['post_id']) ? $_REQUEST['post_id'] : null;
            if ($id) {
                $tpl_type = get_post_meta($id, '_ha_library_type', true);
                wp_send_json_success($tpl_type);
            } else {
                wp_send_json_error();
            }
            //_ha_display_cond;
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    public function process_condition_update() {
        try {
            $this->validate_reqeust();
            $templateID = isset($_REQUEST['template_id']) ? $_REQUEST['template_id'] : null;
            $conditions = isset($_REQUEST['conds']) ? $_REQUEST['conds'] : [];

            if ($templateID) {
                $cond = update_post_meta($templateID, '_ha_display_cond', $conditions);
                $updates = get_post_meta($templateID, '_ha_display_cond');

                if ($cond) {
                    $this->cache->regenerate();
                    wp_send_json_success($updates);
                } else {
                    wp_send_json_error();
                }
            } else {

                wp_send_json_error();
            }

            //_ha_display_cond;
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    public function process_autocomplete() {
        try {
            $this->validate_reqeust();

            $object_type = !empty($_REQUEST['object_type']) ? trim($_REQUEST['object_type']) : '';

            if (!in_array($object_type, ['post', 'tax', 'author', 'archive', 'singular'], true)) {
                throw new Exception('Invalid object type');
            }

            $response = [];

            if ($object_type === 'post') {
                $response = $this->process_post();
            }

            if ($object_type === 'tax') {
                $response = $this->process_term();
            }

            if ($object_type === 'singular') {
                $response = $this->singular_conditions();
            }

            if ($object_type === 'archive') {
                $response = $this->archive_conditions();
            }

            wp_send_json_success($response);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    private function process_post() {
        $post_type    = !empty($_REQUEST['object_term']) ? $_REQUEST['object_term'] : 'any';
        $query_term   = !empty($_REQUEST['q']) ? $_REQUEST['q'] : '';

        $args = [
            'post_type'        => $post_type,
            'suppress_filters' => false,
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'ASC',
            'post_status'      => 'publish',
        ];

        if ($query_term) {
            $args['s'] = $query_term;
        }

        $posts = get_posts($args);

        if (empty($posts)) {
            return [];
        }

        $out = [];

        foreach ($posts as $post) {
            $out["{$post->ID}"] = esc_html($post->post_title);
        }

        return $out;
    }

    public function process_term() {
        $term_taxonomy = !empty($_REQUEST['object_term']) ? $_REQUEST['object_term'] : '';
        $query_term    = !empty($_REQUEST['q']) ? $_REQUEST['q'] : '';

        $prefix = "Categories: ";

        if ($term_taxonomy == 'post_tag') {
            $prefix = "Tags: ";
        }

        if (empty($term_taxonomy)) {
            throw new Exception('Invalid taxonomy');
        }

        $args = [
            'taxonomy'   => $term_taxonomy,
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'number'     => -1,
        ];

        if ($query_term) {
            $args['search'] = $query_term;
        }

        $terms = get_terms($args);

        if (empty($terms) || is_wp_error($terms)) {
            return [];
        }

        $out = [];

        foreach ($terms as $term) {
            $title = !empty($query_term) ? $prefix . $term->name : $prefix . $term->name;
            $out["{$term->term_id}"] = $title;
        }

        return $out;
    }

    public function get_theme_templates_ids($location) {
        $templates = $this->get_location_templates($location);
        return $templates;
    }

    public function get_location_templates($location) {
        $tpl_priority = [];

        $conditions_groups = $this->cache->get_by_location($location);

        if (empty($conditions_groups)) {
            return $tpl_priority;
        }

        $excludes = [];
        foreach ($conditions_groups as $template_id => $conditions) {

            foreach ($conditions as $condition) {
                foreach ($conditions as $condition) {
                    $parsed_condition = $this->parse_condition($condition);

                    $include = $parsed_condition['type'];
                    $name = $parsed_condition['name'];
                    $sub_name = $parsed_condition['sub_name'];
                    $sub_id = $parsed_condition['sub_id'];

                    $is_include = 'include' === $include;
                    // $condition_instance = $this->get_condition($name);

                    // if (!$condition_instance) {
                    //     continue;
                    // }

                    $condition_pass = $this->check_cond_name($name);
                    $sub_condition_instance = null;

                    if ($condition_pass && $sub_name) {
                        $condition_pass = $this->check_cond_sub_name($sub_name, $parsed_condition);
                    }

                    if ($condition_pass) {

                        $post_status = get_post_status($template_id);

                        if ('publish' !== $post_status) {
                            continue;
                        }

                        if ($is_include) {
                            $tpl_priority[$template_id] = $this->get_condition_priority($name, $sub_name, $sub_id);
                        } else {
                            $excludes[] = $template_id;
                        }
                    }
                } // End foreach().
            }
        }

        foreach ($excludes as $exclude_id) {
            unset($tpl_priority[$exclude_id]);
        }

        asort($tpl_priority);

        return $tpl_priority;
    }

    private function get_condition($cond_name) {
        return $this->all_conds[$cond_name];
    }

    private function check_cond_name($name) {
        $conds = $this->get_condition('name');
        return in_array($name, $conds);
    }

    private function get_condition_priority($name, $sub_name, $sub_id) {
        $priority = 100;
        if ($name !== 'general') {
            $priority = $this->get_priority_by_key($name);

            if ($sub_name !== 'all') {
                $sub_priority = $this->get_priority_by_key($sub_name);
                if ($sub_priority < $priority) {
                    $priority = $sub_priority;
                }

                $priority -= 10;

                if ($sub_id) {
                    $priority -= 10;
                }
            }
        }

        return $priority;
    }

    private function get_priority_by_key($key) {
        $priority = 100;
        switch ($key) {
            case 'archive':
                return 80;
                break;
            case 'author':
            case 'date':
            case 'search':
            case 'post_archive':
                return 70;
                break;
            case 'singular':
                return 60;
                break;
            case 'post':
            case 'in_category':
            case 'in_category_children':
            case 'in_post_tag':
            case 'post_by_author':
            case 'page':
            case 'page_by_author':
            case 'child_of':
            case 'any_child_of':
            case 'by_author':
                return 40;
                break;
            case 'front_page':
                return 30;
                break;
            case 'not_found404':
                return 20;
                break;
        }
        return $priority;
    }

    private function check_cond_sub_name($sub_name, $parsed_condition) {
        $name = $parsed_condition['name'];

        // if ($name == 'archive') {
        //     if ($sub_name == 'all') {
        //         $is_archive = is_archive() || is_home() || is_search();
        //         // WooCommerce is handled by `woocommerce` module.
        //         if ($is_archive && class_exists('woocommerce') && is_woocommerce()) {
        //             $is_archive = false;
        //         }

        //         return $is_archive;
        //     } else {
        //         return apply_filters('happyaddons/conditions/check/cond_sub_id', $sub_id, $parsed_condition);
        //     }
        // }

        // if ($name == 'singular') {

        //     if ($sub_name == 'all') {
        //         return true;
        //     } else {
        //         return apply_filters('happyaddons/conditions/check/cond_sub_id', $sub_id, $parsed_condition);
        //     }
        // }


        if ($sub_name == 'all') {
            if ($name == 'archive') {
                $is_archive = is_archive() || is_home() || is_search();
                // WooCommerce is handled by `woocommerce` module.
                if ($is_archive && class_exists('woocommerce') && is_woocommerce()) {
                    $is_archive = false;
                }
                return $is_archive;
            }
            if ($name == 'singular') {
                return (is_singular() && !is_embed()) || is_404();
            }
            return false;
        }
        return apply_filters('happyaddons/conditions/check/cond_sub_id', $sub_name, $parsed_condition);
    }

    // private function check_cond_sub_id($sub_id) {
    // }

    protected function parse_condition($condition) {
        list($type, $name, $sub_name, $sub_id) = array_pad(explode('/', $condition), 4, '');

        return compact('type', 'name', 'sub_name', 'sub_id');
    }

    public function get_documents_for_location($location) {
        if (isset($this->location_cache[$location])) {
            return $this->location_cache[$location];
        }

        $theme_templates_ids = $this->get_theme_templates_ids( $location );

        $documents = [];

        foreach ( $theme_templates_ids as $theme_template_id => $priority ) {
            $documents[ ] = $theme_template_id;
		}

        return $documents;
    }
}

Condition_Manager::instance();
