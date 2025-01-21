<?php

namespace Happy_Addons\Appsero;

use stdClass;

/**
 * Appsero Updater
 *
 * This class will show new updates project
 */
class Updater {
    /**
     * Appsero\Client
     *
     * @var object
     */
    protected $client;

    /**
     * Object of Updater
     *
     * @var object
     */
    protected static $instance;

    /**
     * Cache key
     *
     * @var string
     */
    protected $cache_key;

    /**
     * Initialize the class
     *
     * @param Happy_Addons\Appsero\Client
     */
    public function __construct($client) {
        $this->client = $client;
        $this->cache_key = 'appsero_' . md5(sanitize_key($this->client->slug)) . '_version_info';

        add_filter('upgrader_pre_download', [$this , 'validate_plugin_update_url'], 10, 2);

        // Run hooks based on the client type
        if ($this->client->type === 'plugin') {
            $this->run_plugin_hooks();
        } elseif ($this->client->type === 'theme') {
            $this->run_theme_hooks();
        }
    }

    /**
     * Initialize the Updater
     *
     * @param object $client
     * @return object
     */
    public static function init($client) {
        if (!self::$instance) {
            self::$instance = new self($client);
        }

        return self::$instance;
    }

    /**
     * Set up WordPress filter hooks to get plugin updates
     *
     * @return void
     */
    public function run_plugin_hooks() {
        add_action('admin_init', [$this, 'check_plugin_update']);
    }

    /**
     * Set up WordPress filter hooks to get theme updates
     *
     * @return void
     */
    public function run_theme_hooks() {
        add_filter('pre_set_site_transient_update_themes', [$this, 'check_theme_update']);
    }

    /**
     * Check for plugin updates
     *
     * @param object $transient_data
     * @return object
     */
    public function check_plugin_update($transient_data) {
        global $pagenow;

        if (!is_object($transient_data)) {
            $transient_data = new stdClass();
        }

        if ('plugins.php' === $pagenow && is_multisite()) {
            return $transient_data;
        }

        if (!empty($transient_data->response) && !empty($transient_data->response[$this->client->basename])) {
            return $transient_data;
        }

        $version_info = $this->get_version_info();

        if (false !== $version_info && is_object($version_info) && isset($version_info->new_version)) {
            unset($version_info->sections);

            // If new version available, set to response
            if (version_compare($this->client->project_version, $version_info->new_version, '<')) {
                $required_plugins = isset($version_info->required_plugins) && (is_array($version_info->required_plugins) || is_object($version_info->required_plugins))
                ? $version_info->required_plugins
                : [];

                $warnings = $this->check_required_plugins($required_plugins);

                if (!empty($warnings)) {
                    $this->show_warning_notice($warnings);
                } else {
                    $transient_data->response[$this->client->basename] = $version_info;
                    add_filter('pre_set_site_transient_update_plugins', function () use ($transient_data) {
                        return $transient_data;
                    });
                    add_filter('plugins_api', [$this, 'plugins_api_filter'], 10, 3);
                }
            } else {
                $transient_data->no_update[$this->client->basename] = $version_info;
            }

            $transient_data->last_checked = time();
            $transient_data->checked[$this->client->basename] = $this->client->project_version;
        }

        return $transient_data;
    }

    /**
     * Get cached version info from the database
     *
     * @return object|bool
     */
    private function get_cached_version_info() {
        global $pagenow;

        // If updater page, fetch from API now
        if ('update-core.php' === $pagenow) {
            return false; // Force to fetch data
        }

        $value = get_transient($this->cache_key);

        if (!$value || !isset($value->name)) {
            return false; // Cache is expired
        }

        // Turn the icons into an array
        if (isset($value->icons)) {
            $value->icons = (array)$value->icons;
        }

        // Turn the banners into an array
        if (isset($value->banners)) {
            $value->banners = (array)$value->banners;
        }

        if (isset($value->sections)) {
            $value->sections = (array)$value->sections;
        }

        return $value;
    }

    /**
     * Set version info to the database
     *
     * @param object $value
     * @return void
     */
    private function set_cached_version_info($value) {
        if (!$value) {
            return;
        }

        set_transient($this->cache_key, $value, 3 * HOUR_IN_SECONDS);
    }

    /**
     * Get project latest version info from Appsero
     *
     * @return object|bool
     */
    private function get_project_latest_version() {
        $license = $this->client->license()->get_license();

        $params = [
            'version' => $this->client->project_version,
            'name' => sanitize_text_field($this->client->name),
            'slug' => sanitize_key($this->client->slug),
            'basename' => sanitize_text_field($this->client->basename),
            'license_key' => !empty($license) && isset($license['key']) ? sanitize_text_field($license['key']) : '',
        ];

        $route = 'v2/update/' . $this->client->hash . '/check';

        $response = $this->client->send_request($params, $route, true);

        if (is_wp_error($response)) {
            return false;
        }

        $response = json_decode(wp_remote_retrieve_body($response));

        if (!isset($response->slug)) {
            return false;
        }

        if (isset($response->icons)) {
            $response->icons = (array)$response->icons;
        }

        if (isset($response->banners)) {
            $response->banners = (array)$response->banners;
        }

        if (isset($response->sections)) {
            $response->sections = (array)$response->sections;
        }

        return $response;
    }

    /**
     * Update information on the "View version x.x details" page with custom data
     *
     * @param mixed  $data
     * @param string $action
     * @param object $args
     * @return object
     */
    public function plugins_api_filter($data, $action = '', $args = null) {
        if ($action !== 'plugin_information') {
            return $data;
        }

        if (!isset($args->slug) || ($args->slug !== $this->client->slug)) {
            return $data;
        }

        return $this->get_version_info();
    }

    /**
     * Check for theme updates
     *
     * @param object $transient_data
     * @return object
     */
    public function check_theme_update($transient_data) {
        global $pagenow;

        if (!is_object($transient_data)) {
            $transient_data = new stdClass();
        }

        if ('themes.php' === $pagenow && is_multisite()) {
            return $transient_data;
        }

        if (!empty($transient_data->response) && !empty($transient_data->response[$this->client->slug])) {
            return $transient_data;
        }

        $version_info = $this->get_version_info();

        if (false !== $version_info && is_object($version_info) && isset($version_info->new_version)) {
            // If new version available, set to response
            if (version_compare($this->client->project_version, $version_info->new_version, '<')) {
                $transient_data->response[$this->client->slug] = (array)$version_info;
            } else {
                // If new version is not available, set to no_update
                $transient_data->no_update[$this->client->slug] = (array)$version_info;
            }

            $transient_data->last_checked = time();
            $transient_data->checked[$this->client->slug] = $this->client->project_version;
        }

        return $transient_data;
    }

    /**
     * Get version information
     *
     * @return object|bool
     */
    private function get_version_info() {
        $version_info = $this->get_cached_version_info();

        if (false === $version_info) {
            $version_info = $this->get_project_latest_version();
            $this->set_cached_version_info($version_info);
        }

        return $version_info;
    }

    /**
     * Check required plugins
     *
     * @param array $required_plugins
     * @return array
     */
    private function check_required_plugins($required_plugins = []) {
        $warnings = [];

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $installed_plugins = get_plugins();

        foreach ($required_plugins as $plugin_slug => $required_version) {
            $plugin_file = $this->get_plugin_file($plugin_slug, $installed_plugins);
            $plugin_name = isset($installed_plugins[$plugin_file]) ? $installed_plugins[$plugin_file]['Name'] : $plugin_slug;

            if (!$plugin_file) {
                $warnings[] = $this->client->__trans( sprintf('%s (version %s) is required but not installed.', $plugin_name, $required_version) );
            } elseif (!is_plugin_active($plugin_file)) {
                $warnings[] = $this->client->__trans( sprintf('%s (version %s) is required but not active.', $plugin_name, $required_version) );
            } else {
                $installed_version = $installed_plugins[$plugin_file]['Version'];
                if (version_compare($installed_version, $required_version, '<')) {
                    $warnings[] = $this->client->__trans( sprintf('%s requires version %s, but %s is installed. Please update %s.', $plugin_name, $required_version, $installed_version, $plugin_name) );
                }
            }
        }

        return $warnings;
    }

    /**
     * Get plugin file from slug
     *
     * @param string $plugin_slug
     * @param array  $installed_plugins
     * @return string|null
     */
    private function get_plugin_file($plugin_slug, $installed_plugins) {
        foreach ($installed_plugins as $plugin_file => $plugin_info) {
            if (strpos($plugin_file, $plugin_slug . '/') === 0) {
                return $plugin_file;
            }
        }

        return null;
    }

    /**
     * Show warning notice for required plugins
     *
     * @param array $warnings
     * @return void
     */
    public function show_warning_notice($warnings) {
        add_action("after_plugin_row_{$this->client->basename}", function ($plugin_file, $plugin_data, $status) use ($warnings) {
            $this->add_custom_plugin_row($plugin_file, $plugin_data, $status, $warnings);
        }, 10, 3);
    }

    /**
     * Add custom plugin row with warnings
     *
     * @param string $plugin_file
     * @param array  $plugin_data
     * @param string $status
     * @param array  $warnings
     * @return void
     */
    public function add_custom_plugin_row($plugin_file, $plugin_data, $status, $warnings) {
        $plugin_slug = dirname($plugin_file);
        $wp_list_table = _get_list_table('WP_Plugins_List_Table', ['screen' => get_current_screen()]);
        $column_count = esc_attr($wp_list_table->get_column_count());

        $version_info = $this->get_version_info();
        $new_version = $version_info->new_version;

        printf(
            '<tr class="plugin-update-tr active" id="%s-update" data-slug="%s" data-plugin="%s">' .
            '<td colspan="%s" class="plugin-update colspanchange">' .
            '<div class="update-message notice inline notice-warning notice-alt">' .
            '<p>%s</p>' .
            '<p>%s</p>' .
            '</div></td></tr>',
            esc_attr($plugin_slug),
            esc_attr($plugin_slug),
            esc_attr($plugin_file),
            $column_count,
            esc_html(sprintf(
                __('%s %s is available. %s', 'happy-elementor-addons'),
                $plugin_data['Name'],
                $new_version,
                __('Please resolve the following issues to update the plugin:', 'happy-elementor-addons')
            )),
            esc_html(implode('&nbsp;', $warnings))
        );
    }

    public function validate_plugin_update_url($reply, $package) {
        // Local file or remote?
        if ( ! preg_match( '!^(http|https|ftp)://!i', $package ) && file_exists( $package ) ) {
            return $reply; // Must be a local file.
        }

        $response = wp_remote_get($package);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {

            $error_message = is_wp_error($response)
                ? $response->get_error_message()
                : wp_remote_retrieve_body($response);

            if (empty($error_message)) {
                $error_message = wp_remote_retrieve_response_message($response);
            }

            return new \WP_Error('invalid_update_url', $error_message);
        }

        return $reply;
    }
}
