<?php

namespace Happy_Addons\Elementor;

defined('ABSPATH') || die();

use Exception;
use Happy_Addons\Elementor\Conditions_Cache;
class Condition_Manager {

    private static $cache;

    public static function init() {
        self::$cache = new Conditions_Cache();
        
        add_action('wp_ajax_ha_condition_autocomplete', [__CLASS__, 'process_autocomplete']);
        add_action('wp_ajax_ha_condition_update', [__CLASS__, 'process_condition_update']);
        add_action('wp_ajax_ha_cond_template_type', [__CLASS__, 'ha_get_template_type']);
    }

    private function initial_conditions() {
        $conditions = [
            'general' => [
                'title' => __('Entire Site', 'happy-elementor-addons'),
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

    private static function archive_conditions() {
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


    private static function singular_conditions() {
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

    protected static function validate_reqeust() {
        $nonce = !empty($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';

        if (!wp_verify_nonce($nonce, 'ha_editor_nonce')) {
            throw new Exception('Invalid request');
        }

        if (!current_user_can('edit_posts')) {
            throw new Exception('Unauthorized request');
        }
    }

    public static function ha_get_template_type() {
        try {
            self::validate_reqeust();

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

    public static function process_condition_update() {
        try {
            self::validate_reqeust();
            $templateID = isset($_REQUEST['template_id']) ? $_REQUEST['template_id'] : null;
            $conditions = isset($_REQUEST['conds']) ? $_REQUEST['conds'] : [];

            if ($templateID) {
                $cond = update_post_meta($templateID, '_ha_display_cond', $conditions);
                $updates = get_post_meta($templateID, '_ha_display_cond');

                if ($cond) {
                    self::$cache->regenerate();
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

    public static function process_autocomplete() {
        try {
            self::validate_reqeust();

            $object_type = !empty($_REQUEST['object_type']) ? trim($_REQUEST['object_type']) : '';

            if (!in_array($object_type, ['post', 'tax', 'author', 'archive', 'singular'], true)) {
                throw new Exception('Invalid object type');
            }

            $response = [];

            if ($object_type === 'post') {
                $response = self::process_post();
            }

            if ($object_type === 'tax') {
                $response = self::process_term();
            }

            if ($object_type === 'singular') {
                $response = self::singular_conditions();
            }

            if ($object_type === 'archive') {
                $response = self::archive_conditions();
            }

            wp_send_json_success($response);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    private static function process_post() {
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

    public static function process_term() {
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
}

Condition_Manager::init();
