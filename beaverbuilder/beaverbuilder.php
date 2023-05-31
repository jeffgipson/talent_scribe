<?php
function my_text_button_enqueue_scripts() {
wp_enqueue_script('my-text-button-script', plugins_url('/beaverbuilder/js/my-text-button.js', __FILE__), array('jquery', 'fl-builder'), '1.0', true);

}

add_action('admin_enqueue_scripts', 'my_text_button_enqueue_scripts');
