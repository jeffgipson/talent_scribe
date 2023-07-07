<?php
//is page parameter exists and isset
if (isset($_GET['page_type']) && !empty($_GET['page_type'])) {
    $page = $_GET['page_type'];
} else {
    $page = 'POST';
}

if ($page == 'long-form-content') {
    ?>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

    <script>

//3.5 turbo 16k function
        function turbo16(servicekey, step) {
            var url = "https://api.openai.com/v1/chat/completions";
            var title = jQuery('#title').val();
            var intro = jQuery('#opener').val();
            var body = jQuery('#main_body').val();
            var conclusion = jQuery('#conclusion').val();
            var entire_post = intro + body + conclusion;
            var company_profile = "<?php echo str_replace(array("\r", "\n", "\t", "'"), array('', '', '', "\'"), get_option('rw-ts_company_profile')); ?>";
            var company_summary = "<?php echo get_option('rw-ts_company_summary'); ?>"
            var site_name = "<?php echo get_bloginfo('name'); ?>";
            var industry = "<?php echo get_option('rw-ts_industry'); ?>";
            var final_response = false;
            var past_blogs = ""
            var text = '';
            var prompt = '';

            <?php
            //fetch the last 10 blog posts and return the titles put them into a comma separated string
            $args = array(
                'numberposts' => 10
            );

            $latest_posts = get_posts($args);
            //loop over latest posts and get the titles
            $titles = '';
            foreach ($latest_posts as $post) {
                $titles .= $post->post_title . ', ';
            }
            ?>
            past_blogs = "<?php echo $titles; ?>";

            if (step == 'intro') {
                if (company_profile != '') {
                    prompt = "Just to give you some background information: " + company_summary + " Company profile: " + company_profile + " We are going to create a blog post. Here is the title: " + title + " Please write an introduction paragraph use 175 - 250 words. This will not be the entire blog post, just the introduction. Our company should not be the main focus but rather we are looking to provide value to our website users so you do not need to use the background information I gave I about us its just for reference. DO NOT use these words: 'in this blog' or 'in this blog post' or 'in this article'. I will ask you to create the rest of the blog post in the next prompt.";
                } else {
                    prompt = "Just to give you some background information: " + company_summary + " We are going to create a blog post. Here is the title: " + title + " Please write an introduction paragraph use 175 - 250 words. This will not be the entire blog post, just the introduction. Our company should not be the main focus but rather we are looking to provide value to our website users so you do not need to use the background information I gave I about us its just for reference. DO NOT use these words: 'in this blog' or 'in this blog post' or 'in this article'. I will ask you to create the rest of the blog post in the next prompt.";
                }
            } else if (step == 'body') {
                if (company_profile != '') {
                    prompt = "We are continuing our blog post titled: " + title + ". So far, we have this introduction paragraph: " + intro + ". Your task is to craft an engaging and detailed body for the article, consisting of 5-8 paragraphs and spanning 800 - 1000 words. To captivate readers, focus on expanding and diving deep into each strategy mentioned in the introduction. The goal is to provide valuable insights, practical tips, and real-world examples that showcase the effectiveness of these strategies in finding the right accounting talent. Emphasize the unique benefits and challenges associated with each strategy, and explore how small businesses can leverage them to gain a competitive edge in the talent market. Use bold headings (using HTML bold tags) for each paragraph to create a visually appealing structure. Avoid repeating information from the introduction; instead, seamlessly build upon the provided introduction by introducing new perspectives, fresh ideas, and captivating storytelling techniques. Feel free to use synonyms, rephrase sentences, and employ dynamic language to ensure a rich and diverse vocabulary. Your objective is to provide readers with an in-depth understanding of the strategies, inspiring them to take action and revolutionize their accounting talent acquisition process. I'm not telling you to use this but here is some background information about our company: " + company_summary + " Company profile: " + company_profile
                } else {
                    prompt = "We are continuing our blog post titled: " + title + ". So far, we have this introduction paragraph: " + intro + ". Your task is to craft an engaging and detailed body for the article, consisting of 5-8 paragraphs and spanning 800 - 1000 words. To captivate readers, focus on expanding and diving deep into each strategy mentioned in the introduction. The goal is to provide valuable insights, practical tips, and real-world examples that showcase the effectiveness of these strategies in finding the right accounting talent. Emphasize the unique benefits and challenges associated with each strategy, and explore how small businesses can leverage them to gain a competitive edge in the talent market. Use bold headings (using HTML bold tags) for each paragraph to create a visually appealing structure. Avoid repeating information from the introduction; instead, seamlessly build upon the provided introduction by introducing new perspectives, fresh ideas, and captivating storytelling techniques. Feel free to use synonyms, rephrase sentences, and employ dynamic language to ensure a rich and diverse vocabulary. Your objective is to provide readers with an in-depth understanding of the strategies, inspiring them to take action and revolutionize their accounting talent recruitment efforts.I'm not telling you to use this but here is some background information about our company: " + company_summary
                }
            } else if (step == 'conclusion') {
                if (company_profile != '') {
                    prompt = "Please use this company summary for reference:" + company_summary + " Company profile:" + company_profile + " We are continuing our blog post titled: " + title + " So far we have this content: " + intro + ' ' + body + " Please conclude the article with unique content and include information about our companies area of focus. DO NOT repeat the same words or phrases. DO NOT use these words: 'in conclusion' or 'in summary' or 'in this blog' or 'in this article'. ";
                } else {
                    prompt = "Please use this company summary for reference:" + company_summary + " We are continuing our blog post titled: " + title + " So far we have this content: " + intro + ' ' + body + " Please conclude the article with unique content and include information about our companies area of focus. DO NOT repeat the same words or phrases. DO NOT use these words: 'in conclusion' or 'in summary' or 'in this blog' or 'in this article'. ";
                }
            } else if (step == 'rewrite') {
                if (company_profile != '') {
                    prompt = "DO NOT INCLUDE THE TITLE IN YOUR RESPONSE AND YOUR RESPONSE SHOULD BE AT LEAST 800 WORDS!! You are a skilled marketing and copywriting expert for " + site_name + ". Some background information for the company, " + company_summary + ". Company Profile: " + company_profile + " Pretend you are an executive recruiter writing a thoroughly researched blog post titled:" + title + " about the " + industry + " industry. Based on our brainstorming, this is what we have so far: " + intro + " " + body + " " + conclusion + ". Your comprehensive article should be rich in detail, brimming with informative content, and maintain an engaging narrative.  Adhere to the following guidelines: 1. Turn this into a detailed, long-form blog post, supporting your main points with relevant facts, examples, or metaphors. 2. Please use synonyms and rewrite phrases and sentence to remove repetitive phrases and insure a great reader experience by staying on topic and not repeating content. Use the company name sparingly, only in the introduction and conclusion. Keep the content focused on educating and informing the reader and less on promoting your company. The post should be 99% educational and informative and 1% about us toward the end. FORMAT THE BLOG WITH RICH TEXT AND HTML WITH BOLD HEADINGS!"
                } else {
                    prompt = "DO NOT INCLUDE THE TITLE IN YOUR RESPONSE AND YOUR RESPONSE SHOULD BE AT LEAST 800 WORDS!! You are a skilled marketing and copywriting expert for " + site_name + ". Some background information for the company, " + company_summary + ". Pretend you are an executive recruiter writing a thoroughly researched blog post titled:" + title + " about the " + industry + " industry. Based on our brainstorming, this is what we have so far: " + intro + " " + body + " " + conclusion + ". Your comprehensive article should be rich in detail, brimming with informative content, and maintain an engaging narrative.  Adhere to the following guidelines: 1. Turn this into a detailed, long-form blog post, supporting your main points with relevant facts, examples, or metaphors. 2. Please use synonyms and rewrite phrases and sentence to remove repetitive phrases and insure a great reader experience by staying on topic and not repeating content. Use the company name sparingly, only in the introduction and conclusion. Keep the content focused on educating and informing the reader and less on promoting your company. The post should be 99% educational and informative and 1% about us toward the end. FORMAT THE BLOG WITH RICH TEXT AND HTML WITH BOLD HEADINGS!"
                }
            } else if (step == 'ideas') {
                if (company_profile != '') {
                    prompt = "ONLY RETURN THE JSON ARRAY NO PRETEXT OR POST TEXT: Generate a JSON array with ideas for post title for 10 blog posts for " + site_name + " based on this summary: " + company_summary + " and this company profile: " + company_profile + " . The Last 10 blog posts were: " + past_blogs + "They shouldnt be too similar to the last 10 blog posts but should be relevant to " + site_name + "MUST BE A JSON ARRAY! NO PRETEXT OR POST TEXT"
                } else {
                    prompt = "ONLY RETURN THE JSON ARRAY NO PRETEXT OR POST TEXT: Generate a JSON array with ideas for post title for 10 blog posts for " + site_name + " based on this summary: " + company_summary + ". The Last 10 blog posts were: " + past_blogs + "They shouldnt be too similar to the last 10 blog posts but should be relevant to " + site_name + "MUST BE A JSON ARRAY! NO PRETEXT OR POST TEXT"
                }
            } else if (step == 'seo') {
                prompt = 'Write a SEO meta description that is NO LONGER THAN 139 CHARACTERS! for this blog post:' + title
            } else if (step == 'title'){
                prompt = "ONLY RETURN THE JSON ARRAY NO PRETEXT OR POST TEXT: Generate a JSON array of 10 unordered title options for this article: " + title +" ONLY RETURN THE JSON ARRAY NO PRETEXT OR POST TEXT:";
            }

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
                    messages: [{role: "user", content: prompt}],
                    model: "gpt-3.5-turbo-16k"
                }),
                success: function (response) {
                    text = response.choices[0]['message']['content'];
                    console.log(response)
//if step is intro
                    if (step == 'intro') {
                        console.log(step)
                        jQuery('#rw-ts-intro-loading img').attr('src', '<?php echo esc_url(plugins_url('../assets/Body.png', __FILE__)); ?>')
                        jQuery('#newtitle').append('<div id="rw_ts_post_wrapper" class="rw_ts_post_wrapper"><div id="gentitle">' + title + '</div><br><div id="genintro"></div></div>');
                        typeWriter(step, text, 0, 15, final_response)

                        //place the intro content in the intro textarea
                        jQuery('#opener-html').click()
                        jQuery('#opener').val(text);
                        jQuery('#opener-tmce').click()

                        jQuery('.nav-item').css('color', '#e9e9e9');
                        jQuery('.body-nav').css('color', '#3792c8');

                        turbo16(servicekey, 'body')
// if step is body
                    } else if (step == 'body') {
                        console.log(step)
                        jQuery('#rw-ts-intro-loading img').attr('src', '<?php echo esc_url(plugins_url('../assets/Conclusion.png', __FILE__)); ?>')
                        jQuery('#rw_ts_post_wrapper').append('<div id="genbody"></div>');

                        typeWriter(step, text, 0, 15, final_response)

                        //place the intro content in the intro textarea
                        jQuery('#main_body-html').click()
                        jQuery('#main_body').val(text);
                        jQuery('#main_body-tmce').click()

                        jQuery('.nav-item').css('color', '#e9e9e9');
                        jQuery('.outro-nav').css('color', '#3792c8');

                        turbo16(servicekey, 'conclude')
//if step is conclude
                    } else if (step == 'conclude') {
                        console.log(step)
                        jQuery('#rw-ts-intro-loading img').attr('src', '<?php echo esc_url(plugins_url('../assets/SEO.png', __FILE__)); ?>')
                        jQuery('#rw_ts_post_wrapper').append('<div id="genconclude"></div>');

                        typeWriter(step, text, 0, 15, final_response)
                        //place the intro content in the intro textarea
                        jQuery('#conclusion-html').click();
                        jQuery('#conclusion').val(text);
                        jQuery('#conclusion-tmce').click();

                        //add a save button after conclusion
                        jQuery('#wp-conclusion-wrap').append('<div id="rw_ts_save_button_wrapper" class="rw_ts_save_button_wrapper"><button id="rw_ts_save_button" class="rw_ts_save_button button button-primary button-large">Next</button></div>');
                        //prevent button from submitting the form
                        jQuery('#rw_ts_save_button').click(function (e) {
                            e.preventDefault();
                        });
                        //onclick of the save button do stuff

                        jQuery('#content-html').click();
                        jQuery('#conclusion-html').click();
                        jQuery('#main_body-html').click();
                        jQuery('#opener-html').click();
                        title = jQuery('#title').val();
                        intro = jQuery('#opener').val();
                        body = jQuery('#main_body').val();

                        turbo16(servicekey, 'rewrite')
//if step is rewrite
                    } else if (step == 'rewrite') {
                        console.log(step)
                        final_response = true

                        entire_post = response.choices[0]['message']['content']

                        console.log(entire_post)
                        document.getElementById("genintro").innerHTML = entire_post
                        document.getElementById("genbody").remove()
                        document.getElementById("genconclude").remove()
                        jQuery('#content').val(entire_post);
                        jQuery('#content-tmce').click()

                        jQuery('.nav-item').css('color', '#e9e9e9');
                        jQuery('.seo-nav').css('color', '#3792c8');

                        turbo16(servicekey, 'seo')

                        jQuery('.nav-item').css('color', '#e9e9e9');
                        jQuery('.image-nav').css('color', '#3792c8');
                        jQuery('#rw-ts-intro-loading').hide();
// if step is ideas
                    } else if (step == 'ideas') {
                        jQuery('#rw-ts-ideas-loading').hide()
                        var title_options = response.choices[0]['message']['content'].replace('\n', '').split(",").map(function (item) {
                            return item.trim();
                            title_options = title_options.replaceAll('\r', '')
                            title_options = title_options.replaceAll('\n', '')
                            title_options = title_options.replaceAll('post_title:', '')
                        })
                        console.log(title_options)
                        var i = 0;
                        //loop through the array of title options
                        jQuery.each(title_options, function (key, value) {
                            //add each title option to the select
                            console.log(value)
                            value = value.replaceAll('"', '').replace('[', '').replace(']', '').replace('{', '').replace('}', '').replace('title:', '').replace('postTitle:').replace('Title:', '').trim();
                            console.log('after')
                            console.log(value)
                            jQuery('#response').append('<p class="selecttitle" id="' + value + '">' + value + '</p>');
                            i++;
                        });

                        //add a close button to the response div
                        jQuery('#response').append('<button id="close" class="button button-primary button-large">X</button>');
                        //on click of the close button hide the response div
                        jQuery('#close').click(function () {
                            jQuery('#response').hide();
                        })
                        //prevent close button from submitting the form'
                        jQuery('#close').click(function (e) {
                            e.preventDefault();
                        });
                        //on click of the title option add it to the title input
                        jQuery('.selecttitle').click(function () {
                            var title = jQuery(this).attr('id');
                            jQuery('#title').val(title);
                            jQuery('#response').hide();
                            //click on the skip button
                            jQuery('#skip').click();
                            jQuery('.nav-item').css('color', '#e9e9e9');
                            jQuery('.title-nav').css('color', '#3792c8');
                        })
//if step is seo
                    } else if (step == 'seo') {
                        //hide the loading screen
                        jQuery('#rw-ts-loading-seo').hide()
                        //remove the pulse class
                        jQuery('#rw-ts-loading img').removeClass('pulse')

                        jQuery('#imageheading').show()
                        //append a search input and button after the image heading
                        jQuery('#next').show()
                        jQuery('#hcf_description').text(response.choices[0]['message']['content'].replace("Meta description:", "").replace("Meta Description:", "").replace("\n\n", "").replace(" :", "").replace('"', ''))
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
                        //js delay function to wait for the yoast title and description to be populated
                        setTimeout(function () {
                        //if the yoast title is empty then populate it with the hcf title
                            if (jQuery('#yoast_wpseo_title').val() == '') {
                                jQuery('#yoast_wpseo_title').val(jQuery('#hcf_title').val())
                            }
                            //if the yoast description is empty then populate it with the hcf description
                            if (jQuery('#yoast_wpseo_metadesc').val() == '') {
                                jQuery('#yoast_wpseo_metadesc').val(jQuery('#hcf_description').text())
                            }
                            //click save
                            jQuery('#save-post').click();

                        }, 500);
//if step is title
                    } else if(step = 'title'){

                        jQuery('#rw-ts-titles-loading').hide()
                        console.log(response)
                        var title_options = response.choices[0]['message']['content'].replace('\n', '').split(",").map(function (item) {
                            return item.trim();
                            title_options = title_options.replaceAll('\r', '')
                            title_options = title_options.replaceAll('\n', '')
                            title_options = title_options.replaceAll('post_title:', '')
                        })
                        console.log(title_options)
                        jQuery('#response').show();
                        var i = 0;
                        //loop through the array of title options
                        jQuery.each(title_options, function (key, value) {
                            //add each title option to the select
                            console.log(value)
                            value = value.replaceAll('"', '').replace('[', '').replace(']', '').replace('{', '').replace('}', '').replace('title:', '').replace('postTitle:').replace('Title:', '').trim();
                            console.log('after')
                            console.log(value)
                            jQuery('#response').append('<p class="selecttitle" id="' + value + '">' + value + '</p>');
                            i++;
                        });

                        //add a close button to the response div
                        jQuery('#response').append('<button id="close" class="button button-primary button-large">X</button>');
                        //on click of the close button hide the response div
                        jQuery('#close').click(function () {
                            jQuery('#response').hide();
                        })
                        //prevent close button from submitting the form'
                        jQuery('#close').click(function (e) {
                            e.preventDefault();
                        });
                        //on click of the title option add it to the title input
                        jQuery('.selecttitle').click(function () {
                            var title = jQuery(this).attr('id');
                            jQuery('#title').val(title);
                            jQuery('#response').hide();
                            //click on the skip button
                            // jQuery('#skip').click();
                            jQuery('.nav-item').css('color', '#e9e9e9');
                            jQuery('.title-nav').css('color', '#3792c8');
                        })

                        }
                },

                error: function handleError(xhr, status, error) {
                    // Display error message and retry button for the first request
                    //remove all loading screens
                    jQuery('#rw-ts-loading').hide()
                    jQuery('#rw-ts-loading-seo').hide()
                    jQuery('#rw-ts-titles-loading').hide()
                    jQuery('#rw-ts-ideas-loading').hide()
                    jQuery('#rw-ts-intro-loading').hide()
                    //remove all pulse classes
                    jQuery('#rw-ts-loading img').removeClass('pulse')
                    jQuery('#rw-ts-loading-seo img').removeClass('pulse')
                    jQuery('#rw-ts-titles-loading img').removeClass('pulse')
                    jQuery('#rw-ts-ideas-loading img').removeClass('pulse')
                    jQuery('#rw-ts-intro-loading img').removeClass('pulse')

                    var errorMessage = 'An error occurred in the ' + step + ' step. Please try again. If the error persists, please <a href="mailto:support@recruiterswebsites.com">contact support</a>.';


                    var popupElement = jQuery('<div id="comm_error">').html(errorMessage);

                    popupElement.dialog({
                        modal: true,
                        title: 'Error',
                        open: function(event, ui) {
                            // Find the title element and prepend the icon
                            jQuery('.ui-dialog-title', ui.dialog).prepend('<i class="fa-solid fa-circle-exclamation"></i> ');
                            jQuery('#adminmenuback').css('z-index', '0 !important')

                        },
                        buttons: [
                            {
                                text: 'Retry ' + step.charAt(0).toUpperCase() + step.slice(1) + ' Step',
                                click: function () {
                                    jQuery(this).dialog('close');
                                    // Retry the first AJAX request
                                    turbo16(servicekey, step);
                                }
                            }
                        ]
                    });

                }
            })
        }

        function typeWriter(step, response, i, speed, final_response) {
            if (final_response) {
                return;
            }
            if (i < response.length) {
                document.getElementById("gen" + step).innerHTML += response.charAt(i);
                i++;
                setTimeout(function () {
                    typeWriter(step, response, i, speed, final_response);
                }, speed);
            }
        }


        setTimeout(function () {
            //stop nav-item from going to page
            jQuery('.nav-tab').click(function (e) {
                e.preventDefault();
            });

            jQuery('.nav-item').css('color', '#e9e9e9');
            jQuery('.idea-nav').css('color', '#3792c8');
            jQuery('#wp-opener-wrap, #wp-main_body-wrap, #wp-conclusion-wrap,.step2,.step3,.step4').css('display', 'none');

            var titlesection = jQuery("#titlediv")
            jQuery('#newtitle').append(titlesection)

            jQuery.ajax({
                url: "https://app.recruiterswebsites.com/api/v1/licenses/?key=<?php echo get_option('rw-ts_text_apikey'); ?>&usage=true",
                type: 'GET',
                dataType: 'json',
                headers: {
                    'Content-Type': 'application/json',
                    "Authorization": "Bearer <?php echo get_option('rw-ts_text_apikey'); ?>"
                },
                success: function (data) {

                    //if the license is valid
                    if (data.status == 'Active') {
                        servicekey = data.service_key
                    }
                    //if the license is invalid
                    else {
                        console.log('invalid')
                    }
                },
            })
            jQuery('#titlewrap').append('<div id="rw_ts_button_wrapper" class="rw_ts_button"><button id="rw_ts_button" class="rw_ts_button button button-primary button-large"><i class="fa-sharp fa-regular fa-gear"></i> Generate Title Options</button></div>');
            //prevent button from submitting the form
            jQuery('#rw_ts_button').click(function (e) {
                e.preventDefault();
            });
            //on click of the button

            jQuery('#rw_ts_button').click(function () {
            //loading screen
                jQuery('body').append('<div id="rw-ts-titles-loading" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 9999;"><img src="<?php echo esc_url(plugins_url('../assets/Titles.png', __FILE__)); ?>" style="width: 250px; position: absolute;top: 37%;left: calc(50% - 50px) ;transform: translate(-50%, -50%);"></div>')
                //make the image pulse  /Users/jeffgipson/Local Sites/plugin-dev/app/public/wp-content/plugins/talentscribe/assets/Ideas.png
                jQuery('#rw-ts-loading img').addClass('pulse')
                //create the pulse class in the css
                jQuery('head').append('<style>.pulse {animation: pulse 1s infinite;}</style>')
                //create the pulse animation
                jQuery('head').append('<style>@keyframes pulse {0% {transform: scale(1);}50% {transform: scale(1.1);}100% {transform: scale(1);}}</style>')
                // show the loading screen
                jQuery('#rw-ts-titles-loading').show()
                //add the pulse class to the image
                jQuery('#rw-ts-titles-loading img').addClass('pulse')

                    turbo16(servicekey, 'title')
            });

            //blog ideas
            jQuery("#titlewrap").after("<div id='blog_ideas_wrapper'><div id='blog_ideas'><h3 id='generate'><i class='fa-solid fa-lightbulb'></i> Generate Ideas</h3> <h3 id='skip'><i class='fa-sharp fa-regular fa-brain-circuit'></i> I Have An Idea</h3></div>" +
                "<div id='response'></div></div>")

            //on hover of id skip change the icon
            jQuery("#skip").hover(function () {
                jQuery("#skip").html("<i class='fa-sharp fa-solid fa-brain-circuit'></i> I Have An Idea")
            }, function () {
                jQuery("#skip").html("<i class='fa-sharp fa-regular fa-brain-circuit'></i> I Have An Idea")
            });

            // on hover of id blog_ideas change the icon
            jQuery("#generate").hover(function () {
                jQuery("#generate").html("<i class='fa-regular fa-lightbulb-on'></i> Generate Ideas")
            }, function () {
                jQuery("#generate").html("<i class='fa-solid fa-lightbulb'></i> Generate Ideas")
            });

            //onclick of blog ideas button
            jQuery("#generate").click(function () {

                //loading screen
                jQuery('body').append('<div id="rw-ts-ideas-loading" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 9999;"><img src="<?php echo esc_url(plugins_url('../assets/Ideas.png', __FILE__)); ?>" style="width: 250px; position: absolute;top: 37%;left: calc(50% - 50px) ;transform: translate(-50%, -50%);"></div>')
                //make the image pulse  /Users/jeffgipson/Local Sites/plugin-dev/app/public/wp-content/plugins/talentscribe/assets/Ideas.png
                jQuery('#rw-ts-loading img').addClass('pulse')
                //create the pulse class in the css
                jQuery('head').append('<style>.pulse {animation: pulse 1s infinite;}</style>')
                //create the pulse animation
                jQuery('head').append('<style>@keyframes pulse {0% {transform: scale(1);}50% {transform: scale(1.1);}100% {transform: scale(1);}}</style>')
                // show the loading screen
                jQuery('#rw-ts-ideas-loading').show()
                jQuery('#rw-ts-ideas-loading img').addClass('pulse')
                turbo16(servicekey, 'ideas')
            })
            jQuery('#skip').click(function () {
                console.log('clicked')
                jQuery('#blog_ideas').hide();
                jQuery('#response').hide();
                jQuery('.step1 label').text('Enter your idea and we will generate a dropdown with title options for you')
                jQuery('#titlewrap').css('display', 'flex').css('margin-bottom', '10px');
                //add a next button below the title input
                jQuery('#nextstep').remove()
                jQuery('#titlewrap').after('<button id="nextstep" class="button"><i class="fa-solid fa-pen"></i> Create my post!</button>');
                jQuery('#nextstep').click(function (e) {
                    e.preventDefault();
                });
                //disable the .idea-nav button
                jQuery('.idea-nav').attr('disabled', 'disabled');
                //turn all .nav-items grey except for the one with .title-nav
                jQuery('.nav-item').css('color', '#e9e9e9');
                jQuery('.title-nav').css('color', '#3792c8');
                //set placeholder text for title input
                jQuery('#title').attr("placeholder", "Enter your idea's title here");

                jQuery('#nextstep').click(function () {
                    jQuery('body').append('<div id="rw-ts-intro-loading" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 9999;"><img src="<?php echo esc_url(plugins_url('../assets/Intro.png', __FILE__)); ?>" style="width: 250px; position: absolute;top: 37%;left: calc(50% - 50px) ;transform: translate(-50%, -50%);"></div>')
                    //make the image pulse  /Users/jeffgipson/Local Sites/plugin-dev/app/public/wp-content/plugins/talentscribe/assets/Ideas.png
                    jQuery('#rw-ts-loading img').addClass('pulse')
                    //create the pulse class in the css
                    jQuery('head').append('<style>.pulse {animation: pulse 1s infinite;}</style>')
                    //create the pulse animation
                    jQuery('head').append('<style>@keyframes pulse {0% {transform: scale(1);}50% {transform: scale(1.1);}100% {transform: scale(1);}}</style>')
                    // show the loading screen
                    jQuery('#rw-ts-intro-loading').show()
                    jQuery('#rw-ts-intro-loading img').addClass('pulse')
                    //hide next button
                    jQuery('#nextstep').hide();
                    jQuery('#titlewrap').hide();
                    jQuery('.step1 label').html('<i class="fa-regular fa-umbrella-beach"></i> Sit back and relax while we generate your blog post')
                    jQuery('.nav-item').css('color', '#e9e9e9');
                    jQuery('.intro-nav').css('color', '#3792c8');
                    //Call the function to generate the content
                    turbo16(servicekey, 'intro')
                });
            })

            //prevent rw_ts_button from submitting the form
            jQuery('#rw_ts_button').click(function (e) {
                e.preventDefault();
            });
        }, 750)
    </script>

<?php } ?>

<script>
// set the image search on load
    setTimeout(function () {
        console.log('delayed')
        jQuery(document).ready(function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var c = url.searchParams.get("action");

            if (c == 'edit') {
                jQuery('#search').val(jQuery('#title').val())
                jQuery('#searchbtn').click()
            }
        });
    }, 750)
</script>