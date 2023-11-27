<?php

use BitApps\AssistPro\Config;

if (!defined('ABSPATH')) {
    exit;
}
?>
<style>
    .mainCard {
        margin-top: 110px;
        text-align: center;
    }

    .formCard {
        width: 40%;
        margin: 0 auto;
        background-color: #fff;
        padding: 7px 7px 7px 15px;
        border-radius: 100px;
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 5%);
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
        color: #00ffa9;
    }

    .bit-assist-logo div span {
        margin: 0 2px;
        color: #707b83;
    }

    .myBtn {
        background-color: #00ffa9;
        border: none;
        cursor: pointer;
        padding: 10px 20px;
        color: #1b0145;
        border-radius: 100px;
    }

    .formCard form {
        display: flex;
        justify-content: space-between;
    }

    .inputControl {
        width: 100%;
        border: none !important;
    }

    .inputControl:focus {
        box-shadow: none !important;
    }

    .errorMsg {
        color: red;
        margin-top: 14px;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .errorMsg svg {
        margin-right: 10px;
    }

    .successMsg {
        width: 40%;
        padding: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 140px auto 0 auto;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 5%);
    }

    .successMsg svg {
        color: green;
        width: 80px;
        height: auto;
    }

    .successMsg p {
        font-size: 18px;
        font-family: 'Outfit';
        color: #3b4e5d;
        margin-bottom: 0;
    }

    .supportLink {
        margin-top: 18px;
    }

    .supportLink a {
        display: inline-block;
    }

    .supportLink a:hover svg {
        color: #00ffa9;
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
        border: 0.15em solid #1b0145;
        color: #1b0145;
    }

    .subscribeBtn:hover {
        color: #1b0145;
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

    .autoActivateBtn {
        background-color: #00ffa9;
        padding: 13px 20px;
        color: #1b0145;
        border-radius: 12px;
        text-decoration: none;
        margin-bottom: 12px;
        display: inline-block;
        transition: box-shadow 0.3s ease !important;
        font-weight: 500;
        font-size: 16px;
        box-shadow: 1px 2px 3px 0px #a9a9a9, 1px 5px 10px 0px #00000017;
        display: flex;
        align-items: center;
        margin-left: auto;
        margin-right: auto;
        width: fit-content;
    }

    .autoActivateBtn svg {
        margin-right: 5px;
    }

    .autoActivateBtn:hover {
        color: #1b0145;
        box-shadow: 0px 3px 10px 1px rgb(0 0 0 / 20%);
    }

    .orDivider {
        color: #707b83;
        text-decoration: none;
        font-size: 15px;
        margin-bottom: 13px;
        display: inline-block;
    }
</style>

<?php
function bit_assist_get_current_admin_url()
{
    return admin_url(sprintf(basename($_SERVER['REQUEST_URI'])));
}

$licenseKey = '';
$checkForLicense = false;

if (isset($_GET['licenseKey'])) {
    $licenseKey = $_GET['licenseKey'];
    $checkForLicense = true;
} elseif (isset($_POST) && isset($_POST['licenseKey'])) {
    $licenseKey = $_POST['licenseKey'];
    $checkForLicense = true;
}

$getStatus = false;
$getErrorMsg;

function bitAssistActivateLicenseKey($licenseKey)
{
    $activationStatus = BitApps\AssistPro\Core\Update\API::activateLicense($licenseKey);
    $data = [];
    if ($activationStatus === true) {
        $data['status'] = true;
        $data['message'] = '';
    } else {
        $data['status'] = false;
        $data['message'] = $activationStatus;
    }

    return $data;
}

if (!empty($licenseKey)) {
    $status = bitAssistActivateLicenseKey($licenseKey);
    $getStatus = $status['status'];
    $getErrorMsg = $status['message'];
}
?>

<?php if ($getStatus) : ?>
<div class="successMsg">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
        <polyline points="22 4 12 14.01 9 11.01"></polyline>
    </svg>
    <p>License Key Activated Successfully</p>
</div>
<?php endif; ?>

<?php if (!$getStatus) : ?>
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
    <div>
        <a href="https://subscription.bitapps.pro/wp/activateLicense/?slug=bit-assist-pro&redirect=<?php echo bit_assist_get_current_admin_url() ?>"
            class="autoActivateBtn" tabindex="3">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                xmlns:svgjs="http://svgjs.com/svgjs" version="1.1" width="20" height="20" x="0" y="0"
                viewBox="0 0 16.91b014533 16.91b014534" style="enable-background:new 0 0 512 512" xml:space="preserve"
                class="">
                <g>
                    <g xmlns="http://www.w3.org/2000/svg" id="layer1" transform="translate(0 -280.067)">
                        <g fill="#33658a">
                            <path id="path17087"
                                d="m2.9871068 283.42851 1.5878911 1.58789c.25.25.6249998-.125.375-.375l-1.587891-1.58789c-.04977-.0512-.118087-.08-.1894531-.0801-.238968-.002-.357568.28919-.185547.45508z"
                                font-variant-ligatures="normal" font-variant-position="normal"
                                font-variant-caps="normal" font-variant-numeric="normal"
                                font-variant-alternates="normal" font-feature-settings="normal" text-indent="0"
                                text-align="start" text-decoration-line="none" text-decoration-style="solid"
                                text-decoration-color="#000000" text-transform="none" text-orientation="mixed"
                                white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal"
                                solid-color="#000000" solid-opacity="1" vector-effect="none" fill="#ede6ff"
                                data-original="#33658a" class="" />
                            <path id="path17089"
                                d="m6.5847635 281.65116a.26460982.26460982 0 0 0 -.234375.26758v1.85156a.2646485.2646485 0 1 0 .529297 0v-1.85156a.26460982.26460982 0 0 0 -.294922-.26758z"
                                font-variant-ligatures="normal" font-variant-position="normal"
                                font-variant-caps="normal" font-variant-numeric="normal"
                                font-variant-alternates="normal" font-feature-settings="normal" text-indent="0"
                                text-align="start" text-decoration-line="none" text-decoration-style="solid"
                                text-decoration-color="#000000" text-transform="none" text-orientation="mixed"
                                white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal"
                                solid-color="#000000" solid-opacity="1" vector-effect="none" fill="#ede6ff"
                                data-original="#33658a" class="" />
                            <path id="path17091"
                                d="m1.8523378 286.94609h1.851562c.3528651 0 .3528651-.5293 0-.5293h-1.865232c-.366206.0185-.338866.54782.01367.5293z"
                                font-variant-ligatures="normal" font-variant-position="normal"
                                font-variant-caps="normal" font-variant-numeric="normal"
                                font-variant-alternates="normal" font-feature-settings="normal" text-indent="0"
                                text-align="start" text-decoration-line="none" text-decoration-style="solid"
                                text-decoration-color="#000000" text-transform="none" text-orientation="mixed"
                                white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal"
                                solid-color="#000000" solid-opacity="1" vector-effect="none" fill="#ede6ff"
                                data-original="#33658a" class="" />
                            <path id="path17093"
                                d="m11.983201 292.4246 1.587891 1.58789c.25.25.625-.125.375-.375l-1.587891-1.58789c-.04977-.0512-.118087-.08-.189453-.0801-.238968-.002-.357568.2892-.185547.45508z"
                                font-variant-ligatures="normal" font-variant-position="normal"
                                font-variant-caps="normal" font-variant-numeric="normal"
                                font-variant-alternates="normal" font-feature-settings="normal" text-indent="0"
                                text-align="start" text-decoration-line="none" text-decoration-style="solid"
                                text-decoration-color="#000000" text-transform="none" text-orientation="mixed"
                                white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal"
                                solid-color="#000000" solid-opacity="1" vector-effect="none" fill="#ede6ff"
                                data-original="#33658a" class="" />
                            <path id="path17095"
                                d="m10.287889 293.02812a.26460982.26460982 0 0 0 -.234375.26757v1.85157a.264648.264648 0 1 0 .529296 0v-1.85157a.26460982.26460982 0 0 0 -.294921-.26757z"
                                font-variant-ligatures="normal" font-variant-position="normal"
                                font-variant-caps="normal" font-variant-numeric="normal"
                                font-variant-alternates="normal" font-feature-settings="normal" text-indent="0"
                                text-align="start" text-decoration-line="none" text-decoration-style="solid"
                                text-decoration-color="#000000" text-transform="none" text-orientation="mixed"
                                white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal"
                                solid-color="#000000" solid-opacity="1" vector-effect="none" fill="#ede6ff"
                                data-original="#33658a" class="" />
                            <path id="path17097"
                                d="m13.229295 290.11991a.26465.26465 0 1 0 0 .5293h1.851562a.26465.26465 0 1 0 0-.5293z"
                                font-variant-ligatures="normal" font-variant-position="normal"
                                font-variant-caps="normal" font-variant-numeric="normal"
                                font-variant-alternates="normal" font-feature-settings="normal" text-indent="0"
                                text-align="start" text-decoration-line="none" text-decoration-style="solid"
                                text-decoration-color="#000000" text-transform="none" text-orientation="mixed"
                                white-space="normal" shape-padding="0" isolation="auto" mix-blend-mode="normal"
                                solid-color="#000000" solid-opacity="1" vector-effect="none" fill="#ede6ff"
                                data-original="#33658a" class="" />
                        </g>
                        <path id="path3135"
                            d="m12.957865 280.59634c-.880547 0-1.762332.33865-2.435511 1.01183l-3.6896965 3.68556c-.282226.28205-.499748.60347-.663009.94154.472509-.22882.984906-.34722 1.498616-.34726.271838-.00002.542994.0362.808736.10025l3.2132405-3.21221c.352888-.35288.809135-.52709 1.267624-.52709.458491 0 .916805.17421 1.26969.52709.705779.70578.705779 1.82947 0 2.53525l-3.691765 3.68763c-.7060755.70563-1.8294645.70578-2.5352455 0-.399762-.39976-.568077-.93406-.514697-1.45107-.394697.0403-.779113.2119-1.085204.51779l-.4769755.47646c.1432851.59463.4439555 1.15967.9089895 1.62471 1.346332 1.34633 3.5243215 1.34583 4.8710215 0l3.691763-3.68763c1.346335-1.34634 1.346335-3.52469 0-4.87102-.673179-.67318-1.557028-1.01183-2.437577-1.01183z"
                            fill="#d3c1ff" font-variant-ligatures="normal" font-variant-position="normal"
                            font-variant-caps="normal" font-variant-numeric="normal" font-variant-alternates="normal"
                            font-feature-settings="normal" text-indent="0" text-align="start"
                            text-decoration-line="none" text-decoration-style="solid" text-decoration-color="#000000"
                            text-transform="none" text-orientation="mixed" white-space="normal" shape-padding="0"
                            isolation="auto" mix-blend-mode="normal" solid-color="#000000" solid-opacity="1"
                            vector-effect="none" data-original="#33b9ef" class="" />
                        <path id="path3152"
                            d="m3.9783939 296.4681c.8805458 0 1.7623309-.33865 2.4355096-1.01183l3.6896975-3.68556c.282226-.28205.499748-.60347.663009-.94154-.472509.22882-.9849065.34723-1.4986165.34726-.271838.00002-.542994-.0362-.808736-.10025l-3.2132398 3.21221c-.352888.35288-.8091358.52709-1.2676238.52709-.4584922 0-.9168061-.17421-1.2696909-.52709-.7057792-.70578-.7057792-1.82947 0-2.53525l3.6917655-3.68763c.706075-.70563 1.829464-.70578 2.535245 0 .399762.39976.568077.93406.514697 1.45107.394697-.0403.7791135-.2119 1.0852045-.51779l.476975-.47646c-.143285-.59463-.443955-1.15967-.908989-1.62471-1.3463325-1.34633-3.5243225-1.34583-4.8710222 0l-3.691763 3.68763c-1.34633486 1.34634-1.34633486 3.52469 0 4.87102.67318.67318 1.5570281 1.01183 2.4375781 1.01183z"
                            fill="#ede6ff" font-variant-ligatures="normal" font-variant-position="normal"
                            font-variant-caps="normal" font-variant-numeric="normal" font-variant-alternates="normal"
                            font-feature-settings="normal" text-indent="0" text-align="start"
                            text-decoration-line="none" text-decoration-style="solid" text-decoration-color="#000000"
                            text-transform="none" text-orientation="mixed" white-space="normal" shape-padding="0"
                            isolation="auto" mix-blend-mode="normal" solid-color="#000000" solid-opacity="1"
                            vector-effect="none" data-original="#33658a" class="" />
                    </g>
                </g>
            </svg>

            Connect with Bit Apps subscription
        </a>
    </div>
    <div><span class="orDivider">Or</span></div>
    <div class="formCard">
        <form action="" method="post">
            <input type="text" tabindex="4" name="licenseKey" class="inputControl" placeholder="Enter License Key here">
            <input type="submit" tabindex="5" value="Activate" class="myBtn">
        </form>
    </div>
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
    <?php if ($checkForLicense && empty($licenseKey)) : ?>
    <span class="errorMsg"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-alert-triangle">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg> License key is missing</span>
    <?php endif; ?>

    <?php if (isset($getErrorMsg) && !empty($getErrorMsg)) : ?>
    <span class="errorMsg"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-alert-triangle">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg><?= $getErrorMsg ?></span>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="footerBtn">
    <a href="https://subscription.bitapps.pro/wp/login" tabindex="10" class="subscribeBtn">Go to Subscription</a>
    <a href="<?= get_admin_url() ?>admin.php?page=bit-assist#/"
        tabindex="11" class="homeBtn">Go to Bit Assist Dashboard</a>
</div>