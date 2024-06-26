<!DOCTYPE html>
<!-- Version 3 -->
<html lang="de">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>

<meta content="IE=edge" http-equiv="X-UA-Compatible"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>

<link href="css/main.css" rel="stylesheet"/>

<title>FDPG - Forschungsdatenportal für Gesundheit</title>

<script defer="" src="js/twemoji.js"></script>
<script defer="" src="js/wp-emoji.js"></script>

<style>
                    :root {
                        --swiper-theme-color: #007aff
                    }
                    
                    .hero {
                    background-size: cover !important;
                    }
                    
                    .mySwiper {
                    
                    margin-top: -120px !important; 
                    
                    }

                    .swiper,
                    swiper-container {
                        margin-left: auto;
                        margin-right: auto;
                        position: relative;
                        overflow: hidden;
                        list-style: none;
                        padding: 0;
                        z-index: 1;
                        display: block
                    }

                    :host(.swiper-vertical)>.swiper-wrapper {
                        flex-direction: column
                    }

                    .swiper-wrapper {
                        position: relative;
                        width: 100%;
                        height: 100%;
                        z-index: 1;
                        display: flex;
                        transition-property: transform;
                        transition-timing-function: var(--swiper-wrapper-transition-timing-function, initial);
                        box-sizing: content-box
                    }

                    .swiper-android swiper-slide,
                    .swiper-wrapper {
                        transform: translate3d(0px, 0, 0)
                    }

                    .swiper-horizontal {
                        touch-action: pan-y
                    }

                    .swiper-vertical {
                        touch-action: pan-x
                    }

                    swiper-slide {
                        flex-shrink: 0;
                        width: 100%;
                        height: 100%;
                        position: relative;
                        transition-property: transform;
                        display: block
                    }

                    .swiper-slide-invisible-blank {
                        visibility: hidden
                    }

                    .swiper-autoheight,
                    .swiper-autoheight swiper-slide {
                        height: auto
                    }

                    :host(.swiper-autoheight) .swiper-wrapper {
                        align-items: flex-start;
                        transition-property: transform, height
                    }

                    .swiper-backface-hidden swiper-slide {
                        transform: translateZ(0);
                        -webkit-backface-visibility: hidden;
                        backface-visibility: hidden
                    }

                    :host(.swiper-3d.swiper-css-mode) .swiper-wrapper {
                        perspective: 1200px
                    }

                    :host(.swiper-3d) .swiper-wrapper {
                        transform-style: preserve-3d
                    }

                    .swiper-3d {
                        perspective: 1200px
                    }

                    .swiper-3d .swiper-cube-shadow,
                    .swiper-3d .swiper-slide-shadow,
                    .swiper-3d .swiper-slide-shadow-bottom,
                    .swiper-3d .swiper-slide-shadow-left,
                    .swiper-3d .swiper-slide-shadow-right,
                    .swiper-3d .swiper-slide-shadow-top,
                    .swiper-3d swiper-slide {
                        transform-style: preserve-3d
                    }

                    .swiper-3d .swiper-slide-shadow,
                    .swiper-3d .swiper-slide-shadow-bottom,
                    .swiper-3d .swiper-slide-shadow-left,
                    .swiper-3d .swiper-slide-shadow-right,
                    .swiper-3d .swiper-slide-shadow-top {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        pointer-events: none;
                        z-index: 10
                    }

                    .swiper-3d .swiper-slide-shadow {
                        background: rgba(0, 0, 0, .15)
                    }

                    .swiper-3d .swiper-slide-shadow-left {
                        background-image: linear-gradient(to left, rgba(0, 0, 0, .5), rgba(0, 0, 0, 0))
                    }

                    .swiper-3d .swiper-slide-shadow-right {
                        background-image: linear-gradient(to right, rgba(0, 0, 0, .5), rgba(0, 0, 0, 0))
                    }

                    .swiper-3d .swiper-slide-shadow-top {
                        background-image: linear-gradient(to top, rgba(0, 0, 0, .5), rgba(0, 0, 0, 0))
                    }

                    .swiper-3d .swiper-slide-shadow-bottom {
                        background-image: linear-gradient(to bottom, rgba(0, 0, 0, .5), rgba(0, 0, 0, 0))
                    }

                    :host(.swiper-css-mode)>.swiper-wrapper {
                        overflow: auto;
                        scrollbar-width: none;
                        -ms-overflow-style: none
                    }

                    :host(.swiper-css-mode)>.swiper-wrapper::-webkit-scrollbar {
                        display: none
                    }

                    .swiper-css-mode>swiper-slide {
                        scroll-snap-align: start start
                    }

                    :host(.swiper-horizontal.swiper-css-mode)>.swiper-wrapper {
                        scroll-snap-type: x mandatory
                    }

                    :host(.swiper-vertical.swiper-css-mode)>.swiper-wrapper {
                        scroll-snap-type: y mandatory
                    }

                    :host(.swiper-css-mode.swiper-free-mode)>.swiper-wrapper {
                        scroll-snap-type: none
                    }

                    .swiper-css-mode.swiper-free-mode>swiper-slide {
                        scroll-snap-align: none
                    }

                    :host(.swiper-centered)>.swiper-wrapper::before {
                        content: '';
                        flex-shrink: 0;
                        order: 9999
                    }

                    .swiper-centered>swiper-slide {
                        scroll-snap-align: center center;
                        scroll-snap-stop: always
                    }

                    .swiper-centered.swiper-horizontal>swiper-slide:first-child {
                        margin-inline-start: var(--swiper-centered-offset-before)
                    }

                    :host(.swiper-centered.swiper-horizontal)>.swiper-wrapper::before {
                        height: 100%;
                        min-height: 1px;
                        width: var(--swiper-centered-offset-after)
                    }

                    .swiper-centered.swiper-vertical>swiper-slide:first-child {
                        margin-block-start: var(--swiper-centered-offset-before)
                    }

                    :host(.swiper-centered.swiper-vertical)>.swiper-wrapper::before {
                        width: 100%;
                        min-width: 1px;
                        height: var(--swiper-centered-offset-after)
                    }

                    .swiper-lazy-preloader {
                        width: 42px;
                        height: 42px;
                        position: absolute;
                        left: 50%;
                        top: 50%;
                        margin-left: -21px;
                        margin-top: -21px;
                        z-index: 10;
                        transform-origin: 50%;
                        box-sizing: border-box;
                        border: 4px solid var(--swiper-preloader-color, var(--swiper-theme-color));
                        border-radius: 50%;
                        border-top-color: transparent
                    }

                    .swiper-watch-progress .swiper-slide-visible .swiper-lazy-preloader,
                    .swiper:not(.swiper-watch-progress) .swiper-lazy-preloader,
                    swiper-container:not(.swiper-watch-progress) .swiper-lazy-preloader {
                        animation: swiper-preloader-spin 1s infinite linear
                    }

                    .swiper-lazy-preloader-white {
                        --swiper-preloader-color: #fff
                    }

                    .swiper-lazy-preloader-black {
                        --swiper-preloader-color: #000
                    }

                    @keyframes swiper-preloader-spin {
                        0% {
                            transform: rotate(0deg)
                        }

                        100% {
                            transform: rotate(360deg)
                        }
                    }

                    .swiper-virtual swiper-slide {
                        -webkit-backface-visibility: hidden;
                        transform: translateZ(0)
                    }

                    :host(.swiper-virtual.swiper-css-mode) .swiper-wrapper::after {
                        content: '';
                        position: absolute;
                        left: 0;
                        top: 0;
                        pointer-events: none
                    }

                    :host(.swiper-virtual.swiper-css-mode.swiper-horizontal) .swiper-wrapper::after {
                        height: 1px;
                        width: var(--swiper-virtual-size)
                    }

                    :host(.swiper-virtual.swiper-css-mode.swiper-vertical) .swiper-wrapper::after {
                        width: 1px;
                        height: var(--swiper-virtual-size)
                    }

                    :root {
                        --swiper-navigation-size: 44px
                    }

                    .swiper-button-next,
                    .swiper-button-prev {
                        position: absolute;
                        top: var(--swiper-navigation-top-offset, 50%);
                        width: calc(var(--swiper-navigation-size)/ 44 * 27);
                        height: var(--swiper-navigation-size);
                        margin-top: calc(0px - (var(--swiper-navigation-size)/ 2));
                        z-index: 10;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: var(--swiper-navigation-color, var(--swiper-theme-color))
                    }

                    .swiper-button-next.swiper-button-disabled,
                    .swiper-button-prev.swiper-button-disabled {
                        opacity: .35;
                        cursor: auto;
                        pointer-events: none
                    }

                    .swiper-button-next.swiper-button-hidden,
                    .swiper-button-prev.swiper-button-hidden {
                        opacity: 0;
                        cursor: auto;
                        pointer-events: none
                    }

                    .swiper-navigation-disabled .swiper-button-next,
                    .swiper-navigation-disabled .swiper-button-prev {
                        display: none !important
                    }

                    .swiper-button-next:after,
                    .swiper-button-prev:after {
                        font-family: swiper-icons;
                        font-size: var(--swiper-navigation-size);
                        text-transform: none !important;
                        letter-spacing: 0;
                        font-variant: initial;
                        line-height: 1
                    }

                    .swiper-button-prev,
                    :host(.swiper-rtl) .swiper-button-next {
                        left: var(--swiper-navigation-sides-offset, 10px);
                        right: auto
                    }

                    .swiper-button-prev:after,
                    :host(.swiper-rtl) .swiper-button-next:after {
                        content: 'prev'
                    }

                    .swiper-button-next,
                    :host(.swiper-rtl) .swiper-button-prev {
                        right: var(--swiper-navigation-sides-offset, 10px);
                        left: auto
                    }

                    .swiper-button-next:after,
                    :host(.swiper-rtl) .swiper-button-prev:after {
                        content: 'next'
                    }

                    .swiper-button-lock {
                        display: none
                    }

                    .swiper-pagination {
                        position: absolute;
                        text-align: center;
                        transition: .3s opacity;
                        transform: translate3d(0, 0, 0);
                        z-index: 10
                    }

                    .swiper-pagination.swiper-pagination-hidden {
                        opacity: 0
                    }

                    .swiper-pagination-disabled>.swiper-pagination,
                    .swiper-pagination.swiper-pagination-disabled {
                        display: none !important
                    }

                    .swiper-horizontal>.swiper-pagination-bullets,
                    .swiper-pagination-bullets.swiper-pagination-horizontal,
                    .swiper-pagination-custom,
                    .swiper-pagination-fraction {
                        bottom: var(--swiper-pagination-bottom, 8px);
                        top: var(--swiper-pagination-top, auto);
                        left: 0;
                        width: 100%
                    }

                    .swiper-pagination-bullets-dynamic {
                        overflow: hidden;
                        font-size: 0
                    }

                    .swiper-pagination-bullets-dynamic .swiper-pagination-bullet {
                        transform: scale(.33);
                        position: relative
                    }

                    .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active {
                        transform: scale(1)
                    }

                    .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-main {
                        transform: scale(1)
                    }

                    .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-prev {
                        transform: scale(.66)
                    }

                    .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-prev-prev {
                        transform: scale(.33)
                    }

                    .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-next {
                        transform: scale(.66)
                    }

                    .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-next-next {
                        transform: scale(.33)
                    }

                    .swiper-pagination-bullet {
                        width: var(--swiper-pagination-bullet-width, var(--swiper-pagination-bullet-size, 8px));
                        height: var(--swiper-pagination-bullet-height, var(--swiper-pagination-bullet-size, 8px));
                        display: inline-block;
                        border-radius: var(--swiper-pagination-bullet-border-radius, 50%);
                        background: var(--swiper-pagination-bullet-inactive-color, #000);
                        opacity: var(--swiper-pagination-bullet-inactive-opacity, .2)
                    }

                    button.swiper-pagination-bullet {
                        border: none;
                        margin: 0;
                        padding: 0;
                        box-shadow: none;
                        -webkit-appearance: none;
                        appearance: none
                    }

                    .swiper-pagination-clickable .swiper-pagination-bullet {
                        cursor: pointer
                    }

                    .swiper-pagination-bullet:only-child {
                        display: none !important
                    }

                    .swiper-pagination-bullet-active {
                        opacity: var(--swiper-pagination-bullet-opacity, 1);
                        background: var(--swiper-pagination-color, var(--swiper-theme-color))
                    }

                    .swiper-pagination-vertical.swiper-pagination-bullets,
                    .swiper-vertical>.swiper-pagination-bullets {
                        right: var(--swiper-pagination-right, 8px);
                        left: var(--swiper-pagination-left, auto);
                        top: 50%;
                        transform: translate3d(0px, -50%, 0)
                    }

                    .swiper-pagination-vertical.swiper-pagination-bullets .swiper-pagination-bullet,
                    .swiper-vertical>.swiper-pagination-bullets .swiper-pagination-bullet {
                        margin: var(--swiper-pagination-bullet-vertical-gap, 6px) 0;
                        display: block
                    }

                    .swiper-pagination-vertical.swiper-pagination-bullets.swiper-pagination-bullets-dynamic,
                    .swiper-vertical>.swiper-pagination-bullets.swiper-pagination-bullets-dynamic {
                        top: 50%;
                        transform: translateY(-50%);
                        width: 8px
                    }

                    .swiper-pagination-vertical.swiper-pagination-bullets.swiper-pagination-bullets-dynamic .swiper-pagination-bullet,
                    .swiper-vertical>.swiper-pagination-bullets.swiper-pagination-bullets-dynamic .swiper-pagination-bullet {
                        display: inline-block;
                        transition: .2s transform, .2s top
                    }

                    .swiper-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet,
                    .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet {
                        margin: 0 var(--swiper-pagination-bullet-horizontal-gap, 4px)
                    }

                    .swiper-horizontal>.swiper-pagination-bullets.swiper-pagination-bullets-dynamic,
                    .swiper-pagination-horizontal.swiper-pagination-bullets.swiper-pagination-bullets-dynamic {
                        left: 50%;
                        transform: translateX(-50%);
                        white-space: nowrap
                    }

                    .swiper-horizontal>.swiper-pagination-bullets.swiper-pagination-bullets-dynamic .swiper-pagination-bullet,
                    .swiper-pagination-horizontal.swiper-pagination-bullets.swiper-pagination-bullets-dynamic .swiper-pagination-bullet {
                        transition: .2s transform, .2s left
                    }

                    .swiper-horizontal.swiper-rtl>.swiper-pagination-bullets-dynamic .swiper-pagination-bullet,
                    :host(.swiper-horizontal.swiper-rtl) .swiper-pagination-bullets-dynamic .swiper-pagination-bullet {
                        transition: .2s transform, .2s right
                    }

                    .swiper-pagination-fraction {
                        color: var(--swiper-pagination-fraction-color, inherit)
                    }

                    .swiper-pagination-progressbar {
                        background: var(--swiper-pagination-progressbar-bg-color, rgba(0, 0, 0, .25));
                        position: absolute
                    }

                    .swiper-pagination-progressbar .swiper-pagination-progressbar-fill {
                        background: var(--swiper-pagination-color, var(--swiper-theme-color));
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        transform: scale(0);
                        transform-origin: left top
                    }

                    :host(.swiper-rtl) .swiper-pagination-progressbar .swiper-pagination-progressbar-fill {
                        transform-origin: right top
                    }

                    .swiper-horizontal>.swiper-pagination-progressbar,
                    .swiper-pagination-progressbar.swiper-pagination-horizontal,
                    .swiper-pagination-progressbar.swiper-pagination-vertical.swiper-pagination-progressbar-opposite,
                    .swiper-vertical>.swiper-pagination-progressbar.swiper-pagination-progressbar-opposite {
                        width: 100%;
                        height: var(--swiper-pagination-progressbar-size, 4px);
                        left: 0;
                        top: 0
                    }

                    .swiper-horizontal>.swiper-pagination-progressbar.swiper-pagination-progressbar-opposite,
                    .swiper-pagination-progressbar.swiper-pagination-horizontal.swiper-pagination-progressbar-opposite,
                    .swiper-pagination-progressbar.swiper-pagination-vertical,
                    .swiper-vertical>.swiper-pagination-progressbar {
                        width: var(--swiper-pagination-progressbar-size, 4px);
                        height: 100%;
                        left: 0;
                        top: 0
                    }

                    .swiper-pagination-lock {
                        display: none
                    }

                    .swiper-scrollbar {
                        border-radius: var(--swiper-scrollbar-border-radius, 10px);
                        position: relative;
                        -ms-touch-action: none;
                        background: var(--swiper-scrollbar-bg-color, rgba(0, 0, 0, .1))
                    }

                    .swiper-scrollbar-disabled>.swiper-scrollbar,
                    .swiper-scrollbar.swiper-scrollbar-disabled {
                        display: none !important
                    }

                    .swiper-horizontal>.swiper-scrollbar,
                    .swiper-scrollbar.swiper-scrollbar-horizontal {
                        position: absolute;
                        left: var(--swiper-scrollbar-sides-offset, 1%);
                        bottom: var(--swiper-scrollbar-bottom, 4px);
                        top: var(--swiper-scrollbar-top, auto);
                        z-index: 50;
                        height: var(--swiper-scrollbar-size, 4px);
                        width: calc(100% - 2 * var(--swiper-scrollbar-sides-offset, 1%))
                    }

                    .swiper-scrollbar.swiper-scrollbar-vertical,
                    .swiper-vertical>.swiper-scrollbar {
                        position: absolute;
                        left: var(--swiper-scrollbar-left, auto);
                        right: var(--swiper-scrollbar-right, 4px);
                        top: var(--swiper-scrollbar-sides-offset, 1%);
                        z-index: 50;
                        width: var(--swiper-scrollbar-size, 4px);
                        height: calc(100% - 2 * var(--swiper-scrollbar-sides-offset, 1%))
                    }

                    .swiper-scrollbar-drag {
                        height: 100%;
                        width: 100%;
                        position: relative;
                        background: var(--swiper-scrollbar-drag-bg-color, rgba(0, 0, 0, .5));
                        border-radius: var(--swiper-scrollbar-border-radius, 10px);
                        left: 0;
                        top: 0
                    }

                    .swiper-scrollbar-cursor-drag {
                        cursor: move
                    }

                    .swiper-scrollbar-lock {
                        display: none
                    }

                    .swiper-zoom-container {
                        width: 100%;
                        height: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        text-align: center
                    }

                    .swiper-zoom-container>canvas,
                    .swiper-zoom-container>img,
                    .swiper-zoom-container>svg {
                        max-width: 100%;
                        max-height: 100%;
                        object-fit: contain
                    }

                    .swiper-slide-zoomed {
                        cursor: move;
                        touch-action: none
                    }

                    .swiper .swiper-notification,
                    swiper-container .swiper-notification {
                        position: absolute;
                        left: 0;
                        top: 0;
                        pointer-events: none;
                        opacity: 0;
                        z-index: -1000
                    }

                    :host(.swiper-free-mode)>.swiper-wrapper {
                        transition-timing-function: ease-out;
                        margin: 0 auto
                    }

                    :host(.swiper-grid)>.swiper-wrapper {
                        flex-wrap: wrap
                    }

                    :host(.swiper-grid-column)>.swiper-wrapper {
                        flex-wrap: wrap;
                        flex-direction: column
                    }

                    .swiper-fade.swiper-free-mode swiper-slide {
                        transition-timing-function: ease-out
                    }

                    .swiper-fade swiper-slide {
                        pointer-events: none;
                        transition-property: opacity
                    }

                    .swiper-fade swiper-slide swiper-slide {
                        pointer-events: none
                    }

                    .swiper-fade .swiper-slide-active,
                    .swiper-fade .swiper-slide-active .swiper-slide-active {
                        pointer-events: auto
                    }

                    .swiper-cube {
                        overflow: visible
                    }

                    .swiper-cube swiper-slide {
                        pointer-events: none;
                        -webkit-backface-visibility: hidden;
                        backface-visibility: hidden;
                        z-index: 1;
                        visibility: hidden;
                        transform-origin: 0 0;
                        width: 100%;
                        height: 100%
                    }

                    .swiper-cube swiper-slide swiper-slide {
                        pointer-events: none
                    }

                    .swiper-cube.swiper-rtl swiper-slide {
                        transform-origin: 100% 0
                    }

                    .swiper-cube .swiper-slide-active,
                    .swiper-cube .swiper-slide-active .swiper-slide-active {
                        pointer-events: auto
                    }

                    .swiper-cube .swiper-slide-active,
                    .swiper-cube .swiper-slide-next,
                    .swiper-cube .swiper-slide-prev,
                    .swiper-cube swiper-slide-next+swiper-slide {
                        pointer-events: auto;
                        visibility: visible
                    }

                    .swiper-cube .swiper-slide-shadow-bottom,
                    .swiper-cube .swiper-slide-shadow-left,
                    .swiper-cube .swiper-slide-shadow-right,
                    .swiper-cube .swiper-slide-shadow-top {
                        z-index: 0;
                        -webkit-backface-visibility: hidden;
                        backface-visibility: hidden
                    }

                    .swiper-cube .swiper-cube-shadow {
                        position: absolute;
                        left: 0;
                        bottom: 0px;
                        width: 100%;
                        height: 100%;
                        opacity: .6;
                        z-index: 0
                    }

                    .swiper-cube .swiper-cube-shadow:before {
                        content: '';
                        background: #000;
                        position: absolute;
                        left: 0;
                        top: 0;
                        bottom: 0;
                        right: 0;
                        filter: blur(50px)
                    }

                    .swiper-flip {
                        overflow: visible
                    }

                    .swiper-flip swiper-slide {
                        pointer-events: none;
                        -webkit-backface-visibility: hidden;
                        backface-visibility: hidden;
                        z-index: 1
                    }

                    .swiper-flip swiper-slide swiper-slide {
                        pointer-events: none
                    }

                    .swiper-flip .swiper-slide-active,
                    .swiper-flip .swiper-slide-active .swiper-slide-active {
                        pointer-events: auto
                    }

                    .swiper-flip .swiper-slide-shadow-bottom,
                    .swiper-flip .swiper-slide-shadow-left,
                    .swiper-flip .swiper-slide-shadow-right,
                    .swiper-flip .swiper-slide-shadow-top {
                        z-index: 0;
                        -webkit-backface-visibility: hidden;
                        backface-visibility: hidden
                    }

                    .swiper-creative swiper-slide {
                        -webkit-backface-visibility: hidden;
                        backface-visibility: hidden;
                        overflow: hidden;
                        transition-property: transform, opacity, height
                    }

                    .swiper-cards {
                        overflow: visible
                    }

                    .swiper-cards swiper-slide {
                        transform-origin: center bottom;
                        -webkit-backface-visibility: hidden;
                        backface-visibility: hidden;
                        overflow: hidden
                    }
                

            /**
 * These rules are needed for backwards compatibility.
 * They should match the button element rules in the base theme.json file.
 */
            .wp-block-button__link {
                color: #ffffff;
                background-color: #32373c;
                border-radius: 9999px;
                /* 100% causes an oval, but any explicit but really high value retains the pill shape. */

                /* This needs a low specificity so it won't override the rules from the button element if defined in theme.json. */
                box-shadow: none;
                text-decoration: none;

                /* The extra 2px are added to size solids the same as the outline versions.*/
                padding: calc(0.667em + 2px) calc(1.333em + 2px);

                font-size: 1.125em;
            }

            .wp-block-file__button {
                background: #32373c;
                color: #ffffff;
                text-decoration: none;
            }
        

                        .intro__inner ul li {
                            color: #ffffff;
                        }
                    

                                    li::before {
                                        content: "• ";
                                        padding-right: 5px;
                                        color: #FFFFFF;
                                    }
                                

            .wpml-ls-statics-footer a,
            .wpml-ls-statics-footer .wpml-ls-sub-menu a,
            .wpml-ls-statics-footer .wpml-ls-sub-menu a:link,
            .wpml-ls-statics-footer li:not(.wpml-ls-current-language) .wpml-ls-link,
            .wpml-ls-statics-footer li:not(.wpml-ls-current-language) .wpml-ls-link:link {
                color: #444444;
                background-color: #ffffff;
            }

            .wpml-ls-statics-footer a,
            .wpml-ls-statics-footer .wpml-ls-sub-menu a:hover,
            .wpml-ls-statics-footer .wpml-ls-sub-menu a:focus,
            .wpml-ls-statics-footer .wpml-ls-sub-menu a:link:hover,
            .wpml-ls-statics-footer .wpml-ls-sub-menu a:link:focus {
                color: #000000;
                background-color: #eeeeee;
            }

            .wpml-ls-statics-footer .wpml-ls-current-language>a {
                color: #444444;
                background-color: #ffffff;
            }

            .wpml-ls-statics-footer .wpml-ls-current-language:hover>a,
            .wpml-ls-statics-footer .wpml-ls-current-language>a:focus {
                color: #000000;
                background-color: #eeeeee;
            }
        

        /**
 * Core styles: block-supports
 */
    

                            #updateItemLink,
                            #updateItemLink2,
                            #updateItemLink3,
                            #updateItemLink4,
                            #updateItemLink5,
                            #updateItemLink6,
                            #updateItemLink7,
                            #updateItemLink8,
                            #updateItemLink9 {
                                cursor: pointer;
                                transition: transform 0.3s ease;
                                transform: perspective(1000px) rotateY(0);
                            }

                            #updateItemLink:hover,
                            #updateItemLink2:hover,
                            #updateItemLink3:hover,
                            #updateItemLink4:hover,
                            #updateItemLink5:hover,
                            #updateItemLink6:hover,
                            #updateItemLink7:hover,
                            #updateItemLink8:hover,
                            #updateItemLink9:hover {
                                transform: perspective(1000px) rotateY(5deg);
                            }
                        

                                .bietet li::before {
                                    color: #94c11b;
                                    content: "•";
                                    display: inline-block;
                                    font-size: 1.5em;
                                    font-weight: bold;
                                    margin-left: -1.1em;
                                    width: 1em;
                                }
                            

            img.wp-smiley,
            img.emoji {
                display: inline !important;
                border: none !important;
                box-shadow: none !important;
                height: 1em !important;
                width: 1em !important;
                margin: 0 0.07em !important;
                vertical-align: -0.1em !important;
                background: none !important;
                padding: 0 !important;
            }
        

        @font-face {
            font-family: swiper-icons;
            src: url('data:application/font-woff;charset=utf-8;base64, d09GRgABAAAAAAZgABAAAAAADAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABGRlRNAAAGRAAAABoAAAAci6qHkUdERUYAAAWgAAAAIwAAACQAYABXR1BPUwAABhQAAAAuAAAANuAY7+xHU1VCAAAFxAAAAFAAAABm2fPczU9TLzIAAAHcAAAASgAAAGBP9V5RY21hcAAAAkQAAACIAAABYt6F0cBjdnQgAAACzAAAAAQAAAAEABEBRGdhc3AAAAWYAAAACAAAAAj//wADZ2x5ZgAAAywAAADMAAAD2MHtryVoZWFkAAABbAAAADAAAAA2E2+eoWhoZWEAAAGcAAAAHwAAACQC9gDzaG10eAAAAigAAAAZAAAArgJkABFsb2NhAAAC0AAAAFoAAABaFQAUGG1heHAAAAG8AAAAHwAAACAAcABAbmFtZQAAA/gAAAE5AAACXvFdBwlwb3N0AAAFNAAAAGIAAACE5s74hXjaY2BkYGAAYpf5Hu/j+W2+MnAzMYDAzaX6QjD6/4//Bxj5GA8AuRwMYGkAPywL13jaY2BkYGA88P8Agx4j+/8fQDYfA1AEBWgDAIB2BOoAeNpjYGRgYNBh4GdgYgABEMnIABJzYNADCQAACWgAsQB42mNgYfzCOIGBlYGB0YcxjYGBwR1Kf2WQZGhhYGBiYGVmgAFGBiQQkOaawtDAoMBQxXjg/wEGPcYDDA4wNUA2CCgwsAAAO4EL6gAAeNpj2M0gyAACqxgGNWBkZ2D4/wMA+xkDdgAAAHjaY2BgYGaAYBkGRgYQiAHyGMF8FgYHIM3DwMHABGQrMOgyWDLEM1T9/w8UBfEMgLzE////P/5//f/V/xv+r4eaAAeMbAxwIUYmIMHEgKYAYjUcsDAwsLKxc3BycfPw8jEQA/gZBASFhEVExcQlJKWkZWTl5BUUlZRVVNXUNTQZBgMAAMR+E+gAEQFEAAAAKgAqACoANAA+AEgAUgBcAGYAcAB6AIQAjgCYAKIArAC2AMAAygDUAN4A6ADyAPwBBgEQARoBJAEuATgBQgFMAVYBYAFqAXQBfgGIAZIBnAGmAbIBzgHsAAB42u2NMQ6CUAyGW568x9AneYYgm4MJbhKFaExIOAVX8ApewSt4Bic4AfeAid3VOBixDxfPYEza5O+Xfi04YADggiUIULCuEJK8VhO4bSvpdnktHI5QCYtdi2sl8ZnXaHlqUrNKzdKcT8cjlq+rwZSvIVczNiezsfnP/uznmfPFBNODM2K7MTQ45YEAZqGP81AmGGcF3iPqOop0r1SPTaTbVkfUe4HXj97wYE+yNwWYxwWu4v1ugWHgo3S1XdZEVqWM7ET0cfnLGxWfkgR42o2PvWrDMBSFj/IHLaF0zKjRgdiVMwScNRAoWUoH78Y2icB/yIY09An6AH2Bdu/UB+yxopYshQiEvnvu0dURgDt8QeC8PDw7Fpji3fEA4z/PEJ6YOB5hKh4dj3EvXhxPqH/SKUY3rJ7srZ4FZnh1PMAtPhwP6fl2PMJMPDgeQ4rY8YT6Gzao0eAEA409DuggmTnFnOcSCiEiLMgxCiTI6Cq5DZUd3Qmp10vO0LaLTd2cjN4fOumlc7lUYbSQcZFkutRG7g6JKZKy0RmdLY680CDnEJ+UMkpFFe1RN7nxdVpXrC4aTtnaurOnYercZg2YVmLN/d/gczfEimrE/fs/bOuq29Zmn8tloORaXgZgGa78yO9/cnXm2BpaGvq25Dv9S4E9+5SIc9PqupJKhYFSSl47+Qcr1mYNAAAAeNptw0cKwkAAAMDZJA8Q7OUJvkLsPfZ6zFVERPy8qHh2YER+3i/BP83vIBLLySsoKimrqKqpa2hp6+jq6RsYGhmbmJqZSy0sraxtbO3sHRydnEMU4uR6yx7JJXveP7WrDycAAAAAAAH//wACeNpjYGRgYOABYhkgZgJCZgZNBkYGLQZtIJsFLMYAAAw3ALgAeNolizEKgDAQBCchRbC2sFER0YD6qVQiBCv/H9ezGI6Z5XBAw8CBK/m5iQQVauVbXLnOrMZv2oLdKFa8Pjuru2hJzGabmOSLzNMzvutpB3N42mNgZGBg4GKQYzBhYMxJLMlj4GBgAYow/P/PAJJhLM6sSoWKfWCAAwDAjgbRAAB42mNgYGBkAIIbCZo5IPrmUn0hGA0AO8EFTQAA');
            font-weight: 400;
            font-style: normal
        }

        :root {
            --swiper-theme-color: #007aff
        }

        .swiper,
        swiper-container {
            margin-left: auto;
            margin-right: auto;
            position: relative;
            overflow: hidden;
            list-style: none;
            padding: 0;
            z-index: 1;
            display: block
        }

        :host(.swiper-vertical)>.swiper-wrapper {
            flex-direction: column
        }

        .swiper-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            z-index: 1;
            display: flex;
            transition-property: transform;
            transition-timing-function: var(--swiper-wrapper-transition-timing-function, initial);
            box-sizing: content-box
        }

        .swiper-android swiper-slide,
        .swiper-wrapper {
            transform: translate3d(0px, 0, 0)
        }

        .swiper-horizontal {
            touch-action: pan-y
        }

        .swiper-vertical {
            touch-action: pan-x
        }

        swiper-slide {
            flex-shrink: 0;
            width: 100%;
            height: 100%;
            position: relative;
            transition-property: transform;
            display: block
        }

        .swiper-slide-invisible-blank {
            visibility: hidden
        }

        .swiper-autoheight,
        .swiper-autoheight swiper-slide {
            height: auto
        }

        :host(.swiper-autoheight) .swiper-wrapper {
            align-items: flex-start;
            transition-property: transform, height
        }

        .swiper-backface-hidden swiper-slide {
            transform: translateZ(0);
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden
        }

        :host(.swiper-3d.swiper-css-mode) .swiper-wrapper {
            perspective: 1200px
        }

        :host(.swiper-3d) .swiper-wrapper {
            transform-style: preserve-3d
        }

        .swiper-3d {
            perspective: 1200px
        }

        .swiper-3d .swiper-cube-shadow,
        .swiper-3d .swiper-slide-shadow,
        .swiper-3d .swiper-slide-shadow-bottom,
        .swiper-3d .swiper-slide-shadow-left,
        .swiper-3d .swiper-slide-shadow-right,
        .swiper-3d .swiper-slide-shadow-top,
        .swiper-3d swiper-slide {
            transform-style: preserve-3d
        }

        .swiper-3d .swiper-slide-shadow,
        .swiper-3d .swiper-slide-shadow-bottom,
        .swiper-3d .swiper-slide-shadow-left,
        .swiper-3d .swiper-slide-shadow-right,
        .swiper-3d .swiper-slide-shadow-top {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 10
        }

        .swiper-3d .swiper-slide-shadow {
            background: rgba(0, 0, 0, .15)
        }

        .swiper-3d .swiper-slide-shadow-left {
            background-image: linear-gradient(to left, rgba(0, 0, 0, .5), rgba(0, 0, 0, 0))
        }

        .swiper-3d .swiper-slide-shadow-right {
            background-image: linear-gradient(to right, rgba(0, 0, 0, .5), rgba(0, 0, 0, 0))
        }

        .swiper-3d .swiper-slide-shadow-top {
            background-image: linear-gradient(to top, rgba(0, 0, 0, .5), rgba(0, 0, 0, 0))
        }

        .swiper-3d .swiper-slide-shadow-bottom {
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, .5), rgba(0, 0, 0, 0))
        }

        :host(.swiper-css-mode)>.swiper-wrapper {
            overflow: auto;
            scrollbar-width: none;
            -ms-overflow-style: none
        }

        :host(.swiper-css-mode)>.swiper-wrapper::-webkit-scrollbar {
            display: none
        }

        .swiper-css-mode>swiper-slide {
            scroll-snap-align: start start
        }

        :host(.swiper-horizontal.swiper-css-mode)>.swiper-wrapper {
            scroll-snap-type: x mandatory
        }

        :host(.swiper-vertical.swiper-css-mode)>.swiper-wrapper {
            scroll-snap-type: y mandatory
        }

        :host(.swiper-css-mode.swiper-free-mode)>.swiper-wrapper {
            scroll-snap-type: none
        }

        .swiper-css-mode.swiper-free-mode>swiper-slide {
            scroll-snap-align: none
        }

        :host(.swiper-centered)>.swiper-wrapper::before {
            content: '';
            flex-shrink: 0;
            order: 9999
        }

        .swiper-centered>swiper-slide {
            scroll-snap-align: center center;
            scroll-snap-stop: always
        }

        .swiper-centered.swiper-horizontal>swiper-slide:first-child {
            margin-inline-start: var(--swiper-centered-offset-before)
        }

        :host(.swiper-centered.swiper-horizontal)>.swiper-wrapper::before {
            height: 100%;
            min-height: 1px;
            width: var(--swiper-centered-offset-after)
        }

        .swiper-centered.swiper-vertical>swiper-slide:first-child {
            margin-block-start: var(--swiper-centered-offset-before)
        }

        :host(.swiper-centered.swiper-vertical)>.swiper-wrapper::before {
            width: 100%;
            min-width: 1px;
            height: var(--swiper-centered-offset-after)
        }

        .swiper-lazy-preloader {
            width: 42px;
            height: 42px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -21px;
            margin-top: -21px;
            z-index: 10;
            transform-origin: 50%;
            box-sizing: border-box;
            border: 4px solid var(--swiper-preloader-color, var(--swiper-theme-color));
            border-radius: 50%;
            border-top-color: transparent
        }

        .swiper-watch-progress .swiper-slide-visible .swiper-lazy-preloader,
        .swiper:not(.swiper-watch-progress) .swiper-lazy-preloader,
        swiper-container:not(.swiper-watch-progress) .swiper-lazy-preloader {
            animation: swiper-preloader-spin 1s infinite linear
        }

        .swiper-lazy-preloader-white {
            --swiper-preloader-color: #fff
        }

        .swiper-lazy-preloader-black {
            --swiper-preloader-color: #000
        }

        @keyframes swiper-preloader-spin {
            0% {
                transform: rotate(0deg)
            }

            100% {
                transform: rotate(360deg)
            }
        }

        .swiper-virtual swiper-slide {
            -webkit-backface-visibility: hidden;
            transform: translateZ(0)
        }

        :host(.swiper-virtual.swiper-css-mode) .swiper-wrapper::after {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            pointer-events: none
        }

        :host(.swiper-virtual.swiper-css-mode.swiper-horizontal) .swiper-wrapper::after {
            height: 1px;
            width: var(--swiper-virtual-size)
        }

        :host(.swiper-virtual.swiper-css-mode.swiper-vertical) .swiper-wrapper::after {
            width: 1px;
            height: var(--swiper-virtual-size)
        }

        :root {
            --swiper-navigation-size: 44px
        }

        .swiper-button-next,
        .swiper-button-prev {
            position: absolute;
            top: var(--swiper-navigation-top-offset, 50%);
            width: calc(var(--swiper-navigation-size)/ 44 * 27);
            height: var(--swiper-navigation-size);
            margin-top: calc(0px - (var(--swiper-navigation-size)/ 2));
            z-index: 10;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--swiper-navigation-color, var(--swiper-theme-color))
        }

        .swiper-button-next.swiper-button-disabled,
        .swiper-button-prev.swiper-button-disabled {
            opacity: .35;
            cursor: auto;
            pointer-events: none
        }

        .swiper-button-next.swiper-button-hidden,
        .swiper-button-prev.swiper-button-hidden {
            opacity: 0;
            cursor: auto;
            pointer-events: none
        }

        .swiper-navigation-disabled .swiper-button-next,
        .swiper-navigation-disabled .swiper-button-prev {
            display: none !important
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-family: swiper-icons;
            font-size: var(--swiper-navigation-size);
            text-transform: none !important;
            letter-spacing: 0;
            font-variant: initial;
            line-height: 1
        }

        .swiper-button-prev,
        :host(.swiper-rtl) .swiper-button-next {
            left: var(--swiper-navigation-sides-offset, 10px);
            right: auto
        }

        .swiper-button-prev:after,
        :host(.swiper-rtl) .swiper-button-next:after {
            content: 'prev'
        }

        .swiper-button-next,
        :host(.swiper-rtl) .swiper-button-prev {
            right: var(--swiper-navigation-sides-offset, 10px);
            left: auto
        }

        .swiper-button-next:after,
        :host(.swiper-rtl) .swiper-button-prev:after {
            content: 'next'
        }

        .swiper-button-lock {
            display: none
        }

        .swiper-pagination {
            position: absolute;
            text-align: center;
            transition: .3s opacity;
            transform: translate3d(0, 0, 0);
            z-index: 10
        }

        .swiper-pagination.swiper-pagination-hidden {
            opacity: 0
        }

        .swiper-pagination-disabled>.swiper-pagination,
        .swiper-pagination.swiper-pagination-disabled {
            display: none !important
        }

        .swiper-horizontal>.swiper-pagination-bullets,
        .swiper-pagination-bullets.swiper-pagination-horizontal,
        .swiper-pagination-custom,
        .swiper-pagination-fraction {
            bottom: var(--swiper-pagination-bottom, 8px);
            top: var(--swiper-pagination-top, auto);
            left: 0;
            width: 100%
        }

        .swiper-pagination-bullets-dynamic {
            overflow: hidden;
            font-size: 0
        }

        .swiper-pagination-bullets-dynamic .swiper-pagination-bullet {
            transform: scale(.33);
            position: relative
        }

        .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active {
            transform: scale(1)
        }

        .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-main {
            transform: scale(1)
        }

        .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-prev {
            transform: scale(.66)
        }

        .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-prev-prev {
            transform: scale(.33)
        }

        .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-next {
            transform: scale(.66)
        }

        .swiper-pagination-bullets-dynamic .swiper-pagination-bullet-active-next-next {
            transform: scale(.33)
        }

        .swiper-pagination-bullet {
            width: var(--swiper-pagination-bullet-width, var(--swiper-pagination-bullet-size, 8px));
            height: var(--swiper-pagination-bullet-height, var(--swiper-pagination-bullet-size, 8px));
            display: inline-block;
            border-radius: var(--swiper-pagination-bullet-border-radius, 50%);
            background: var(--swiper-pagination-bullet-inactive-color, #000);
            opacity: var(--swiper-pagination-bullet-inactive-opacity, .2)
        }

        button.swiper-pagination-bullet {
            border: none;
            margin: 0;
            padding: 0;
            box-shadow: none;
            -webkit-appearance: none;
            appearance: none
        }

        .swiper-pagination-clickable .swiper-pagination-bullet {
            cursor: pointer
        }

        .swiper-pagination-bullet:only-child {
            display: none !important
        }

        .swiper-pagination-bullet-active {
            opacity: var(--swiper-pagination-bullet-opacity, 1);
            background: var(--swiper-pagination-color, var(--swiper-theme-color))
        }

        .swiper-pagination-vertical.swiper-pagination-bullets,
        .swiper-vertical>.swiper-pagination-bullets {
            right: var(--swiper-pagination-right, 8px);
            left: var(--swiper-pagination-left, auto);
            top: 50%;
            transform: translate3d(0px, -50%, 0)
        }

        .swiper-pagination-vertical.swiper-pagination-bullets .swiper-pagination-bullet,
        .swiper-vertical>.swiper-pagination-bullets .swiper-pagination-bullet {
            margin: var(--swiper-pagination-bullet-vertical-gap, 6px) 0;
            display: block
        }

        .swiper-pagination-vertical.swiper-pagination-bullets.swiper-pagination-bullets-dynamic,
        .swiper-vertical>.swiper-pagination-bullets.swiper-pagination-bullets-dynamic {
            top: 50%;
            transform: translateY(-50%);
            width: 8px
        }

        .swiper-pagination-vertical.swiper-pagination-bullets.swiper-pagination-bullets-dynamic .swiper-pagination-bullet,
        .swiper-vertical>.swiper-pagination-bullets.swiper-pagination-bullets-dynamic .swiper-pagination-bullet {
            display: inline-block;
            transition: .2s transform, .2s top
        }

        .swiper-horizontal>.swiper-pagination-bullets .swiper-pagination-bullet,
        .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet {
            margin: 0 var(--swiper-pagination-bullet-horizontal-gap, 4px)
        }

        .swiper-horizontal>.swiper-pagination-bullets.swiper-pagination-bullets-dynamic,
        .swiper-pagination-horizontal.swiper-pagination-bullets.swiper-pagination-bullets-dynamic {
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap
        }

        .swiper-horizontal>.swiper-pagination-bullets.swiper-pagination-bullets-dynamic .swiper-pagination-bullet,
        .swiper-pagination-horizontal.swiper-pagination-bullets.swiper-pagination-bullets-dynamic .swiper-pagination-bullet {
            transition: .2s transform, .2s left
        }

        .swiper-horizontal.swiper-rtl>.swiper-pagination-bullets-dynamic .swiper-pagination-bullet,
        :host(.swiper-horizontal.swiper-rtl) .swiper-pagination-bullets-dynamic .swiper-pagination-bullet {
            transition: .2s transform, .2s right
        }

        .swiper-pagination-fraction {
            color: var(--swiper-pagination-fraction-color, inherit)
        }

        .swiper-pagination-progressbar {
            background: var(--swiper-pagination-progressbar-bg-color, rgba(0, 0, 0, .25));
            position: absolute
        }

        .swiper-pagination-progressbar .swiper-pagination-progressbar-fill {
            background: var(--swiper-pagination-color, var(--swiper-theme-color));
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            transform: scale(0);
            transform-origin: left top
        }

        :host(.swiper-rtl) .swiper-pagination-progressbar .swiper-pagination-progressbar-fill {
            transform-origin: right top
        }

        .swiper-horizontal>.swiper-pagination-progressbar,
        .swiper-pagination-progressbar.swiper-pagination-horizontal,
        .swiper-pagination-progressbar.swiper-pagination-vertical.swiper-pagination-progressbar-opposite,
        .swiper-vertical>.swiper-pagination-progressbar.swiper-pagination-progressbar-opposite {
            width: 100%;
            height: var(--swiper-pagination-progressbar-size, 4px);
            left: 0;
            top: 0
        }

        .swiper-horizontal>.swiper-pagination-progressbar.swiper-pagination-progressbar-opposite,
        .swiper-pagination-progressbar.swiper-pagination-horizontal.swiper-pagination-progressbar-opposite,
        .swiper-pagination-progressbar.swiper-pagination-vertical,
        .swiper-vertical>.swiper-pagination-progressbar {
            width: var(--swiper-pagination-progressbar-size, 4px);
            height: 100%;
            left: 0;
            top: 0
        }

        .swiper-pagination-lock {
            display: none
        }

        .swiper-scrollbar {
            border-radius: var(--swiper-scrollbar-border-radius, 10px);
            position: relative;
            -ms-touch-action: none;
            background: var(--swiper-scrollbar-bg-color, rgba(0, 0, 0, .1))
        }

        .swiper-scrollbar-disabled>.swiper-scrollbar,
        .swiper-scrollbar.swiper-scrollbar-disabled {
            display: none !important
        }

        .swiper-horizontal>.swiper-scrollbar,
        .swiper-scrollbar.swiper-scrollbar-horizontal {
            position: absolute;
            left: var(--swiper-scrollbar-sides-offset, 1%);
            bottom: var(--swiper-scrollbar-bottom, 4px);
            top: var(--swiper-scrollbar-top, auto);
            z-index: 50;
            height: var(--swiper-scrollbar-size, 4px);
            width: calc(100% - 2 * var(--swiper-scrollbar-sides-offset, 1%))
        }

        .swiper-scrollbar.swiper-scrollbar-vertical,
        .swiper-vertical>.swiper-scrollbar {
            position: absolute;
            left: var(--swiper-scrollbar-left, auto);
            right: var(--swiper-scrollbar-right, 4px);
            top: var(--swiper-scrollbar-sides-offset, 1%);
            z-index: 50;
            width: var(--swiper-scrollbar-size, 4px);
            height: calc(100% - 2 * var(--swiper-scrollbar-sides-offset, 1%))
        }

        .swiper-scrollbar-drag {
            height: 100%;
            width: 100%;
            position: relative;
            background: var(--swiper-scrollbar-drag-bg-color, rgba(0, 0, 0, .5));
            border-radius: var(--swiper-scrollbar-border-radius, 10px);
            left: 0;
            top: 0
        }

        .swiper-scrollbar-cursor-drag {
            cursor: move
        }

        .swiper-scrollbar-lock {
            display: none
        }

        .swiper-zoom-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center
        }

        .swiper-zoom-container>canvas,
        .swiper-zoom-container>img,
        .swiper-zoom-container>svg {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain
        }

        .swiper-slide-zoomed {
            cursor: move;
            touch-action: none
        }

        .swiper .swiper-notification,
        swiper-container .swiper-notification {
            position: absolute;
            left: 0;
            top: 0;
            pointer-events: none;
            opacity: 0;
            z-index: -1000
        }

        :host(.swiper-free-mode)>.swiper-wrapper {
            transition-timing-function: ease-out;
            margin: 0 auto
        }

        :host(.swiper-grid)>.swiper-wrapper {
            flex-wrap: wrap
        }

        :host(.swiper-grid-column)>.swiper-wrapper {
            flex-wrap: wrap;
            flex-direction: column
        }

        .swiper-fade.swiper-free-mode swiper-slide {
            transition-timing-function: ease-out
        }

        .swiper-fade swiper-slide {
            pointer-events: none;
            transition-property: opacity
        }

        .swiper-fade swiper-slide swiper-slide {
            pointer-events: none
        }

        .swiper-fade .swiper-slide-active,
        .swiper-fade .swiper-slide-active .swiper-slide-active {
            pointer-events: auto
        }

        .swiper-cube {
            overflow: visible
        }

        .swiper-cube swiper-slide {
            pointer-events: none;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            z-index: 1;
            visibility: hidden;
            transform-origin: 0 0;
            width: 100%;
            height: 100%
        }

        .swiper-cube swiper-slide swiper-slide {
            pointer-events: none
        }

        .swiper-cube.swiper-rtl swiper-slide {
            transform-origin: 100% 0
        }

        .swiper-cube .swiper-slide-active,
        .swiper-cube .swiper-slide-active .swiper-slide-active {
            pointer-events: auto
        }

        .swiper-cube .swiper-slide-active,
        .swiper-cube .swiper-slide-next,
        .swiper-cube .swiper-slide-prev,
        .swiper-cube swiper-slide-next+swiper-slide {
            pointer-events: auto;
            visibility: visible
        }

        .swiper-cube .swiper-slide-shadow-bottom,
        .swiper-cube .swiper-slide-shadow-left,
        .swiper-cube .swiper-slide-shadow-right,
        .swiper-cube .swiper-slide-shadow-top {
            z-index: 0;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden
        }

        .swiper-cube .swiper-cube-shadow {
            position: absolute;
            left: 0;
            bottom: 0px;
            width: 100%;
            height: 100%;
            opacity: .6;
            z-index: 0
        }

        .swiper-cube .swiper-cube-shadow:before {
            content: '';
            background: #000;
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;
            filter: blur(50px)
        }

        .swiper-flip {
            overflow: visible
        }

        .swiper-flip swiper-slide {
            pointer-events: none;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            z-index: 1
        }

        .swiper-flip swiper-slide swiper-slide {
            pointer-events: none
        }

        .swiper-flip .swiper-slide-active,
        .swiper-flip .swiper-slide-active .swiper-slide-active {
            pointer-events: auto
        }

        .swiper-flip .swiper-slide-shadow-bottom,
        .swiper-flip .swiper-slide-shadow-left,
        .swiper-flip .swiper-slide-shadow-right,
        .swiper-flip .swiper-slide-shadow-top {
            z-index: 0;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden
        }

        .swiper-creative swiper-slide {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            overflow: hidden;
            transition-property: transform, opacity, height
        }

        .swiper-cards {
            overflow: visible
        }

        .swiper-cards swiper-slide {
            transform-origin: center bottom;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            overflow: hidden
        }
    

        #hmenu_load_1 .hmenu_main_holder {
            background: #fff !important;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#CCCCCC', endColorstr='#CCCCCC', GradientType=1);
        }

        #hmenu_load_1 .hmenu_navigation_holder>ul>li>a,
        #hmenu_load_1 .hmenu_social_holder>ul>li>a,
        #hmenu_load_1 .hmenu_product_holder>ul>li>a,
        #hmenu_load_1 .hmenu_toggle_holder>ul>li>a {
            color: rgba(8, 19, 64, 0.4) !important;

            font-size: 16px !important;
            font-family: Montserrat, sans-serif !important;
            line-height: 19px !important;
            padding: 5px !important;
            font-weight: 600 !important;
            position: relative !important;
        }
    

            .header {
                position: unset;
            }

            p,
            li {
                color: #666 !important;
                font-family: "Montserrat", sans-serif;
                !important;
            }

            .team-member-html .projectsCard h5 {
                height: 30px;
            }

            /* 
   Elementor-spezifische Stilisierungen
   -----------------------------------
*/

            /* 
   Ändert die Textfarbe und Unterstreichung von a-Tags, die direkte Kinder von p-Tags sind,
   und sich innerhalb eines Elements mit der Klasse "elementor" befinden.
*/
            .elementor p>a {
                color: #94c11b !important;
                text-decoration: underline !important;
            }

            /* 
   Ändert die Textfarbe und Unterstreichung von a-Tags, die direkte Kinder eines Elements 
   mit der Klasse "elementor" sind.
*/
            .elementor>a {
                color: #94c11b !important;
                text-decoration: underline !important;
            }

            /* 
   Ändert die Textfarbe und Unterstreichung von a-Tags innerhalb des "elementor-text-editor".
*/
            .elementor-text-editor a {
                color: #94c11b !important;
                text-decoration: underline !important;
            }

            /* 
   Listenpunkt-Stilisierungen
   --------------------------
   Überschreibt die globale Einstellung für Listenpunkte.
   Wird nur auf Elemente angewendet, denen in Elementor für den Abschnitt die Klasse "show-list-style" zugewiesen wurde.
*/

            .show-list-style ul li {
                padding-left: 30px;
                /* Linker Einzug für das Listenelement */
            }

            .show-list-style ul ul li {
                padding-top: 2px;
                /* Linker Einzug für das Listenelement */
            }

            .show-list-style ul li::before {
                content: "\2022";
                color: #94c11b;
                font-weight: bold;
                display: inline-block;
                width: 1em;
                font-size: 1.5em;
                margin-left: -1em;
            }

            .show-list-style ul ul li::before {
                content: "\25CB";
                font-size: 1.2em;
            }
        

        @media screen and (max-width: 1180px) {
            .nav__inner .menu-item:hover>.sub-menu {
                display: none;
            }

            li.menu-item-has-children.dropdown.active>.sub-menu {
                display: block;
                position: relative;
                top: 0;
                left: 0;
                width: 100% !IMPORTANT;
                margin-top: 10px;
                padding: 0;
            }

            .nav__inner .dropdown::before {
                -webkit-transform: translate(0) rotate(135deg) !important;
                transform: translate(0) rotate(135deg) !important;
            }

            .nav__inner .dropdown.active::before {
                -webkit-transform: translate(0) rotate(-45deg) !important;
                transform: translate(0) rotate(-45deg) !important;
            }

            .nav__inner .menu-item {
                padding: 0 !IMPORTANT;
            }

            .nav__inner .sub-menu {
                box-shadow: none;
            }

            ul#menu-header-menu {
                padding: 20px;
            }

            .nav__inner .menu-item a {
                font-size: 16px;
            }

            .nav__inner .sub-menu .menu-item a {
                white-space: wrap !IMPORTANT;
            }
        }
    

        ul#menu-header-menu,
        ul#menu-header-menu li,
        ul.sub-menu,
        ul.sub-menu li,
        wpml-ls-slot-footer {
            list-style-type: none;
        }

        ul#menu-header-menu,
        ul.sub-menu,
        wpml-ls-slot-footer {
            padding-left: 0;
        }
    

            .elementor-kit-503 {
                --e-global-color-primary: #081340;
                --e-global-color-secondary: #94C11B;
                --e-global-color-text: #666666;
                --e-global-color-accent: #61CE70;
                --e-global-typography-primary-font-family: "Montserrat";
                --e-global-typography-primary-font-size: 43px;
                --e-global-typography-primary-font-weight: 700;
                --e-global-typography-primary-line-height: 1.3em;
                --e-global-typography-secondary-font-family: "Montserrat";
                --e-global-typography-secondary-font-size: 16px;
                --e-global-typography-secondary-font-weight: 400;
                --e-global-typography-text-font-family: "Montserrat";
                --e-global-typography-text-font-weight: 400;
                --e-global-typography-accent-font-family: "Roboto";
                --e-global-typography-accent-font-weight: 500;
                color: var(--e-global-color-primary);
                font-size: 17px;
                line-height: 1.4em;
            }

            .elementor-kit-503 a {
                color: var(--e-global-color-secondary);
                text-decoration: underline;
            }

            .elementor-kit-503 a:hover {
                color: var(--e-global-color-secondary);
            }

            .elementor-kit-503 h1 {
                color: var(--e-global-color-secondary);
                font-family: "Montserrat", Sans-serif;
                font-size: 43px;
                font-weight: 700;
                line-height: 1.3em;
            }

            .elementor-kit-503 h2 {
                color: var(--e-global-color-primary);
                font-family: "Montserrat", Sans-serif;
                font-size: 32px;
                font-weight: 600;
                line-height: 1.3em;
            }

            .elementor-kit-503 button,
            .elementor-kit-503 input[type="button"],
            .elementor-kit-503 input[type="submit"],
            .elementor-kit-503 .elementor-button {
                color: #FFFFFF;
                background-color: #94C11B;
            }

            .elementor-section.elementor-section-boxed>.elementor-container {
                max-width: 1140px;
            }

            .e-con {
                --container-max-width: 1140px;
            }

            .elementor-widget:not(:last-child) {
                margin-bottom: 20px;
            }

            .elementor-element {
                --widgets-spacing: 20px;
            }

                {}

            h1.entry-title {
                display: var(--page-title-display);
            }

            @media(max-width:1024px) {
                .elementor-section.elementor-section-boxed>.elementor-container {
                    max-width: 1024px;
                }

                .e-con {
                    --container-max-width: 1024px;
                }
            }

            @media(max-width:767px) {
                .elementor-section.elementor-section-boxed>.elementor-container {
                    max-width: 767px;
                }

                .e-con {
                    --container-max-width: 767px;
                }
            }

            .elementor-widget-heading .elementor-heading-title {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-image .widget-image-caption {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-text-editor {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap {
                background-color: var(--e-global-color-primary);
            }

            .elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap,
            .elementor-widget-text-editor.elementor-drop-cap-view-default .elementor-drop-cap {
                color: var(--e-global-color-primary);
                border-color: var(--e-global-color-primary);
            }

            .elementor-widget-button .elementor-button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-divider {
                --divider-color: var(--e-global-color-secondary);
            }

            .elementor-widget-divider .elementor-divider__text {
                color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-divider.elementor-view-stacked .elementor-icon {
                background-color: var(--e-global-color-secondary);
            }

            .elementor-widget-divider.elementor-view-framed .elementor-icon,
            .elementor-widget-divider.elementor-view-default .elementor-icon {
                color: var(--e-global-color-secondary);
                border-color: var(--e-global-color-secondary);
            }

            .elementor-widget-divider.elementor-view-framed .elementor-icon,
            .elementor-widget-divider.elementor-view-default .elementor-icon svg {
                fill: var(--e-global-color-secondary);
            }

            .elementor-widget-image-box .elementor-image-box-title {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-image-box .elementor-image-box-description {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-icon.elementor-view-stacked .elementor-icon {
                background-color: var(--e-global-color-primary);
            }

            .elementor-widget-icon.elementor-view-framed .elementor-icon,
            .elementor-widget-icon.elementor-view-default .elementor-icon {
                color: var(--e-global-color-primary);
                border-color: var(--e-global-color-primary);
            }

            .elementor-widget-icon.elementor-view-framed .elementor-icon,
            .elementor-widget-icon.elementor-view-default .elementor-icon svg {
                fill: var(--e-global-color-primary);
            }

            .elementor-widget-icon-box.elementor-view-stacked .elementor-icon {
                background-color: var(--e-global-color-primary);
            }

            .elementor-widget-icon-box.elementor-view-framed .elementor-icon,
            .elementor-widget-icon-box.elementor-view-default .elementor-icon {
                fill: var(--e-global-color-primary);
                color: var(--e-global-color-primary);
                border-color: var(--e-global-color-primary);
            }

            .elementor-widget-icon-box .elementor-icon-box-title {
                color: var(--e-global-color-primary);
            }

            .elementor-widget-icon-box .elementor-icon-box-title,
            .elementor-widget-icon-box .elementor-icon-box-title a {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-icon-box .elementor-icon-box-description {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-star-rating .elementor-star-rating__title {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-image-gallery .gallery-item .gallery-caption {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-icon-list .elementor-icon-list-item:not(:last-child):after {
                border-color: var(--e-global-color-text);
            }

            .elementor-widget-icon-list .elementor-icon-list-icon i {
                color: var(--e-global-color-primary);
            }

            .elementor-widget-icon-list .elementor-icon-list-icon svg {
                fill: var(--e-global-color-primary);
            }

            .elementor-widget-icon-list .elementor-icon-list-item>.elementor-icon-list-text,
            .elementor-widget-icon-list .elementor-icon-list-item>a {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-icon-list .elementor-icon-list-text {
                color: var(--e-global-color-secondary);
            }

            .elementor-widget-counter .elementor-counter-number-wrapper {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-counter .elementor-counter-title {
                color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-progress .elementor-progress-wrapper .elementor-progress-bar {
                background-color: var(--e-global-color-primary);
            }

            .elementor-widget-progress .elementor-title {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-testimonial .elementor-testimonial-content {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-testimonial .elementor-testimonial-name {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-testimonial .elementor-testimonial-job {
                color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-tabs .elementor-tab-title,
            .elementor-widget-tabs .elementor-tab-title a {
                color: var(--e-global-color-primary);
            }

            .elementor-widget-tabs .elementor-tab-title.elementor-active,
            .elementor-widget-tabs .elementor-tab-title.elementor-active a {
                color: var(--e-global-color-accent);
            }

            .elementor-widget-tabs .elementor-tab-title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-tabs .elementor-tab-content {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-accordion .elementor-accordion-icon,
            .elementor-widget-accordion .elementor-accordion-title {
                color: var(--e-global-color-primary);
            }

            .elementor-widget-accordion .elementor-accordion-icon svg {
                fill: var(--e-global-color-primary);
            }

            .elementor-widget-accordion .elementor-active .elementor-accordion-icon,
            .elementor-widget-accordion .elementor-active .elementor-accordion-title {
                color: var(--e-global-color-accent);
            }

            .elementor-widget-accordion .elementor-active .elementor-accordion-icon svg {
                fill: var(--e-global-color-accent);
            }

            .elementor-widget-accordion .elementor-accordion-title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-accordion .elementor-tab-content {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-toggle .elementor-toggle-title,
            .elementor-widget-toggle .elementor-toggle-icon {
                color: var(--e-global-color-primary);
            }

            .elementor-widget-toggle .elementor-toggle-icon svg {
                fill: var(--e-global-color-primary);
            }

            .elementor-widget-toggle .elementor-tab-title.elementor-active a,
            .elementor-widget-toggle .elementor-tab-title.elementor-active .elementor-toggle-icon {
                color: var(--e-global-color-accent);
            }

            .elementor-widget-toggle .elementor-toggle-title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-toggle .elementor-tab-content {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-alert .elementor-alert-title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-alert .elementor-alert-description {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-item .wpml-ls-link,
            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-legacy-dropdown a {
                color: var(--e-global-color-text);
            }

            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-legacy-dropdown a:hover,
            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-legacy-dropdown a:focus,
            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-legacy-dropdown .wpml-ls-current-language:hover>a,
            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-item .wpml-ls-link:hover,
            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-item .wpml-ls-link.wpml-ls-link__active,
            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-item .wpml-ls-link.highlighted,
            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-item .wpml-ls-link:focus {
                color: var(--e-global-color-accent);
            }

            .elementor-widget-wpml-language-switcher .wpml-elementor-ls .wpml-ls-statics-post_translations {
                color: var(--e-global-color-text);
            }

            .elementor-widget-text-path {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-theme-site-logo .widget-image-caption {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-theme-site-title .elementor-heading-title {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-theme-page-title .elementor-heading-title {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-theme-post-title .elementor-heading-title {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-theme-post-excerpt .elementor-widget-container {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-theme-post-content {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-theme-post-featured-image .widget-image-caption {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-theme-archive-title .elementor-heading-title {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-archive-posts .elementor-post__title,
            .elementor-widget-archive-posts .elementor-post__title a {
                color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-archive-posts .elementor-post__meta-data {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-archive-posts .elementor-post__excerpt p {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-archive-posts .elementor-post__read-more {
                color: var(--e-global-color-accent);
            }

            .elementor-widget-archive-posts a.elementor-post__read-more {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-archive-posts .elementor-post__card .elementor-post__badge {
                background-color: var(--e-global-color-accent);
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-archive-posts .elementor-pagination {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-archive-posts .elementor-button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-archive-posts .e-load-more-message {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-archive-posts .elementor-posts-nothing-found {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-posts .elementor-post__title,
            .elementor-widget-posts .elementor-post__title a {
                color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-posts .elementor-post__meta-data {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-posts .elementor-post__excerpt p {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-posts .elementor-post__read-more {
                color: var(--e-global-color-accent);
            }

            .elementor-widget-posts a.elementor-post__read-more {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-posts .elementor-post__card .elementor-post__badge {
                background-color: var(--e-global-color-accent);
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-posts .elementor-pagination {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-posts .elementor-button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-posts .e-load-more-message {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-portfolio a .elementor-portfolio-item__overlay {
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-portfolio .elementor-portfolio-item__title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-portfolio .elementor-portfolio__filter {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-portfolio .elementor-portfolio__filter.elementor-active {
                color: var(--e-global-color-primary);
            }

            .elementor-widget-gallery .elementor-gallery-item__title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-gallery .elementor-gallery-item__description {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-gallery {
                --galleries-title-color-normal: var(--e-global-color-primary);
                --galleries-title-color-hover: var(--e-global-color-secondary);
                --galleries-pointer-bg-color-hover: var(--e-global-color-accent);
                --gallery-title-color-active: var(--e-global-color-secondary);
                --galleries-pointer-bg-color-active: var(--e-global-color-accent);
            }

            .elementor-widget-gallery .elementor-gallery-title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-form .elementor-field-group>label,
            .elementor-widget-form .elementor-field-subgroup label {
                color: var(--e-global-color-text);
            }

            .elementor-widget-form .elementor-field-group>label {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-form .elementor-field-type-html {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-form .elementor-field-group .elementor-field {
                color: var(--e-global-color-text);
            }

            .elementor-widget-form .elementor-field-group .elementor-field,
            .elementor-widget-form .elementor-field-subgroup label {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-form .elementor-button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-form .e-form__buttons__wrapper__button-next {
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-form .elementor-button[type="submit"] {
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-form .e-form__buttons__wrapper__button-previous {
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-form .elementor-message {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-form .e-form__indicators__indicator,
            .elementor-widget-form .e-form__indicators__indicator__label {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-form {
                --e-form-steps-indicator-inactive-primary-color: var(--e-global-color-text);
                --e-form-steps-indicator-active-primary-color: var(--e-global-color-accent);
                --e-form-steps-indicator-completed-primary-color: var(--e-global-color-accent);
                --e-form-steps-indicator-progress-color: var(--e-global-color-accent);
                --e-form-steps-indicator-progress-background-color: var(--e-global-color-text);
                --e-form-steps-indicator-progress-meter-color: var(--e-global-color-text);
            }

            .elementor-widget-form .e-form__indicators__indicator__progress__meter {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-login .elementor-field-group>a {
                color: var(--e-global-color-text);
            }

            .elementor-widget-login .elementor-field-group>a:hover {
                color: var(--e-global-color-accent);
            }

            .elementor-widget-login .elementor-form-fields-wrapper label {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-login .elementor-field-group .elementor-field {
                color: var(--e-global-color-text);
            }

            .elementor-widget-login .elementor-field-group .elementor-field,
            .elementor-widget-login .elementor-field-subgroup label {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-login .elementor-button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-login .elementor-widget-container .elementor-login__logged-in-message {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-slides .elementor-slide-heading {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-slides .elementor-slide-description {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-slides .elementor-slide-button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-nav-menu .elementor-nav-menu .elementor-item {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item {
                color: var(--e-global-color-text);
                fill: var(--e-global-color-text);
            }

            .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item:hover,
            .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item.elementor-item-active,
            .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item.highlighted,
            .elementor-widget-nav-menu .elementor-nav-menu--main .elementor-item:focus {
                color: var(--e-global-color-accent);
                fill: var(--e-global-color-accent);
            }

            .elementor-widget-nav-menu .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:before,
            .elementor-widget-nav-menu .elementor-nav-menu--main:not(.e--pointer-framed) .elementor-item:after {
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-nav-menu .e--pointer-framed .elementor-item:before,
            .elementor-widget-nav-menu .e--pointer-framed .elementor-item:after {
                border-color: var(--e-global-color-accent);
            }

            .elementor-widget-nav-menu {
                --e-nav-menu-divider-color: var(--e-global-color-text);
            }

            .elementor-widget-nav-menu .elementor-nav-menu--dropdown .elementor-item,
            .elementor-widget-nav-menu .elementor-nav-menu--dropdown .elementor-sub-item {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-animated-headline .elementor-headline-dynamic-wrapper path {
                stroke: var(--e-global-color-accent);
            }

            .elementor-widget-animated-headline .elementor-headline-plain-text {
                color: var(--e-global-color-secondary);
            }

            .elementor-widget-animated-headline .elementor-headline {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-animated-headline {
                --dynamic-text-color: var(--e-global-color-secondary);
            }

            .elementor-widget-animated-headline .elementor-headline-dynamic-text {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-hotspot .widget-image-caption {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-hotspot {
                --hotspot-color: var(--e-global-color-primary);
                --hotspot-box-color: var(--e-global-color-secondary);
                --tooltip-color: var(--e-global-color-secondary);
            }

            .elementor-widget-hotspot .e-hotspot__label {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-hotspot .e-hotspot__tooltip {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-price-list .elementor-price-list-header {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-price-list .elementor-price-list-price {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-price-list .elementor-price-list-description {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-price-list .elementor-price-list-separator {
                border-bottom-color: var(--e-global-color-secondary);
            }

            .elementor-widget-price-table {
                --e-price-table-header-background-color: var(--e-global-color-secondary);
            }

            .elementor-widget-price-table .elementor-price-table__heading {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-price-table .elementor-price-table__subheading {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-price-table .elementor-price-table .elementor-price-table__price {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-price-table .elementor-price-table__original-price {
                color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-price-table .elementor-price-table__period {
                color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-price-table .elementor-price-table__features-list {
                --e-price-table-features-list-color: var(--e-global-color-text);
            }

            .elementor-widget-price-table .elementor-price-table__features-list li {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-price-table .elementor-price-table__features-list li:before {
                border-top-color: var(--e-global-color-text);
            }

            .elementor-widget-price-table .elementor-price-table__button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-price-table .elementor-price-table__additional_info {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-price-table .elementor-price-table__ribbon-inner {
                background-color: var(--e-global-color-accent);
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-flip-box .elementor-flip-box__front .elementor-flip-box__layer__title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-flip-box .elementor-flip-box__front .elementor-flip-box__layer__description {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-flip-box .elementor-flip-box__back .elementor-flip-box__layer__title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-flip-box .elementor-flip-box__back .elementor-flip-box__layer__description {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-flip-box .elementor-flip-box__button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-call-to-action .elementor-cta__title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-call-to-action .elementor-cta__description {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-call-to-action .elementor-cta__button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-call-to-action .elementor-ribbon-inner {
                background-color: var(--e-global-color-accent);
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-media-carousel .elementor-carousel-image-overlay {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-testimonial-carousel .elementor-testimonial__text {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-testimonial-carousel .elementor-testimonial__name {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-testimonial-carousel .elementor-testimonial__title {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-reviews .elementor-testimonial__header,
            .elementor-widget-reviews .elementor-testimonial__name {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-reviews .elementor-testimonial__text {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-table-of-contents {
                --header-color: var(--e-global-color-secondary);
                --item-text-color: var(--e-global-color-text);
                --item-text-hover-color: var(--e-global-color-accent);
                --marker-color: var(--e-global-color-text);
            }

            .elementor-widget-table-of-contents .elementor-toc__header,
            .elementor-widget-table-of-contents .elementor-toc__header-title {
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-table-of-contents .elementor-toc__list-item {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-countdown .elementor-countdown-item {
                background-color: var(--e-global-color-primary);
            }

            .elementor-widget-countdown .elementor-countdown-digits {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-countdown .elementor-countdown-label {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-countdown .elementor-countdown-expire--message {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-search-form input[type="search"].elementor-search-form__input {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-search-form .elementor-search-form__input,
            .elementor-widget-search-form .elementor-search-form__icon,
            .elementor-widget-search-form .elementor-lightbox .dialog-lightbox-close-button,
            .elementor-widget-search-form .elementor-lightbox .dialog-lightbox-close-button:hover,
            .elementor-widget-search-form.elementor-search-form--skin-full_screen input[type="search"].elementor-search-form__input {
                color: var(--e-global-color-text);
                fill: var(--e-global-color-text);
            }

            .elementor-widget-search-form .elementor-search-form__submit {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
                background-color: var(--e-global-color-secondary);
            }

            .elementor-widget-author-box .elementor-author-box__name {
                color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-author-box .elementor-author-box__bio {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-author-box .elementor-author-box__button {
                color: var(--e-global-color-secondary);
                border-color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-author-box .elementor-author-box__button:hover {
                border-color: var(--e-global-color-secondary);
                color: var(--e-global-color-secondary);
            }

            .elementor-widget-post-navigation span.post-navigation__prev--label {
                color: var(--e-global-color-text);
            }

            .elementor-widget-post-navigation span.post-navigation__next--label {
                color: var(--e-global-color-text);
            }

            .elementor-widget-post-navigation span.post-navigation__prev--label,
            .elementor-widget-post-navigation span.post-navigation__next--label {
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-post-navigation span.post-navigation__prev--title,
            .elementor-widget-post-navigation span.post-navigation__next--title {
                color: var(--e-global-color-secondary);
                font-family: var(--e-global-typography-secondary-font-family), Sans-serif;
                font-size: var(--e-global-typography-secondary-font-size);
                font-weight: var(--e-global-typography-secondary-font-weight);
            }

            .elementor-widget-post-info .elementor-icon-list-item:not(:last-child):after {
                border-color: var(--e-global-color-text);
            }

            .elementor-widget-post-info .elementor-icon-list-icon i {
                color: var(--e-global-color-primary);
            }

            .elementor-widget-post-info .elementor-icon-list-icon svg {
                fill: var(--e-global-color-primary);
            }

            .elementor-widget-post-info .elementor-icon-list-text,
            .elementor-widget-post-info .elementor-icon-list-text a {
                color: var(--e-global-color-secondary);
            }

            .elementor-widget-post-info .elementor-icon-list-item {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-sitemap .elementor-sitemap-title {
                color: var(--e-global-color-primary);
                font-family: var(--e-global-typography-primary-font-family), Sans-serif;
                font-size: var(--e-global-typography-primary-font-size);
                font-weight: var(--e-global-typography-primary-font-weight);
                line-height: var(--e-global-typography-primary-line-height);
            }

            .elementor-widget-sitemap .elementor-sitemap-item,
            .elementor-widget-sitemap span.elementor-sitemap-list,
            .elementor-widget-sitemap .elementor-sitemap-item a {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-sitemap .elementor-sitemap-item {
                color: var(--e-global-color-text);
            }

            .elementor-widget-blockquote .elementor-blockquote__content {
                color: var(--e-global-color-text);
            }

            .elementor-widget-blockquote .elementor-blockquote__author {
                color: var(--e-global-color-secondary);
            }

            .elementor-widget-lottie {
                --caption-color: var(--e-global-color-text);
            }

            .elementor-widget-lottie .e-lottie__caption {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-video-playlist .e-tabs-header .e-tabs-title {
                color: var(--e-global-color-text);
            }

            .elementor-widget-video-playlist .e-tabs-header .e-tabs-videos-count {
                color: var(--e-global-color-text);
            }

            .elementor-widget-video-playlist .e-tabs-header .e-tabs-header-right-side i {
                color: var(--e-global-color-text);
            }

            .elementor-widget-video-playlist .e-tabs-header .e-tabs-header-right-side svg {
                fill: var(--e-global-color-text);
            }

            .elementor-widget-video-playlist .e-tab-title .e-tab-title-text {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-video-playlist .e-tab-title .e-tab-title-text a {
                color: var(--e-global-color-text);
            }

            .elementor-widget-video-playlist .e-tab-title .e-tab-duration {
                color: var(--e-global-color-text);
            }

            .elementor-widget-video-playlist .e-tabs-items-wrapper .e-tab-title:where(.e-active, :hover) .e-tab-title-text {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-video-playlist .e-tabs-items-wrapper .e-tab-title:where(.e-active, :hover) .e-tab-title-text a {
                color: var(--e-global-color-text);
            }

            .elementor-widget-video-playlist .e-tabs-items-wrapper .e-tab-title:where(.e-active, :hover) .e-tab-duration {
                color: var(--e-global-color-text);
            }

            .elementor-widget-video-playlist .e-tabs-items-wrapper .e-section-title {
                color: var(--e-global-color-text);
            }

            .elementor-widget-video-playlist .e-tabs-inner-tabs .e-inner-tabs-wrapper .e-inner-tab-title a {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-video-playlist .e-tabs-inner-tabs .e-inner-tabs-content-wrapper .e-inner-tab-content .e-inner-tab-text {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-video-playlist .e-tabs-inner-tabs .e-inner-tabs-content-wrapper .e-inner-tab-content button {
                color: var(--e-global-color-text);
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
            }

            .elementor-widget-video-playlist .e-tabs-inner-tabs .e-inner-tabs-content-wrapper .e-inner-tab-content button:hover {
                color: var(--e-global-color-text);
            }

            .elementor-widget-paypal-button .elementor-button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-paypal-button .elementor-message {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-stripe-button .elementor-button {
                font-family: var(--e-global-typography-accent-font-family), Sans-serif;
                font-weight: var(--e-global-typography-accent-font-weight);
                background-color: var(--e-global-color-accent);
            }

            .elementor-widget-stripe-button .elementor-message {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            .elementor-widget-progress-tracker .current-progress-percentage {
                font-family: var(--e-global-typography-text-font-family), Sans-serif;
                font-weight: var(--e-global-typography-text-font-weight);
            }

            @media(max-width:1024px) {
                .elementor-widget-heading .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-divider .elementor-divider__text {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-image-box .elementor-image-box-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-icon-box .elementor-icon-box-title,
                .elementor-widget-icon-box .elementor-icon-box-title a {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-counter .elementor-counter-number-wrapper {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-counter .elementor-counter-title {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-testimonial .elementor-testimonial-name {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-testimonial .elementor-testimonial-job {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-tabs .elementor-tab-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-accordion .elementor-accordion-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-toggle .elementor-toggle-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-alert .elementor-alert-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-theme-site-title .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-theme-page-title .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-theme-post-title .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-theme-archive-title .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-archive-posts .elementor-post__title,
                .elementor-widget-archive-posts .elementor-post__title a {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-archive-posts .elementor-post__meta-data {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-archive-posts .elementor-pagination {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-archive-posts .e-load-more-message {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-posts .elementor-post__title,
                .elementor-widget-posts .elementor-post__title a {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-posts .elementor-post__meta-data {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-posts .elementor-pagination {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-posts .e-load-more-message {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-portfolio .elementor-portfolio-item__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-portfolio .elementor-portfolio__filter {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-gallery .elementor-gallery-item__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-gallery .elementor-gallery-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-slides .elementor-slide-heading {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-slides .elementor-slide-description {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-nav-menu .elementor-nav-menu .elementor-item {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-animated-headline .elementor-headline {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-animated-headline .elementor-headline-dynamic-text {
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-hotspot .e-hotspot__label {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-hotspot .e-hotspot__tooltip {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-price-list .elementor-price-list-header {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-list .elementor-price-list-price {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-table .elementor-price-table__heading {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-table .elementor-price-table__subheading {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-price-table .elementor-price-table .elementor-price-table__price {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-table .elementor-price-table__original-price {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-table .elementor-price-table__period {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-flip-box .elementor-flip-box__front .elementor-flip-box__layer__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-flip-box .elementor-flip-box__back .elementor-flip-box__layer__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-call-to-action .elementor-cta__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-testimonial-carousel .elementor-testimonial__name {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-testimonial-carousel .elementor-testimonial__title {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-reviews .elementor-testimonial__header,
                .elementor-widget-reviews .elementor-testimonial__name {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-table-of-contents .elementor-toc__header,
                .elementor-widget-table-of-contents .elementor-toc__header-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-countdown .elementor-countdown-label {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-author-box .elementor-author-box__name {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-post-navigation span.post-navigation__prev--label,
                .elementor-widget-post-navigation span.post-navigation__next--label {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-post-navigation span.post-navigation__prev--title,
                .elementor-widget-post-navigation span.post-navigation__next--title {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-sitemap .elementor-sitemap-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }
            }

            @media(max-width:767px) {
                .elementor-widget-heading .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-divider .elementor-divider__text {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-image-box .elementor-image-box-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-icon-box .elementor-icon-box-title,
                .elementor-widget-icon-box .elementor-icon-box-title a {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-counter .elementor-counter-number-wrapper {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-counter .elementor-counter-title {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-testimonial .elementor-testimonial-name {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-testimonial .elementor-testimonial-job {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-tabs .elementor-tab-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-accordion .elementor-accordion-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-toggle .elementor-toggle-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-alert .elementor-alert-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-theme-site-title .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-theme-page-title .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-theme-post-title .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-theme-archive-title .elementor-heading-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-archive-posts .elementor-post__title,
                .elementor-widget-archive-posts .elementor-post__title a {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-archive-posts .elementor-post__meta-data {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-archive-posts .elementor-pagination {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-archive-posts .e-load-more-message {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-posts .elementor-post__title,
                .elementor-widget-posts .elementor-post__title a {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-posts .elementor-post__meta-data {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-posts .elementor-pagination {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-posts .e-load-more-message {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-portfolio .elementor-portfolio-item__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-portfolio .elementor-portfolio__filter {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-gallery .elementor-gallery-item__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-gallery .elementor-gallery-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-slides .elementor-slide-heading {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-slides .elementor-slide-description {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-nav-menu .elementor-nav-menu .elementor-item {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-animated-headline .elementor-headline {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-animated-headline .elementor-headline-dynamic-text {
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-hotspot .e-hotspot__label {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-hotspot .e-hotspot__tooltip {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-price-list .elementor-price-list-header {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-list .elementor-price-list-price {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-table .elementor-price-table__heading {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-table .elementor-price-table__subheading {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-price-table .elementor-price-table .elementor-price-table__price {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-table .elementor-price-table__original-price {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-price-table .elementor-price-table__period {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-flip-box .elementor-flip-box__front .elementor-flip-box__layer__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-flip-box .elementor-flip-box__back .elementor-flip-box__layer__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-call-to-action .elementor-cta__title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-testimonial-carousel .elementor-testimonial__name {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-testimonial-carousel .elementor-testimonial__title {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-reviews .elementor-testimonial__header,
                .elementor-widget-reviews .elementor-testimonial__name {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-table-of-contents .elementor-toc__header,
                .elementor-widget-table-of-contents .elementor-toc__header-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-countdown .elementor-countdown-label {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-author-box .elementor-author-box__name {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }

                .elementor-widget-post-navigation span.post-navigation__prev--label,
                .elementor-widget-post-navigation span.post-navigation__next--label {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-post-navigation span.post-navigation__prev--title,
                .elementor-widget-post-navigation span.post-navigation__next--title {
                    font-size: var(--e-global-typography-secondary-font-size);
                }

                .elementor-widget-sitemap .elementor-sitemap-title {
                    font-size: var(--e-global-typography-primary-font-size);
                    line-height: var(--e-global-typography-primary-line-height);
                }
            }
        

        @import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Noto+Sans+Georgian:wght@100..900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap");
    

        .otgs-development-site-front-end a {
            color: white;
        }

        .otgs-development-site-front-end .icon {
            background: url(#wp-content/plugins/sitepress-multilingual-cms/vendor/otgs/installer//res/img/icon-wpml-info-white.svg) no-repeat;
            width: 20px;
            height: 20px;
            display: inline-block;
            position: absolute;
            margin-left: -23px;
        }

        .otgs-development-site-front-end {
            background-size: 32px;
            padding: 22px 0px;
            font-size: 12px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            line-height: 18px;
            text-align: center;
            color: white;
            background-color: #33879E;
        }
    

        ul {
            box-sizing: border-box;
            color: #081340;
            font-family: Montserrat, sans-serif;
            font-size: 17px;
            line-height: 25.5px;
            margin: 0px;
            outline: none;
            padding: 0px;
            padding-left: 0px;
        }

        li {
            box-sizing: border-box;
            color: #666666;
            font-family: Montserrat, sans-serif;
            font-size: 17px;
            line-height: 25.5px;
            list-style: none;
            margin: 0px;
            outline: none;
            padding: 0px;
            padding-left: 30px;
        }

        li::before {
            color: #94c11b;
            content: "•";
            display: inline-block;
            font-size: 1.5em;
            font-weight: bold;
            margin-left: 0em;
            width: 1em;
        }

        .icon {
            width: 38px;
            height: auto;
            filter: invert(24%) sepia(12%) saturate(1093%) hue-rotate(202deg) brightness(86%) contrast(91%);
            /* Dunkelblaue Farbe rgba(8,19,64,.4) */
            transition: filter 0.3s;
            margin-right: 20px;
        }

        .icon:hover {
            filter: invert(19%) sepia(72%) saturate(1364%) hue-rotate(204deg) brightness(88%) contrast(91%);
            /* Dunkelblaue Farbe beim Hovern rgba(8,19,64,0.6) */
        }

        .header__inner-buttons-link.login-button {
            background-color: white;
            color: #94c11b;
            border: 1px solid #94c11b;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s;
            display: inline-block;
            margin-left: 10px;
            line-height: normal;
            vertical-align: middle;
            font-size: 16px;
        }

        .header__inner-buttons-link.login-button:hover {
            background-color: #94c11b;
            color: white;
        }


        .header__inner-buttons-link,
        .button {
            box-sizing: border-box;
            height: 40px;
        }
    

            body {
                --wp--preset--color--black: #000000;
                --wp--preset--color--cyan-bluish-gray: #abb8c3;
                --wp--preset--color--white: #ffffff;
                --wp--preset--color--pale-pink: #f78da7;
                --wp--preset--color--vivid-red: #cf2e2e;
                --wp--preset--color--luminous-vivid-orange: #ff6900;
                --wp--preset--color--luminous-vivid-amber: #fcb900;
                --wp--preset--color--light-green-cyan: #7bdcb5;
                --wp--preset--color--vivid-green-cyan: #00d084;
                --wp--preset--color--pale-cyan-blue: #8ed1fc;
                --wp--preset--color--vivid-cyan-blue: #0693e3;
                --wp--preset--color--vivid-purple: #9b51e0;
                --wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg, rgba(6, 147, 227, 1) 0%, rgb(155, 81, 224) 100%);
                --wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg, rgb(122, 220, 180) 0%, rgb(0, 208, 130) 100%);
                --wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg, rgba(252, 185, 0, 1) 0%, rgba(255, 105, 0, 1) 100%);
                --wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg, rgba(255, 105, 0, 1) 0%, rgb(207, 46, 46) 100%);
                --wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg, rgb(238, 238, 238) 0%, rgb(169, 184, 195) 100%);
                --wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg, rgb(74, 234, 220) 0%, rgb(151, 120, 209) 20%, rgb(207, 42, 186) 40%, rgb(238, 44, 130) 60%, rgb(251, 105, 98) 80%, rgb(254, 248, 76) 100%);
                --wp--preset--gradient--blush-light-purple: linear-gradient(135deg, rgb(255, 206, 236) 0%, rgb(152, 150, 240) 100%);
                --wp--preset--gradient--blush-bordeaux: linear-gradient(135deg, rgb(254, 205, 165) 0%, rgb(254, 45, 45) 50%, rgb(107, 0, 62) 100%);
                --wp--preset--gradient--luminous-dusk: linear-gradient(135deg, rgb(255, 203, 112) 0%, rgb(199, 81, 192) 50%, rgb(65, 88, 208) 100%);
                --wp--preset--gradient--pale-ocean: linear-gradient(135deg, rgb(255, 245, 203) 0%, rgb(182, 227, 212) 50%, rgb(51, 167, 181) 100%);
                --wp--preset--gradient--electric-grass: linear-gradient(135deg, rgb(202, 248, 128) 0%, rgb(113, 206, 126) 100%);
                --wp--preset--gradient--midnight: linear-gradient(135deg, rgb(2, 3, 129) 0%, rgb(40, 116, 252) 100%);
                --wp--preset--font-size--small: 13px;
                --wp--preset--font-size--medium: 20px;
                --wp--preset--font-size--large: 36px;
                --wp--preset--font-size--x-large: 42px;
                --wp--preset--spacing--20: 0.44rem;
                --wp--preset--spacing--30: 0.67rem;
                --wp--preset--spacing--40: 1rem;
                --wp--preset--spacing--50: 1.5rem;
                --wp--preset--spacing--60: 2.25rem;
                --wp--preset--spacing--70: 3.38rem;
                --wp--preset--spacing--80: 5.06rem;
                --wp--preset--shadow--natural: 6px 6px 9px rgba(0, 0, 0, 0.2);
                --wp--preset--shadow--deep: 12px 12px 50px rgba(0, 0, 0, 0.4);
                --wp--preset--shadow--sharp: 6px 6px 0px rgba(0, 0, 0, 0.2);
                --wp--preset--shadow--outlined: 6px 6px 0px -3px rgba(255, 255, 255, 1), 6px 6px rgba(0, 0, 0, 1);
                --wp--preset--shadow--crisp: 6px 6px 0px rgba(0, 0, 0, 1);
            }

            :where(.is-layout-flex) {
                gap: 0.5em;
            }

            :where(.is-layout-grid) {
                gap: 0.5em;
            }

            body .is-layout-flow>.alignleft {
                float: left;
                margin-inline-start: 0;
                margin-inline-end: 2em;
            }

            body .is-layout-flow>.alignright {
                float: right;
                margin-inline-start: 2em;
                margin-inline-end: 0;
            }

            body .is-layout-flow>.aligncenter {
                margin-left: auto !important;
                margin-right: auto !important;
            }

            body .is-layout-constrained>.alignleft {
                float: left;
                margin-inline-start: 0;
                margin-inline-end: 2em;
            }

            body .is-layout-constrained>.alignright {
                float: right;
                margin-inline-start: 2em;
                margin-inline-end: 0;
            }

            body .is-layout-constrained>.aligncenter {
                margin-left: auto !important;
                margin-right: auto !important;
            }

            body .is-layout-constrained> :where(:not(.alignleft):not(.alignright):not(.alignfull)) {
                max-width: var(--wp--style--global--content-size);
                margin-left: auto !important;
                margin-right: auto !important;
            }

            body .is-layout-constrained>.alignwide {
                max-width: var(--wp--style--global--wide-size);
            }

            body .is-layout-flex {
                display: flex;
            }

            body .is-layout-flex {
                flex-wrap: wrap;
                align-items: center;
            }

            body .is-layout-flex>* {
                margin: 0;
            }

            body .is-layout-grid {
                display: grid;
            }

            body .is-layout-grid>* {
                margin: 0;
            }

            :where(.wp-block-columns.is-layout-flex) {
                gap: 2em;
            }

            :where(.wp-block-columns.is-layout-grid) {
                gap: 2em;
            }

            :where(.wp-block-post-template.is-layout-flex) {
                gap: 1.25em;
            }

            :where(.wp-block-post-template.is-layout-grid) {
                gap: 1.25em;
            }

            .has-black-color {
                color: var(--wp--preset--color--black) !important;
            }

            .has-cyan-bluish-gray-color {
                color: var(--wp--preset--color--cyan-bluish-gray) !important;
            }

            .has-white-color {
                color: var(--wp--preset--color--white) !important;
            }

            .has-pale-pink-color {
                color: var(--wp--preset--color--pale-pink) !important;
            }

            .has-vivid-red-color {
                color: var(--wp--preset--color--vivid-red) !important;
            }

            .has-luminous-vivid-orange-color {
                color: var(--wp--preset--color--luminous-vivid-orange) !important;
            }

            .has-luminous-vivid-amber-color {
                color: var(--wp--preset--color--luminous-vivid-amber) !important;
            }

            .has-light-green-cyan-color {
                color: var(--wp--preset--color--light-green-cyan) !important;
            }

            .has-vivid-green-cyan-color {
                color: var(--wp--preset--color--vivid-green-cyan) !important;
            }

            .has-pale-cyan-blue-color {
                color: var(--wp--preset--color--pale-cyan-blue) !important;
            }

            .has-vivid-cyan-blue-color {
                color: var(--wp--preset--color--vivid-cyan-blue) !important;
            }

            .has-vivid-purple-color {
                color: var(--wp--preset--color--vivid-purple) !important;
            }

            .has-black-background-color {
                background-color: var(--wp--preset--color--black) !important;
            }

            .has-cyan-bluish-gray-background-color {
                background-color: var(--wp--preset--color--cyan-bluish-gray) !important;
            }

            .has-white-background-color {
                background-color: var(--wp--preset--color--white) !important;
            }

            .has-pale-pink-background-color {
                background-color: var(--wp--preset--color--pale-pink) !important;
            }

            .has-vivid-red-background-color {
                background-color: var(--wp--preset--color--vivid-red) !important;
            }

            .has-luminous-vivid-orange-background-color {
                background-color: var(--wp--preset--color--luminous-vivid-orange) !important;
            }

            .has-luminous-vivid-amber-background-color {
                background-color: var(--wp--preset--color--luminous-vivid-amber) !important;
            }

            .has-light-green-cyan-background-color {
                background-color: var(--wp--preset--color--light-green-cyan) !important;
            }

            .has-vivid-green-cyan-background-color {
                background-color: var(--wp--preset--color--vivid-green-cyan) !important;
            }

            .has-pale-cyan-blue-background-color {
                background-color: var(--wp--preset--color--pale-cyan-blue) !important;
            }

            .has-vivid-cyan-blue-background-color {
                background-color: var(--wp--preset--color--vivid-cyan-blue) !important;
            }

            .has-vivid-purple-background-color {
                background-color: var(--wp--preset--color--vivid-purple) !important;
            }

            .has-black-border-color {
                border-color: var(--wp--preset--color--black) !important;
            }

            .has-cyan-bluish-gray-border-color {
                border-color: var(--wp--preset--color--cyan-bluish-gray) !important;
            }

            .has-white-border-color {
                border-color: var(--wp--preset--color--white) !important;
            }

            .has-pale-pink-border-color {
                border-color: var(--wp--preset--color--pale-pink) !important;
            }

            .has-vivid-red-border-color {
                border-color: var(--wp--preset--color--vivid-red) !important;
            }

            .has-luminous-vivid-orange-border-color {
                border-color: var(--wp--preset--color--luminous-vivid-orange) !important;
            }

            .has-luminous-vivid-amber-border-color {
                border-color: var(--wp--preset--color--luminous-vivid-amber) !important;
            }

            .has-light-green-cyan-border-color {
                border-color: var(--wp--preset--color--light-green-cyan) !important;
            }

            .has-vivid-green-cyan-border-color {
                border-color: var(--wp--preset--color--vivid-green-cyan) !important;
            }

            .has-pale-cyan-blue-border-color {
                border-color: var(--wp--preset--color--pale-cyan-blue) !important;
            }

            .has-vivid-cyan-blue-border-color {
                border-color: var(--wp--preset--color--vivid-cyan-blue) !important;
            }

            .has-vivid-purple-border-color {
                border-color: var(--wp--preset--color--vivid-purple) !important;
            }

            .has-vivid-cyan-blue-to-vivid-purple-gradient-background {
                background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;
            }

            .has-light-green-cyan-to-vivid-green-cyan-gradient-background {
                background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;
            }

            .has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background {
                background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;
            }

            .has-luminous-vivid-orange-to-vivid-red-gradient-background {
                background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;
            }

            .has-very-light-gray-to-cyan-bluish-gray-gradient-background {
                background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;
            }

            .has-cool-to-warm-spectrum-gradient-background {
                background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;
            }

            .has-blush-light-purple-gradient-background {
                background: var(--wp--preset--gradient--blush-light-purple) !important;
            }

            .has-blush-bordeaux-gradient-background {
                background: var(--wp--preset--gradient--blush-bordeaux) !important;
            }

            .has-luminous-dusk-gradient-background {
                background: var(--wp--preset--gradient--luminous-dusk) !important;
            }

            .has-pale-ocean-gradient-background {
                background: var(--wp--preset--gradient--pale-ocean) !important;
            }

            .has-electric-grass-gradient-background {
                background: var(--wp--preset--gradient--electric-grass) !important;
            }

            .has-midnight-gradient-background {
                background: var(--wp--preset--gradient--midnight) !important;
            }

            .has-small-font-size {
                font-size: var(--wp--preset--font-size--small) !important;
            }

            .has-medium-font-size {
                font-size: var(--wp--preset--font-size--medium) !important;
            }

            .has-large-font-size {
                font-size: var(--wp--preset--font-size--large) !important;
            }

            .has-x-large-font-size {
                font-size: var(--wp--preset--font-size--x-large) !important;
            }

            .wp-block-navigation a:where(:not(.wp-element-button)) {
                color: inherit;
            }

            :where(.wp-block-post-template.is-layout-flex) {
                gap: 1.25em;
            }

            :where(.wp-block-post-template.is-layout-grid) {
                gap: 1.25em;
            }

            :where(.wp-block-columns.is-layout-flex) {
                gap: 2em;
            }

            :where(.wp-block-columns.is-layout-grid) {
                gap: 2em;
            }

            .wp-block-pullquote {
                font-size: 1.5em;
                line-height: 1.6;
            }
        </style></head>
<body class="stk--anim-init">
<!-- header start
    =========================================== -->
<header class="header" id="header">
<div class="auto__container" id="auto__container">
<div class="header__inner">
<div class="header__inner-logo">
<a data-wpel-link="internal" href="#" rel="" target="_self">
<img alt="Forschen für Gesundheit" src="images/logo.webp"/>
</a>
</div>
<nav class="nav">
<ul class="nav__inner" id="menu-header-menu" style="z-index: 20;">
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-650 dropdown">
<a href="#">Forschungsprojekte</a>
<ul class="sub-menu">
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-355">
<a data-wpel-link="internal" href="#projektregister/" rel="" target="_self">Projektregister</a>
</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-450">
<a data-wpel-link="internal" href="#patienteninformationen/" rel="" target="_self">Patienteninformationen</a>
</li>
</ul>
</li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-652 dropdown">
<a href="#">Ein Projekt durchführen</a>
<ul class="sub-menu">
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-346">
<a data-wpel-link="internal" href="#daten-und-bioproben/" rel="" target="_self">Daten und Bioproben</a>
</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-434">
<a data-wpel-link="internal" href="#daten-und-bioproben/prozesse-der-antragstellung-und-datennutzung-in-der-mii/" rel="" target="_self">Prozesse der Antragstellung und
                                        Datennutzung in der MII</a>
</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-406">
<a data-wpel-link="internal" href="#daten-und-bioproben/projekt-vorbereiten/" rel="" target="_self">Projekt vorbereiten</a>
</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-373">
<a data-wpel-link="internal" href="#daten-und-bioproben/daten-finden/" rel="" target="_self">Daten und Bioproben finden</a>
</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-396">
<a data-wpel-link="internal" href="#daten-und-bioproben/daten-und-bioproben-fur-ein-forschungsprojekt-beantragen/" rel="" target="_self">Daten und Bioproben für ein
                                        Forschungsprojekt beantragen</a>
</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-654">
<a data-wpel-link="internal" href="#daten-und-bioproben/daten-und-proben-analysieren/" rel="" target="_self">Daten und Proben analysieren</a>
</li>
</ul>
</li>
<li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-653 dropdown">
<a href="#">Infrastruktur MII</a>
<ul class="sub-menu">
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-661">
<a data-wpel-link="internal" href="#diz/" rel="" target="_self">Standorte</a>
</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-661">
<a data-wpel-link="internal" href="#uber-das-forschungsdatenportal/" rel="" target="_self">Über das
                                        Forschungsdatenportal</a>
</li>
<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-661">
<a data-wpel-link="internal" href="#datengeber-werden/" rel="" target="_self">Datengeber werden</a>
</li>
</ul>
</li>
</ul>
</nav>
<div class="header__inner-buttons">
<a class="header__inner-buttons-link" data-wpel-link="internal" href="#leichte-sprache" rel="" target="_self">
<img alt="Icon 1" class="icon" src="images/leichte-sprache.webp"/>
</a>
<a class="header__inner-buttons-link" data-wpel-link="internal" href="#informationen-in-gebaerdensprache/" rel="" target="_self">
<img alt="Icon 2" class="icon" src="images/gebaer-sprache.webp"/>
</a>
<a class="header__inner-buttons-link login-button" data-wpel-link="internal" href="#" rel="" target="_self">Login</a>
<a class="button" data-wpel-link="internal" href="#registrierung/" rel="" style="margin-left: 15px !important" target="_self">Registrierung</a>
<div class="burger" id="menuBtn">
<span></span>
</div>
</div>
</div>
</div>
<title>Forschen für Gesundheit</title>
<meta content="noindex, nofollow" name="robots"/>
<link href="#" hreflang="de" rel="alternate"/>
<link href="#" hreflang="x-default" rel="alternate"/>
<script async="" id="sendinblue-js" src="js/sa.js" type="text/javascript"></script>

<link href="css/style.css" id="wp-block-library-css" media="all" rel="stylesheet" type="text/css"/>


<link href="css/styles.css" id="wpml-blocks-css" media="all" rel="stylesheet" type="text/css"/>
<link href="css/dashicons.css" id="dashicons-css" media="all" rel="stylesheet" type="text/css"/>
<link href="css/font-awesome.min.css" id="wpel-font-awesome-css" media="all" rel="stylesheet" type="text/css"/>
<link href="css/wpel.css" id="wpel-style-css" media="all" rel="stylesheet" type="text/css"/>
<link href="css/style.min.css" id="wpml-legacy-horizontal-list-0-css" media="all" rel="stylesheet" type="text/css"/>

<link href="css/style(1).css" id="htmlwp-style-css" media="all" rel="stylesheet" type="text/css"/>
<link href="css/elementor-icons.css" id="elementor-icons-css" media="all" rel="stylesheet" type="text/css"/>
<link href="css/frontend-legacy.css" id="elementor-frontend-legacy-css" media="all" rel="stylesheet" type="text/css"/>
<link href="css/frontend.css" id="elementor-frontend-css" media="all" rel="stylesheet" type="text/css"/>

<link href="css/swiper.css" id="swiper-css" media="all" rel="stylesheet" type="text/css"/>
<link href="css/frontend(1).css" id="elementor-pro-css" media="all" rel="stylesheet" type="text/css"/>
<link href="css/mailin-front.css" id="sib-front-css-css" media="all" rel="stylesheet" type="text/css"/>
<link href="images/css" id="google-fonts-1-css" media="all" rel="stylesheet" type="text/css"/>
<script id="wpml-cookie-js-extra" type="text/javascript">
            /* <![CDATA[ */
            var wpml_cookies = { "wp-wpml_current_language": { "value": "de", "expires": 1, "path": "\/" } };
            var wpml_cookies = { "wp-wpml_current_language": { "value": "de", "expires": 1, "path": "\/" } };
            /* ]]> */
        </script>
<script data-wp-strategy="defer" defer="defer" id="wpml-cookie-js" src="js/language-cookie.js" type="text/javascript"></script>
<script id="jquery-core-js" src="js/jquery.js" type="text/javascript"></script>
<script id="jquery-migrate-js" src="js/jquery-migrate.js" type="text/javascript"></script>
<script id="awp-tracking-script-js" src="js/tracking-info.js" type="text/javascript"></script>
<script id="sib-front-js-js" src="js/mailin-front.js" type="text/javascript"></script>
<link href="#wp-json/wp/v2/pages/352" rel="alternate" type="application/json"/>
<link href="#xmlrpc.php?rsd" rel="EditURI" title="RSD" type="application/rsd+xml"/>
<meta content="WordPress 6.4.3" name="generator"/>
<meta content="WPML ver:4.6.8 stt:1,3;" name="generator"/>
<meta content="/wp-content/uploads/simply-static/configs/" name="ssp-config-path"/>
<meta content="Elementor 3.14.0; settings: css_print_method-internal, google_font-enabled, font_display-swap" name="generator"/>
<script type="text/javascript">
            (function () {
                window.sib = { equeue: [], client_key: "p8bfv41t92vuv6rl5uzqwl8x" };/* OPTIONAL: email for identify request*/
                window.sib.email_id = "";
                window.sendinblue = {}; for (var j = ['track', 'identify', 'trackLink', 'page'], i = 0; i < j.length; i++) { (function (k) { window.sendinblue[k] = function () { var arg = Array.prototype.slice.call(arguments); (window.sib[k] || function () { var t = {}; t[k] = arg; window.sib.equeue.push(t); })(arg[0], arg[1], arg[2]); }; })(j[i]); } var n = document.createElement("script"), i = document.getElementsByTagName("script")[0]; n.type = "text/javascript", n.id = "sendinblue-js", n.async = !0, n.src = "" + window.sib.client_key, i.parentNode.insertBefore(n, i), window.sendinblue.page();
            })();
        </script>
<link href="#wp-content/uploads/2023/02/cropped-zars-logo-ui-32x32.png" rel="icon" sizes="32x32"/>
<link href="#wp-content/uploads/2023/02/cropped-zars-logo-ui-192x192.png" rel="icon" sizes="192x192"/>
<link href="#wp-content/uploads/2023/02/cropped-zars-logo-ui-180x180.png" rel="apple-touch-icon"/>
<meta content="#wp-content/uploads/2023/02/cropped-zars-logo-ui-270x270.png" name="msapplication-TileImage"/>

</header>
<script type="text/javascript">
        requestAnimationFrame(() => document.body.classList.add("stk--anim-init"))
    </script>
<script type="text/javascript">
        if (typeof ClipboardJS !== 'undefined') {
            new ClipboardJS('.btn');
        }
    </script>
<script id="tmpl-elementor-templates-modal__header" type="text/template">
  <div class="elementor-templates-modal__header__logo-area"></div>
  <div class="elementor-templates-modal__header__menu-area"></div>
  <div class="elementor-templates-modal__header__items-area">
    <# if ( closeType ) { #>
      <div class="elementor-templates-modal__header__close elementor-templates-modal__header__close--{{{ closeType }}} elementor-templates-modal__header__item">
        <# if ( 'skip' === closeType ) { #>
          <span>Skip</span>
        <# } #>
        <i class="eicon-close" aria-hidden="true"></i>
        <span class="elementor-screen-only">Close</span>
      </div>
    <# } #>
    <div id="elementor-template-library-header-tools"></div>
  </div>
</script>
<script id="tmpl-elementor-templates-modal__header__logo" type="text/template">
  <span class="elementor-templates-modal__header__logo__icon-wrapper e-logo-wrapper">
    <i class="eicon-elementor"></i>
  </span>
  <span class="elementor-templates-modal__header__logo__title">{{{ title }}}</span>
</script>
<script id="tmpl-elementor-finder" type="text/template">
  <div id="elementor-finder__search">
    <i class="eicon-search" aria-hidden="true"></i>
    <input id="elementor-finder__search__input" placeholder="Type to find anything in Elementor" autocomplete="off">
  </div>
  <div id="elementor-finder__content"></div>
</script>
<script id="tmpl-elementor-finder-results-container" type="text/template">
  <div id="elementor-finder__no-results">No Results Found</div>
  <div id="elementor-finder__results"></div>
</script>
<script id="tmpl-elementor-finder__results__category" type="text/template">
  <div class="elementor-finder__results__category__title">{{{ title }}}</div>
  <div class="elementor-finder__results__category__items"></div>
</script>
<script id="tmpl-elementor-finder__results__item" type="text/template">
  <a href="{{ url }}" class="elementor-finder__results__item__link">
    <div class="elementor-finder__results__item__icon">
      <i class="eicon-{{{ icon }}}" aria-hidden="true"></i>
    </div>
    <div class="elementor-finder__results__item__title">{{{ title }}}</div>
    <# if ( description ) { #>
      <div class="elementor-finder__results__item__description">- {{{ description }}}</div>
    <# } #>

    <# if ( lock ) { #>
      <div class="elementor-finder__results__item__badge"><i class="{{{ lock.badge.icon }}}"></i>{{ lock.badge.text }}</div>
    <# } #>
  </a>
  <# if ( actions.length ) { #>
    <div class="elementor-finder__results__item__actions">
    <# jQuery.each( actions, function() { #>
      <a class="elementor-finder__results__item__action elementor-finder__results__item__action--{{ this.name }}" href="{{ this.url }}" target="_blank">
        <i class="eicon-{{{ this.icon }}}"></i>
      </a>
    <# } ); #>
    </div>
  <# } #>
</script>
<!--   </body>
</html> -->
<!-- header end
=========================================== -->
<main class="main">
<div class="main__bg">
<div class="main__bg-image">
<img alt="" src="images/main-bg.svg"/>
</div>
</div>
<!-- hero start
  =========================================== -->
<swiper-container class="mySwiper swiper-initialized swiper-horizontal swiper-backface-hidden" navigation="true">

<template shadowrootmode="open">

<slot name="container-start"></slot>
<div aria-live="polite" class="swiper-wrapper" id="swiper-wrapper-0f33af674d53a1019">
<slot></slot>
</div>
<slot name="container-end"></slot>
<div aria-controls="swiper-wrapper-0f33af674d53a1019" aria-disabled="true" aria-label="Previous slide" class="swiper-button-prev swiper-button-disabled" part="button-prev" role="button" tabindex="-1">
</div>
<div aria-controls="swiper-wrapper-0f33af674d53a1019" aria-disabled="false" aria-label="Next slide" class="swiper-button-next" part="button-next" role="button" tabindex="0"></div><span aria-atomic="true" aria-live="assertive" class="swiper-notification"></span>
</template>
<swiper-slide aria-label="1 / 2" class="swiper-slide-active" role="group" style="width: 1272px;"><template shadowrootmode="open">
<slot></slot>
</template>
<section class="hero" style="background:  linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%), url(images/forscher-slider.jpg) no-repeat center top;">
<div class="auto__container">
<div class="hero__inner">
<div class="hero__inner-content">
<h1>Daten für mein Forschungsprojekt<br/><span>zentral beantragen</span></h1>
<p>
                                    Entdecken Sie Daten der Universitätskliniken der Medizininformatik-Initiative,
                                    standardisiert, aktuell und in nie dagewesenem Umfang.
                                </p>
<a class="button" data-wpel-link="internal" href="#registrierung/" rel="" target="_self"> Jetzt registrieren </a>
</div>
</div>
</div>
</section>
</swiper-slide>
<swiper-slide aria-label="2 / 2" class="swiper-slide-next" role="group" style="width: 1272px;"><template shadowrootmode="open">
<slot></slot>
</template>
<section class="hero" style="background:  linear-gradient(to right, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%), url(images/patient-forschung-datenschutz-header-scaled.jpg) no-repeat center top;">
<div class="auto__container">
<div class="hero__inner">
<div class="hero__inner-content">
<h1>
                                    Mit Patientendaten<br/>
<span>die medizinische Forschung<br/>unterstützten</span>
</h1>
<p>
                                    Je mehr Patientinnen und Patienten sich mit ihrer Einwilligung beteiligen, desto
                                    reicher ist der
                                    Datenschatz, mit dem die Forschenden arbeiten können.
                                </p>
<a class="button" data-wpel-link="internal" href="#newsletter/" rel="" target="_self"> Newsletter abonieren </a>
</div>
</div>
</div>
</section>
</swiper-slide>
</swiper-container>
<!-- hero end
  =========================================== -->
<!-- intro start
  =========================================== -->
<section class="intro">
<div class="auto__container">
<div class="intro__inner">
<div class="content-container" style="display: flex; align-items: flex-start; width: 100%">
<div class="intro__inner-content">
<h4>Forschen für Gesundheit</h4>
<p class="dark" style="flex: 1; min-width: 80%">

                                Das Deutsche Forschungsdatenportal für Gesundheit (FDPG) ist zentraler Anlaufpunkt für
                                Wissenschaftlerinnen und Wissenschaftler, die ein Forschungsprojekt mit Routinedaten der
                                deutschen Universitätsmedizin durchführen möchten.

                                Im Rahmen der Medizininformatik-Initiative (MII), gefördert vom Bundesministerium für
                                Bildung
                                und Forschung, werden in den Datenintegrationszentren der universitätsmedizinischen <a data-wpel-link="internal" href="#diz/" rel="" target="_self">Standorte</a> Patientendaten und Bioproben aus der
                                Routineversorgung
                                für die medizinische Forschung nutzbar gemacht und datenschutzgerecht bereitgestellt.


                            </p>

<h5>Das FDPG bietet:</h5>
<ul class="bietet" style="margin-left: 30px;">
<li>eine Übersicht über Datenbestände für die standortübergreifende Forschung
                                </li>
<li>die Möglichkeit, die Machbarkeit spezifischer Forschungsfragen anhand von
                                    Machbarkeitsabfragen zu evaluieren
                                </li>
<li>einen standardisierten Prozess zur Beantragung von Daten und Bioproben
                                </li>
<li>etablierte vertragliche Rahmenbedingungen zur einfachen Datennutzung
                                </li>
<li>eine zentrale Koordination der Datenbereitstellung</li>
<li>eine transparente Darstellung von Forschungsprojekten im Projektregister
                                </li>
</ul>
<p>
                                Mehr Informationen finden Sie im <a class="wpel-icon-right" data-wpel-link="advanced" href="#" rel="nofollow" target="_blank" title="">Flyer<i aria-hidden="true" class="wpel-icon fa fa-save"></i></a>.
                            </p>
</div>
<a class="wpel-icon-right" data-wpel-link="advanced" href="#" rel="nofollow" target="_blank" title=""><img alt="FDPG PDF Download" src="images/fdpg-download-compr.webp" style="width: 700px; height: auto; margin-right: 20px;"/><i aria-hidden="true" class="wpel-icon fa fa-save"></i></a>
</div>

<section class="projects" style="margin-top: 40px;">
<div class="auto__container" style="background-color: #206fa7 !important; opacity: 0.8 !important; padding: 10px !important; color: #fff !important;">
<div class="projects__inner" style=" opacity: 0.8 !important; color: #ffffff !important">
<p style=" color: #ffffff !important">
<br/>
                                    Seit 16. Mai 2023 dürfen Forschende (auch außerhalb der MII) Zugang zu
                                    Patientendaten
                                    und Bioproben für medizinische Forschungszwecke beantragen und Machbarkeitsanfragen
                                    stellen.


                                </p>

<ul id="blueattention" style=" color: #ffffff !important;">
<li id="blueattentionli" style=" color: #ffffff !important">Das Portal ist als
                                        Betaversion verfügbar, da die
                                        zugehörige Dateninfrastruktur zunächst anhand erster Nutzungsprojekte getestet
                                        und
                                        laufend verbessert wird. Ziel ist, dass alle Standorte der MII Daten liefern
                                        können.<br/><br/>
</li>
<li id="blueattentionli" style=" color: #ffffff !important">Die Daten werden im
                                        FHIR-Format standardisiert
                                        bereitgestellt. Dennoch ist die Heterogenität der Daten nach wie vor eine große
                                        Herausforderung. Die MII arbeitet gemeinsam mit den Datennutzenden und -gebenden
                                        im
                                        Sinne eines lernenden Systems weiterhin an Verbesserungen der Standardisierung
                                        und
                                        Verfügbarkeit der Daten.<br/><br/></li>
<li id="blueattentionli" style=" color: #ffffff !important">Die Sichtung durch die
                                        Standorte (UACs) und die
                                        Frist für die Datenbereitstellung dauert jeweils bis zu zwei Monate. Außerdem
                                        müssen
                                        Forschende Zeit für das Ethikvotum und den Vertragsschluss mit den Standorten
                                        einplanen. Daher sollten Forschungsprojekte mindestens fünf Monate Zeit
                                        einplanen.
                                    </li>
</ul>
<br/>
</div>
</div>
</section>
<div class="intro__inner-row">
<div class="introItem">
<h6>Daten und Bioproben beantragen</h6>
<p>
                                Forschende können über das Deutsche Forschungsdatenportal die
                                Verfügbarkeit von Daten und Bioproben mit einer
                                Machbarkeitsanfrage prüfen und zentral einen
                                Antrag auf Nutzung von Daten und Bioproben
                                stellen.
                            </p>
<a data-wpel-link="internal" href="#daten-und-bioproben/daten-und-bioproben-fur-ein-forschungsprojekt-beantragen/" rel="" target="_self">
<span>
<img alt="" src="images/more.webp"/>
</span>
                                Wie können Daten beantragt werden?
                            </a>
</div>
<div class="introItem">
<h6>Forschungsprojekte finden</h6>
<p>
                                Das Projektregister des FDPG bietet Patientinnen und
                                Patienten, Forschenden und allen Interessierten einen
                                transparenten Überblick über beantragte, laufende und bereits
                                abgeschlossene Forschungsprojekte im Rahmen der MII.
                            </p>
<a data-wpel-link="internal" href="#projektregister/" rel="" target="_self">
<span>
<img alt="" src="images/more.webp"/>
</span>
                                Welche Projekte gibt es bereits?
                            </a>
</div>
<div class="introItem">
<h6>Patienteninformationen</h6>
<p>
                                Medizinische Forschung hilft, Krankheiten besser zu erkennen,
                                zu behandeln und ihnen vorzubeugen. Mit Ihren Gesundheitsdaten
                                können Sie die medizinische Forschung in Deutschland
                                unterstützen.
                            </p>
<a data-wpel-link="internal" href="#patienteninformationen/" rel="" target="_self">
<span>
<img alt="" src="images/more.webp"/>
</span>
                                Wie werden Patientendaten geschützt?
                            </a>
</div>
</div>
</div>
</div>
</section>
<!-- intro end





  =========================================== -->
<!-- update start
  =========================================== -->
<section class="update" style="padding-bottom: 120px !important;">
<div class="update__bg">
<div class="update__bg-image">
<img alt="" src="images/main-bg.svg"/>
</div>
</div>
<div class="auto__container">
<div class="update__inner" style="margin-top: 75px;">
<div class="content-container" style="display: flex; align-items: flex-start; width: 100%">
<div class="update__inner-header" style="min-width: 60%">
<h4>Datenübersicht</h4>
<p class="dark" style="flex: 1; min-width: 80%">
                                Hier finden Sie eine Übersicht über alle verfügbaren Daten.
                                Diese wird regelmäßig aktualisiert.<br/><br/>
                                Die folgenden Zahlen in der Datenübersicht sind Summen der Rückmeldungen aller Standorte
                                zu den jeweiligen Suchanfragen, die in Abhängigkeit davon, wie viele und welche
                                Standorte zum Zeitpunkt der Suchanfrage antworten, variieren können. Abweichungen von
                                den Ergebnissen der Machbarkeitsanfragen sind daher erwartbar.
                            </p>
</div>
<img alt="FDPG Daten" src="images/fdpg-macbook-2.webp" style="width: 380px; height: auto; margin-left: 50px;"/>
</div>
<!--
              <div class="update__inner-title">
                <h4>letzte Aktualisierung</h4>
                <h2>10.11.2022</h2>
              </div>-->
<div class="update__inner-row">

<div class="updateItem" id="updateItemLink">
<div class="updateItem__inner">
<h2>+10 Mio</h2>
<h4>Personen</h4>
<p>
                                    Basisdaten eines Krankenhausaufenthaltes von Patientinnen und Patienten
                                </p>
</div>
<div class="updateItem__icon">
<img alt="" src="images/persons.svg"/>
</div>
</div>
<script>
                            var linkZiel = '/daten-und-bioproben/#modulperson';

                            document.getElementById('updateItemLink').addEventListener('click', function () {
                                window.location.href = linkZiel;
                            });
                        </script>
<div class="updateItem" id="updateItemLink2">
<div class="updateItem__inner">
<h2>25</h2>
<h4>Standorte</h4>
<p>
                                    Datenintegrationszentren, die über das Forschungsdatenportal Daten bereitstellen
                                </p>
</div>
<div class="updateItem__icon sm">
<img alt="" src="images/location.svg"/>
</div>
</div>
<script>
                            var linkZiel2 = '/diz';

                            document.getElementById('updateItemLink2').addEventListener('click', function () {
                                window.location.href = linkZiel2;
                            });	
                        </script>
<div class="updateItem" id="updateItemLink3">
<div class="updateItem__inner">
<h2>+75 Mio</h2>
<h4>Diagnosen</h4>
<p>
                                    Beschreibung der Krankheiten einer Person
                                </p>
</div>
<div class="updateItem__icon sm">
<img alt="" src="images/diagnosis.svg"/>
</div>
</div>
<script>
                            var linkZiel3 = '/daten-und-bioproben/#moduldiagnose';

                            document.getElementById('updateItemLink3').addEventListener('click', function () {
                                window.location.href = linkZiel3;
                            });	
                        </script>
<div class="updateItem" id="updateItemLink4">
<div class="updateItem__inner">
<h2>+800 Mio</h2>
<h4>Laborwerte</h4>
<p>
                                    Daten zu Laboruntersuchungen von Patientinnen und Patienten
                                </p>
</div>
<div class="updateItem__icon sm">
<img alt="" src="images/laboratory.svg"/>
</div>
</div>
<script>
                            var linkZiel4 = '/daten-und-bioproben/#modullaborbefund';

                            document.getElementById('updateItemLink4').addEventListener('click', function () {
                                window.location.href = linkZiel4;
                            });	
                        </script>
<div class="updateItem" id="updateItemLink5">
<div class="updateItem__inner">
<h2>+50 Mio</h2>
<h4>Prozeduren</h4>
<p>
                                    Dokumentation von Operationen und medizinischen Eingriffen
                                </p>
</div>
<div class="updateItem__icon big">
<img alt="" src="images/procedure.svg"/>
</div>
</div>
<script>
                            var linkZiel5 = '/daten-und-bioproben/#modulprozedur';

                            document.getElementById('updateItemLink5').addEventListener('click', function () {
                                window.location.href = linkZiel5;
                            });	
                        </script>
<div class="updateItem" id="updateItemLink6">
<div class="updateItem__inner">
<h2>+80 Mio</h2>
<h4>Medikationsdaten</h4>
<p>
                                    Dokumentation von Arzneimittelverordnungen und -gaben
                                </p>
</div>
<div class="updateItem__icon sm">
<img alt="" src="images/medication.svg"/>
</div>
</div>
<script>
                            var linkZiel6 = '/daten-und-bioproben/#modulmedikation';

                            document.getElementById('updateItemLink6').addEventListener('click', function () {
                                window.location.href = linkZiel6;
                            });	
                        </script>
<div class="updateItem" id="updateItemLink7">
<div class="updateItem__inner">
<h2>+550k</h2>
<h4>Bioproben</h4>
<p>
                                    Verfügbare Bioproben, die zur Diagnose oder Therapie entnommen wurden
                                </p>
</div>
<div class="updateItem__icon">
<img alt="" src="images/bioproben.svg"/>
</div>
</div>
<script>
                            var linkZiel7 = '/daten-und-bioproben/#modulbiobank';

                            document.getElementById('updateItemLink7').addEventListener('click', function () {
                                window.location.href = linkZiel7;
                            });	
                        </script>
<div class="updateItem" id="updateItemLink8">
<div class="updateItem__inner">
<h2>+100k</h2>
<h4>Einwilligungen</h4>
<p>
                                    Verfügbare positive Einwilligungsinformationen von Patientinnen und Patienten
                                </p>
</div>
<div class="updateItem__icon big">
<img alt="" src="images/handshake.svg" style="width: 65%; height: auto;"/>
</div>
</div>
<script>
                            var linkZiel8 = '';

                            document.getElementById('updateItemLink8').addEventListener('click', function () {
                                window.location.href = linkZiel8;
                            });	
                        </script>
<div class="updateItem" id="updateItemLink9">
<div class="updateItem__inner">
<h2>29</h2>
<h4>Projektanträge</h4>
<p>
                                    Neuanträge, die das Forschungsdatenportal für Gesundheit erreichen
                                </p>
</div>
<div class="updateItem__icon big">
<img alt="" src="images/contract.svg" style="width: 55%; height: auto;"/>
</div>
</div>
<script>
                            var linkZiel9 = '/daten-und-bioproben/prozesse-der-antragstellung-und-datennutzung-in-der-mii/';

                            document.getElementById('updateItemLink9').addEventListener('click', function () {
                                window.location.href = linkZiel9;
                            });	
                        </script>
</div>
</div>
</div>
</section>
<!-- update end -->
<section class="projects">
<div class="auto__container">
<div class="projects__inner">
<div class="projects__inner-title">
<h4> </h4>
</div>
</div>
</div>
</section>
<!-- projects start
  =========================================== -->
<section class="projects">
<div class="auto__container">
<div class="projects__inner" style="margin-top: 75px;">
<div class="projects__inner-title">
<h4>Forschungsprojekte</h4>
<a data-wpel-link="internal" href="#projektregister/" rel="" target="_self">
<span>
<img alt="FDP Projektregister" src="images/more.webp"/>
</span>
                            Alle Projekte
                        </a>
</div>
<div class="projects__inner-row slider active" style="margin-top: 40px; margin-bottom: 100px; ">
<div class="projectsCard">
<div class="projectsCard__image">
<a data-wpel-link="internal" href="#fdpgx-project/datenintegrationsplattform-des-deutschen-netzwerks-fuer-personalisierte-medizin/" rel="" target="_self"><img alt="" src="images/OnkologiePathologie-_iStock-1296627879.jpg"/></a>
</div>
<div class="projectsCard__content">
<h5>
<a data-wpel-link="internal" href="#fdpgx-project/datenintegrationsplattform-des-deutschen-netzwerks-fuer-personalisierte-medizin/" rel="" style="color: #94c11b;" target="_self">Datenintegrationsplattform des Deutschen Netzwerks für personalisierte
                                        Medizin</a>
</h5>
<p>
                                    Im Rahmen des Deutschen Netzwerks für Personalisierte Medizin (DNPM) soll eine
                                    IT-Infrastruktur (Register) aufgebaut werden, die zunächst im Bereich der
                                    Tumorbehandlung Daten von molekular diagnostizierten Patientinnen und Patienten
                                    verknüpft. Das Ziel besteht darin, aus diesen Datenbeständen neue Handlungsoptionen
                                    abzuleiten und individuelle Therapieansätze zu bewerten sowie langfristig
                                    qualitätskontrolliert anzuwenden. </p>
<div class="projectsCard__content-bottom">
<a class="button" data-wpel-link="internal" href="#fdpgx-project/datenintegrationsplattform-des-deutschen-netzwerks-fuer-personalisierte-medizin/" rel="" target="_self"> Details </a>
</div>
</div>
</div>
<div class="projectsCard">
<div class="projectsCard__image">
<a data-wpel-link="internal" href="#fdpgx-project/machbarkeitsanfragen/" rel="" target="_self"><img alt="" src="images/forscher-slider.jpg"/></a>
</div>
<div class="projectsCard__content">
<h5>
<a data-wpel-link="internal" href="#fdpgx-project/machbarkeitsanfragen/" rel="" style="color: #94c11b;" target="_self">Regelmäßige Ausführung von verteilten Machbarkeitsanfragen zur
                                        Vorbereitung und Durchführung standortübergreifender Forschungsprojekte</a>
</h5>
<p>
                                    Im Rahmen der nationalen medizininformatischen Forschungsverbünde, insbesondere der
                                    Medizininformatik-Initiative (MII) und des Netzwerkes Universitätsmedizin (NUM),
                                    sollen in Zukunft multizentrische Forschungsprojekte unter Beteiligung im Idealfall
                                    aller deutschen Universitätsklinika und weiterer Partner durchgeführt werden. Um
                                    eine erfolgreiche Projektdurchführung zu gewährleisten, ist es notwendig, die zu
                                    erwartenden Fallzahlen bereits im Vorfeld zu ermitteln. Nach derzeitiger Rechtslage
                                    können an manchen MII-Standorten diese Machbarkeitsanfragen ausschließlich auf der
                                    Datenbasis derjenigen Patienten ausgeführt werden, die ihre ausdrückliche Zustimmung
                                    zur erweiterten Forschungsdatennutzung durch Unterzeichnung des in Bonn verwendeten
                                    „broad consent“ nach dem Muster der Medizininformatik-Initiative erklärt haben.

                                    Mit einer Machbarkeitsanfrage erfahren Forschende, wie viele Fälle für ihre
                                    Suchkriterien (Ein- und Ausschlusskriterien) in den Datenintegrationszentren der
                                    angeschlossenen Standorte bundesweit vorhanden sind und für medizinische
                                    Forschungszwecke beantragt werden können. </p>
<div class="projectsCard__content-bottom">
<a class="button" data-wpel-link="internal" href="#fdpgx-project/machbarkeitsanfragen/" rel="" target="_self"> Details </a>
</div>
</div>
</div>
<div class="projectsCard">
<div class="projectsCard__image">
<a data-wpel-link="internal" href="#fdpgx-project/vhf-mi-dezentral/" rel="" target="_self"><img alt="" src="images/Herz-Kreislauf-Erkrankungen-Kardiologie_iStock-1283285509_-1.jpg"/></a>
</div>
<div class="projectsCard__content">
<h5>
<a data-wpel-link="internal" href="#fdpgx-project/vhf-mi-dezentral/" rel="" style="color: #94c11b;" target="_self">NT-proBNP als Marker bei Vorhofflimmern (dezentral)</a>
</h5>
<p>
                                    Es ist manchmal schwierig und langwierig Vorhofflimmern, eine meist chronische
                                    Herzrhythmusstörung, bei Patienten und Patientinnen zuverlässig zu diagnostizieren.
                                    In der Regel nutzt man dafür ein Langzeit-EKG (Elektrokardiogramm), also eine
                                    Messung der Herzströme. Im vorliegenden Projekt NT-proBNP als Marker bei
                                    Vorhofflimmern soll nun festgestellt werden, ob man zusätzlich zum EKG auch die
                                    Messung des Biomarkers NT-proBNP als Anhaltspunkt für eine zuverlässige Diagnose
                                    nutzen kann. (Ein Biomarker ist ein biologisches Merkmal, das in Blut- oder
                                    Gewebeproben gemessen werden kann.) Dafür wird der Zusammenhang zwischen
                                    Vorhofflimmern und dem Auftreten des Biomarkers NT-proBNP an allen teilnehmenden
                                    Unikliniken analysiert.

                                    Dieses Projekt ist inhaltlich identisch mit dem Projekt
                                    #fdpgx-project/nt-probnp/, jedoch
                                    unterscheidet es sich in der Methodik. Anstelle einer zentralen Analyse wird hier
                                    verteilt gerechnet. Beim verteilten Rechnen werden die Analyseskripte an den
                                    Standorten ausgeführt, sodass die Daten dort verbleiben können. </p>
<div class="projectsCard__content-bottom">
<a class="button" data-wpel-link="internal" href="#fdpgx-project/vhf-mi-dezentral/" rel="" target="_self"> Details </a>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- projects end







=========================================== -->
<!-- footer start
  =========================================== -->
<script src="js/swiper-element-bundle.min.js"></script>
<footer class="footer">
<div class="auto__container">
<div class="footer__inner">
<div class="footer__inner-logo">
<img alt="MII Forschungsdatenportal für Gesundheit" src="images/footer-logo.webp"/>
</div>
<div class="footer__inner-row">
<div class="footer__inner-col sm">
<a data-wpel-link="internal" href="projektregister/" rel="" target="_self">
                                Forschungsprojekte
                            </a>
<a data-wpel-link="internal" href="daten-und-bioproben/" rel="" target="_self">
                                Daten und Bioproben
                            </a>
<a data-wpel-link="internal" href="diz/" rel="" target="_self">
                                Standorte
                            </a>
</div>
<div class="footer__inner-col sm">
<a data-wpel-link="internal" href="impressum/" rel="" target="_self">
                                Impressum
                            </a>
<a data-wpel-link="internal" href="datenschutz/" rel="" target="_self">
                                Datenschutz
                            </a>
<a href="mailto:info@forschen-fuer-gesundheit.de" id="emailLink">
                                Kontakt
                            </a>
</div>
<div class="footer__inner-col">
<h6>
                                 
                            </h6>
<a data-wpel-link="internal" href="registrierung/" rel="" target="_self">
                                Registrierung
                            </a>
<a data-wpel-link="internal" href="https://diz.forschen-fuer-gesundheit.de" rel="" target="_blank">
                                DIZ-Dashboard
                            </a>
<a data-wpel-link="internal" href="newsletter/" rel="" target="_self">
                                Newsletter
                            </a>
</div>
<div class="footer__inner-col">
<h6 class="dark">
                                 
                            </h6>
<p class="big">
                                 
                            </p>
</div>
</div>
<p class="big" style="color: #E6E8EA  !important">
Forschungsdatenportal für Gesundheit (FDPG) der Medizininformatik-Initiative (MII).
</p>
</div>
</div>
</footer>
<section class="sponsors">
<div class="auto__container">
<div class="sponsors__inner">
<a class="sponsors__inner-logo" data-wpel-link="external" href="#" rel="noopener noreferrer" target="_blank">
<img alt="" src="images/Medizin-Informatik-Initiative.png"/>
</a>
<a class="sponsors__inner-logo" data-wpel-link="external" href="#" rel="noopener noreferrer" style="margin-top: 40px;" target="_blank">
<img alt="" src="images/logo.svg"/>
</a>
<a class="sponsors__inner-logo" data-wpel-link="external" href="#" rel="noopener noreferrer" target="_blank">
<img alt="" src="images/img_logo_BMBF_DE.jpg"/>
</a>
</div>
</div>
</section>
<!-- footer end
    =========================================== -->
</main>
<!-- Sprites
    =========================================== -->
<svg style="display: none">
<symbol id="arrow-right" viewbox="0 0 14 9">
<g>
<path clip-rule="evenodd" d="M9.09094 0.265207C9.49676 -0.109399 10.1294 -0.0840962 10.504 0.321722L13.7348 3.82168C14.0884 4.20474 14.0884 4.79518 13.7348 5.17824L10.504 8.67828C10.1294 9.08411 9.49677 9.10941 9.09095 8.73481C8.68513 8.36021 8.65982 7.72755 9.03442 7.32173L10.716 5.49997L0.999999 5.49997C0.447714 5.49997 -7.64154e-07 5.05225 -7.86799e-07 4.49997C-8.09444e-07 3.94768 0.447714 3.49997 0.999999 3.49997L10.716 3.49997L9.03443 1.67829C8.65982 1.27247 8.68513 0.639813 9.09094 0.265207Z" fill-rule="evenodd">
</path>
</g>
</symbol>
</svg>
<!-- js
    =========================================== -->
<script src="js/jquery(1).js"></script>
<script src="js/slick.min.js"></script>
<script src="js/main.js"></script>
<script type="text/javascript">
        if (typeof ClipboardJS !== 'undefined') {
            new ClipboardJS('.btn');
        }
    </script>
<span class="ssp-id" style="display:none">114</span>


<script id="awp-calendly-script-js" src="js/calendly.js" type="text/javascript"></script>
<script id="awp-paperform-script-js" src="js/paperform.js" type="text/javascript"></script>
<script id="awp-wufooforms-script-js" src="js/wufooforms.js" type="text/javascript"></script>
<script id="hidemyemail-js" src="js/hidemyemail.js" type="text/javascript"></script>
<script id="wpel-front-js" src="js/wpel-front.js" type="text/javascript"></script>
<script id="ssp-wpml-geo-js" src="js/ssp-wpml-geo.js" type="text/javascript"></script>


<script defer="" id="“dacs“" src="images/digiaccess" type="7695612dd15d98fd81118b38-text/javascript"></script>
<script>
        document.addEventListener("DOMContentLoaded", function () {

            const liStyle = `
			display: flex;
			align-items: flex-start;
			box-sizing: border-box;
			color: #666666;
			font-family: Montserrat, sans-serif;
			font-size: 17px;
			line-height: 25.5px;
			list-style: none;
			margin: 0px;
			outline: none;
			padding: 0px;
		`;

            const liBeforeStyle = `
			color: #94c11b;
			content: "•";
			display: block;
			font-size: 1.5em;
			font-weight: bold;
			margin-right: 10px;
			flex-shrink: 0;
		`;

            const liTextStyle = `
			flex-grow: 1;
		`;

            const styleSheet = document.createElement("style");
            styleSheet.type = "text/css";
            styleSheet.innerHTML = `
			ul#menu-header-menu, ul#menu-header-menu li, ul.sub-menu, ul.sub-menu li, wpml-ls-slot-footer {
				list-style-type: none;
			}
			ul#menu-header-menu, ul.sub-menu, wpml-ls-slot-footer {
				padding-left: 0;
			}
		`;

            document.head.appendChild(styleSheet);
        });

        document.addEventListener('DOMContentLoaded', function () {

            var url = window.location.href;
            if (url.includes('sort=asc')) {
                var button = document.getElementById('sortBtn');
                button.style.backgroundColor = '#94c11b';
            }

            if (url.includes('sort=desc')) {
                var button = document.getElementById('sortBtn2');
                button.style.backgroundColor = '#94c11b';
            }

        });

        document.addEventListener('DOMContentLoaded', function () {
            var filterBtn = document.getElementById('filterBtn');
            var isGreen = false;

            filterBtn.addEventListener('click', function () {
                if (isGreen) {
                    filterBtn.style.backgroundColor = '#CED0D9';
                    isGreen = false;
                } else {
                    filterBtn.style.backgroundColor = '#94c11b';
                    isGreen = true;
                }
            });


            var filterBtnColor = '#94c11b';

            function handleClick(targetUrl) {
                var filterBtn = document.getElementById('filterBtn');
                var currentColor = window.getComputedStyle(filterBtn, null).backgroundColor;
                var hexColor = rgbToHex(currentColor);

                if (hexColor.toUpperCase() === filterBtnColor.toUpperCase()) {
                    targetUrl += "&list=1";
                }
                window.open(targetUrl, '_top');
            }

            function rgbToHex(rgb) {
                if (rgb.indexOf('rgb') === -1) {
                    return rgb;
                }
                var parts = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
                delete (parts[0]);
                for (var i = 1; i <= 3; ++i) {
                    parts[i] = parseInt(parts[i]).toString(16);
                    if (parts[i].length == 1) parts[i] = '0' + parts[i];
                }
                return '#' + parts.join('');
            }

            document.getElementById('sortBtn').addEventListener('click', function () {
                handleClick('#projektregister/?sort=asc');
            });

            document.getElementById('sortBtn2').addEventListener('click', function () {
                handleClick('#projektregister/?sort=desc');
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.href.includes('list=1')) {
                document.getElementById('filterBtn').click();
            }
        });

    </script>
<script>
        var asciiCodes = [105, 110, 102, 111, 64, 102, 111, 114, 115, 99, 104, 101, 110, 45, 102, 117, 101, 114, 45, 103, 101, 115, 117, 110, 100, 104, 101, 105, 116, 46, 100, 101];
        var subject = 'Anfrage über die Webseite';

        var email = asciiCodes.map(function (code) {
            return String.fromCharCode(code);
        }).join('');

        document.getElementById('emailLink').addEventListener('click', function (event) {
            event.preventDefault();
            window.location.href = 'mailto:' + email + '?subject=' + encodeURIComponent(subject);
        });
    </script>
<script>
        jQuery(function ($) {
            $(document).on('click', '#menuBtn', function () {
                $('nav').toggleClass('active');
            });
            $(document).on('click', '.menu-item-has-children', function () {
                $(this).toggleClass('active');
            });

            $(document).on('click', function (event) {
                if ($(event.target).hasClass('nav active') && !$(event.target).is('#menu-header-menu')) {
                    $('nav').toggleClass('active');
                }
            });

        });</script>

</body>
</html>