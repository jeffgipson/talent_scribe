<div class="lfc_box">
    <img class="second-box" src="<?php echo esc_url(plugins_url('../assets/header.png', __FILE__)); ?>"
         style="width: calc(100% + 24px);margin-left: -12px;margin-top: -6px;">
    <div class="long-form-cont">
        <input type="hidden" name="lfc_image" id="lfc_image"
               value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'lfc_image', true)); ?>">
        <p class="meta-options lfc_field">
            <label for="rich_text_editor">Step 1: Introduction</label><br>
            <?php wp_editor('', 'opener', array('textarea_name' => 'rich_text_editor_opener')); ?>
        </p>
        <p class="meta-options lfc_field">
            <label for="rich_text_editor">Step 2: Main body</label><br>
            <?php wp_editor('', 'main_body', array('textarea_name' => 'rich_text_editor_main_body')); ?>
        </p>
        <p class="meta-options lfc_field">
            <label for="rich_text_editor">Step 3: Conclusion</label><br>
            <?php wp_editor('', 'conclusion', array('textarea_name' => 'rich_text_editor_conclusion')); ?>
        </p>
        <p class="meta-options hcf_field">
            <label for="hcf_title">Meta Title</label>
            <input id="hcf_title"
                   type="text"
                   name="hcf_title"
                   value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'hcf_title', true)); ?>">
        </p>

        <p class="meta-options hcf_field">
            <label for="hcf_description">Meta Description</label>
            <textarea id="hcf_description"
                      type="text"
                      name="hcf_description"
            ><?php echo esc_attr(get_post_meta(get_the_ID(), 'hcf_description', true)); ?></textarea>
        </p>

    </div>
    <h3 id="imageheading" style="display: none">Select A Featured Image</h3>
    <div id="blog-images"></div>
    <div id="page">
        <span style="display: none" id="prev">< Previous Page</span> <span style="display: none"
                                                                           id="next">Next Page ></span>
    </div>
    <br>
    <br>
    <a href="https://www.pexels.com">Photos provided by Pexels</a>

</div>