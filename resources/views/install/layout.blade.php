<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>iBlog</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600|Rubik:400,500">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        @include('install.css')
    </head>

    <body>
        <div class="wrapper">
            <div class="col-lg-8 col-md-9 col-sm-10">
                <div class="row">

                    <div class="col-md-3">
                        <ul class="list-inline left-sidebar">
                            <li class="{{ request()->is('install/pre-installation') ? 'active' : 'complete' }}">
                                1. Pre-Installation
                            </li>

                            <li class="{{ request()->is('install/configuration') ? 'active' : '' }} {{ request()->is('install/complete') ? 'complete' : '' }}">
                                2. Configuration
                            </li>

                            <li class="{{ request()->is('install/complete') ? 'active' : '' }}">
                                3. Complete
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-9">
                        <div class="content-wrapper clearfix">
                            <div class="content">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

        @stack('scripts')
    </body>
</html>
