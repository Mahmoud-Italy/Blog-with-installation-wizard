<!doctype html>
<html amp lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1">
    @yield('meta')
    <link rel="canonical" href="{{ str_replace('amp','',url()->current()) }}" />
    <script type='text/javascript' src='https://cdn.ampproject.org/v0.js' async></script>

    @include('amp.themes.philosphy.layouts.style-amp')

    <script type="application/ld+json">

    </script>

    <meta name="generator" content="AMP Plugin v1.4.3; mode=reader; experiences=website">
    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
</head>

<body class="">
    
    @yield('content')

    <footer class="amp-wp-footer">
        <div class="amp-wp-footer-container">
            
            <div class="amp-wp-footer-logo">
                <a href="{{ url('/amp') }}">
                    <span class="amp-wp-footer-logo-img" 
                        style="background-image: url( //assets/frontend/img/logo.png )">
                    </span>
                </a>
            </div>

            <div class="amp-wp-footer-nav">
                <div class="sh-nav-container">
                    
                </div>
            </div>

            <div class="amp-wp-footer-copyrights">
                <span class="developer-copyrights">Copyright 2020 All rights reserved. </span> <span></span>
            </div>
            <a href="#top" class="amp-wp-back-to-top">Back to top</a>
        </div>
    </footer>

</body>
</html>