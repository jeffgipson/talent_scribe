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
    background-image: url(<?php echo esc_url( plugins_url('../assets/background.png', __FILE__ ) ); ?>);
    background-size: cover;
    background-color: #fff;
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
    margin-top: 10px;
}

#prev {
    background: linear-gradient(90deg, rgb(52 137 191) 0%, rgb(104 77 148) 100%);
    color: #fff;
    border: none;
    cursor: pointer;
    padding: 5px;
    margin-top: 10px;
    float: left;
}

#searchbtn {
    background: linear-gradient(90deg, rgb(52 137 191) 0%, rgb(104 77 148) 100%);
    color: #fff;
    border: none;
    cursor: pointer;
    padding: 5px;
    margin-top: 10px;
}
input#search {
    margin: 10px 0;
}
/*Settings page popup*/
#myModal {
    padding: 60px 10px;
    position: fixed;
    bottom: 0;
    right: 0;
    width: 300px;
    background: linear-gradient(90deg, rgb(52 137 191) 0%, rgb(104 77 148) 100%);
    color: #fff;
    border: 1px solid #000;
    z-index: 9;
}

.close {
    float: right;
    top: -54px;
    position: relative;
    font-size: 27px;
    right: -3px;
    cursor: pointer;
    z-index: 999999;
    color: #0b0b0b;
}
textarea#user-response-input-box {
    position: fixed;
    bottom: 38px;
    width: 230px;
}
#wpfooter p {
    font-size: 13px;
    margin: 0;
    line-height: 1.55
    z-index: -1;
    position: relative;
}
div#text-bubble {
    background: #fff;
    color: #000;
    padding: 6px;
    border-radius: 27px;
    width: 225px;
    float: right;
}
.robot {
    font-size: 15px;
    background: #080101;
    border-radius: 100%;
    width: 14px;
    height: 14px;
    padding: 6px;
    top: 44px;
    position: relative;
    justify-content: center;
    display: flex;
}

#modalhead {
    background: white;
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    color: #000;
    padding: 5px 10px 5px 10px;
}
.left {
    --_d: 0%;
    border-left: var(--t) solid #0000;
    margin-right: var(--t);
    place-self: start;
}
.bubble {
    --r: 25px;
    --t: 30px;
    max-width: 300px;
    padding: calc(2*var(--r)/3);
    -webkit-mask:
            radial-gradient(var(--t) at var(--_d) 0,#0000 98%,#000 102%)
            var(--_d) 100%/calc(100% - var(--r)) var(--t) no-repeat,
            conic-gradient(at var(--r) var(--r),#000 75%,#0000 0)
            calc(var(--r)/-2) calc(var(--r)/-2) padding-box,
            radial-gradient(50% 50%,#000 98%,#0000 101%)
            0 0/var(--r) var(--r) space padding-box;
}
.modal-content {
    display: grid;
    gap: 0px;
    font-family: system-ui, sans-serif;
    font-size: 20px;
    margin-bottom: 40px;
    overflow-y: scroll;
}
.user-bubble {
    --r: 25px;
    --t: 30px;
    max-width: 300px;
    padding: calc(2*var(--r)/3);
    -webkit-mask:
            radial-gradient(var(--t) at var(--_d) 0,#0000 98%,#000 102%)
            var(--_d) 100%/calc(100% - var(--r)) var(--t) no-repeat,
            conic-gradient(at var(--r) var(--r),#000 75%,#0000 0)
            calc(var(--r)/-2) calc(var(--r)/-2) padding-box,
            radial-gradient(50% 50%,#000 98%,#0000 101%)
            0 0/var(--r) var(--r) space padding-box;
}
#user-response-submit {
    position: fixed;
    bottom: 26px;
    right: 5px;
    background: #fff;
    color: #000;
    font-size: 28px;
    padding: 9px;
    border-radius: 30px;
}
textarea#user-response-input-box {
    position: fixed;
    bottom: 9px;
    width: 230px;
    border: none;
    resize:none;
    border: none;
    overflow: auto;
    outline: none;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
}
#user-response-input {
    background: #fff;
    width: 100%;
    position: fixed;
    height: 91px;
    bottom: 0px;
    margin-left: -10px;
    padding-left: 9px;
}
#user-response-submit-button {
    cursor: pointer;
}
.right {
    --_d: 100%;
    border-right: var(--t) solid #0000;
    margin-left: var(--t);
    place-self: end;
}
.user {
    font-size: 15px;
    background: #080101;
    border-radius: 100%;
    width: 14px;
    height: 14px;
    padding: 6px;
    position: relative;
    float: right;
    top: -48px;
    right: 20px;
}
#user-text-bubble {
    background: #fff;
    color: #000;
    padding: 6px;
    border-radius: 27px;
    width: 225px;
    float: left;
    margin-top: 19px;
    left: -23px;
    position: relative;
}
.bubble {
    /* other CSS properties */
    margin-bottom: 10px; /* adjust the value as per your desired spacing */
}

.user-bubble {
    /* other CSS properties */
    margin-bottom: 10px; /* adjust the value as per your desired spacing */
}
/*.user-response {*/
/*    height: 0px;*/
/*}*/
/*.bot-response {*/
/*    height: 19px;*/
/*}*/

</style>