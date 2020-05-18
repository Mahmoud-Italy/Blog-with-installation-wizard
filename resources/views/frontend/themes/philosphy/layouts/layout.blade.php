<!doctype html>
<html lang="en" class="no-js">
<head>
	@yield('meta')
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.csrf_token = "{{ csrf_token() }}"</script>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<link rel="stylesheet" href="{{ url('themes/philosphy/assets/css/base.css') }}">
	<link rel="stylesheet" href="{{ url('themes/philosphy/assets/css/vendor.css') }}">
	<link rel="stylesheet" href="{{ url('themes/philosphy/assets/css/main.css') }}">

	<script src="{{ url('themes/philosphy/assets/js/modernizr.js') }}" type="text/javascript"></script>
	<script src="{{ url('themes/philosphy/assets/js/pace.min.js') }}" type="text/javascript"></script>

	<link rel="shortcut icon" href="{{ url('themes/philosphy/assets/favicon.ico') }}" type="image/x-icon">
	<link rel="icon" href="{{ url('themes/philosphy/assets/favicon.ico') }}" type="image/x-icon">
	@yield('css')
</head>
<body id="top">

	
		
	@include('frontend.themes.philosphy.layouts.header')

		@yield('content')

	@include('frontend.themes.philosphy.layouts.footer')

	

	<div id="preloader">
		<div id="loader">
			<div class="line-scale">
				<div></div>
					<div></div>
						<div></div>
					<div></div>
				<div></div>
			</div>
		</div>
	</div>

	<script src="{{ url('themes/philosphy/assets/js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('themes/philosphy/assets/js/plugins.js') }}" type="text/javascript"></script>
	<script src="{{ url('themes/philosphy/assets/js/main.js') }}" type="text/javascript"></script>
	<script src="{{ url('themes/philosphy/assets/js/rocket-loader.js') }}" type="text/javascript"></script>

	@yield('scripts')
</body>
</html>