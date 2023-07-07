<style>
    <?php
//  if (get_post_type() == 'long-form-content') {
if (isset($_GET['page_type']) && !empty($_GET['page_type'])) {
    $page = $_GET['page_type'];
} else {
    $page = 'POST';
}

if ($page == 'long-form-content') {
  ?>
    /*#wp-opener-wrap, #wp-main_body-wrap, #wp-conclusion-wrap,.step2,.step3,.step4 {*/
    /*    visibility: hidden;*/
    /*}*/
    .mce-statusbar!important {
        display: none;
    }
    #titlewrap {
        display: flex;
        gap: 7px;
    }
    .wp-core-ui .button-group.button-large .button, .wp-core-ui .button.button-large {
        padding: 3px 12px !important;
    }
    .wp-core-ui select {
        max-width: unset !important;;
    }
    #title{
        width: 100% !important;
    }
    #rw_ts_into_button, #rw_ts_body_button, #rw_ts_conclusion_button {
        float: right;
        margin-top: -75px;
        position: relative;
        left: -20px;
    }
    #rw_ts_save_button {
        float: right;
        margin-top: 35px;
        position: relative;
        left: -20px;
    }

    #rwgpt {
        display: none;
    }
    #postdivrich {
        display: none;
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

    .long-form-cont {
        margin-bottom: 30px;
    }

    .long-form-cont p {
        display: block;
    }

    .long-form-cont p input {
        width: 100%;
    }

    .long-form-cont label {
        display: block;
    }

    .long-form-cont textarea {
        width: 100%;
        /*min-height: 60px;*/
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
        background-image: url("<?php echo esc_url(plugins_url('../assets/checkmark.png', __FILE__)); ?>");
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
    #nextstep {
        background: #3792c8;
        color: #fff;
        border: none;
        cursor: pointer;
        padding: 5px;
        margin-bottom: 25px;
        font-size: 20px;
    }
    #rw_ts_button{
        background: #3792c8;
    }
    div#rw_ts_save_button_wrapper {
        margin-bottom: 83px;
    }
    #newtitle label {
        font-size: 25px;
    }
    #blog_ideas {
        cursor: pointer;
        display: inline-grid;
        grid-template-columns: 1fr 1fr;
        grid-column-gap: 10px;
        align-items: center;
        font-size: 14px;
    }
    #generate {
        cursor: pointer;
        background: #2271b1;
        color: #fff;
        text-align: center;
        padding: 20px;
    }
    #skip {
        cursor: pointer;
        background: #643e98;
        color: #fff;
        text-align: center;
        padding: 20px;
    }
    #skip:hover {
        background: #FFF;
        color: #643e98;
        border: 2px solid #643e98;
    }
    #generate:hover {
        background: #FFF;
        color: #2271b1;
        border: 2px solid #2271b1;
    }

    button#close {
        position: absolute;
        top: 115px;
        right: 0px;
        display: none;
    }
    label#title-prompt-text {
        display: none;
    }
    .nav-item.active {
        border-bottom: 2px solid #2271b1;
    }
    #navbar {
        text-align: center;
        padding-bottom: 10px;
    }
    .nav-item {
        margin: 33px 20px;
        padding: 9px 10px;
        font-size: 17px;
        text-decoration: none;
        color: #3792c8;
    }
    #titlewrap{
        display: none;
    }
    .step1{
        text-align: center;
    }
    #blog_ideas_wrapper {
        margin: auto;
        text-align: center;
    }
    #response {
        cursor: pointer;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-column-gap: 22px;
        grid-row-gap: 22px;
        margin: 0;
        padding: 0;
    }
    .selecttitle {
        border: 1px solid #f0f0f1;
        padding: 25px;
        background: #fbfbfb;
        font-size: 21px;
        box-shadow: 1px 2px 10px #979797;
        margin: 0;
    }
    .selecttitle:hover {
        background: #fff;
        border: 1px solid #2271b1;
        background: #2271b1;
        color: #fff;
    }
    #edit-slug-box{
        display: none;
    }

    #rw_ts_post_wrapper {
        text-align: left;
        font-size: 16px;
        margin: 0px 60px;
    }

    #gentitle {
        font-size: 27px;
    }

    #genintro {
        margin-bottom: 20px;
    }

    #genbody {
        margin-bottom: 20px;
    }
    /*#lfcboc {*/
    /*    background-image: url(https://plugin-dev.local/wp-content/plugins/talentscribe/styles/../assets/background.png);*/
    /*    background-size: cover;*/
    /*    background-color: #fff;*/
    /*}*/

/*error*/
    .ui-widget-overlay {
        background: #000000 !important;
        opacity: .8 !important;
        -ms-filter: Alpha(Opacity=30);
        z-index: 9999 !important;
    }
    .ui-dialog {
        z-index: 99999 !important ;
    }

    .ui-dialog-buttonset button {
        background: linear-gradient(90deg, rgb(52 137 191) 0%, rgb(104 77 148) 100%);
        color: #fff;
        border: none;
        cursor: pointer;
        padding: 5px;
        float: right;
    }

    .ui-dialog-buttonset button:hover {
        background: linear-gradient(90deg, rgb(104 77 148) 0%, rgb(52 137 191) 100%);
        color: #fff;
        border: none;
        cursor: pointer;
        padding: 5px;
        float: right;
    }

<!--    --><?php }
    ?>
</style>
