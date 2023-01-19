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
    'options' => [
        'page_slug' => 'bootstrap-settings',
        'section_slug' => 'bootstrap_settings_section',
        'group_slug' => 'bootstap_settings',
    ],
    'fields' => [
        'version' => 'bootstrap_version',
        'enable_css' => 'bootstrap_enable_css',
        'enable_js' => 'bootstrap_enable_js',
    ],
    'versions' => [
        '3.3.7',
        '4.6.2',
        '5.0.2',
    ],
]);

add_action('admin_menu', function() {
    add_theme_page(
        'Bootstrap Settings',
        'Bootstrap',
        'edit_theme_options',
        ADD_BOOTSTRAP['options']['page_slug'],
        function () {
            echo '<div>
                <h1>'. get_admin_page_title() . '</h1>
                <form method="post" action="options.php">';
                    settings_fields(ADD_BOOTSTRAP['options']['group_slug']);
                    do_settings_sections(ADD_BOOTSTRAP['options']['page_slug']);
                    submit_button();
                echo '</form>
            </div>';
        }
    );
});

add_action('admin_init', function() {
    add_settings_section(
        ADD_BOOTSTRAP['options']['section_slug'],
        'Settings',
        '',
        ADD_BOOTSTRAP['options']['page_slug']
    );

    register_setting(
        ADD_BOOTSTRAP['options']['group_slug'],
        ADD_BOOTSTRAP['fields']['version'],
        [
            'type' => 'string',
            'sanitize_callback' => function($version) {
                return in_array($version, ADD_BOOTSTRAP['versions'])
                    ? $version
                    : null;
            },
            'default' => '',
        ]
    );
    add_settings_field(
        ADD_BOOTSTRAP['fields']['version'],
        'Version',
        function() {
            $current_version = get_option(ADD_BOOTSTRAP['fields']['version']);

            printf(
                '<select id="%1$s" name="%1$s" value="%2$s">',
                ADD_BOOTSTRAP['fields']['version'],
                $current_version
            );
            echo '<option>Select a version</option>';

            foreach(ADD_BOOTSTRAP['versions'] as $allowed_version) {
                printf(
                    '<option value="%1$s" %2$s>%1$s</option>',
                    $allowed_version,
                    $allowed_version === $current_version ? 'selected' : ''
                );
            }

            echo '</select>';
        },
        ADD_BOOTSTRAP['options']['page_slug'],
        ADD_BOOTSTRAP['options']['section_slug']
    );

    register_setting(
        ADD_BOOTSTRAP['options']['group_slug'],
        ADD_BOOTSTRAP['fields']['enable_css'],
        [
            'type' => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'default' => true,
        ]
    );
    add_settings_field(
        ADD_BOOTSTRAP['fields']['enable_css'],
        'Enable CSS',
        function() {
            $option_value = get_option(ADD_BOOTSTRAP['fields']['enable_css']);

            printf(
                '<input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s>',
                ADD_BOOTSTRAP['fields']['enable_css'],
                $option_value ? 'checked' : ''
            );
        },
        ADD_BOOTSTRAP['options']['page_slug'],
        ADD_BOOTSTRAP['options']['section_slug']
    );

    register_setting(
        ADD_BOOTSTRAP['options']['group_slug'],
        ADD_BOOTSTRAP['fields']['enable_js'],
        [
            'type' => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'default' => false,
        ]
    );
    add_settings_field(
        ADD_BOOTSTRAP['fields']['enable_js'],
        'Enable JS',
        function() {
            $option_value = get_option(ADD_BOOTSTRAP['fields']['enable_js']);

            printf(
                '<input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s>',
                ADD_BOOTSTRAP['fields']['enable_js'],
                $option_value ? 'checked' : ''
            );
        },
        ADD_BOOTSTRAP['options']['page_slug'],
        ADD_BOOTSTRAP['options']['section_slug']
    );
});

add_action('wp_enqueue_scripts', function () {
    $version = get_option(ADD_BOOTSTRAP['fields']['version']);

    if (in_array($version, ADD_BOOTSTRAP['versions'])) {
        $is_css_enabled = get_option(ADD_BOOTSTRAP['fields']['enable_css']);
        if ($is_css_enabled) {
            wp_enqueue_style(
                'bootstrap-css',
                'https://cdn.jsdelivr.net/npm/bootstrap@'. $version . '/dist/css/bootstrap.min.css',
                [],
                $version
            );
        }

        $is_js_enabled = get_option(ADD_BOOTSTRAP['fields']['enable_js']);
        if ($is_js_enabled) {
            wp_enqueue_script(
                'bootstrap-js',
                'https://cdn.jsdelivr.net/npm/bootstrap@' . $version . '/dist/js/bootstrap.bundle.min.js',
                [],
                $version
            );
        }
    }
});
