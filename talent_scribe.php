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

function add_rw_ts_button()
{


    ?>
    <style>
        div#mceu_34 {
            display: none;
        }

        button#rw-ts-btn {
            background: linear-gradient(90deg, rgb(52 137 191) 0%, rgb(104 77 148) 100%);
            color: #fff;
            border: none;
            cursor: pointer;
        }

        #rw-ts-btn img {
            filter: brightness(0) invert(1);
        }

        div#rw-ts-prompt {
            padding: 10px 20px 0px 0px;
        }

        div#rwgpt {
            background-image: url(<?php echo esc_url( plugins_url('/assets/background.png', __FILE__ ) ); ?>);
            background-size: cover;
        }

        #rw-ts-prompt {
            margin: 5px 48px;
        }

        #blog-images img {
            width: 100%;
            object-fit: cover;
            aspect-ratio: 16 / 9;
            cursor: pointer;
        }

        #blog-images {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 20px;
            width: 100%;
        }

        .seo-cont {
            margin-bottom: 30px;
        }

        .seo-cont p {
            display: block;
        }

        .seo-cont p input {
            width: 100%;
        }

        .seo-cont label {
            display: block;
        }

        .seo-cont textarea {
            width: 100%;
            min-height: 60px;
        }

        .hcf_field {
            display: contents;
        }

        .img-cont {
            position: relative;
        }

        .image-check {
            position: absolute;
            content: "";
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            background-image: url("<?php echo esc_url(plugins_url('/assets/checkmark.png', __FILE__)); ?>");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 50px;
        }

        #next {
            background: linear-gradient(90deg, rgb(52 137 191) 0%, rgb(104 77 148) 100%);
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 5px;
            float: right;
        }

        #prev {
            background: linear-gradient(90deg, rgb(52 137 191) 0%, rgb(104 77 148) 100%);
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 5px;
        }
    </style>
    <script>

        var servicekey = '';


        //While we wait from the response show a preloading image with a whole screen overlay
        jQuery(document).ready(function () {

            //on click of the image, set the hidden field value to the image url
            jQuery(document).on('click', '#blog-images img', function () {
                console.log('clicked on image')
                var image_url = jQuery(this).attr('src');
                jQuery('#hcf_image').val(image_url);


                //prepend <div class="image-check"></div> to the image
                jQuery(this).closest('div').prepend('<div class="image-check"></div>');

                //remove all other images overlay and styling
                jQuery('#blog-images img').not(this).closest('div').find('.image-check').remove();

            });


            //make ajax call to lic server to check if the license is valid
            jQuery.ajax({
                url: 'https://app.recruiterswebsites.com/api/v1/licenses/?key=<?php echo get_option('rw-ts_text_apikey'); ?>&usage=true',
                type: 'GET',
                dataType: 'json',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer <?php echo get_option('rw-ts_text_apikey'); ?>'
                },
                success: function (data) {
                    // console.log(data)

                    //if the license is valid
                    if (data.status == 'Active') {
                        // console.log(data.status)
                        // console.log(data.service_key)
                        servicekey = data.service_key


                        //console.log(servicekey)

                    }
                    //if the license is invalid
                    else {
                        console.log('invalid')
                    }
                },
            })

            jQuery('body').append('<div id="rw-ts-loading" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 9999;"><img src="<?php echo esc_url(plugins_url('/assets/Writing.png', __FILE__)); ?>" style="width: 250px; position: absolute;top: 37%;left: calc(50% - 125px) ;transform: translate(-50%, -50%);"></div>')
            //make the image pulse
            jQuery('#rw-ts-loading img').addClass('pulse')
            //create the pulse class in the css
            jQuery('head').append('<style>.pulse {animation: pulse 1s infinite;}</style>')
            //create the pulse animation
            jQuery('head').append('<style>@keyframes pulse {0% {transform: scale(1);}50% {transform: scale(1.1);}100% {transform: scale(1);}}</style>')
            // set the image size

            jQuery('body').append('<div id="rw-ts-loading-seo" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 9999;"><img src="<?php echo esc_url(plugins_url('/assets/SEO.png', __FILE__)); ?> "style="width: 250px; position: absolute;top: 37%;left: calc(50% - 125px) ;transform: translate(-50%, -50%);"></div>')
            //make the image pulse
            jQuery('#rw-ts-loading-seo img').addClass('pulse')


        })


        var delayInMilliseconds = 500; //1 second

        setTimeout(function () {

            jQuery(document).ready(function () {
                //create a form for custom prompt with checkbox to enable it
                // jQuery('#content').after('<div id="rw-ts-prompt" style="display: none;"><label for="rw-ts-prompt-checkbox">Use Custom Prompt</label><input type="checkbox" id="rw-ts-prompt-checkbox" name="rw-ts-prompt-checkbox" value="rw-ts-prompt-checkbox"><input type="text" id="rw-ts-prompt-text" name="rw-ts-prompt-text" value="" placeholder="Enter Custom Prompt"></div>')
                //create the button
                jQuery('#content').after('' +
                    '<div style="" id="rwgpt">' +
                    '<img src="<?php echo esc_url(plugins_url('/assets/header.png', __FILE__)); ?>" style="width: 100%;">' +
                    '<div style="" id="rw-ts-prompt">' +
                    '<form id="rwchatform">' +
                    '<div id="prompt_options">' +
                    '<h3>Choose a prompt</h3>' +
                    <?php

                    if (get_post_type() == 'job_listing') {

                    ?>
                    //create a checkbox for a job description rewrite prompt
                    '<input type="checkbox" id="rw-ts-job-description-rewrite" name="rw-ts-job-description-rewrite" value=""><label for="rw-ts-job-description-rewrite">Job Description Rewrite</label><br>' +
                    //create a checkbox for job description creation based on the job title and the location
                    '<input type="checkbox" id="rw-ts-job-description-creation" name="rw-ts-job-description-creation" value=""><label for="rw-ts-job-description-creation">Job Description Creation(Based on job title and location)</label><br>' +
                    <?php
                    } else {
                    //do other stuff


                    $custom_prompts = get_option('rw-ts_custom_prompts');
                    if ($custom_prompts){
                    ?>
                    '<p style="text-decoration:underline;">My prompts</p>' +
                    //loop over custom prompts and create a checkbox for each one
                    <?php

                    $i = 0;
                    $custom_prompts = explode(";; ", $custom_prompts);
                    if ($custom_prompts) {
                        foreach ($custom_prompts as $custom_prompt) {
                            echo '\'<input type="checkbox" class="custom_prompt_check" id="rw-ts-prompt-checkbox-' . $i . '" name="' . $custom_prompt . '" value="' . $custom_prompt . '"><label for="rw-ts-prompt-checkbox">' . $custom_prompt . '</label><br>\'+';
                            $i++;
                        }

                    }
                    }
                    else{
                    ?>
                    '<p><span class="dashicons dashicons-info"></span> Add saved prompts in the <a href="<?php echo admin_url('admin.php?page=rw-ts-settings'); ?>">Settings page.</a></p>' +


                    <?php }}

                    ?>
                    '<p style="text-decoration:underline;">Advanced Mode</p>' +
                    '<input type="checkbox" id="rw-ts-prompt-checkbox" name="rw-ts-prompt-checkbox" value="rw-ts-prompt-checkbox">' +
                    '<label for="rw-ts-prompt-checkbox">Use Custom Prompt</label>' +
                    '</div>' +
                    '<p id="custom_prompt_directions" style="display:none;">Use <span id="entertitle" style="color: #080808;cursor:pointer;background: #ddd9d9;padding: 3px;border: 1px solid #bebebe;border-radius: 5px;">{{title}}</span> or <span id="entercontent" style="color: #080808;cursor:pointer;background: #ddd9d9;padding: 3px;border: 1px solid #bebebe;border-radius: 5px;">{{content}}</span> as variables in your prompt</p>' +
                    '<input style="display: none; width: 100%;" type="text" id="rw-ts-prompt-text" name="rw-ts-prompt-text" value="" placeholder="Enter Custom Prompt"><br><br>' +
                    '</div>' +
                    '<button id="rw-ts-btn" style="margin:10px; padding:12px;" type="button">Generate AI Content</button><br><br>' +
                    // '<input type="button" id="rw-ts-btn2" style="padding:12px;" value="Generate AI Content">' +
                    '</form>' +
                    '</div>')

                //on click of text variable add it to the prompt text field
                jQuery('#entertitle').on('click', function () {
                    jQuery('#rw-ts-prompt-text').val(jQuery('#rw-ts-prompt-text').val() + '{{title}}')
                })
                jQuery('#entercontent').on('click', function () {
                    jQuery('#rw-ts-prompt-text').val(jQuery('#rw-ts-prompt-text').val() + '{{content}}')
                })


                //When a checkbox in rw-ts-prompt div is checked remove the other checks and uncheck the other checkboxes
                jQuery('#prompt_options input[type="checkbox"]').on('change', function () {
                    jQuery('#prompt_options input[type="checkbox"]').not(this).prop('checked', false);
                    //if the checkbox is use custom prompt or is not checked hide the prompt text field
                    if (jQuery('#rw-ts-prompt-checkbox').is(':checked')) {
                        jQuery('#rw-ts-prompt-text').show()
                        jQuery('#custom_prompt_directions').show()
                    } else {
                        jQuery('#rw-ts-prompt-text').hide()
                        jQuery('#custom_prompt_directions').hide()
                    }
                });

                //when the checkbox is clicked show the prompt text field
                jQuery('#rw-ts-prompt-checkbox').on('click', function () {
                    if (jQuery('#rw-ts-prompt-checkbox').is(':checked')) {
                        jQuery('#rw-ts-prompt-text').show()
                    } else {
                        jQuery('#rw-ts-prompt-text').hide()
                    }
                })

                //add logo to button in front of text inside the button
                jQuery('#rw-ts-btn').prepend('<img src="<?php echo esc_url(plugins_url('/assets/RW.png', __FILE__)); ?>" style="width: 20px;margin-right: 10px;">')
                jQuery('#rw-ts-btn').on('click', function () {
                    var url = "https://api.openai.com/v1/completions";
                    if ('<?php echo str_replace(' ', '', get_option('rw-ts_language_model')); ?>' == 'gpt-3.5-turbo') {
                        url = "https://api.openai.com/v1/chat/completions";
                    } else {
                        url = "https://api.openai.com/v1/completions";
                    }
                    console.log('clicked')

                    var prompt = ""
                    var title = jQuery('#title').val();
                    var content = jQuery('#content').val();


                    //if job description rewrite is checked use the job description rewrite prompt
                    if (jQuery('#rw-ts-job-description-rewrite').is(':checked')) {
                        prompt = "Rewrite this job description add more details and make it more appealing to candidates. Description " + content + " Title: " + title + " Location: " + jQuery('#_job_location').val() + " .The first paragraph should be a summary of the location with details about how its a great place to live and work then write in detail about the job and what the candidate will be doing. Include well formatted lists of requirements and qualifications. Include a call to action at the end of the job description. "
                    }
                    //if job description creation is checked use the job description rewrite prompt
                    else if (jQuery('#rw-ts-job-description-creation').is(':checked')) {
                        prompt = "Write a job description for " + title + " Location: " + jQuery('#_job_location').val() + ".The first paragraph should be a summary of the location with details about how its a great place to live and work then write in detail about the job and what the candidate will be doing. Include well formatted lists of requirements and qualifications. Include a call to action at the end of the job description. "
                    } else if (jQuery('#rw-ts-prompt-checkbox').is(':checked')) {
                        prompt = jQuery('#rw-ts-prompt-text').val()
                    } else if (jQuery('.custom_prompt_check:checkbox:checked')[0]) {
                        prompt = jQuery('.custom_prompt_check:checkbox:checked')[0].value
                    } else {
                        <?php if(get_post_type() == 'job_listing'){ ?>
                        prompt = "Executive recruiter, also known as executive headhunter, is a recruiting professional who focuses on finding candidates for hard to fill positions within companies in a variety of industries. Now that you know what a recruiter is pretend you are one and write a detailed job description about this " + title
                        " job located in" + jQuery('#_job_location').val()
                        <?php }
                        else { ?>
                        prompt = "Executive recruiter, also known as executive headhunter, is a recruiting professional who focuses on finding candidates for hard to fill positions within companies in a variety of industries. Now that you know what a recruiter is pretend you are one and write a blog post about " + title
                        <?php } ?>
                    }


                //string replace {{title}} with the title of the post
                prompt = prompt.replace("{{title}}", title)
                //string replace {{title}} with the title of the post
                prompt = prompt.replace("{{content}}", content)

                jQuery('#rw-ts-loading').show()

                jQuery.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    contentType: "application/json",
                    headers: {
                        Authorization: "Bearer " + servicekey,
                        contentType: "application/json",
                    },


                    data: JSON.stringify({
                        //if rw-ts-prompt-checkbox is checked then use it

                        prompt: prompt,
                        temperature: <?php echo get_option('rw-ts_rewriter_temperature'); ?>,
                        //model: "<?php //echo str_replace(' ', '', get_option('rw-ts_language_model')); ?>//",
                        model: "text-davinci-003",
                        max_tokens: <?php echo get_option('rw-ts_rewriter_max_tokens'); ?>
                    }),
                    success: function (response) {

                        jQuery.ajax({
                            url: 'https://app.recruiterswebsites.com/api/v1/licenses/?key=<?php echo get_option('rw-ts_text_apikey'); ?>',
                            type: 'GET',
                            dataType: 'json',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer <?php echo get_option('rw-ts_text_apikey'); ?>'
                            },
                            success: function (data) {
                                // console.log(data)

                            },
                        })


                        jQuery('#content-html').click()

                        var contentstring = response.choices[0].text
                        if (contentstring.startsWith('.\n\n')) {
                            contentstring = contentstring.slice('.\n\n'.length);
                        }

                        if (contentstring.startsWith('\n\n')) {
                            contentstring = contentstring.slice('\n\n'.length);
                        }


                        jQuery('.wp-editor-area').val(contentstring)
                        // jQuery('.wp-editor-area').val(response.choices[0].text.replace('.\n\n', '').replace('\n\n', ''))
                        // jQuery('#rw-ts-btn').after('<button id="rw-ts-btn-more">Add More Content</button>')
                        //Todo add another call to add more content
                        jQuery('#content-tmce').click()
                        //Hide the loading screen
                        jQuery('#rw-ts-loading').hide()
                        // change the button text to regenerate instead of generate
                        jQuery('#rw-ts-btn').text('Regenerate AI Content')
                        jQuery('#rw-ts-btn').prepend('<img src="<?php echo esc_url(plugins_url('/assets/RW.png', __FILE__)); ?>" style="width: 20px;margin-right: 10px;">')


                        //if yoast id is on the page then run another call to get seo content
                        if (jQuery("#wpseo-metabox-root").length == 0) {
                            console.log('Not here')
                        } else {
                            jQuery('#rw-ts-loading-seo').show()

                            console.log('Here')
                            //make an ajax call to get the seo content
                            contentFromRewriter = response.choices[0].text


                            prompt = 'Write a SEO meta description that is between 100-150 characters for this blog post:' + title
                            jQuery.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                contentType: "application/json",
                                headers: {
                                    Authorization: "Bearer " + servicekey,
                                    contentType: "application/json",
                                },


                                data: JSON.stringify({
                                    //if rw-ts-prompt-checkbox is checked then use it

                                    prompt: prompt,
                                    temperature: <?php echo get_option('rw-ts_rewriter_temperature'); ?>,
                                    //model: "<?php //echo str_replace(' ', '', get_option('rw-ts_language_model')); ?>//",
                                    model: "text-davinci-003",
                                    max_tokens: <?php echo get_option('rw-ts_rewriter_max_tokens'); ?>
                                }),
                                success: function (response) {
                                    console.log(response)
                                    //hide the loading screen
                                    //hide the loading screen
                                    jQuery('#rw-ts-loading-seo').hide()
                                    //remove the pulse class
                                    jQuery('#rw-ts-loading img').removeClass('pulse')


                                    jQuery('#imageheading').show()
                                    jQuery('#next').show()

                                    jQuery('#hcf_description').text(response.choices[0].text.replace("Meta description:", "").replace("Meta Description:", "").replace("\n\n", "").replace(" :", "").replace('"',''))
                                    jQuery('#yoast_wpseo_metadesc').val(jQuery('#hcf_description').text())
                                    jQuery('#hcf_title').val(title)
                                    jQuery('#yoast_wpseo_title').val(jQuery('#hcf_title').val())

                                    //on change of hcftitle change yoast title
                                    jQuery('#hcf_title').on('change', function () {
                                        jQuery('#yoast_wpseo_title').val(jQuery('#hcf_title').val())
                                    });
                                    //on change of hcfdescription change yoast description
                                    jQuery('#hcf_description').on('change', function () {
                                        jQuery('#yoast_wpseo_metadesc').val(jQuery('#hcf_description').text())
                                    });

                                    prompt = "Return a single most important keyword or phrase for this blog post: " + title

                                    //make an ajax request to openai to get keywords based on the content
                                    var keywords = ''
                                    var nextpage = ''
                                    var prevpage = ''

                                    jQuery.ajax({
                                        url: url,
                                        type: 'POST',
                                        dataType: 'json',
                                        contentType: "application/json",
                                        headers: {
                                            Authorization: "Bearer " + servicekey,
                                            contentType: "application/json",
                                        },
                                        data: JSON.stringify({
                                            prompt: prompt,
                                            temperature: <?php echo get_option('rw-ts_rewriter_temperature'); ?>,
                                            model: "text-davinci-003",
                                            max_tokens: <?php echo get_option('rw-ts_rewriter_max_tokens'); ?>,
                                        }),
                                        success: function (response) {
                                            console.log(response)
                                            keywords = response.choices[0].text
                                            keywords = keywords.replace("\n\n", "").replace(" :", "").replace("\n", "").replace(",", " ")
                                            //remove everything except the keywords
                                            console.log(keywords)


                                            jQuery.ajax({
                                                url: 'https://api.pexels.com/v1/search?query=' + keywords + '&orientation=landscape&size=medium&per_page=10',
                                                type: 'GET',
                                                dataType: 'json',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'Authorization': 'UH5Ye7s1xiQZBsIIkCiVPfAReSPHuXYmH3i0b0NroOOzBtqDDXKrIfHt'
                                                },
                                                success: function (data) {
                                                    //get next page url
                                                    nextpage = data.next_page
                                                    prevpage = data.prev_page
                                                    console.log(data)
                                                    //loop over response and add images to blog-image div
                                                    for (var i = 0; i < 10; i++) {
                                                        jQuery('#blog-images').append('<div class="img-cont"><img src="' + data.photos[i].src.medium + '"></div>')
                                                    }
                                                    //on click of next page button make another call to pexels api
                                                    //on click of id next run code
                                                    jQuery('#next').on("click", function () {
                                                        //show prev button
                                                        jQuery('#prev').show()
                                                        jQuery('#blog-images img').remove()
                                                        jQuery('.img-cont').remove()
                                                        jQuery.ajax({
                                                            url: nextpage,
                                                            type: 'GET',
                                                            dataType: 'json',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'Authorization': 'UH5Ye7s1xiQZBsIIkCiVPfAReSPHuXYmH3i0b0NroOOzBtqDDXKrIfHt'
                                                            },
                                                            success: function (data) {
                                                                //get next page url
                                                                nextpage = data.next_page
                                                                prevpage = data.prev_page
                                                                console.log(data)
                                                                //loop over response and add images to blog-image div
                                                                for (var i = 0; i < 10; i++) {
                                                                    jQuery('#blog-images').append('<div class="img-cont"><img src="' + data.photos[i].src.medium + '"></div>')
                                                                }


                                                            },
                                                        })
                                                    })

                                                    //on click of prev page button make another call to pexels api
                                                    //on click of id prev run code
                                                    jQuery('#prev').on("click", function () {
                                                        jQuery('#blog-images img').remove()
                                                        jQuery('.img-cont').remove()
                                                        jQuery.ajax({
                                                            url: prevpage,
                                                            type: 'GET',
                                                            dataType: 'json',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'Authorization': 'UH5Ye7s1xiQZBsIIkCiVPfAReSPHuXYmH3i0b0NroOOzBtqDDXKrIfHt'
                                                            },
                                                            success: function (data) {
                                                                //get next page url
                                                                nextpage = data.next_page
                                                                prevpage = data.prev_page
                                                                console.log(data)
                                                                //loop over response and add images to blog-image div
                                                                for (var i = 0; i < 10; i++) {
                                                                    jQuery('#blog-images').append('<div class="img-cont"><img src="' + data.photos[i].src.medium + '"></div>')
                                                                }


                                                            },
                                                        })
                                                    })


                                                },
                                            })

                                        }


                                    })


                                    //make an ajax request to get images from pexels
                                    //https://www.pexels.com/api/documentation/#photos-search


                                }
                            });


                        }
                    }
                });

            });


        });
        },
        delayInMilliseconds
        )
        ;


    </script>
    <?php
}

add_action('admin_footer-post.php', 'add_rw_ts_button');
add_action('admin_footer-post-new.php', 'add_rw_ts_button');


// Add settings page for plugin


function add_rw_ts_settings_page()
{
    add_options_page('rw-ts Settings', 'RW Talent Scribe', 'manage_options', 'rw-ts-settings', 'render_rw_ts_settings_page');
}

add_action('admin_menu', 'add_rw_ts_settings_page');


//function add_rw_ts_signup_page()
//{
//    add_options_page('rw-ts Settings', 'Sign Up', 'manage_options', 'rw-ts-pricing', 'render_rw_ts_pricing_page');
//}
//
//add_action('admin_menu', 'add_rw_ts_signup_page');
//
//function render_rw_ts_pricing_page(){
//    //pricing page content
//
//}


// Render settings page for plugin

function my_custom_admin_head()
{
//    echo '<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />';
//    echo '<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>';
}

add_action('admin_head', 'my_custom_admin_head');
add_action('wp_head', 'my_custom_admin_head');


function render_rw_ts_settings_page()
{

    ?>

    <style>
        #wpcontent {
            background: #fff;
            /*background-image: url('https://recruiterswebsites.com/wp-content/plugins/rwchat/assets/background.png');*/
            /*background-size: cover;*/
        }

        .active {
            background: green;
            color: #fff;
            padding: 4px;
            border-radius: 6px;
        }

        .inactive {
            background: red;
            color: #fff;
            padding: 4px;
            border-radius: 6px;
        }

        button#rw-ts-btn {
            background: linear-gradient(90deg, rgb(52 137 191) 0%, rgb(104 77 148) 100%);
            color: #fff;
            border: none;
            cursor: pointer;
        }

        #rw-ts-btn img {
            filter: brightness(0) invert(1);
        }
    </style>
    <img src="<?php echo esc_url(plugins_url('/assets/header.png', __FILE__)); ?>"
         style="width: calc(100% + 20px); margin-left: -20px;"><br>

    <a id="btnlink" target="_blank" href="https://app.recruiterswebsites.com/pricing">
        <button id="rw-ts-btn" style="margin:10px; padding:12px;font-size: 19px;" type="button">Sign up</button>
    </a>
    <script>
        //on load add the rw logo to the button

        jQuery('#rw-ts-btn').prepend('<img src="<?php echo esc_url(plugins_url('/assets/RW.png', __FILE__)); ?>" style="width: 20px;margin-right: 10px;">')
    </script>
    <div class="wrap">
        <h1>RW Talent Scribe Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('rw-ts-settings-group'); ?>
            <?php do_settings_sections('rw-ts-settings-group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">RW API Key:</th>
                    <td>
                        <input type="password" name="rw-ts_text_apikey"
                               value="<?php echo get_option('rw-ts_text_apikey'); ?>"
                               id="rw-ts_text_apikey"><br>

                    </td>
                </tr>
                <tr>
                    <th scope="row">API Usage:</th>
                    <td>
                        <script>
                            var usage = 0;
                            var limit = 0;
                            var status = '';

                            //make ajax call to get api usage from app.recruitersswebsites.com
                            jQuery.ajax({
                                url: 'https://app.recruiterswebsites.com/api/v1/licenses/?key=<?php echo get_option('rw-ts_text_apikey'); ?>&usage=true',
                                type: 'GET',
                                dataType: 'json',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': 'Bearer <?php echo get_option('rw-ts_text_apikey'); ?>'
                                },
                                success: function (data) {
                                    console.log(data)
                                    status = data.status

                                    if (status == 'Active') {
                                        jQuery('#status').addClass('active')
                                        jQuery('#rw-ts-btn').html('My Account')
                                        jQuery('#rw-ts-btn').prepend('<img src="<?php echo esc_url(plugins_url('/assets/RW.png', __FILE__)); ?>" style="width: 20px;margin-right: 10px;">')
                                        //set url to my account page
                                        jQuery('#btnlink').attr('href', 'https://app.recruiterswebsites.com/users/sign_in')

                                    } else {
                                        jQuery('#status').addClass('inactive')
                                        jQuery('#rw-ts-btn').html('Sign Up')
                                        jQuery('#rw-ts-btn').prepend('<img src="<?php echo esc_url(plugins_url('/assets/RW.png', __FILE__)); ?>" style="width: 20px;margin-right: 10px;">')
                                        //set url to sign up page
                                        jQuery('#btnlink').attr('href', 'https://app.recruiterswebsites.com/pricing')
                                    }

                                    //if the license is valid
                                    if (data.status == 'Active') {
                                        // console.log(data.service_key)
                                        limit = data.limit
                                        usage = data.usage
                                        jQuery(document).ready(function () {
                                            //set progress bar value to usage
                                            jQuery('#file').val(usage)
                                            jQuery('#file').attr('max', limit)
                                            jQuery('#file').html(usage + '%')
                                            jQuery('#status').html(status)
                                            jQuery('#usage').html(usage)
                                            jQuery('#limit').html(limit + ' calls used this month')


                                        });

                                    }
                                    //if the license is invalid
                                    else {
                                        console.log('invalid')
                                    }
                                },
                            })

                        </script>
                        <!--                        Show progress bar with usage vs limit and show status-->
                        <label>Status:</label> <span class="" id="status"></span> <span id="usage"></span> of <span
                                id="limit"></span><br>
                        <div class="progress">
                            <progress id="file" value="" max=""></progress>
                        </div>


                    </td>
                </tr>

                <!--                <tr>-->
                <!--                    <th scope="row">Industries:</th>-->
                <!--                    <td>-->
                <!--                        <select style="width:200px;" class="js-example-basic-multiple" name="industries[]" multiple="multiple">-->
                <!--                            <option value="accounting">Accounting</option>-->
                <!--                            <option value="finance">Finance</option>-->
                <!--                        </select>-->
                <!--                        <script>-->
                <!--                            jQuery(document).ready(function() {-->
                <!--                                jQuery('.js-example-basic-multiple').select2(-->
                <!--                                    {-->
                <!--                                        placeholder: "Select Industries"-->
                <!--                                    }-->
                <!--                                );-->
                <!--                            });-->
                <!--                        </script>-->
                <!---->
                <!---->
                <!--                    </td>-->
                <!--                </tr>-->
                <!--                <tr>-->
                <!--                    <th scope="row">Language model:</th>-->
                <!--                    <td>-->
                <!--                        <select id="language_model" name="rw-ts_language_model" disabled >-->
                <!--                            --><?php
                //                            $response = wp_remote_get('https://api.openai.com/v1/models',
                //                                array('timeout' => 10,
                //                                    'headers' => array(
                //                                        'Authorization' => 'Bearer ' . $service_key,
                //                                    )));
                //                            $data = json_decode($response['body']);
                //                            //        print_r($data);
                //                            //loop over the models and add them to the select
                //                            foreach ($data->data as $model) {
                //                                echo '<option value="' . $model->id . '" ' . (get_option('rw-ts_language_model') == $model->id ? 'selected' : '') . '>' . $model->id . '</option>';
                //                            }
                //
                //
                ?>
                <!--                        </select>-->
                <script>
                    // when the select changes make an api call to get the model details
                    //jQuery('#language_model').on('change', function () {
                    //    //Run api call to get the details
                    //    jQuery.ajax({
                    //        url: "https://api.openai.com/v1/models/" + this.value,
                    //        type: "GET",
                    //        contentType: "application/json",
                    //        headers: {
                    //            Authorization: "Bearer <?php //echo get_option('rw-ts_text_apikey'); ?>//",
                    //            contentType: "application/json",
                    //        },
                    //        success: function (response) {
                    //            console.log(response)
                    //        }
                    //    });
                    //
                    //});


                </script>


                <!--                    </td>-->
                <!--                </tr>-->
                <tr>
                    <th scope="row">Rewriter settings:</th>
                    <td>
                        <input type="checkbox" name="rw-ts_use_rewriter"
                               value="1" <?php echo get_option('rw-ts_use_rewriter') == '1' ? 'checked' : ''; ?>> Use
                        rewriter<br>
                        <label for="rw-ts_rewriter_temperature">Temperature:</label>
                        <input type="range" name="rw-ts_rewriter_temperature" min="0" max="1" step="0.1"
                               value="<?php echo get_option('rw-ts_rewriter_temperature', 0.5); ?>"
                               id="rw-ts_rewriter_temperature"><br>
                        <label for="rw-ts_rewriter_max_tokens">Max tokens:</label>
                        <input type="number" name="rw-ts_rewriter_max_tokens"
                               value="<?php echo get_option('rw-ts_rewriter_max_tokens', 1024); ?>"
                               id="rw-ts_rewriter_max_tokens"><br>
                    </td>
                </tr>


                <tr>

                    <th scope="row">Custom Prompt Settings:</th>
                    <td>
                        Create a custom prompt to be used for the ai to generate content from. You can use the following
                        variables in your prompt:<br>
                        <code>{{title}}</code> - the title of the post<br>
                        <code>{{content}}</code> - the content of the post<br>
                        <!--                        <code>{{excerpt}}</code> - the excerpt of the post<br>-->
                        <!--                        <code>{{tags}}</code> - the tags of the post<br>-->
                        <!--                        <code>{{categories}}</code> - the categories of the post<br>-->
                        <!--                        <code>{{author}}</code> - the author of the post<br>-->
                        <!--                        <code>{{date}}</code> - the date of the post<br>-->
                        <!--                        <code>{{time}}</code> - the time of the post<br>-->
                        <!--                        <code>{{year}}</code> - the year of the post<br>-->
                        <!--                        <code>{{month}}</code> - the month of the post<br>-->
                        <!--                        <code>{{day}}</code> - the day of the post<br>-->
                        <!--                        <code>{{hour}}</code> - the hour of the post<br>-->
                        <!--                        <code>{{minute}}</code> - the minute of the post<br>-->
                        <!--                        <code>{{second}}</code> - the second of the post<br>-->
                        <!--                        <code>{{post_id}}</code> - the id of the post<br>-->
                        <!--                        <code>{{post_type}}</code> - the type of the post<br>-->
                        <!--                        <code>{{post_status}}</code> - the status of the post<br>-->
                        <!--                        <code>{{post_name}}</code> - the name of the post<br>-->
                        <!--                        <code>{{post_url}}</code> - the url of the post<br>-->
                        <!--                        <code>{{post_modified}}</code> - the modified date of the post<br>-->
                        <br>


                        <?php
                        //if rw-ts_custom_prompts then loop over them and display them as textareas
                        //                        if (get_option('rw-ts_custom_prompts')) {
                        //                            $custom_prompts = json_decode(get_option('rw-ts_custom_prompts'));
                        //                            foreach ($custom_prompts as $key => $prompt) {
                        //                                echo '<label for="rw-ts_custom_prompt_' . $key . '">' . $key . ':</label>';
                        //                                echo '<textarea name="rw-ts_custom_prompt_' . $key . '" id="rw-ts_custom_prompt_' . $key . '" cols="100" rows="10">' . $prompt . '</textarea><br>';
                        //                            }
                        //                            //else display a default prompt as a textarea
                        //                        } else {
                        echo '<textarea placeholder="Create a blog post about {{title}}." class="rw-ts_custom_prompt_text" name="rw-ts_custom_prompt_0" id="rw-ts_custom_prompt" cols="100" rows="5"></textarea><br>';

                        //                        }
                        ?>
                        <?php
                        //append a button to add another prompt as a textarea

                        ?>
                        <div style="display: flex;">
                            <p style="padding: 7px;background: #2271b1;color: #fff;margin: 2px;cursor: pointer; width: 130px; text-align: center;"
                               class="gptbutton" id="add_custom_prompt"><span class="dashicons dashicons-plus"></span>
                                Save prompt</p>
                        </div>
                        <!--create a hidden field to store the custom prompts as a json string-->
                        <input type="hidden" name="rw-ts_custom_prompts" id="rw-ts_custom_prompts"
                               value="<?php echo get_option('rw-ts_custom_prompts'); ?>">


                        <h3 id="my-prompts-heading">My Prompts</h3>
                        <?php // if no custom prompts then display a message
                        if (!get_option('rw-ts_custom_prompts')) {
                            echo '<p id="noprompts">You have no custom prompts.</p>';
                        } ?>
                        <ul id="my-prompts">


                        </ul>

                    </td>
                </tr>
            </table>


            <style>

                .st {
                    text-decoration: line-through;
                }
            </style>


            <script>

                jQuery('#add_custom_prompt').on("click", function () {
                    jQuery('#noprompts').hide();
                    var arr = jQuery("#rw-ts_custom_prompts").val().split(';; ');
                    arr = arr.filter(function (e) {
                        return e
                    });
                    name = jQuery('#rw-ts_custom_prompt').val().trim();
                    if (arr.includes(name)) {
                        return;
                    } else {
                        arr.push(name)
                        jQuery('#my-prompts').append("<li><span style='background: #fff;padding: 5px;margin: 5px;border: 1px solid black;display: inline-flex;width: 73%;'>" + name + '</span><span style="background: #2271b1;color: #fff;padding: 5.5px;margin-top: 4px;" onclick="edit_prompt(this)" class="dashicons dashicons-edit"></span> <span style="background: #2271b1;color: #fff;padding: 5.5px;margin-top: 4px;" onclick="remove_prompt(this)" class="dashicons dashicons-trash"></span></li>')
//                        jQuery('#my-prompts').append("<li onclick='remove_prompt(this)'>"+ name + "</li>")


                    }
                    jQuery('#rw-ts_custom_prompts').val(arr.join(";; "))
                    jQuery('.delete').remove();
                    jQuery('#cancel_edit').remove();
                    jQuery('.dashicons-edit').show()
                });


                function remove_prompt(item) {
                    var arr = jQuery("#rw-ts_custom_prompts").val().split(';; ');
                    arr = arr.filter(function (e) {
                        return e
                    });
                    name = jQuery(item).closest('li').text().trim();
                    console.log(name);
                    if (arr.includes(name)) {
                        arr = arr.filter(function (e) {
                            return e !== name
                        })
                    } else {
                        return;
                    }
                    jQuery('#rw-ts_custom_prompts').val(arr.join(";; "))
                    jQuery(item).closest('li').remove()
//                    jQuery(item).prev('div').remove();
                }

                function edit_prompt(item) {
                    var arr = jQuery("#rw-ts_custom_prompts").val().split(';; ');
                    arr = arr.filter(function (e) {
                        return e
                    });
                    name = jQuery(item).closest('li').text().trim();
                    console.log(name);
                    if (arr.includes(name)) {
                        arr = arr.filter(function (e) {
                            return e !== name
                        })
                    } else {
                        return;
                    }
                    jQuery('#rw-ts_custom_prompts').val(arr.join(";; "))
                    jQuery(item).closest('li').addClass('delete')
                    jQuery(item).prev().addClass('st')
                    jQuery('.dashicons-edit').hide()
                    jQuery('#rw-ts_custom_prompt').val(name.trim());
                    jQuery('#add_custom_prompt').after('<p onclick="cancel_edit()" style="padding: 7px;background: #2271b1;color: #fff;margin: 2px;cursor: pointer; width: 90px;"class="gptbutton" id="cancel_edit">Cancel Edit</p>')
//                    jQuery(item).prev('div').remove();
                }


                function cancel_edit() {
                    jQuery('#cancel_edit').remove();

                    var arr = jQuery("#rw-ts_custom_prompts").val().split(';; ');
                    arr = arr.filter(function (e) {
                        return e
                    });
                    name = jQuery('.st').text().trim();
                    if (arr.includes(name)) {
                        return;
                    } else {
                        arr.push(name)
                        jQuery('#my-prompts').append("<li><span style='background: #fff;padding: 5px;margin: 5px;border: 1px solid black;display: inline-flex;width: 73%;'>" + name + '</span><span style="background: #2271b1;color: #fff;padding: 5.5px;margin-top: 4px;" onclick="edit_prompt(this)" class="dashicons dashicons-edit"></span> <span style="background: #2271b1;color: #fff;padding: 5.5px;margin-top: 4px;" onclick="remove_prompt(this)" class="dashicons dashicons-trash"></span></li>')
//                        jQuery('#my-prompts').append("<li onclick='remove_prompt(this)'>"+ name + "</li>")


                    }
                    jQuery('#rw-ts_custom_prompts').val(arr.join(";; "))
                    jQuery('.dashicons-edit').show()
                    jQuery('.delete').remove()
                }


                jQuery(document).ready(function () {
                    var arr = jQuery("#rw-ts_custom_prompts").val().split(';; ');
                    arr = arr.filter(function (e) {
                        return e
                    });
                    for (var item of arr) {
                        jQuery('#my-prompts').append("<li><span style='background: #fff;padding: 5px;margin: 5px;border: 1px solid black;display: inline-flex;width: 73%;' >" + item + '</span><span style="background: #2271b1;color: #fff;padding: 5.5px;margin-top: 4px;" onclick="edit_prompt(this)" class="dashicons dashicons-edit"></span> <span style="background: #2271b1;color: #fff;padding: 5.5px;margin-top: 4px;" onclick="remove_prompt(this)" class="dashicons dashicons-trash"></span></li>')
                    }
                });

            </script>


            <?php submit_button(); ?>
        </form>

    </div>
    <?php
}


// Register settings for plugin
function register_rw_ts_settings()
{
    register_setting('rw-ts-settings-group', 'rw-ts_text_apikey');
//    register_setting('rw-ts-settings-group', 'rw-ts_num_paragraphs');
//    register_setting('rw-ts-settings-group', 'rw-ts_language_model');
    register_setting('rw-ts-settings-group', 'rw-ts_use_rewriter');
    register_setting('rw-ts-settings-group', 'rw-ts_rewriter_temperature');
    register_setting('rw-ts-settings-group', 'rw-ts_rewriter_max_tokens');
    register_setting('rw-ts-settings-group', 'rw-ts_rewriter_n');
    register_setting('rw-ts-settings-group', 'rw-ts_custom_prompt');
    register_setting('rw-ts-settings-group', 'rw-ts_custom_prompts');


}

add_action('admin_init', 'register_rw_ts_settings');

// Enqueue scripts for plugin
function enqueue_rw_ts_scripts()
{
    wp_enqueue_script('jquery');
}

add_action('admin_enqueue_scripts', 'enqueue_rw_ts_scripts');