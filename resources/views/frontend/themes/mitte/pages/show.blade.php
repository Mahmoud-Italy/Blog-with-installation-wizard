@extends('frontend.themes.mitte.layouts.layout')
@section('meta')
	@include('frontend.themes.mitte.layouts.meta', ['meta'=>$row])
@stop

@section('content')
	
	<!-- page-banner section  -->
	<section class="page-banner-section">
		<div class="container">
			<h1>{{ $row->title }}</h1>
			<span class="pull-right mtop-10">
				<a href="{{ url('/') }}" class="text-muted">Home</a>
				<span>&nbsp; / &nbsp;</span>
				<span> {{ $row->title }} </span>
			</span>
		</div>
	</section>
	<!-- End page-banner section -->

	<!-- blog section  -->
	<section class="blog-section">
		<div class="container">
			<div class="row">
				
				<div class="col-lg-12">
					<div class="single-post">
						<div class="single-post-content">

							<p>{!! nl2br($row->body) !!}</p>

						</div>
					</div>
				</div>

			</div>
		</div>
	</section>
	<!-- End blog section -->
@stop