@extends('frontend.themes.philosphy.layouts.layout')
@section('meta')
	@include('frontend.themes.philosphy.layouts.meta', ['meta'=>$row])
@stop

@section('content')
	
	
	<section class="s-content">

		<!-- page-banner -->
		<div class="row narrow">
			<div class="col-full s-content__header" data-aos="fade-up">
				<h1>Tag Results for &nbsp;&quot;{{str_replace('-',' ',\Request::segment(2))}}&quot;</h1>
				<!-- <p class="lead">
					<span>
						<a href="{{ url('/') }}" class="text-muted">Home</a>
						<span>&nbsp; / &nbsp;</span>
						<span> Tag </span>
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
					<div id="tag-nofound" class="bg-light pd-15 hidden">
						<h2>Nothing Found.</h2>
						<p>Sorry, but nothing matched your tag. Please try again with different tags.</p>
					</div>
	                <!-- End No Data Found -->

	                <!-- Loading Webkit animation -->
	                <div id="tag-webkit">
	                    @for($i=0; $i <= 5; $i++)
	                        @include('frontend.themes.philosphy.common.post-grid-md-webkit')
	                    @endfor
	                </div>
	                <!-- End Loading Webkit animation -->

	                <!-- Data Containter -->
	                <div id="tag-container"></div>
	                <!-- End Data Container -->


	        </div>

					<!-- Load more -->
					<div class="text-center w100per">
						<button id="tag-loadmore" 
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
	@include('frontend.themes.philosphy.tags.jsCode')
@stop