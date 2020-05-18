    <style amp-boilerplate>
        body {
            -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            animation: -amp-start 8s steps(1, end) 0s 1 normal both
        }
        
        @-webkit-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }
        
        @-moz-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }
        
        @-ms-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }
        
        @-o-keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }
        
        @keyframes -amp-start {
            from {
                visibility: hidden
            }
            to {
                visibility: visible
            }
        }
    </style>


    <noscript>
        <style amp-boilerplate>
            body {
                -webkit-animation: none;
                -moz-animation: none;
                -ms-animation: none;
                animation: none
            }
        </style>
    </noscript>


    <style amp-custom>
        /* Generic WP styling */
        
        .alignright {
            float: right;
        }
        
        .alignleft {
            float: left;
        }
        
        .aligncenter {
            display: block;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
        
        .amp-wp-enforced-sizes {
            /** Our sizes fallback is 100vw, and we have a padding on the container; the max-width here prevents the element from overflowing. **/
            max-width: 100%;
            margin: 0 auto;
        }
        /* * Prevent cases of amp-img converted from img to appear with stretching by using object-fit to scale. * See <https://github.com/ampproject/amphtml/issues/21371#issuecomment-475443219>. * Also use object-fit:contain in worst case scenario when we can't figure out dimensions for an image. * Additionally, in side of \AMP_Img_Sanitizer::determine_dimensions() it could $amp_img->setAttribute( 'object-fit', 'contain' ) * so that the following rules wouldn't be needed. */
        
        amp-img.amp-wp-enforced-sizes[layout="intrinsic"] > img,
        amp-anim.amp-wp-enforced-sizes[layout="intrinsic"] > img {
            object-fit: contain;
        }
        
        amp-fit-text blockquote,
        amp-fit-text h1,
        amp-fit-text h2,
        amp-fit-text h3,
        amp-fit-text h4,
        amp-fit-text h5,
        amp-fit-text h6 {
            font-size: inherit;
        }
        /** * Override a style rule in Twenty Sixteen and Twenty Seventeen. * It set display:none for audio elements. * This selector is the same, though it adds body and uses amp-audio instead of audio. */
        
        body amp-audio:not([controls]) {
            display: inline-block;
            height: auto;
        }
        /* * Style the default template messages for submit-success, submit-error, and submitting. These elements are inserted * by the form sanitizer when a POST form lacks the action-xhr attribute. */
        
        .amp-wp-default-form-message > p {
            margin: 1em 0;
            padding: 0.5em;
        }
        
        .amp-wp-default-form-message[submitting] > p,
        .amp-wp-default-form-message[submit-success] > p.amp-wp-form-redirecting {
            font-style: italic;
        }
        
        .amp-wp-default-form-message[submit-success] > p:not(.amp-wp-form-redirecting) {
            border: solid 1px #008000;
            background-color: #90ee90;
            color: #000;
        }
        
        .amp-wp-default-form-message[submit-error] > p {
            border: solid 1px #f00;
            background-color: #ffb6c1;
            color: #000;
        }
        /* Prevent showing empty success message in the case of an AMP-Redirect-To response header. */
        
        .amp-wp-default-form-message[submit-success] > p:empty {
            display: none;
        }
        
        amp-carousel .amp-wp-gallery-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 1rem;
        }
        
        .wp-block-gallery[data-amp-carousel="true"] {
            display: block;
            flex-wrap: unset;
        }
        /* Template Styles */
        
        body {
            color: #393939;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Open Sans", "Arial", "Helvetica Neue", sans-serif;
            font-weight: 300;
            line-height: 1.75em;
        }
        
        p,
        ol,
        ul,
        figure {
            margin: 0 0 1em;
            padding: 0;
        }
        
        a,
        a:visited {
            color: #2b2b2b;
        }
        
        a:hover,
        a:active,
        a:focus {
            color: #393939;
        }
        /* Quotes */
        
        blockquote {
            color: #393939;
            background: rgba(127, 127, 127, .125);
            border-left: 2px solid #2b2b2b;
            margin: 8px 0 24px 0;
            padding: 16px;
        }
        
        blockquote p:last-child {
            margin-bottom: 0;
        }
        /* UI Fonts */
        
        .amp-wp-meta,
        .amp-wp-header div,
        .amp-wp-title,
        .wp-caption-text,
        .amp-wp-tax-category,
        .amp-wp-tax-tag,
        .amp-wp-comments-link,
        .amp-wp-footer p,
        .back-to-top {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen-Sans", "Ubuntu", "Cantarell", "Helvetica Neue", sans-serif;
        }
        /* Header */
        
        .amp-wp-header div {
            font-size: 1em;
            font-weight: 400;
            margin: 0 auto;
            max-width: calc(840px - 32px);
            padding: .875em 16px;
            position: relative;
        }
        
        .amp-wp-header a {
            text-decoration: none;
        }
        /* Article */
        
        .amp-wp-article {
            color: #393939;
            font-weight: 400;
            margin: 1.5em auto;
            max-width: 840px;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }
        /* Article Header */
        
        .amp-wp-article-header {
            align-items: center;
            align-content: stretch;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 1.5em 16px 0;
        }
        
        .amp-wp-title {
            color: #393939;
            display: block;
            flex: 1 0 100%;
            font-weight: 900;
            margin: 0 0 .625em;
            width: 100%;
        }
        /* Article Meta */
        
        .amp-wp-meta {
            display: inline-block;
            flex: 2 1 50%;
            font-size: .875em;
            line-height: 1.5em;
            margin: 0 0 1.5em;
            padding: 0;
        }
        
        .amp-wp-article-header .amp-wp-meta:last-of-type {
            text-align: right;
        }
        
        .amp-wp-article-header .amp-wp-meta:first-of-type {
            text-align: left;
        }
        
        .amp-wp-byline amp-img,
        .amp-wp-byline .amp-wp-author {
            display: inline-block;
            vertical-align: middle;
        }
        
        .amp-wp-byline amp-img {
            border: 1px solid #2b2b2b;
            border-radius: 50%;
            position: relative;
            margin-right: 6px;
        }
        
        .amp-wp-posted-on {
            text-align: right;
        }
        /* Featured image */
        
        .amp-wp-article-featured-image {
            margin: 0 0 1em;
            margin-top: -50px; /* recently added */
        }
        
        .amp-wp-article-featured-image amp-img {
            margin: 0 auto;
        }
        
        .amp-wp-article-featured-image.wp-caption .wp-caption-text {
            margin: 0 18px;
        }
        /* Article Content */
        
        .amp-wp-article-content {
            margin: 0 16px;
        }
        
        .amp-wp-article-content ul,
        .amp-wp-article-content ol {
            margin-left: 1em;
        }
        
        .amp-wp-article-content .wp-caption {
            max-width: 100%;
        }
        
        .amp-wp-article-content amp-img {
            margin: 0 auto;
        }

        .amp-wp-article-content img {
            width: 100%;
        }
        
        .amp-wp-article-content amp-img.alignright {
            margin: 0 0 1em 16px;
        }
        
        .amp-wp-article-content amp-img.alignleft {
            margin: 0 16px 1em 0;
        }
        /* Captions */
        
        .wp-caption {
            padding: 0;
        }
        
        .wp-caption.alignleft {
            margin-right: 16px;
        }
        
        .wp-caption.alignright {
            margin-left: 16px;
        }
        
        .wp-caption .wp-caption-text {
            border-bottom: 1px solid #EDEDED;
            font-size: .875em;
            line-height: 1.5em;
            margin: 0;
            padding: .66em 10px .75em;
        }
        /* AMP Media */
        
        .alignwide,
        .alignfull {
            clear: both;
        }
        
        amp-carousel {
            background: #EDEDED;
            margin: 0 -16px 1.5em;
        }
        
        amp-iframe,
        amp-youtube,
        amp-instagram,
        amp-vine {
            background: #EDEDED;
            margin: 0 -16px 1.5em;
        }
        
        .amp-wp-article-content amp-carousel amp-img {
            border: none;
        }
        
        amp-carousel > amp-img > img {
            object-fit: contain;
        }
        
        .amp-wp-iframe-placeholder {
            background: #EDEDED url(https://gillion.shufflehound.com/wp-content/plugins/amp/assets/images/placeholder-icon.png ) no-repeat center 40%;
            background-size: 48px 48px;
            min-height: 48px;
        }
        /* Article Footer Meta */
        
        .amp-wp-article-footer .amp-wp-meta {
            display: block;
        }
        
        .amp-wp-tax-category,
        .amp-wp-tax-tag {
            font-size: .875em;
            line-height: 1.5em;
            margin: 1.5em 16px;
        }
        
        .amp-wp-comments-link {
            font-size: .875em;
            line-height: 1.5em;
            text-align: center;
            margin: 2.25em 0 1.5em;
        }
        
        .amp-wp-comments-link a {
            border-style: solid;
            border-color: #EDEDED;
            border-width: 1px 1px 2px;
            border-radius: 4px;
            background-color: transparent;
            color: #2b2b2b;
            cursor: pointer;
            display: block;
            font-size: 14px;
            font-weight: 600;
            line-height: 18px;
            margin: 0 auto;
            max-width: 200px;
            padding: 11px 16px;
            text-decoration: none;
            width: 50%;
        }
        /* AMP Footer */
        
        .amp-wp-footer {
            border-top: 1px solid #EDEDED;
            margin: calc(1.5em - 1px) 0 0;
        }
        
        .amp-wp-footer .amp-wp-footer-container {
            margin: 0 auto;
            max-width: calc(840px - 32px);
            padding: 1.25em 16px 1.25em;
            position: relative;
        }
        
        .amp-wp-footer h2 {
            font-size: 1em;
            line-height: 1.375em;
            margin: 0 0 .5em;
        }
        
        .amp-wp-footer p {
            font-size: .8em;
            line-height: 1.5em;
            margin: 0 85px 0 0;
        }
        
        .amp-wp-footer a {
            text-decoration: none;
        }
        /*** Custom - Basic*/
        
        body {
            font-size: 14px;
            color: #393939;
        }
        
        img,
        .amp-wp-article-related-posts-item .sh-ratio-content {
            border-radius: 8px;
        }
        
        a,
        h3 {
            text-decoration: none;
            transition: opacity 0.3s ease-in-out;
        }
        
        .amp-wp-meta.amp-wp-comments-link a:hover,
        .amp-wp-article-related-posts-item h3:hover,
        .amp-wp-tax-category a:hover,
        .amp-wp-article-content a:hover {
            opacity: 0.8;
        }
        
        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 0;
            border-top: 1px solid #eee;
        }
        
        figcaption.wp-caption-text,
        .wp-caption figcaption.wp-caption-text {
            font-size: 12px;
            text-align: left;
            border-width: 0px;
            padding: 5px 0;
        }
        
        .sh-dropcaps {
            font-weight: bold;
            margin-right: 15px;
            line-height: 1;
            float: left;
            margin: 25px 35px;
        }
        
        body .sh-dropcaps {
            font-size: 48px;
        }
        
        blockquote {
            display: block;
            padding: 0 0 0 60px;
            font-style: italic;
            border-left-width: 0;
            font-weight: 600;
            position: relative;
            text-align: left;
            background-color: transparent;
        }
        
        blockquote cite {
            display: block;
            color: #f63a4c;
            font-style: normal;
            font-size: 14px;
            font-weight: 600;
            margin-top: 5px;
        }
        
        blockquote:before {
            content: "”";
            display: block;
            position: absolute;
            top: 50%;
            left: 0;
            font-size: 50px;
            line-height: 0;
            margin-top: 6px;
            margin-left: 0;
            color: inherit;
        }
        
        blockquote:after {
            content: "";
            display: block;
            position: absolute;
            top: 5px;
            left: 40px;
            bottom: 5px;
            width: 2px;
            background-color: #f63a4c;
        }
        
        blockquote p {
            font-size: 18px;
        }
        /* Uppercase */
        
        .amp-wp-tax-category a,
        .amp-wp-tax-category span,
        .amp-wp-tax-tag a,
        .amp-wp-tax-tag span,
        .amp-wp-article-related-posts h2,
        .amp-wp-article-footer .amp-wp-comments-link,
        .amp-wp-footer-nav li a,
        .amp-wp-footer .amp-wp-back-to-top {
            text-transform: uppercase;
        }
        /*** Dynamic Images*/
        
        .sh-ratio {
            position: relative;
        }
        
        .sh-ratio-container {
            padding-bottom: 56.25%;
        }
        
        .sh-ratio-content {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background-size: cover;
            background-position: 50% 50%;
        }
        
        .sh-ratio-content iframe {
            width: 100%;
            height: 100%;
        }
        /*** Post Content*/
        
        .amp-wp-article-media,
        .amp-wp-article-featured-image {
            margin-bottom: 30px;
        }
        
        .amp-wp-article-categories a {
            font-style: italic;
            font-weight: 600;
            font-size: 13px;
            color: #7e7e7e;
        }
        
        .amp-wp-meta {
            width: auto;
        }
        
        .amp-wp-article {
            margin-top: 40px;
        }
        
        .amp-wp-article-header {
            margin: 0;
        }
        
        h1.amp-wp-title {
            font-size: 36px;
            line-height: 110%;
            font-weight: 600;
        }
        
        .amp-wp-meta {
            display: inline-block;
            position: relative;
            color: #8d8d8d;
            line-height: 25px;
            font-size: 12px;
        }
        
        .amp-wp-meta:not(:last-child) {
            padding-right: 10px;
            margin-right: 10px;
        }
        
        .amp-wp-meta:not(:last-child):not(.amp-wp-tax-category):after {
            content: "";
            display: block;
            position: absolute;
            background-color: #d6d6d6;
            width: 1px;
            top: 7px;
            bottom: 7px;
            right: -1px;
        }
        
        .amp-wp-meta img {
            border-radius: 5px;
            filter: grayscale(100%);
            transition: 0.3s all ease-in-out;
        }
        
        .amp-wp-meta img:hover {
            filter: grayscale(0%);
        }
        
        .amp-wp-byline amp-img {
            border-radius: 0px;
            border-width: 0px;
        }
        
        .amp-wp-article-content {
            margin: 0;
        }
        
        .amp-wp-article-content,
        .amp-wp-article-content p {
            line-height: 1.8;
            font-size: 16px;
            color: #393939;
        }
        
        .amp-wp-article-content p {
            margin-bottom: 15px;
        }
        /* Content Footer */
        
        .amp-wp-article-footer {
            margin-top: 40px;
        }
        
        .amp-wp-article-footer .amp-wp-meta {
            margin-left: 0px;
            margin-right: 0px;
        }
        
        .amp-wp-article-footer .amp-wp-comments-link {
            margin: 60px 0;
        }
        
        .amp-wp-article-footer .amp-wp-comments-link a {
            border-width: 0;
            background-color: #f63a4c;
            color: #fff;
            border-radius: 100px;
            line-height: 50px;
            height: 50px;
            padding-top: 0px;
            padding-bottom: 0px;
            font-size: 13px;
        }
        /*** Header*/
        
        header.amp-wp-header {
            text-align: center;
            font-size: 0px;
            line-height: 0px;
            padding: 15px 0;
            border-bottom: 1px solid #EDEDED;
        }
        
        .amp-wp-header-logo-img,
        .amp-wp-footer-logo-img {
            display: block;
            height: 38px;
            width: 100%;
            background-size: contain;
            background-position: 50% 50%;
            background-repeat: no-repeat;
            -webkit-backface-visibility: hidden;
        }
        
        .amp-site-title {
            /*display: none;*/
            display: block;
            font-size: 15px;
            margin-top: 20px;
            text-transform: capitalize;
        }
        /*** Footer*/
        
        .amp-wp-footer {
            background-color: #f5f5f5;
            color: #8d8d8d;
            padding: 40px 10px;
            margin: 0;
            border-width: 0px;
            margin-top: 40px;
            text-align: center;
        }
        
        .amp-wp-footer .amp-wp-footer-container {
            padding: 0;
        }
        
        .amp-wp-footer-logo {
            margin-bottom: 15px;
        }
        
        .amp-wp-footer .amp-wp-back-to-top {
            display: block;
            margin-top: 30px;
            color: #393939;
            font-size: 12px;
            font-weight: 600;
        }
        
        .amp-wp-footer-copyrights.amp-wp-footer-copyrights a {
            font-size: 12px;
            color: #8d8d8d;
        }
        /* Footer - Navigation */
        
        .amp-wp-footer-nav ul {
            list-style: none;
            margin-bottom: 15px;
        }
        
        .amp-wp-footer-nav li {
            display: inline-block;
        }
        
        .amp-wp-footer-nav li a {
            padding: 3px 6px;
            color: #8d8d8d;
            font-weight: 600;
        }
        /*** Related Posts*/
        
        .amp-wp-article-related-posts {
            margin-top: 60px;
        }
        
        .amp-wp-article-related-posts h2 {
            margin-bottom: 30px;
        }
        
        .amp-wp-article-related-posts-list {
            position: relative;
            margin: 0 -10px;
        }
        
        .amp-wp-article-related-posts-item {
            display: inline-block;
            vertical-align: top;
            width: 33.3%;
            margin-right: -4px;
        }
        
        .amp-wp-article-related-posts-item a {
            display: block;
            padding: 0 10px;
        }
        
        .amp-wp-article-related-posts-item h3 {
            font-size: 18px;
            font-weight: 600px;
            line-height: 130%;
            color: #393939;
            margin-bottom: 6px;
        }
        
        .amp-wp-article-related-posts-item .amp-wp-meta {
            margin-bottom: 0;
        }
        /*** Categories, Tags*/
        
        .amp-wp-tax-category a,
        .amp-wp-tax-category span,
        .amp-wp-tax-tag a,
        .amp-wp-tax-tag span {
            display: inline-block;
            position: relative;
            padding: 0 15px;
            line-height: 18px;
            margin-right: 10px;
            font-size: 11px;
            border-radius: 100px;
            border: 1px solid #e0e0e0;
            color: #8d8d8d;
            transition: 0.3s all ease-in-out;
            margin-top: 10px;
        }
        
        .amp-wp-tax-category .amp-wp-tax-title,
        .amp-wp-tax-tag .amp-wp-tax-title {
            font-weight: bold;
            background-color: #393939;
            color: #fff;
            border-color: #393939;
        }
        /*** Responsive*/
        
        @media ( max-width: 850px) {
            .amp-wp-header,
            .amp-wp-article,
            .amp-wp-footer {
                padding-left: 15px;
                padding-right: 15px;
            }
            .amp-wp-article {
                margin-top: 15px;
            }
            .amp-wp-article-related-posts-item {
                width: 100%;
                margin-bottom: 30px;
            }
            .amp-wp-article-footer .amp-wp-comments-link {
                margin-top: 0px;
            }
            .amp-wp-header-logo-img,
            .amp-wp-footer-logo-img {
                height: 22px;
            }
        }
        /*** Review styling*/
        /* Inline stylesheets */
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-579c05c {
            width: 423px
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-768e1f2 {
            font-size: 18px
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-46366ac {
            color: #38bdff
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-102b7f8 {
            text-align: left
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-cdd8ca0 {
            text-align: center
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-cdbf967 {
            font-size: 36px;
            color: #999
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-1551e6d {
            font-size: 24px
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-87efdfd {
            background: transparent;
            padding: 0;
            margin: 0px auto;
            margin-top: 0;
            margin-bottom: 0
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-93b8ea5 {
            display: none
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-e9fe603 {
            visibility: hidden
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-7b75264 {
            font-size: 30px
        }
        
        :root:not(#_):not(#_):not(#_):not(#_):not(#_) .amp-wp-95453b0 {
            color: #f63a4c
        }
    </style>