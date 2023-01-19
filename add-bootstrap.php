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
    ]
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
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '',
        ]
    );
    add_settings_field(
        ADD_BOOTSTRAP['fields']['version'],
        'Version',
        function() {
            printf(
                '<input type="text" id="%1$s" name="%1$s" value="%2$s">',
                ADD_BOOTSTRAP['fields']['version'],
                get_option(ADD_BOOTSTRAP['fields']['version'])
            );
        },
        ADD_BOOTSTRAP['options']['page_slug'],
        ADD_BOOTSTRAP['options']['section_slug']
    );
});
