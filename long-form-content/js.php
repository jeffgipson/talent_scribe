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

            //stop nav-item from going to page
            jQuery('.nav-tab').click(function (e) {
                e.preventDefault();
            });

            jQuery('.nav-item').css('color', '#e9e9e9');
            jQuery('.idea-nav').css('color', '#3792c8');
            jQuery('#wp-opener-wrap, #wp-main_body-wrap, #wp-conclusion-wrap,.step2,.step3,.step4').css('display', 'none');


            var titlesection = jQuery("#titlediv")
            jQuery('#newtitle').append(titlesection)

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
                        jQuery('#rw-ts-titles-loading').hide()
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
                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    }
                })
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
                var past_blogs = "<?php echo $titles; ?>"
                var site_name = "<?php echo get_bloginfo('name'); ?>"
                var company_summary = "<?php echo get_option('rw-ts_company_summary'); ?>"
                var prompt = "Generate a JSON array with ideas for post title for 10 blog posts for " + site_name + " based on this summary: " + company_summary + ". The Last 10 blog posts were: " + past_blogs + "They shouldnt be too similar to the last 10 blog posts but should be relevant to " + site_name + "."
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
                        jQuery('#rw-ts-ideas-loading').hide()
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
                            //scroll backup to the title input
                            // jQuery('html, body').animate({
                            //     scrollTop: jQuery(".wp-heading-inline").offset().top
                            // }, 2000);
                            jQuery('#response').hide();
                            //click on the skip button
                            jQuery('#skip').click();
                            jQuery('.nav-item').css('color', '#e9e9e9');
                            jQuery('.title-nav').css('color', '#3792c8');


                        })

                    },
                    error: function (request, status, error) {
                        alert(request.responseText);
                    }
                })
            })

            //onclick of #skip button show title input
            jQuery('#skip').click(function () {
                console.log('clicked')
                jQuery('#blog_ideas').hide();
                jQuery('#response').hide();
                jQuery('.step1 label').text('Enter your idea and we will generate a dropdown with title options for you')
                jQuery('#titlewrap').css('display', 'flex').css('margin-bottom', '10px');
                //add a next button below the title input
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
                    console.log('clicked')
                    //hide next button
                    jQuery('#nextstep').hide();
                    jQuery('#titlewrap').hide();
                    jQuery('.step1 label').html('<i class="fa-regular fa-umbrella-beach"></i> Sit back and relax while we generate your blog post')
                    jQuery('.nav-item').css('color', '#e9e9e9');
                    jQuery('.intro-nav').css('color', '#3792c8');
                    //show intro input
                    // jQuery('#wp-opener-wrap').show()
                    //click on rw_ts_into_button
                    //on click of the button to generate intro content
                    console.log('clicked button')
                    var title = jQuery('#title').val();
                    var company_summary = "<?php echo get_option('rw-ts_company_summary'); ?>"
                    var prompt = "Just to give you some background information: " + company_summary + " We are going to create a blog post. Here is the title: " + title + " Please write an introduction paragraph use 175 - 250 words. This will not be the entire blog post, just the introduction. Our company should not be the main focus but rather we are looking to provide value to our website users so you do not need to use the background information I gave I about us its just for reference. DO NOT use these words: 'in this blog' or 'in this blog post' or 'in this article'. I will ask you to create the rest of the blog post in the next prompt.";
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
                            //change the loading image
                            jQuery('#rw-ts-intro-loading img').attr('src', '<?php echo esc_url(plugins_url('../assets/Body.png', __FILE__)); ?>')


                            jQuery('#newtitle').append('<div id="rw_ts_post_wrapper" class="rw_ts_post_wrapper"><div id="gentitle">' + title + '</div><br><div id="genintro"></div></div>');
                            var i = 0;
                            var txt = response.choices[0]['text']
                            var speed = 15;

                            function typeWriter() {
                                if (i < txt.length) {
                                    document.getElementById("genintro").innerHTML += txt.charAt(i);
                                    i++;
                                    setTimeout(typeWriter, speed);
                                }
                            }

                            typeWriter();


                            //place the intro content in the intro textarea
                            jQuery('#opener-html').click()
                            jQuery('#opener').val(response.choices[0]['text']);
                            jQuery('#opener-tmce').click()

                            //hide intro input
                            // jQuery('#wp-opener-wrap').hide()
                            //show main body input
                            // jQuery('#wp-content-wrap').show()
                            jQuery('.nav-item').css('color', '#e9e9e9');
                            jQuery('.body-nav').css('color', '#3792c8');
                            //generate the body content


                            //get the title
                            title = jQuery('#title').val();
                            var intro = jQuery('#opener').val();
                            var prompt = "We are continuing our blog post titled: " + title + " So far we have this introduction paragraph: " + intro + " Please write the body of the article. Use 400 - 600 words in 3-5 paragraphs with bold headings(use html bold tags and insure new lines for headings) DO NOT repeat content from the introduction but rather continue where the intro left off Keep the phrase different DO NOT repeat the same information from the intro. DO NOT use the same words and phrases over and over again. Use synonyms or rephrase the sentence. DO NOT write a conclusion yet I will ask you to create the conclusion in the next prompt.";
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

                                    //change the loading image

                                    jQuery('#rw-ts-intro-loading img').attr('src', '<?php echo esc_url(plugins_url('../assets/Conclusion.png', __FILE__)); ?>')
                                    jQuery('#rw_ts_post_wrapper').append('<div id="genbody"></div>');
                                    var i = 0;
                                    var txt = response.choices[0]['text']
                                    var speed = 15;

                                    function typeWriter() {
                                        if (i < txt.length) {
                                            document.getElementById("genbody").innerHTML += txt.charAt(i);
                                            i++;
                                            setTimeout(typeWriter, speed);
                                        }
                                    }

                                    typeWriter();

                                    //place the intro content in the intro textarea
                                    jQuery('#main_body-html').click()
                                    jQuery('#main_body').val(response.choices[0]['text']);
                                    jQuery('#main_body-tmce').click()


                                    //generate the conclusion content
                                    jQuery('.nav-item').css('color', '#e9e9e9');
                                    jQuery('.outro-nav').css('color', '#3792c8');
                                    //on click of the button to generate intro content
                                    //get the title
                                    title = jQuery('#title').val();
                                    intro = jQuery('#opener').val();
                                    //get the body
                                    var body = jQuery('#main_body').val();
                                    var company_summary = "<?php echo get_option('rw-ts_company_summary'); ?>"
                                    var prompt = "Please use this company summary for reference:" + company_summary + " We are continuing our blog post titled: " + title + " So far we have this content: " + intro + ' ' + body + " Please conclude the article with unique content and include information about our companies area of focus. DO NOT repeat the same words or phrases. DO NOT use these words: 'in conclusion' or 'in summary' or 'in this blog' or 'in this article'. ";
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
                                            jQuery('#rw-ts-intro-loading img').attr('src', '<?php echo esc_url(plugins_url('../assets/SEO.png', __FILE__)); ?>')
                                            jQuery('#rw_ts_post_wrapper').append('<div id="genconclude"></div>');
                                            var i = 0;
                                            var txt = response.choices[0]['text']
                                            var speed = 15;

                                            function typeWriter() {
                                                if (i < txt.length) {
                                                    document.getElementById("genconclude").innerHTML += txt.charAt(i);
                                                    i++;
                                                    setTimeout(typeWriter, speed);
                                                }
                                            }

                                            typeWriter();
                                            //place the intro content in the intro textarea
                                            jQuery('#conclusion-html').click()
                                            jQuery('#conclusion').val(response.choices[0]['text']);
                                            jQuery('#conclusion-tmce').click()

                                            //add a save button after conclusion
                                            jQuery('#wp-conclusion-wrap').append('<div id="rw_ts_save_button_wrapper" class="rw_ts_save_button_wrapper"><button id="rw_ts_save_button" class="rw_ts_save_button button button-primary button-large">Next</button></div>');
                                            //prevent button from submitting the form
                                            jQuery('#rw_ts_save_button').click(function (e) {
                                                e.preventDefault();
                                            });
                                            //onclick of the save button do stuff

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

                                            // send the entire post back to openai with ajax
                                            // var prompt = "Please evaluate this blog post titled:" + title + " . The post has 3 main section: the Introduction:" + intro + " , the body:" + body + " and the conclusion:" + conclusion + ". Rewrite, reformat the entire post and return with rich text and html with bold headings. Please remove any repetition and insure a great reader experience by staying on topic and not repeating content. DO NOT print the post title at the top. The final blog post MUST be 800 - 1200 words ";
                                            var prompt_better = "We are developing an educational and informative blog post titled: '" + title + "'. Drawing from our extensive collaborative session, we have crafted the following content: " + intro + " " + body + " " + conclusion + ". Imagine yourself as a seasoned executive recruiter and transform this into a captivating blog post using rich text and HTML formatting, integrating eye-catching headings. Our primary objective is to provide valuable insights while maintaining a focused discussion and avoiding content repetition. Please note that the final blog post should contain 800 to 1200 words, with only 1% of the content dedicated to showcasing our company towards the end. Exclude the title of the post at the beginning.";
                                            var prompt = "Create a blog post where you Imagine yourself as a reader seeking valuable insights on executive recruitment, job searches, best hiring practices and HR related topics, and prepare to embark on an enlightening journey through this thoughtfully curated blog post. Titled '" + title + "', this article has been meticulously crafted to provide you with expert knowledge and actionable advice. Let's explore the following sections: " + intro + " " + body + " " + conclusion + ". Through the effective utilization of rich text and HTML formatting, which includes bold headings, we aim to deliver an educational and captivating experience tailored specifically to you. We remain committed to a focused discussion, avoiding any content repetition and ensuring coherence throughout. The final blog post will span between 800 and 1200 words, with just 1% of the content dedicated to discussing our company. Let us embark on this enlightening journey together, refraining from explicitly repeating the title.";

                                            // var prompt = "We are writing a blog post titled:" + title + " . Based on our brainstorming this is what we have so far: " + intro + " " + body + " " + conclusion + ". Pretend your an executive recruiter and turn this into a blog post with rich text and html with bold headings. Please use synonyms and rewrite phrases and sentence to remove any repetition and insure a great reader experience by staying on topic and not repeating content. The post should be 99% educational and informative and 1% about us toward the end. DO NOT print the post title at the top. The final blog post MUST BE 800 - 1200 words ";
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
                                                    entire_post = response.choices[0]['text']

                                                    console.log(entire_post)
                                                    jQuery('#content').val(entire_post);
                                                    jQuery('#content-tmce').click()

                                                    jQuery('.nav-item').css('color', '#e9e9e9');
                                                    jQuery('.seo-nav').css('color', '#3792c8');

                                                    GetSeo(servicekey, title, url)

                                                    jQuery('.nav-item').css('color', '#e9e9e9');
                                                    jQuery('.image-nav').css('color', '#3792c8');
                                                    jQuery('#rw-ts-intro-loading').hide();

                                                    GetImage(servicekey, title, url)

                                                }
                                            })


                                        },
                                        error: function (request, status, error) {
                                            alert(request.responseText);
                                        }
                                    })

                                },
                                error: function (request, status, error) {
                                    alert(request.responseText);
                                }
                            })


                        },
                        error: function (request, status, error) {
                            alert(request.responseText);
                        }
                    })
                });
            })

            //prevent rw_ts_button from submitting the form
            jQuery('#rw_ts_button').click(function (e) {
                e.preventDefault();
            });


        }, 750);

    </script>
    <?php
}