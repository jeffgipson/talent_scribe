<?php
//is page parameter exists and isset
if (isset($_GET['page']) && !empty($_GET['page'])){
    $page = $_GET['page'];
} else {
    $page = 'POST';
}
if (isset($_GET['page_type']) && !empty($_GET['page_type'])){
    $page_type = $_GET['page_type'];
} else {
    $page_type = 'page_type';
}
//if page_type == long-form-content then remove current class
if ($page_type == 'long-form-content'){
    ?>
   <script>
    //if page_type == long-form-content then remove current class no jquery
    document.addEventListener("DOMContentLoaded", function() {
       //look for the current class
         var current = document.getElementsByClassName('current');
            //remove the current class
            current[0].classList.remove('current');
        //find the a href with text Content Builder
        var content_builder = document.querySelector('a[href="edit.php?page=long-form-content"]');
        //add the current class to the li parent with text Content Builder
        content_builder.parentElement.classList.add('current');
    });
    </script>
    <?php
}
?>
    <script>

        console.log('loaded')

        var servicekey = '';

        setTimeout(function () {

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

            jQuery('body').append('<div id="rw-ts-loading" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 9999;"><img src="<?php echo esc_url(plugins_url('../assets/Writing.png', __FILE__)); ?>" style="width: 250px; position: absolute;top: 37%;left: calc(50% - 125px) ;transform: translate(-50%, -50%);"></div>')
            //make the image pulse
            jQuery('#rw-ts-loading img').addClass('pulse')
            //create the pulse class in the css
            jQuery('head').append('<style>.pulse {animation: pulse 1s infinite;}</style>')
            //create the pulse animation
            jQuery('head').append('<style>@keyframes pulse {0% {transform: scale(1);}50% {transform: scale(1.1);}100% {transform: scale(1);}}</style>')
            // set the image size

            jQuery('body').append('<div id="rw-ts-loading-seo" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 9999;"><img src="<?php echo esc_url(plugins_url('../assets/SEO.png', __FILE__)); ?> "style="width: 250px; position: absolute;top: 37%;left: calc(50% - 125px) ;transform: translate(-50%, -50%);"></div>')
            //make the image pulse
            jQuery('#rw-ts-loading-seo img').addClass('pulse')


        })
        }, 750);

        var delayInMilliseconds = 500; //1 second

        setTimeout(function () {

            jQuery(document).ready(function () {
                //create a form for custom prompt with checkbox to enable it
                // jQuery('#content').after('<div id="rw-ts-prompt" style="display: none;"><label for="rw-ts-prompt-checkbox">Use Custom Prompt</label><input type="checkbox" id="rw-ts-prompt-checkbox" name="rw-ts-prompt-checkbox" value="rw-ts-prompt-checkbox"><input type="text" id="rw-ts-prompt-text" name="rw-ts-prompt-text" value="" placeholder="Enter Custom Prompt"></div>')
                //create the button
                console.log('ready')
                jQuery('#content,.block-editor-writing-flow,.wpjb-form-group-job').after('' +
                    '<div style="" id="rwgpt">' +
                    '<img src="<?php echo esc_url(plugins_url('../assets/header.png', __FILE__)); ?>" style="width: 100%;">' +
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
                    }

                    if ( $page == 'wpjb-job') {
                     ?>
                    //create a checkbox for a job description rewrite prompt
                    '<input type="checkbox" id="rw-ts-job-description-rewrite" name="rw-ts-job-description-rewrite" value=""><label for="rw-ts-job-description-rewrite">Job Description Rewrite</label><br>' +
                    //create a checkbox for job description creation based on the job title and the location
                    '<input type="checkbox" id="rw-ts-job-description-creation" name="rw-ts-job-description-creation" value=""><label for="rw-ts-job-description-creation">Job Description Creation(Based on job title and location)</label><br>' +
                    <?php }
                    else {
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
                jQuery('#rw-ts-btn').prepend('<img src="<?php echo esc_url(plugins_url('../assets/RW.png', __FILE__)); ?>" style="width: 20px;margin-right: 10px;">')
                jQuery('#rw-ts-btn').on('click', function () {
                    var url = "https://api.openai.com/v1/completions";
                    if ('<?php echo str_replace(' ', '', get_option('rw-ts_language_model')); ?>' == 'gpt-3.5-turbo') {
                        url = "https://api.openai.com/v1/chat/completions";
                    } else {
                        url = "https://api.openai.com/v1/completions";
                    }
                    console.log('clicked')

                    var prompt = ""
                    var title = ""
                    var content = ""

                    //if wp-block class exists on the page set title
                    if (jQuery('.wp-block').length) {
                        title = wp.data.select("core/editor").getEditedPostAttribute('title');
                        content = wp.data.select("core/editor").getEditedPostAttribute('content');
                        console.log(title)
                    }
                    //support for wpjobboard plugin
                    else if(jQuery('.wpjb-form-group-job').length){
                        title = jQuery('#job_title').val();
                        content = tinyMCE.get('job_description').getContent()
                        console.log(title)
                        console.log(content)
                    }
                        else{
                            title = jQuery('#title').val();
                            content = jQuery('#content').val();
                        console.log(title)
                        }


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

                        // jQuery('.wp-editor-area').val(contentstring)
                        if (jQuery('.wp-block').length) {
                            wp.data.dispatch( 'core/editor' ).editPost( { content: contentstring } );
                        }
                        else if(jQuery('.wpjb-form-group-job').length){
                            tinyMCE.get('job_description').setContent(contentstring)
                        }
                        else{
                            jQuery('#content').val(contentstring)
                        }

                        // jQuery('.wp-editor-area').val(response.choices[0].text.replace('.\n\n', '').replace('\n\n', ''))
                        // jQuery('#rw-ts-btn').after('<button id="rw-ts-btn-more">Add More Content</button>')
                        //Todo add another call to add more content
                        jQuery('#content-tmce').click()
                        //Hide the loading screen
                        jQuery('#rw-ts-loading').hide()
                        // change the button text to regenerate instead of generate
                        jQuery('#rw-ts-btn').text('Regenerate AI Content')
                        jQuery('#rw-ts-btn').prepend('<img src="<?php echo esc_url(plugins_url('../assets/RW.png', __FILE__)); ?>" style="width: 20px;margin-right: 10px;">')

                       //seo goes here

                        if (jQuery("#wpseo-metabox-root").length == 0) {
                            console.log('Not here')
                            GetImage(servicekey, title, url)

                        } else {
                            GetSeo(servicekey, title, url)
                            GetImage(servicekey, title, url)
                        }

                    } //end of success function from openai call
                }); //end of openai call to get title

            }); //end of onclick of button

        }); //end of document ready
        }, // end set timeout function
        delayInMilliseconds
        ) //end of set timeout function
        ;

        //create a js function to make the pexels api calls



        // jQuery.ajax({
        //     url: 'https://api.openai.com/v1/chat/completions',
        //     type: 'POST',
        //     dataType: 'json',
        //     headers: {
        //         'Content-Type': 'application/json',
        //         'Authorization': 'Bearer ' + servicekey,
        //     },
        //     data: JSON.stringify({
        //         model: "gpt-3.5-turbo",
        //         messages: [{"role": "user", "content": "Say this is a test!"}]
        //     }),
        //     success: function (data) {
        //          console.log(data)
        //     },
        // })

        function GetSeo(servicekey,title,url){
            console.log('GetSeo')

            prompt = 'Write a SEO meta description that is NO LONGER THAN 139 CHARACTERS! for this blog post:' + title
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
                    console.log('test from seo call')
                    //hide the loading screen
                    jQuery('#rw-ts-loading-seo').hide()
                    //remove the pulse class
                    jQuery('#rw-ts-loading img').removeClass('pulse')

                    jQuery('#imageheading').show()
                    //append a search input and button after the image heading

                    jQuery('#next').show()

                    jQuery('#hcf_description').text(response.choices[0].text.replace("Meta description:", "").replace("Meta Description:", "").replace("\n\n", "").replace(" :", "").replace('"', ''))
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

                }
            })
        }

        function GetImage(servicekey,title,url){
            console.log('GetImage')

           //if the search doesnt exist then append it
            if(!jQuery('#search').length){
                jQuery('#imageheading').after('<div><input type="text" id="search" placeholder="Search for an image" style="width: 90%;margin-right: 10px;"><button id="searchbtn">Search</button></div>')
                //stop the serach from submitting the form
                jQuery('#searchbtn').on('click', function (e) {
                    e.preventDefault()
                    //remove all  the img-cont divs
                    jQuery('.img-cont').remove()

                })
                //on click of the search button run a pelxels search and display the results
                jQuery('#searchbtn').on('click', function () {
                    //get the value of the search input
                    var search = jQuery('#search').val()
                    //call the pexels api
                    GetImage(servicekey, search, url)
                })  }


            prompt = "Please return an unformatted list of the 5 most important keywords or phrase for this blog post: " + title + "DO NOT use numbers or bullets. Each word or phrase should be separated by a space"

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
                    jQuery('#yoast_wpseo_focuskw').val(keywords.replace('"', ''))
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
                            console.log(url)
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
                                        // console.log(data)
                                        //loop over response and add images to blog-image div
                                        for (var i = 0; i < 10; i++) {
                                            jQuery('#blog-images').append('<div class="img-cont"><img src="' + data.photos[i].src.medium + '"></div>')
                                        }
                                    },
                                })
                            }) //end of onlick of Prev button

                        }, //end of success function from pexels api call
                    }) //end of pexels api call

                } // end of success function from keyword call

            }) //end of openai call to get keyword
        }

    </script>

<?php
//if the post has a post id then run the code
if (isset($_GET['post'])) {
    ?>

   <script>
       var search = ''
       setTimeout(function () {
           if(!jQuery('#search').length){
               jQuery('#imageheading').after('<div><input type="text" id="search" placeholder="Search for an image" style="width: 90%;margin-right: 10px;"><button id="searchbtn">Search</button></div>')
               //stop the serach from submitting the form
               jQuery('#searchbtn').on('click', function (e) {
                   console.log('clicked')
                   e.preventDefault()
                   //remove all  the img-cont divs
                   jQuery('.img-cont').remove()

               })
               //on click of the search button run a pelxels search and display the results
               jQuery('#searchbtn').on('click', function () {
                   //get the value of the search input
                    search = jQuery('#search').val()
                   //call the pexels api
                   OnlyImages(search)
               })  }
       //js set timeout function
function OnlyImages(search) {

    jQuery.ajax({
        url: 'https://api.pexels.com/v1/search?query=' + search +'&orientation=landscape&size=medium&per_page=10',
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
                        // console.log(data)
                        //loop over response and add images to blog-image div
                        for (var i = 0; i < 10; i++) {
                            jQuery('#blog-images').append('<div class="img-cont"><img src="' + data.photos[i].src.medium + '"></div>')
                        }
                    },
                })
            }) //end of onlick of Prev button

        }, //end of success function from pexels api call
    }) //end of pexels api call
}
           //show next and prev buttons
              jQuery('#next').show()
              jQuery('#prev').show()



           OnlyImages('Modern Office')
            }, 500);


   </script>
<?php }
