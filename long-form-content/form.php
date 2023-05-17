<?php if (isset($_GET['page_type']) && !empty($_GET['page_type'])) {
$page = $_GET['page_type'];
} else {
$page = 'POST';
}

if ($page == 'long-form-content') {
?>

<div id="lfcboc" class="lfc_box">
    <nav id="navbar">
        <a class="nav-item idea-nav" href="#"><i class="fa-solid fa-circle-1"></i> Idea</a>
        <i class="fa-regular fa-chevron-right"></i>
        <a class="nav-item title-nav" href="#"><i class="fa-solid fa-circle-2"></i> Title</a>
        <i class="fa-regular fa-chevron-right"></i>
        <a class="nav-item intro-nav" href="#"><i class="fa-solid fa-circle-3"></i> Intro</a>
        <i class="fa-regular fa-chevron-right"></i>
        <a class="nav-item body-nav" href="#"><i class="fa-solid fa-circle-4"></i> Body</a>
        <i class="fa-regular fa-chevron-right"></i>
        <a class="nav-item outro-nav" href="#"><i class="fa-solid fa-circle-5"></i> Conclusion</a>
        <i class="fa-regular fa-chevron-right"></i>
        <a class="nav-item seo-nav" href="#"><i class="fa-solid fa-circle-6"></i> SEO</a>
        <i class="fa-regular fa-chevron-right"></i>
        <a class="nav-item image-nav" href="#imageheading"><i class="fa-solid fa-circle-7"></i> Select an Image</a>
    </nav>
    <img class="second-box" src="<?php echo esc_url(plugins_url('../assets/header.png', __FILE__)); ?>"
         style="width: calc(100% + 24px);margin-left: -12px;margin-top: -6px;">
    <div class="long-form-cont">

        <div class="step1" id="newtitle">
            <label for="rich_title_area">Step 1: Generate an Idea</label><br>
        </div>
        <input type="hidden" name="lfc_image" id="lfc_image"
               value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'lfc_image', true)); ?>">
        <p class="meta-options lfc_field step2">
            <?php wp_editor('', 'opener', array('textarea_name' => 'rich_text_editor_opener')); ?>
        </p>
        <p class="meta-options lfc_field step3">
            <?php wp_editor('', 'main_body', array('textarea_name' => 'rich_text_editor_main_body')); ?>
        </p>
        <p class="meta-options lfc_field step4">
            <?php wp_editor('', 'conclusion', array('textarea_name' => 'rich_text_editor_conclusion')); ?>
        </p>
<!--        <p class="meta-options hcf_field step5">-->
<!--            <label for="hcf_title">Meta Title</label>-->
<!--            <input id="hcf_title"-->
<!--                   type="text"-->
<!--                   name="hcf_title"-->
<!--                   value="--><?php //echo esc_attr(get_post_meta(get_the_ID(), 'hcf_title', true)); ?><!--">-->
<!--        </p>-->
<!---->
<!--        <p class="meta-options hcf_field step6">-->
<!--            <label for="hcf_description">Meta Description</label>-->
<!--            <textarea id="hcf_description"-->
<!--                      type="text"-->
<!--                      name="hcf_description"-->
<!--            >--><?php //echo esc_attr(get_post_meta(get_the_ID(), 'hcf_description', true)); ?><!--</textarea>-->
<!--        </p>-->
<!---->
<!--    </div>-->
<!--    <h3 id="imageheading" style="display: none">Select A Featured Image</h3>-->
<!--    <div id="blog-images"></div>-->
<!--    <div id="page">-->
<!--        <span style="display: none" id="prev">< Previous Page</span> <span style="display: none"-->
<!--                                                                           id="next">Next Page ></span>-->
<!--    </div>-->
<!--    <br>-->
<!--    <br>-->
<!--    <a href="https://www.pexels.com">Photos provided by Pexels</a>-->

</div>
<?php
}
