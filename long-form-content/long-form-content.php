<?php

//add meta boxes to long-form-content post type
function long_form_content_meta_boxes()
{
    add_meta_box('long-form-content-meta-box-id', 'Long Form Content Builder', 'lfc_display_callback', 'long-form-content', 'normal', 'high');
}
add_action('add_meta_boxes', 'long_form_content_meta_boxes');
//Register meta boxes
function lfc_register_meta_boxes()
{
    add_meta_box('lfc', __('Talent Scribe AI', 'lfc'), 'lfc_display_callback', 'post');
}

add_action('add_meta_boxes', 'lfc_register_meta_boxes');

//Meta box display callback
function lfc_display_callback($post)
{
    include plugin_dir_path(__FILE__) . './form.php';
}
//Save meta box content
function lfc_save_meta_box($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($parent_id = wp_is_post_revision($post_id)) {
        $post_id = $parent_id;
    }
    $fields = [
        'lfc_title',
        'lfc_description',
        'lfc_image',
        'opener',
        'main_body',
        'conclusion',
    ];
    foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}

function lfc_js_css()
{
//require style.php file
require_once plugin_dir_path(__FILE__) . './style.php';
//require js.php file
require_once plugin_dir_path(__FILE__) . './js.php';
}
add_action('admin_enqueue_scripts', 'lfc_js_css');

//add sub_menu_page to posts menu
function lfc_add_submenu_page()
{
    add_submenu_page(
        'edit.php',
        'Content Builder',
        'Content Builder',
        'manage_options',
        'long-form-content',
        'lfc_submenu_page_callback',
        2
    );
}
add_action('admin_menu', 'lfc_add_submenu_page');

//sub_menu_page callback function
function lfc_submenu_page_callback()
{
    ?>
<script>
    //redirect to post new long-form-content page
    window.location.href = "<?php echo admin_url('post-new.php?page_type=long-form-content'); ?>";
</script>
<?php   }





