<?php
/*
Plugin Name: RW Talent Scribe
Plugin URI: https://recruiterswebsites.com
Description: This plugin will generate AI content for blog posts
Version: 1.0
Author: Recruiters Websites
Author URI: https://recruiterswebsites.com
License: GPLv2 or later
Text Domain: talentscribe
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


// include the settings page
require_once plugin_dir_path(__FILE__) . 'settings/settings.php';


//include long form content php file
require_once plugin_dir_path(__FILE__) . 'long-form-content/long-form-content.php';

