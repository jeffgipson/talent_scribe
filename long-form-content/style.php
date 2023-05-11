<style>
    <?php
  if (get_post_type() == 'long-form-content') {
  ?>
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
    div#rw_ts_save_button_wrapper {
        margin-bottom: 83px;
    }
    label {
        font-size: 25px;
    }
    #response {
        background: #fff;
        border: 1px solid #dddada;
        padding: 0px 16px;
        cursor: pointer;
    }
    #blog_ideas {
        cursor: pointer;
        background: #2271b1;
        color: #fff;
        padding: 1px;
        width: 182px;
        text-align: center;
        margin-top: 8px;
    }
    #blog_ideas h3 {
        color: #fff;
        font-size: 14px;
        font-weight: unset;
    }
    button#close {
        position: absolute;
        top: 115px;
        right: 0px;
    }
    <?php }
    ?>
</style>
