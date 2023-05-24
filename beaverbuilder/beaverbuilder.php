<?php
add_action('wp_footer', 'add_textarea_button');

function add_textarea_button() {
    if (!class_exists('FLBuilderModel')) {
        return;
    }

    $current_post_id = FLBuilderModel::get_post_id();
    if (FLBuilderModel::is_builder_enabled()) {
        ?>

        <script>
            console.log('beaverbuilder.php');
            jQuery(document).ready(function() {
                var buttonHtml = '<button type="button" class="insert-emoji-btn">Insert Emoji</button>';

                jQuery('.fl-builder-content[data-post-id="' + <?php echo $current_post_id; ?> + '"] textarea').each(function() {
                    jQuery(this).after(buttonHtml);
                });

                jQuery('.insert-emoji-btn').on('click', function() {
                    alert('Emoji button clicked!');
                    // add your function here to insert the emoji
                });
            });
        </script>

        <?php
    }
}