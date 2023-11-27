<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<style>
    .mainCard {
        margin-top: 110px;
        text-align: center;
    }

    .bit-assist-logo svg {
        margin-bottom: 0;
        width: 80px;
        height: auto;
    }

    .bit-assist-logo p {
        margin: 0 0 5px 0;
        font-size: 20px;
        color: #46596b;
        font-family: 'Outfit';
        font-weight: 600;
    }

    .bit-assist-logo div {
        margin: 0 0 30px 0;
        display: inline-block;
    }

    .bit-assist-logo div a {
        color: #707b83;
        text-decoration: none;
        font-size: 14px;

    }

    .bit-assist-logo div a:focus-visible {
        color: red;
        border: 1px solid #000;
        padding: 3px;
    }

    .bit-assist-logo div a:focus {
        box-shadow: none;
    }

    .bit-assist-logo div a:hover {
        color: #00ffa3;
    }

    .bit-assist-logo div span {
        margin: 0 2px;
        color: #707b83;
    }

    .errorMsg {
        width: 40%;
        padding: 40px 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 30px auto 0 auto;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 5%);
    }

    .errorMsg svg {
        color: #00ffa3;
        width: 80px;
        height: auto;
    }

    .errorMsg p {
        font-size: 18px;
        font-family: 'Outfit';
        color: #3b4e5d;
        margin-bottom: 0;
    }

    .successMsg svg {
        color: green;
    }

    .formField {
        margin-top: 20px;
    }

    .formField form {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .formField form p {
        font-weight: 600;
        margin: 0 8px 0 0;
        color: #2c3b47;
    }

    .formField form input {
        cursor: pointer;
        background-color: #00ffa3;
        border: none;
        border-radius: 100px;
        padding: 8px 16px;
        color: #141844;
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 5%);
        transition: 0.3s all ease;
    }

    .formField form input:hover {
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 15%);
    }

    .backBtn {
        text-align: center;
        margin-top: 25px;
    }

    .btn2 {
        display: inline-flex;
        text-decoration: none;
        align-items: center;
        font-size: 14px;
        background-color: #03a9f4;
        color: #fff;
        padding: 5px 15px;
        border-radius: 100px;
        font-weight: 600;
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 5%);
    }

    .btn2 svg {
        width: 20px;
        margin-right: 5px;
    }

    .btn2:hover {
        color: #fff;
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 15%);
    }

    .footerBtn {
        margin-top: 60px;
        text-align: center;
    }

    .footerBtn a {
        font-weight: 400;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 100px;
        margin-right: 5px;
        transition: 0.3s all ease;
    }

    .subscribeBtn {
        border: 0.15em solid #141844;
        color: #141844;
        background-color: rgb(255 255 255 / 50%)
    }

    .subscribeBtn:hover {
        color: #141844;
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 15%);
    }

    .homeBtn {
        background-color: #0f1923;
        color: #fff;
        border: 0.15em solid #0f1923;
    }

    .homeBtn:hover {
        color: #fff;
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 15%);
    }

    .supportLink {
        margin-top: 18px;
    }

    .supportLink a {
        display: inline-block;
    }

    .supportLink a:hover svg {
        color: #00ffa3;
    }

    .supportLink a:focus-visible {
        border: 1px solid #000;
        /* padding: 5px; */
    }

    .supportLink a:focus {
        box-shadow: none;
    }

    .supportLink a svg {
        color: #92a5b3;
        width: 20px;
        height: auto;
        margin-right: 10px;
        transition: 0.3s all ease;
    }
</style>


<div class="mainCard">
    <div class="bit-assist-logo">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" version="1.2" viewBox="0 0 654 656">
            <defs>
                <clipPath id="cp1" clipPathUnits="userSpaceOnUse">
                    <path d="M0 0h654v656H0z" />
                </clipPath>
            </defs>
            <style>
                .s1 {
                    fill: #141844
                }
            </style>
            <g id="Clip-Path" clip-path="url(#cp1)">
                <g id="Layer">
                    <path
                        d="M623 419.3c-7.4 56-23.3 108.4-66.2 148.5-30.9 29-68.7 44.3-109.4 51.7-83.7 15.4-167.7 16-251.1-2.3-93.1-20.3-144.5-80.7-161.4-172.9-1.6-8.3-2.7-16.7-4-25-8.3-69.1-8.7-138.1 4-206.7 8.8-47.8 26.3-91.5 63.1-125.3 31.2-28.5 69-43.8 109.7-51.1 84.4-15.1 169-15.8 253 3.4 86.7 19.8 137.8 75.3 156.1 161.6 15.3 72.3 14.8 145.1 6.2 218.1z"
                        style="fill:#00ffa3;stroke:#141844;stroke-width:50" />
                    <path
                        d="M238.5 354c-14.6 0-26.5-11.9-26.5-26.5s11.9-26.5 26.5-26.5 26.5 11.9 26.5 26.5-11.9 26.5-26.5 26.5zM414.5 354c-14.6 0-26.5-11.9-26.5-26.5s11.9-26.5 26.5-26.5 26.5 11.9 26.5 26.5-11.9 26.5-26.5 26.5zM354 327.5c0 3.5-.7 6.9-2 10.1-1.3 3.3-3.3 6.2-5.8 8.6-2.4 2.5-5.3 4.5-8.6 5.8-3.2 1.3-6.6 2-10.1 2s-6.9-.7-10.1-2c-3.3-1.3-6.2-3.3-8.6-5.8-2.5-2.4-4.5-5.3-5.8-8.6-1.3-3.2-2-6.6-2-10.1h26.5z"
                        class="s1" />
                </g>
            </g>
        </svg>
        <p>Bit Assist</p>
        <div>
            <a href="https://docs.bit-assist.bitapps.pro/" tabindex="1" target="_blank">Docs</a>
            <span>•</span>
            <a href="https://www.bitapps.pro/contact" tabindex="2" target="_blank">Support</a>
        </div>
    </div>

    <?php
    if (isset($_POST) && isset($_POST['disconnect'])) {
        $activationStatus = BitApps\AssistPro\Core\Update\API::disconnectLicense();
        if ($activationStatus === true) { ?>
    <div class="errorMsg">
        <svg width="16px" height="16px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M13.617 3.844a2.87 2.87 0 0 0-.451-.868l1.354-1.36L13.904 1l-1.36 1.354a2.877 2.877 0 0 0-.868-.452 3.073 3.073 0 0 0-2.14.075 3.03 3.03 0 0 0-.991.664L7 4.192l4.327 4.328 1.552-1.545c.287-.287.508-.618.663-.992a3.074 3.074 0 0 0 .075-2.14zm-.889 1.804a2.15 2.15 0 0 1-.471.705l-.93.93-3.09-3.09.93-.93a2.15 2.15 0 0 1 .704-.472 2.134 2.134 0 0 1 1.689.007c.264.114.494.271.69.472.2.195.358.426.472.69a2.134 2.134 0 0 1 .007 1.688zm-4.824 4.994l1.484-1.545-.616-.622-1.49 1.551-1.86-1.859 1.491-1.552L6.291 6 4.808 7.545l-.616-.615-1.551 1.545a3 3 0 0 0-.663.998 3.023 3.023 0 0 0-.233 1.169c0 .332.05.656.15.97.105.31.258.597.459.862L1 13.834l.615.615 1.36-1.353c.265.2.552.353.862.458.314.1.638.15.97.15.406 0 .796-.077 1.17-.232.378-.155.71-.376.998-.663l1.545-1.552-.616-.615zm-2.262 2.023a2.16 2.16 0 0 1-.834.164c-.301 0-.586-.057-.855-.17a2.278 2.278 0 0 1-.697-.466 2.28 2.28 0 0 1-.465-.697 2.167 2.167 0 0 1-.17-.854 2.16 2.16 0 0 1 .642-1.545l.93-.93 3.09 3.09-.93.93a2.22 2.22 0 0 1-.711.478z" />
        </svg>
        <p>License Disconnected Successfully</p>
    </div>
    <?php
        } else { ?>
    <div class="errorMsg">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-alert-triangle">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        <p><?php echo $activationStatus; ?></p>
    </div>
    <div class="formField">
        <form action="" method="post">
            <p>Disconnect this site from license? </p>
            <input type="submit" name="disconnect" value="Disconnect">
        </form>
    </div>
    <?php
        }
    } else {
        if (!empty($integrateStatus['expireIn'])) {
            $expireInDays = (strtotime($integrateStatus['expireIn']) - time()) / DAY_IN_SECONDS;
            if ($expireInDays < 25) {
                $notice = $expireInDays > 0 ?
                    sprintf(__('Bit Assist Pro License will expire in %s days', 'bit-assist'), (int) $expireInDays)
                    : __('Bit Assist Pro License is expired', 'bit-assist')
                ?>
    <div class="errorMsg">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-alert-triangle">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        <p><?php echo $notice; ?></p>
    </div>
    <?php
            }
        } ?>
    <div class="formField">
        <form action="" method="post">
            <p>Disconnect this site from license? </p>
            <input type="submit" name="disconnect" value="Disconnect">
        </form>
    </div>
    <?php
    }
?>

    <div class="supportLink">
        <a href="mailto:support@bitapps.pro" tabindex="6" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-mail">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
        </a>
        <a href="https://www.bitapps.pro/bit-assist" tabindex="7" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-globe">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="2" y1="12" x2="22" y2="12"></line>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                </path>
            </svg>
        </a>
        <a href="https://www.facebook.com/groups/bitcommunityusers" tabindex="8" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-facebook">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
            </svg>
        </a>
        <a href="https://www.youtube.com/channel/UCjUl8UGn-G6zXZ-Wpd7Sc3g/featured" tabindex="9" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-youtube">
                <path
                    d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z">
                </path>
                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
            </svg>
        </a>
    </div>
</div>

<div class="footerBtn">
    <a href="https://subscription.bitapps.pro/wp/login" class="subscribeBtn">Go to Subscription</a>
    <a href="<?= get_admin_url() ?>admin.php?page=bit-assist#/"
        class="homeBtn">Go Bit Assist Dashboard</a>
</div>