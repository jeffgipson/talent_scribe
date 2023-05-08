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
include plugin_dir_path(__FILE__) . '/metabox/metabox.php';

function add_rw_ts_button()
{

//include css php file
include plugin_dir_path(__FILE__) . '/styles/style.php';
//include js php file
include plugin_dir_path(__FILE__) . '/js/js.php';

}

add_action('admin_footer-post.php', 'add_rw_ts_button');
add_action('admin_footer-post-new.php', 'add_rw_ts_button');

// include the settings page
require_once plugin_dir_path(__FILE__) . '/settings/settings.php';