@extends('frontend.themes.philosphy.layouts.layout')
@section('meta')
	@include('frontend.themes.philosphy.layouts.meta', ['meta'=>$row])
@stop

@section('content')
	
	
	<section class="s-content">

		<!-- page-banner -->
		<div class="row narrow">
			<div class="col-full s-content__header" data-aos="fade-up">
				<h1>Search Results for &quot;{{ $_GET['q'] ?? '' }}&quot;</h1>
				<!-- <p class="lead">
					<span>
						<a href="{{ url('/') }}" class="text-muted">Home</a>
						<span>&nbsp; / &nbsp;</span>
						<span> Search </span>
					</span>
				</p> -->
			</div>
		</div>
		<!-- End page-banner -->


		<!-- Content -->
		<div class="row masonry-wrap">
			<div class="masonry">
				<div class="grid-sizer"></div>

					<!-- No Data Found -->
					<div id="search-nofound" class="pd-15 hidden">
						<h2>Nothing Found.</h2>
						<p>Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>
					</div>
	                <!-- End No Data Found -->

	                <!-- Loading Webkit animation -->
	                <div id="search-webkit">
	                    @for($i=0; $i <= 5; $i++)
	                        @include('frontend.themes.philosphy.common.post-grid-md-webkit')
	                    @endfor
	                </div>
	                <!-- End Loading Webkit animation -->

	                <!-- Data Containter -->
	                <div id="search-container"></div>
	                <!-- End Data Container -->

	        </div>

					<!-- Load more -->
					<div class="text-center w100per">
						<button id="search-loadmore" 
							type="button" 
							class="submit btn btn--primary hidden"
							data-next-page-url="{{ url()->current().'/render?page=1' }}">
							Load More
						</button>
					</div>
					<!-- End Load more -->
			
		</div>
		<!-- End Content -->

	</section>
@stop

@section('scripts')
	@include('frontend.themes.philosphy.search.jsCode')
@stop