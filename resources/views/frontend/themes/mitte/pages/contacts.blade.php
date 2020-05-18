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

	<!-- contact section  -->
	<section class="contact-section">
		<div class="container">
			<div class="row">
				
				<div class="col-lg-3"></div>
				<div class="col-lg-6">
					<div class="contact-box">

						<form id="contact-form">
								<div class="row">
									<div class="col-md-6">
										<input name="name" id="name" type="text" placeholder="Name*">
									</div>
									<div class="col-md-6">
										<input name="mail" id="mail" type="text" placeholder="Email*">
									</div>
								</div>
								<input name="subject" id="subject" type="text" placeholder="Subject">
								<textarea name="comment" id="comment" placeholder="Your Message*"></textarea>
								<input type="submit" id="submit_contact" value="Submit">
								<div id="msg" class="message"></div>
							</form>

					</div>
				</div>

			</div>
		</div>
	</section>
	<!-- End contact section -->
@stop