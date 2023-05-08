<?php

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