<?php
/**
 * Register meta boxes.
 */
function hcf_register_meta_boxes()
{
    add_meta_box('hcf-1', __('Talent Scribe AI', 'hcf'), 'hcf_display_callback', 'post');
}

add_action('add_meta_boxes', 'hcf_register_meta_boxes');

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function hcf_display_callback($post)
{
    include plugin_dir_path(__FILE__) . './form.php';
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function hcf_save_meta_box($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($parent_id = wp_is_post_revision($post_id)) {
        $post_id = $parent_id;
    }
    $fields = [
        'hcf_title',
        'hcf_description',
        'hcf_image',
    ];
    foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));

        }
//        if hcf_image is set, then we need to set the featured image
        if (array_key_exists('hcf_image', $_POST)) {

            $image_url = $_POST['hcf_image']; // Define the image URL here
            $image_name = 'wp-header-logo.png';
            $upload_dir = wp_upload_dir(); // Set upload folder
            $image_data = file_get_contents($image_url); // Get image data
            $unique_file_name = wp_unique_filename($upload_dir['path'], $image_name); // Generate unique name
            $filename = basename($unique_file_name); // Create image file name

// Check folder permission and define file location
            if (wp_mkdir_p($upload_dir['path'])) {
                $file = $upload_dir['path'] . '/' . $filename;
            } else {
                $file = $upload_dir['basedir'] . '/' . $filename;
            }

// Create the image  file on the server
            file_put_contents($file, $image_data);

// Check image file type
            $wp_filetype = wp_check_filetype($filename, null);

// Set attachment data
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );

// Create the attachment
            $attach_id = wp_insert_attachment($attachment, $file, $post_id);

// Include image.php
            require_once(ABSPATH . 'wp-admin/includes/image.php');

// Define attachment metadata
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);

// Assign metadata to attachment
            wp_update_attachment_metadata($attach_id, $attach_data);

// And finally assign featured image to post
            set_post_thumbnail($post_id, $attach_id);

        }
    }
}

add_action('save_post', 'hcf_save_meta_box');
