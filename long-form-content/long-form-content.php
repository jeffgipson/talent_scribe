<?php
function long_form_content_post_type(){
    $labels = array(
        'name' => 'Long Form Content',
        'singular_name' => 'Long Form Content',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Long Form Content',
        'edit_item' => 'Edit Long Form Content',
        'new_item' => 'New Long Form Content',
        'view_item' => 'View Long Form Content',
        'search_items' => 'Search Long Form Content',
        'not_found' => 'No Long Form Content found',
        'not_found_in_trash' => 'No Long Form Content found in Trash',
        'parent_item_colon' => '',
    );
    $args = array(
        'label' => __('Long Form Content'),
        'labels' => $labels,
        'public' => true,
        'can_export' => true,
        'show_ui' => true,
        '_builtin' => false,
        'capability_type' => 'post',
        'menu_icon' =>  esc_url(plugins_url('../assets/rw_white.png', __FILE__)),
        'hierarchical' => false,
        'rewrite' => array("slug" => "/"),
        'supports' => array('title', 'thumbnail', 'custom-fields', 'editor'),
        'show_in_nav_menus' => true,
        'taxonomies' => array('long-form-content-category'),
    );
    register_post_type('long-form-content', $args);
}
add_action('init', 'long_form_content_post_type');
//create long-form-content-category taxonomy
function long_form_content_taxonomy()
{
    register_taxonomy(
        'long-form-content-category',
        'long-form-content',
        array(
            'hierarchical' => true,
            'label' => 'Categories',
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'long-form-content-category',
                'with_front' => false
            )
        )
    );
}
add_action('init', 'long_form_content_taxonomy');

//add meta boxes to long-form-content post type
function long_form_content_meta_boxes()
{
    add_meta_box('long-form-content-meta-box-id', 'Long Form Content Builder', 'lfc_display_callback', 'long-form-content', 'normal', 'high');
}
add_action('add_meta_boxes', 'long_form_content_meta_boxes');
//Register meta boxes
function lfc_register_meta_boxes()
{
    add_meta_box('lfc', __('Talent Scribe AI', 'lfc'), 'lfc_display_callback', 'long_form_content');
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



//use the pre_get_posts to include long-form-content in the main query
function lfc_include_post_types($query)
{
    if (is_home() && $query->is_main_query()) {
        $query->set('post_type', array('post', 'long-form-content'));
    }
    return $query;
}





//
//function rich_text_editor_callback( $post ) {
//    wp_editor(
//        get_post_meta( $post->ID, 'rich_text_editor', true ),
//        'rich_text_editor',
//        array(
//            'textarea_name' => 'rich_text_editor',
//            'tinymce' => true,
//        )
//    );
//}




