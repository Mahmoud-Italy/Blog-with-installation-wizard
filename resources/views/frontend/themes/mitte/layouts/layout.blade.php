<!doctype html>
<html lang="en" class="no-js">
<head>
	@yield('meta')
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.csrf_token = "{{ csrf_token() }}"</script>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,500i,700,700i,900&display=swap" rel="stylesheet">
	
	<link rel="stylesheet" href="{{ url('themes/mitte/assets/css/mite-assets.min.css') }}">
	<link rel="stylesheet" href="{{ url('themes/mitte/assets/css/style.css') }}">
	@yield('css')
</head>
<body>

	<!-- Container -->
	<div id="container">
		
		@include('frontend.themes.mitte.layouts.header')

			@yield('content')

		@include('frontend.themes.mitte.layouts.footer')

	</div>
	<!-- End Container -->

	<div class="preloader">
		<img alt="loader" src="{{ url('themes/mitte/assets/images/loader.gif') }}">
	</div>

	<script src="{{ url('themes/mitte/assets/js/mite-plugins.min.js') }}"></script>
	<script src="{{ url('themes/mitte/assets/js/popper.js') }}"></script>
	<script src="{{ url('themes/mitte/assets/js/bootstrap.min.js') }}"></script>
	<script src="{{ url('themes/mitte/assets/js/gmap3.min.js') }}"></script>
	<script src="{{ url('themes/mitte/assets/js/script.js') }}"></script>

	@yield('scripts')
</body>
</html>