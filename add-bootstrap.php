<?php
/**
 * Plugin Name:         Add Bootstrap
 * 
 * Description:         Add Bootstrap CSS and JS to your site.
 * Author:              JimJ92120
 * Author URI:          https://github.com/JimJ92120
 * 
 * Version:             0.1.0
 * Requires at least:   5.9
 * Requires PHP:        7.4
 */

define('ADD_BOOTSTRAP', [
    'options_page' => [
        'page_slug' => 'bootstrap-settings',
        'section_slug' => 'bootstrap_settings_section',
        'group_slug' => 'bootstap_settings',
    ],
    'fields' => [
        'version' => 'bootstrap_version',
        'enable_css' => 'bootstrap_enable_css',
        'enable_js' => 'bootstrap_enable_js',
        'css_dependencies' => 'bootstrap_css_dependencies',
    ],
    'versions' => [
        '3.3.7',
        '3.4.1',
        // 4
        '4.4.1',
        '4.5.3',
        '4.6.2',
        // 5
        '5.0.2',
        '5.1.3',
        '5.2.3',
    ],
]);

add_action('admin_menu', function() {
    add_theme_page(
        'Bootstrap Settings',
        'Bootstrap',
        'edit_theme_options',
        ADD_BOOTSTRAP['options_page']['page_slug'],
        function () {
            echo '<div>
                <h1>'. get_admin_page_title() . '</h1>
                <div id="bootstrap-settings"></div>
            </div>';
        }
    );
});

function add_bootstrap_register_settings_fields() {
    register_setting(
        ADD_BOOTSTRAP['options_page']['group_slug'],
        ADD_BOOTSTRAP['fields']['version'],
        [
            'type' => 'string',
            'sanitize_callback' => function($version) {
                return in_array($version, ADD_BOOTSTRAP['versions'])
                    ? $version
                    : null;
            },
            'default' => '',
            'show_in_rest' => true,
        ]
    );

    register_setting(
        ADD_BOOTSTRAP['options_page']['group_slug'],
        ADD_BOOTSTRAP['fields']['enable_css'],
        [
            'type' => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'default' => true,
            'show_in_rest' => true,
        ]
    );

    register_setting(
        ADD_BOOTSTRAP['options_page']['group_slug'],
        ADD_BOOTSTRAP['fields']['enable_js'],
        [
            'type' => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'default' => false,
            'show_in_rest' => true,
        ]
    );

    register_setting(
        ADD_BOOTSTRAP['options_page']['group_slug'],
        ADD_BOOTSTRAP['fields']['css_dependencies'],
        [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
            'show_in_rest' => true,
        ]
    );
}

add_action('admin_init', function () {
    add_bootstrap_register_settings_fields();
});

add_action('rest_api_init', function () {
    add_bootstrap_register_settings_fields();
});

add_action('admin_enqueue_scripts', function () {
    $current_screen = get_current_screen();
    $options_page_id = 'appearance_page_' . ADD_BOOTSTRAP['options_page']['page_slug'];

    if ($current_screen instanceof \WP_Screen
        &&  $options_page_id === $current_screen->id
    ) {
        $assets_file = require_once(plugin_dir_path(__FILE__ ) . 'build/index.asset.php');

        wp_enqueue_script(
            'bootstrap-settings-admin-js',
            plugin_dir_url(__FILE__) . 'build/index.js',
            $assets_file['dependencies'],
            $assets_file['version'],
            true
        );
        wp_localize_script(
            'bootstrap-settings-admin-js',
            'bootstrap_settings',
            [
                'fields' => ADD_BOOTSTRAP['fields'],
                'versions' => ADD_BOOTSTRAP['versions'],
            ]
        );

        wp_enqueue_style('wp-components');
    }
});

add_action('wp_enqueue_scripts', function () {
    $version = get_option(ADD_BOOTSTRAP['fields']['version']);

    if (in_array($version, ADD_BOOTSTRAP['versions'])) {
        $is_css_enabled = get_option(ADD_BOOTSTRAP['fields']['enable_css']);
        $css_dependencies = get_option(ADD_BOOTSTRAP['fields']['css_dependencies']);
        
        if (empty($css_dependencies)) {
            $css_dependencies = [];
        } else {
            $css_dependencies = explode(", ", $css_dependencies);
        }

        if ($is_css_enabled) {
            wp_enqueue_style(
                'bootstrap-css',
                'https://cdn.jsdelivr.net/npm/bootstrap@'. $version . '/dist/css/bootstrap.min.css',
                $css_dependencies,
                $version
            );
        }

        $is_js_enabled = get_option(ADD_BOOTSTRAP['fields']['enable_js']);
        if ($is_js_enabled) {
            $is_bootstrap_4_or_above = version_compare($version, '4.0.0', '>=');
            $is_bootstrap_5_or_above = version_compare($version, '5.0.0', '>=');

            $script_url = $is_bootstrap_4_or_above
                ? 'https://cdn.jsdelivr.net/npm/bootstrap@' . $version . '/dist/js/bootstrap.bundle.min.js'
                : 'https://cdn.jsdelivr.net/npm/bootstrap@' . $version . '/dist/js/bootstrap.min.js';
            $required_dependencies = [];

            if (!$is_bootstrap_5_or_above) {
                $jquery_url = $is_bootstrap_4_or_above
                    ? 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js'
                    : 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js';

                wp_enqueue_script(
                    'bootstrap-jquery',
                    $jquery_url,
                    [],
                    null,
                    true
                );

                $required_dependencies[] = 'bootstrap-jquery';
            }

            wp_enqueue_script(
                'bootstrap-js',
                $script_url,
                $required_dependencies,
                $version
            );
        }
    }
});
