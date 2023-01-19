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

function render_bootstap_settings_page() {
    echo <<<HTML
    <div>
        <h1>Bootstrap Settings</h1>
        <p>Edit Bootstrap settings</p>
    </div>
HTML;
}

add_action('admin_menu', function() {
    add_theme_page(
        'Bootstrap Settings',
        'Bootstrap',
        'edit_theme_options',
        'bootstrap-settings',
        'render_bootstap_settings_page'
    );
});
