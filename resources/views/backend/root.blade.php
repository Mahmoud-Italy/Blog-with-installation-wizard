<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
    <head>
        <title>iBlog | Dashboard</title>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>window.csrf_token = "{{ csrf_token() }}"</script>

        <!-- Favicon -->
        <link rel="shortcut icon" href="/modules/backend/favicon.png" type="image/x-icon">

        <!-- Web Fonts -->
        <link href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

        <!-- Components Vendor Styles -->
        <link rel="stylesheet" href="/modules/backend/vendor/themify-icons/themify-icons.css">
        <link rel="stylesheet" href="/modules/backend/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">

        <!-- Theme Styles -->
        <link rel="stylesheet" href="/modules/backend/css/theme.css">
    </head>
    <body class="">

            <div id="app">
                <router-view></router-view>
            </div>
            
        <!-- App Js  -->
        <script src="{{ asset('js/app.js') }}"></script>

        <!-- Global Vendor -->
        <script src="/modules/backend/vendor/jquery/dist/jquery.min.js"></script>
        <script src="/modules/backend/vendor/jquery-migrate/jquery-migrate.min.js"></script>
        <script src="/modules/backend/vendor/popper.js/dist/umd/popper.min.js"></script>
        <script src="/modules/backend/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Plugins -->
        <script src="/modules/backend/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="/modules/backend/vendor/chart.js/dist/Chart.min.js"></script>
        <script src="/modules/backend/vendor/chartjs-plugin-style/dist/chartjs-plugin-style.min.js"></script>

        <!-- Initialization  -->
        <script src="/modules/backend/js/sidebar-nav.js"></script>
        <script src="/modules/backend/js/main.js"></script>
        
    </body>
</html>
