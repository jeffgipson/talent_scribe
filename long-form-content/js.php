<?php

//is page parameter exists and isset
if (isset($_GET['page_type']) && !empty($_GET['page_type'])) {
    $page = $_GET['page_type'];
} else {
    $page = 'POST';
}

if ($page == 'long-form-content') {
?>
<script>
    setTimeout(function () {
        //authenticating with app.recruiterswebsites.com via ajax
        //make ajax call to lic server to check if the license is valid
        jQuery.ajax({
            url: "https://app.recruiterswebsites.com/api/v1/licenses/?key=<?php echo get_option('rw-ts_text_apikey'); ?>&usage=true",
            type: 'GET',
            dataType: 'json',
            headers: {
                'Content-Type': 'application/json',
                "Authorization": "Bearer <?php echo get_option('rw-ts_text_apikey'); ?>"
            },
            success: function (data) {
                // console.log(data)

                //if the license is valid
                if (data.status == 'Active') {
                    servicekey = data.service_key

                    //blog ideas
                    jQuery("#titlewrap").after("<div id='blog_ideas_wrapper'><div id='blog_ideas'><h3>Blog Post Ideas <span class='dashicons dashicons-lightbulb'></span></h3></div>" +
                        "<hr>" +
                        "<div id='response'></div></div>")
                    //onclick of blog ideas button
                    jQuery("#blog_ideas").click(function () {

                    <?php
                    //fetch the last 10 blog posts and return the titles put them into a comma separated string
                        $args = array(
                            'numberposts' => 10
                        );

                        $latest_posts = get_posts( $args );
                        //loop over latest posts and get the titles
                        $titles = '';
                        foreach ($latest_posts as $post) {
                            $titles .= $post->post_title . ', ';
                        }
                    ?>
                    var past_blogs = "<?php echo $titles; ?>"
                    var site_name = "<?php echo get_bloginfo( 'name' ); ?>"
                    var company_summary = "<?php echo get_option('rw-ts_company_summary'); ?>"
                    var prompt = "Generate a JSON with ideas for post title for 10 blog posts for " + site_name + " based on this summary: " + company_summary + ". The Last 10 blog posts were: " + past_blogs + "They shouldnt be too similar to the last 10 blog posts but should be relevant to " + site_name + "."
                    var url = "https://api.openai.com/v1/completions"
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
                            max_tokens: 1500,
                        }),
                        success: function (response) {
                            console.log(response)
                            var title_options = response.choices[0]['text'].replace('\n', '').split(",").map(function (item) {
                                return item.trim();
                            })
                            console.log(title_options)
                            var i = 0;
                            //loop through the array of title options
                            jQuery.each(title_options, function (key, value) {
                                //add each title option to the select
                                console.log(value)
                                value = value.replaceAll('"', '').replace('[', '').replace(']', '').replace('{', '').replace('}','').replace('title:','').replace('postTitle:').replace('Title:','').trim();
                                console.log('after')
                                console.log(value)
                                jQuery('#response').append('<p class="selecttitle" id="' + value + '">' + value + '</p>');
                                //add a hr to all except the last one
                                if (i < title_options.length - 1) {
                                    jQuery('#response').append('<hr>');
                                }

                                  i++;
                            });
                            //add a close button to the response div
                            jQuery('#response').append('<button id="close" class="button button-primary button-large">X</button>');
                            //on click of the close button hide the response div
                            jQuery('#close').click(function () {
                                jQuery('#response').hide();
                            })
                            //on click of the title option add it to the title input
                            jQuery('.selecttitle').click(function () {
                                var title = jQuery(this).attr('id');
                                jQuery('#title').val(title);
                                //scroll backup to the title input
                                jQuery('html, body').animate({
                                    scrollTop: jQuery(".wp-heading-inline").offset().top
                                }, 2000);

                            })

                        }
                    })
                })

                }
                //if the license is invalid
                else {
                    console.log('invalid')
                }
            },
        })
        jQuery('#titlewrap').append('<div id="rw_ts_button_wrapper" class="rw_ts_button"><button id="rw_ts_button" class="rw_ts_button button button-primary button-large">Generate Title Options</button></div>');
        //prevent button from submitting the form
        jQuery('#rw_ts_button').click(function (e) {
            e.preventDefault();
        });
        //on click of the button

            jQuery('#rw_ts_button').click(function () {
            var title = jQuery('#title').val();
            var prompt = "Generate a JSON array of 10 unordered title options for this article:" + title;
            var url = "https://api.openai.com/v1/completions"
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
            //convert the title input to a select with the title options from the api
            jQuery('#title').replaceWith('<select id="title" name="title" class="rw_ts_title_select"></select>');
            //add the title options to the select
            var title_options = response.choices[0]['text'].replace('\n', '').split(",").map(function (item) {
            return item.trim();
        })
            console.log(title_options)
            var i = 0;
            //loop through the array of title options
            jQuery.each(title_options, function (key, value) {
            //add each title option to the select
            value = value.replaceAll('"', '').replace('[', '').replace(']', '');
            jQuery('.rw_ts_title_select').append('<option value="' + value + '">' + value + '</option>');
            i++;
        });
        }
        })
        });
            //add a button after the wp-opener-wrap input
            jQuery('#wp-opener-wrap').append('<div id="rw_ts_into_button_wrapper" class="rw_ts_into_button_wrapper"><button id="rw_ts_into_button" class="rw_ts_into_button button button-primary button-large">Generate Intro Content</button></div>');
            //prevent button from submitting the form
            jQuery('#rw_ts_into_button').click(function (e) {
            e.preventDefault();
        });
            //on click of the button to generate intro content
            jQuery('#rw_ts_into_button').click(function () {
            //get the title
            var title = jQuery('#title').val();
            var prompt = "We are going to create a blog post. Here is the title: " + title + " Please write an introduction paragraph use 175 - 250 words.";
            var url = "https://api.openai.com/v1/completions"
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
            //place the intro content in the intro textarea
            jQuery('#opener-html').click()
            jQuery('#opener').val(response.choices[0]['text']);
            jQuery('#opener-tmce').click()
        }
        })
        });
            //add a button after the wp-body-wrap input
            jQuery('#wp-main_body-wrap').append('<div id="rw_ts_body_button_wrapper" class="rw_ts_body_button_wrapper"><button id="rw_ts_body_button" class="rw_ts_body_button button button-primary button-large">Generate Body Content</button></div>');

            //prevent button from submitting the form
            jQuery('#rw_ts_body_button').click(function (e) {
            e.preventDefault();
        });
            //on click of the button to generate intro content
            jQuery('#rw_ts_body_button').click(function () {
            //get the title
            title = jQuery('#title').val();
            var intro = jQuery('#opener').val();
            var prompt = "We are continuing our blog post titled: " + title + " So far we have this introduction paragraph: " + intro + " Please write the body of the article. Use 400 - 600 words."
            var url = "https://api.openai.com/v1/completions"
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
            max_tokens: 3000,
        }),
            success: function (response) {
            console.log(response)
            //place the intro content in the intro textarea
            jQuery('#main_body-html').click()
            jQuery('#main_body').val(response.choices[0]['text']);
            jQuery('#main_body-tmce').click()
        }
        })

        });
            //add a button after the wp-body-wrap input
            jQuery('#wp-conclusion-wrap').append('<div id="rw_ts_conclusion_button_wrapper" class="rw_ts_conclusion_button_wrapper"><button id="rw_ts_conclusion_button" class="rw_ts_conclusion_button button button-primary button-large">Generate A Conclusion</button></div>');
            //prevent button from submitting the form
            jQuery('#rw_ts_conclusion_button').click(function (e) {
            e.preventDefault();
        });
            //on click of the button to generate intro content
            jQuery('#rw_ts_conclusion_button').click(function () {
            //get the title
            title = jQuery('#title').val();
            intro = jQuery('#opener').val();
            //get the body
            var body = jQuery('#main_body').val();
            var prompt = "We are continuing our blog post titled: " + title + " So far we have this body content: " + body + " Please write a conclusion for this article. Use 175 - 250 words."
            var url = "https://api.openai.com/v1/completions"
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
            max_tokens: 2500,
        }),
            success: function (response) {
            console.log(response)
            //place the intro content in the intro textarea
            jQuery('#conclusion-html').click()
            jQuery('#conclusion').val(response.choices[0]['text']);
            jQuery('#conclusion-tmce').click()
        }
        })
        });
            //add a save button after conclusion
            jQuery('#wp-conclusion-wrap').append('<div id="rw_ts_save_button_wrapper" class="rw_ts_save_button_wrapper"><button id="rw_ts_save_button" class="rw_ts_save_button button button-primary button-large">Next</button></div>');
            //prevent button from submitting the form
            jQuery('#rw_ts_save_button').click(function (e) {
            e.preventDefault();
        });
            //onclick of the save button do stuff
            jQuery('#rw_ts_save_button').click(function () {

            jQuery('#content-html').click()
                jQuery('#conclusion-html').click()
                jQuery('#main_body-html').click()
                jQuery('#opener-html').click()
                title = jQuery('#title').val();
                intro = jQuery('#opener').val();
                body = jQuery('#main_body').val();
                var url = "https://api.openai.com/v1/completions"
                var conclusion = jQuery('#conclusion').val();
                var entire_post = intro + body + conclusion;
                console.log(entire_post)
            jQuery('#content').val(entire_post);
            jQuery('#content-tmce').click()
            GetSeo(servicekey,title,url)
            GetImage(servicekey,title,url)
        });


    }, 500);

</script>
<?php
}