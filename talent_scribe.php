<?php
/*
Plugin Name: RW Talent Scribe
Plugin URI: https://recruiterswebsites.com
Description: This plugin will generate AI content for blog posts
Version: 1.0.7.2.7.18
Author: Recruiters Websites
Author URI: https://recruiterswebsites.com
License: GPLv2 or later
Text Domain: talentscribe
Update URI:  https://app.recruiterswebsites.com/plugin_updates/1
*/

// Add AI writing button to post content textarea

// include metabox php file
include plugin_dir_path(__FILE__) . 'metabox/metabox.php';

function add_rw_ts_button(){
//include css php file
include plugin_dir_path(__FILE__) . 'styles/style.php';
//include js php file
include plugin_dir_path(__FILE__) . 'js/js.php';

}
//write function to include css and js files
add_action('admin_enqueue_scripts', 'add_rw_ts_button');


// support for wpjoboard plugin
add_action('toplevel_page_wpjb-job', 'add_rw_ts_button');

function fontawesome_dashboard() {
    wp_enqueue_script( 'font-awesome', 'https://kit.fontawesome.com/3d74b65b72.js', array(), '6.0.0', true );
}

add_action('admin_init', 'fontawesome_dashboard');


// include the settings page
require_once plugin_dir_path(__FILE__) . 'settings/settings.php';


//include long form content php file
require_once plugin_dir_path(__FILE__) . 'long-form-content/long-form-content.php';

//force classic editor
add_filter('use_block_editor_for_post', '__return_false', 10);

//check github releases for updates to plugin
require 'plugin-update-checker.php';

//require beaverbuilder support
//require_once plugin_dir_path(__FILE__) . 'beaverbuilder/beaverbuilder.php';

