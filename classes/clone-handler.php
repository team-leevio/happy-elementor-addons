<?php
namespace Happy_Addons\Elementor;

class Clone_Handler {

    const ACTION = 'ha_duplicate_thing';

    public static function init() {
        add_action( 'admin_action_' . self::ACTION, [ __CLASS__, 'duplicate_thing' ] );
    }

    public static function duplicate_thing() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }

        $_uri = $_SERVER['REQUEST_URI'];

        // Resolve finder clone request issue
        if ( stripos( $_uri, '&amp;' ) !== false ) {
            $_uri = html_entity_decode( $_uri );
            $_uri = parse_url( $_uri, PHP_URL_QUERY );
            $valid_args = ['_wpnonce', 'post_id', 'ref'];
            parse_str( $_uri, $args );

            if ( ! empty( $args ) && is_array( $args ) ) {
                foreach ( $args as $key => $val ) {
                    if ( in_array( $key, $valid_args, true ) ) {
                        $_GET[ $key ] = $val;
                    }
                }
            }
        }

        $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
        $post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
        $ref = isset( $_GET['ref'] ) ? $_GET['ref'] : '';

        if ( ! wp_verify_nonce( $nonce, self::ACTION ) ) {
            return;
        }

        if ( is_null( ( $post = get_post( $post_id ) ) ) ) {
            return;
        }

        $post = sanitize_post( $post, 'db' );
        $duplicated_post_id = self::duplicate_post( $post );
        $redirect = add_query_arg( [ 'post_type' => $post->post_type ], admin_url( 'edit.php' ) );

        if ( ! is_wp_error( $duplicated_post_id ) ) {
            self::duplicate_taxonomies( $post, $duplicated_post_id );
            self::duplicate_meta_entries( $post, $duplicated_post_id );

            if ( $ref === 'editor' ) {
                $document = ha_elementor()->documents->get( $duplicated_post_id );
                $redirect = $document->get_edit_url();
            }
        }

        wp_safe_redirect( $redirect );
        die();
    }

    public static function get_url( $post_id, $ref = '' ) {
        return wp_nonce_url(
            add_query_arg(
                [
                    'action' => self::ACTION,
                    'post_id' => $post_id,
                    'ref' => $ref,
                ],
                admin_url( 'admin.php' )
            ),
            self::ACTION
        );
    }

    /**
     * @param $old_post
     * @return int $dulicated post id
     */
    protected static function duplicate_post( $post ) {
        $current_user = wp_get_current_user();

        $duplicated_post_args = [
            'post_author'    => $current_user->ID,
            'post_title'     => $post->post_title,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_parent'    => $post->post_parent,
            'post_status'    => 'draft',
            'ping_status'    => $post->ping_status,
            'comment_status' => $post->comment_status,
            'post_password'  => $post->post_password,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order,
        ];

        return wp_insert_post( $duplicated_post_args );
    }

    protected static function duplicate_taxonomies( $post, $duplicated_post_id ) {
        $taxonomies = get_object_taxonomies( $post->post_type );
        if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
            foreach ( $taxonomies as $taxonomy ) {
                $terms = wp_get_object_terms( $post->ID, $taxonomy, [ 'fields' => 'slugs' ] );
                wp_set_object_terms( $duplicated_post_id, $terms, $taxonomy, false );
            }
        }
    }

    protected static function duplicate_meta_entries( $post, $duplicated_post_id ) {
        global $wpdb;

        $entries = $wpdb->get_results(
            $wpdb->prepare( "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d", $post->ID )
        );

        if ( is_array( $entries ) ) {
            $query = "INSERT INTO {$wpdb->postmeta} ( post_id, meta_key, meta_value ) VALUES ";
            $_records = [];
            foreach ( $entries as $entry ) {
                $_value = wp_slash( $entry->meta_value );
                $_records[] = "( $duplicated_post_id, '{$entry->meta_key}', '{$_value}' )";
            }
            $query .= implode( ', ', $_records ) . ';';
            $wpdb->query( $query  );
        }
    }

}

Clone_Handler::init();
