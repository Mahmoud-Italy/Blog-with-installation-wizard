@extends('frontend.themes.mitte.layouts.layout')
@section('meta')
	@include('frontend.themes.mitte.layouts.meta', ['meta'=>$row])
@stop

@section('content')
	
	<!-- page-banner section  -->
	<section class="page-banner-section">
		<div class="container">
			<h1>Tag Results for &nbsp;&quot;{{str_replace('-',' ',\Request::segment(2))}}&quot;</h1>
			<span class="pull-right mtop-10">
				<a href="{{ url('/') }}" class="text-muted">Home</a>
				<span>&nbsp; / &nbsp;</span>
				<span> Tag </span>
			</span>
		</div>
	</section>
	<!-- End page-banner section -->

	<!-- blog section  -->
	<section class="blog-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="blog-box grid-style">
						<div class="row">

							<!-- No Data Found -->
							<div id="tag-nofound" class="col-md-12 bg-light pd-15 hidden">
								<h2>Nothing Found.</h2>
								<p>Sorry, but nothing matched your tag. Please try again with different tags.</p>
							</div>
	                        <!-- End No Data Found -->

	                        <!-- Loading Webkit animation -->
	                        <div id="tag-webkit" class="row col-12">
	                            @for($i=1; $i <= 6; $i++)
	                                @include('frontend.themes.mitte.common.post-grid-md-webkit')
	                            @endfor
	                        </div>
	                        <!-- End Loading Webkit animation -->

	                        <!-- Data Containter -->
	                        <div id="tag-container" class="row col-12"></div>
	                        <!-- End Data Container -->


							<!-- Load more -->
							<div class="col-md-12 center-button">
								<button id="tag-loadmore" 
									type="button" 
									class="button-one hidden"
									data-next-page-url="{{ url()->current().'/render?page=1' }}">
									Load More
								</button>
							</div>
							<!-- End Load more -->

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End blog section -->
@stop

@section('scripts')
	@include('frontend.themes.mitte.tags.jsCode')
@stop