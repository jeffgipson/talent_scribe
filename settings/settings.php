<?php
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
    echo '<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />';
    echo '<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>';
}

add_action('admin_head', 'my_custom_admin_head');
add_action('wp_head', 'my_custom_admin_head');
add_action('admin_footer', 'my_custom_admin_head');


function render_rw_ts_settings_page()
{

    ?>

    <style>
        progress#file {
            height: 26px;
            width: 302px;
            background: none;
            margin-top: 10px;
        }

        progress#file::-webkit-progress-value {
            background: linear-gradient(90deg, rgb(52 137 191) 0%, rgb(104 77 148) 100%);
        }

        #insert-media-button {
            display: none;
        }

        #wpcontent {
            background: #fff;
            /*background-image: url('https://recruiterswebsites.com/wp-content/plugins/rwchat../assets//background.png');*/
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

        span.select2.select2-container.select2-container--default.select2-container--focus {
            width: 100% !important;
        }
    </style>
    <img src="<?php echo esc_url(plugins_url('../assets//header.png', __FILE__)); ?>"
         style="width: calc(100% + 20px); margin-left: -20px;"><br>

    <a id="btnlink" target="_blank" href="https://app.recruiterswebsites.com/pricing">
        <button id="rw-ts-btn" style="margin:10px; padding:12px;font-size: 19px;" type="button">Sign up</button>
    </a>
    <script>
        //on load add the rw logo to the button

        jQuery('#rw-ts-btn').prepend('<img src="<?php echo esc_url(plugins_url('../assets//RW.png', __FILE__)); ?>" style="width: 20px;margin-right: 10px;">')
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
                                        // change .hidesection class to show
                                        jQuery('tr').removeClass('hidesection')


                                        jQuery('#status').addClass('active')
                                        jQuery('#rw-ts-btn').html('My Account')
                                        //jQuery('#rw-ts-btn').prepend('<img src="<?php //echo esc_url(plugins_url('../assets//RW.png', __FILE__)); ?>//" style="width: 20px;margin-right: 10px;">')
                                        //set url to my account page
                                        jQuery('#btnlink').attr('href', 'https://app.recruiterswebsites.com/users/sign_in')
                                        jQuery('#of').show()
                                    } else {
                                        jQuery('#status').addClass('inactive')
                                        //fill in the text for the button
                                        jQuery('#status').text('Inactive')
                                        jQuery('#of').hide()
                                        jQuery('#rw-ts-btn').html('Sign Up')
                                        //jQuery('#rw-ts-btn').prepend('<img src="<?php //echo esc_url(plugins_url('../assets//RW.png', __FILE__)); ?>//" style="width: 20px;margin-right: 10px;">')
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
                                            servicekey = data.service_key

                                            var site_name = "<?php echo get_bloginfo('name'); ?>"
                                            var site_url = "<?php echo get_bloginfo('url'); ?>"
                                            var industry = "<?php echo implode(get_option('rw-ts_industries')) ?>"
                                            var company_type = "<?php echo get_option('rw-ts_company_type') ?>"
                                            var prompt = "ONLY RETURN THE QUESTION IN A JAVASCRIPT ARRAY NO pretext or post text: In a minute, I’m going to ask you to create copy for my "+ company_type+" firm: " + site_name + "," + site_url + " we focus in these industries:" + industry + ". We will be creating blog content. Before we begin, I want you to fully understand my business.  Ask me 20 questions about my business, candidates, industry niche, Recruiting or staffing, what level we work and anything else you need in order to complete the tasks to the best of your ability. ONLY RETURN THE QUESTION IN A JAVASCRIPT ARRAY NO pretext or post text"

                                            var url = "https://api.openai.com/v1/chat/completions"
                                            <?php if (get_option('rw-ts_text_kickoff_questions') == ''){ ?>
                                            //make ajax call to openai to get kickoff questions
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
                                                    console.log(response)
                                                    //set the value of the text area to the response
                                                    <?php if (get_option('rw-ts_text_kickoff_questions') == '') { ?>
                                                    jQuery('#rw-ts_text_kickoff_questions').val(response.choices[0]['message']['content'].replaceAll('"', '\''))
                                                    <?php } ?>
                                                    // remove overlay rw-ts-loading
                                                    jQuery('#rw-ts-loading').remove()
                                                    // click the save button
                                                    jQuery('#submit').click()
                                                },
                                            });
                                            <?php } ?>


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
                        <label>Status:</label> <span class="" id="status"></span> <span id="usage"></span> <span
                                id="of">of</span> <span
                                id="limit"></span><br>
                        <div class="progress">
                            <progress id="file" value="" max=""></progress>
                        </div>


                    </td>
                </tr>

                <tr>
                    <th scope="row">Company Type:</th>
                    <td>

                        <select name="rw-ts_company_type" id="rw-ts_company_type">
                                <option value="Recruiting">Recruiting</option>
                                <option value="Staffing">Staffing</option>
                        </select>
                        <script>
                            //set the value of the select box to the value in the database
                            jQuery('#rw-ts_company_type').val('<?php echo get_option('rw-ts_company_type'); ?>')
                        </script>

<!--                        <input type="text" name="rw-ts_company_type"-->
<!--                               value="--><?php //echo get_option('rw-ts_company_type'); ?><!--"-->
<!--                               id="rw-ts_company_type" placeholder="Recruiting or Staffing"><br>-->

                    </td>
                </tr>

                <tr>
                    <th scope="row">Industries:</th>
                    <td>
                        <select required id="industries" style="width:200px;" class="js-example-basic-multiple"
                                name="rw-ts_industries[]" multiple="multiple">
                            <option value="agriculture">Agriculture</option>
                            <option value="automotive">Automotive</option>
                            <option value="banking">Banking</option>
                            <option value="biotechnology">Biotechnology</option>
                            <option value="construction">Construction</option>
                            <option value="education">Education</option>
                            <option value="energy">Energy</option>
                            <option value="entertainment">Entertainment</option>
                            <option value="financial services">Financial Services</option>
                            <option value="food processing">Food Processing</option>
                            <option value="healthcare">Healthcare</option>
                            <option value="hospitality">Hospitality</option>
                            <option value="information technology">Information Technology</option>
                            <option value="insurance">Insurance</option>
                            <option value="manufacturing">Manufacturing</option>
                            <option value="media">Media</option>
                            <option value="real estate">Real Estate</option>
                            <option value="retail">Retail</option>
                            <option value="telecommunications">Telecommunications</option>
                            <option value="transportation">Transportation</option>
                            <option value="utilities">Utilities</option>
                            <option value="wholesale trade">Wholesale Trade</option>
                            <option value="other">Other</option>

                        </select>
                        <script>

                            jQuery(document).ready(function () {
                                jQuery('.js-example-basic-multiple').select2(
                                    {
                                        placeholder: "Select Industries",
                                        width: '100%'

                                    }
                                );
                                jQuery('#industries').val(<?php echo json_encode(get_option('rw-ts_industries')); ?>).trigger('change');

                            });
                        </script>
                        <?php
                        //                                        $selected_industries = $_POST['industries'];
                        //                                        update_option('rw-ts_industries', $selected_industries);
                        //                                        print_r( get_option('rw-ts_industries'));
                        ?>

                    </td>
                </tr>

<!--                make a fake submit button-->
<!--                <tr class="hidesection">-->
<!--                    <th scope="row"></th>-->
<!--                    <td>-->
<!--                        <input type="submit" name="submit" id="submit" class="button button-primary"-->
<!--                               value="Save Changes">-->
<!--                    </td>-->
<!---->
<!--                <script>-->
<!--                    //on click of the submit button submit the form-->
<!--                    jQuery('#submit').click(function () {-->
<!--                        jQuery('#form').submit();-->
<!--                    })-->
<!---->
<!--                </script>-->
<!--                </tr>-->

                <tr class="hidesection">
                    <th scope="row">Company Summary:</th>
                    <td>

                        <?php

                        $rw_ts_company_summary = get_option('rw-ts_company_summary');
                        echo wp_editor($rw_ts_company_summary, 'rw-ts_company_summary', array('textarea_name' => 'rw-ts_company_summary'));


                        ?>
                    </td>
                </tr>


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
                <tr class="hidesection">
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


                <tr class="hidesection">

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
                        <!--                        create a hidden textarea for the kickoff questions -->
                        <input type="hidden" name="rw-ts_text_kickoff_questions" id="rw-ts_text_kickoff_questions"
                               value="<?php echo get_option('rw-ts_text_kickoff_questions'); ?>">
 <input type="hidden" name="rw-ts_responses" id="rw-ts_responses"
                               value="<?php echo get_option('rw-ts_responses'); ?>">
 <input type="hidden" name="rw-ts_company_profile" id="rw-ts_company_profile"
                               value="<?php echo get_option('rw-ts_company_profile'); ?>">


                        <h3 id="my-prompts-heading">My Prompts</h3>
                        <?php // if no custom prompts then display a message
                        if (!get_option('rw-ts_custom_prompts')) {
                            echo '<p id="noprompts">You have no custom prompts.</p>';
                        } ?>
                        <ul id="my-prompts">


                        </ul>

                    </td>
                </tr>
                <tr>


                    <?php
                   if(get_option('rw-ts_text_kickoff_questions')){
                       echo '<th id="botheading" scope="row"><i class="fa-solid fa-robot"></i> Talent Scribe Bot Has Some Questions!</th>';
                   }else{
                          echo '<th id="botheading" scope="row"><i class="fa-solid fa-robot"></i> Talent Scribe Bot Is Researching...</th>';
                   }
                    ?>

                    <script>
                        //if questions exist
                        if(jQuery('#rw-ts_text_kickoff_questions').val() == ''){

                            jQuery('#botheading').html('<i class="fa-solid fa-robot"></i> Talent Scribe Bot Is Researching...');
                            // <th scope="row"><i class="fa-solid fa-robot"></i> Talent Scribe Bot Is Researching...</th>

                        }else{
                            // <th scope="row"><i class="fa-solid fa-robot"></i> Talent Scribe Bot Has Some Questions!</th>
                            jQuery('#botheading').html('<i class="fa-solid fa-robot"></i> Talent Scribe Bot Has Some Questions!');
                        }
                    </script>


<!--                    <th scope="row"><i class="fa-solid fa-robot"></i> Talent Scribe Bot Has Some Questions!</th>-->
                    <td>
                        <button id="myBtn">Get Started</button>

                        <!--                --><?php //print_r(get_option('rw-ts_text_kickoff_questions')); ?>

                        <!--                create a reset button -->
                        <button id="reset">Reset</button>
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

<!--            --><?php //echo get_option('rw-ts_text_kickoff_questions') ?><!--;-->
            <div id="modal-wrapper">
                <div id="myModal" class="modal" style="display: none;">
                    <div id="modalhead">
                        <h3>Welcome to TalentScribe!</h3>
                    </div>
                    <span class="close">&times;</span>
                    <div id="modal-content" class="modal-content">
            <span class="bot-response">
                <div class="robot">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="bubble left" id="text-bubble">
                    <div id="text-bubble-text">
                        <p id="text-bubble-text-p">Hi, Welcome to TalentScribe. I'm going to ask you a few questions to learn more about your company. Are you ready?</p>
                    </div>
                </div>
            </span>

                        <span class="bot-response-typing" style="display: none">
                <img src="<?php echo esc_url(plugins_url('../assets//typing.gif', __FILE__)); ?>" width="60"
                     alt="Typing" id="typing">
            </span>


                    </div>
                    <div id="user-response">
                        <div id="user-response-input">
                            <script>
                                    function auto_grow(element) {
                                    element.style.height = "5px";
                                    element.style.height = (element.scrollHeight) + "px";
                                }
                            </script>
                            <textarea oninput="auto_grow(this)" rows="3" cols="7" id="user-response-input-box"
                                      placeholder="Type your response here..."></textarea>
                        </div>
                        <div id="user-response-submit">
                            <div id="user-response-submit-button"><i class="fa-regular fa-paper-plane"></i></div>
                        </div>
                    </div>
                </div>
                </div>




                <script>

//get url params and set to variable
                    var reset = false
                    if(jQuery('#rw-ts_text_kickoff_questions').val() == ''){
                      reset = true
                    }

                    if(reset == true){
                        //js delay
                        setTimeout(function () {
                            // remove overlay from page
                            jQuery('body').append('<div id="rw-ts-loading" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 9999;"><img src="<?php echo esc_url(plugins_url('../assets/Researching.png', __FILE__)); ?>" style="width: 250px; position: absolute;top: 37%;left: calc(50% - 125px) ;transform: translate(-50%, -50%);"></div>')
                            jQuery('#rw-ts-loading').fadeIn();

                        }, 500);
                    }



                    // add overlay to page


                    //on click of reset clear out questions and responses and start over
                    jQuery('#reset').click(function(event){
                        event.preventDefault();
                        jQuery('#rw-ts_text_kickoff_questions').val('')
                        jQuery('#rw-ts_company_profile').val('')
                        jQuery('#rw-ts_responses').val('')
                        setTimeout(function () {
                            console.log('reset')
                         // jQuery('form').attr('action', jQuery('form').attr('action')+'&bot=reset');

                        jQuery('#submit').click()
                        }, 500);


                    })

                    jQuery(document).ready(function(){
                        function scrollToBottom (id) {
                            var div = document.getElementById(id);

                            /*TRY*/
                            div.scrollTop = div.scrollHeight - div.clientHeight;
                            /*OR*/
                            // $('#'+id).scrollTop(div.scrollHeight - div.clientHeight);
                        }


                    });

                    //allow modal-content to scroll
                    // jQuery('.modal-content').css('overflow-y', 'auto');
                    // jQuery('.modal-content').css('height', '400px');

                    var questions;
                    var questionsValue = JSON.stringify(`<?php echo get_option('rw-ts_text_kickoff_questions'); ?>`);
                    if (questionsValue) {
                        questions = JSON.parse(questionsValue);
                        //split on ','
                        questions = questions.replaceAll(/(\r\n|\n|\r)/gm, "");
                        questions = questions.replaceAll("['", "");
                        questions = questions.replaceAll("']", "");
                        questions = questions.replaceAll("', '", "','");
                        questions = questions.replaceAll("' ,'", "','");
                        questions = questions.split("','");

                    } else {
                        questions = [];
                    }

                    var responses;
                    var responsesValue = JSON.stringify(`<?php echo get_option('rw-ts_responses'); ?>`);
                    if (responsesValue) {
                        responses = JSON.parse(responsesValue);
                        responses = responses.replaceAll("['", "");
                        responses = responses.replaceAll("']", "");
                        // responses = responses.split("','");
                        responses = responses.split("\n,");
                    } else {
                        responses = [];
                    }

                        console.log(responses)
                        if (responses.length < 2) {
                            // click on get started button
                            //js delay
                            setTimeout(function () {
                                // remove overlay from page
                                jQuery('#myBtn').click();
                                console.log('showing modal')
                            }, 500);

                        }


                    var question = 0;


                    // show popup on click of button
                    jQuery('#myBtn').click(function (event) {
                        // prevent button from submitting form
                        event.preventDefault();
                        jQuery('#myModal').toggle();

                        // on keypress of enter submit response
                        jQuery('#user-response-input-box').keypress(function (e) {
                            if (e.which == 13) {
                                jQuery('#user-response-submit-button').click();
                            }
                        });

                        jQuery('#user-response-submit-button').click(function () {
                            // check if it's not the starter question
                            //scroll to bottom of modal-content
                            console.log('scrolling')
                             jQuery('.modal-content').animate({scrollTop: jQuery('.modal-content').prop("scrollHeight")}, 500);

                            if (question > 0) {
                                // push the question and response to the array as a key value pair
                                responses.push(questions[question - 1] + ": " + jQuery('#user-response-input-box').val());
                                //save the array to the hidden input field
                                jQuery('#rw-ts_responses').val(responses);
                                console.log(responses);
                            }


                            // append next question after last response from user-bubble
                            var nextquestion = questions[question];
                            console.log(nextquestion);
                            if (nextquestion) {
                                console.log('next question');
                                jQuery('.modal-content').append('<span class="user-response">' +
                                    '<div class="user-bubble right" id="user-text-bubble">' +
                                    '<div id="user-text-bubble-text">' +
                                    '<p id="user-text-bubble-text-p">' + jQuery('#user-response-input-box').val() + '</p>' +
                                    '</div>' +
                                    '</div> ' +
                                    '<div class="user"><i class="fa-solid fa-user"></i>' +
                                    '</div>' +
                                    '</span>');

                                jQuery('.modal-content').append('<span class="bot-response-typing">' +
                                    '<img src="<?php echo esc_url(plugins_url('../assets//typing.gif', __FILE__)); ?>" width="60" alt="Typing" id="typing">' +
                                    '</span>');
                                // hide fake bot response typing class with delay
                                setTimeout(function () {
                                    jQuery('.bot-response-typing').remove();
                                    jQuery('.modal-content').append('<span class="bot-response">' +
                                        '<div class="robot">' +
                                        '<i class="fa-solid fa-robot"></i>' +
                                        '</div>' +
                                        '<div class="bubble left" id="text-bubble">' +
                                        '<div id="text-bubble-text">' +
                                        '<p id="text-bubble-text-p">' + nextquestion + '</p>' +
                                        '</div>' +
                                        '</div>' +
                                        '</span>');

                                    console.log('next question appended');
                                }, 400);

                                question++;


                            }


                            // clear input box
                            jQuery('#user-response-input-box').val('');

                            //scroll to bottom of modal-content
                            // jQuery('.modal-content').scrollTop(jQuery('.modal-content')[0].scrollHeight);
                            console.log(question)
                            console.log(questions.length + 1)

                            if (question == questions.length) {
                                //close modal
                                jQuery('#myModal').toggle();
                                //display overlay
                                jQuery('body').append('<div id="rw-ts-loading" style="display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 9999;"><img src="<?php echo esc_url(plugins_url('../assets/Researching.png', __FILE__)); ?>" style="width: 250px; position: absolute;top: 37%;left: calc(50% - 125px) ;transform: translate(-50%, -50%);"></div>')
                                jQuery('#rw-ts-loading').fadeIn();
                                //make call to open ai api via ajax to create a detailed company profile from the responses array
                                console.log('making call');
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

                                        if (status == 'Active')
                                            servicekey = data.service_key


                                        var company_summary = "<?php echo get_option('rw-ts_company_summary') ?>"
                                        var site_name = "<?php echo get_bloginfo('name'); ?>"
                                        var site_url = "<?php echo get_bloginfo('url'); ?>"
                                        var industry = "<?php echo implode(get_option('rw-ts_industries')) ?>"
                                        var company_type = "<?php echo get_option('rw-ts_company_type') ?>"
                                        var prompt = "A few minutes ago, I asked you to create 20 questions that would help you create copy for my " + company_type + " firm: " + site_name + "," + site_url + " we focus in these industries:" + industry + ". We will be creating blog content later not now. Please use these questions and answers to create a detailed company profile that will be used in later prompts to create blog content. \n\n" + responses.join("\n\n") + "\n\n" + company_summary

                                        var url = "https://api.openai.com/v1/chat/completions"
                                        //make ajax call to openai to get kickoff questions
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
                                                console.log(response)
                                                //set the value of the text area to the response
                                                //remove overlay
                                                jQuery('#rw-ts-loading').fadeOut();
                                                jQuery('#rw-ts_company_profile').val(response.choices[0]['message']['content'])
                                                //click submit button
                                                jQuery('#submit').click();

                                            },
                                        });


                                    }


                                });

                                jQuery('#text-bubble-text-p').text('Thank you for your responses. Please click the button below to continue.');
                                jQuery('#user-response-submit-button').click(function () {
                                    jQuery('#myModal').hide();
                                });
                            }
                        });
                    });

                    // onclick of close button close popup
                    jQuery('.close').click(function () {
                        jQuery('#myModal').hide();
                    });
                </script>

            </div>

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
    register_setting('rw-ts-settings-group', 'rw-ts_industries');
    register_setting('rw-ts-settings-group', 'rw-ts_company_summary');
    register_setting('rw-ts-settings-group', 'rw-ts_text_kickoff_questions');
    register_setting('rw-ts-settings-group', 'rw-ts_responses');
    register_setting('rw-ts-settings-group', 'rw-ts_company_type');
    register_setting('rw-ts-settings-group', 'rw-ts_company_profile');


}

add_action('admin_init', 'register_rw_ts_settings');

// Enqueue scripts for plugin
function enqueue_rw_ts_scripts()
{
    wp_enqueue_script('jquery');
}

add_action('admin_enqueue_scripts', 'enqueue_rw_ts_scripts');

