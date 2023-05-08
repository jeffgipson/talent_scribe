<div class="hcf_box">
    <img class="second-box" src="<?php echo esc_url(plugins_url('/assets/header.png', __FILE__)); ?>" style="width: calc(100% + 24px);margin-left: -12px;margin-top: -6px;height: 27px;object-fit: cover;object-position: top;">
    <div class="seo-cont">
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
        <input type="hidden" name="hcf_image" id="hcf_image"
               value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'hcf_image', true)); ?>">


    </div>
    <h3 id="imageheading" style="display: none" >Select A Featured Image</h3>
    <div id="blog-images"></div>
    <div id="page">
        <span style="display: none" id="prev">< Previous Page</span> <span style="display: none" id="next">Next Page ></span>
    </div>
    <br>
    <br>
    <a href="https://www.pexels.com">Photos provided by Pexels</a>

</div>
