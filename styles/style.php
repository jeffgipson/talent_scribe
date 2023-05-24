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
</style>