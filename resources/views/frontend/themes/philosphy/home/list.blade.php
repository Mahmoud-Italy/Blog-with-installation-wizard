@extends('frontend.themes.philosphy.layouts.layout')
@section('meta')
	@include('frontend.themes.philosphy.layouts.meta', ['meta'=>$row])
@stop

@section('content')

	<!-- blog section  -->
	<section class="s-content">
		<div class="row masonry-wrap">
			<div class="masonry">
				<div class="grid-sizer"></div>

					<!-- No Data Found -->
					<div id="home-nofound" class="bg-light pd-15 hidden">
						<h2>Nothing Found.</h2>
						<p>Sorry, but nothing found.</p>
					</div>
                    <!-- End No Data Found -->

                    <!-- Loading Webkit animation -->
                    <div id="home-webkit">
                        @for($i=0; $i <= 5; $i++)
                            @include('frontend.themes.philosphy.common.post-grid-md-webkit')
                        @endfor
                    </div>
                    <!-- End Loading Webkit animation -->

                    <!-- Data Containter -->
                    <div id="home-container"></div>
                    <!-- End Data Container -->


                </div>

					<!-- Load more -->
					<div class="text-center w100per">
						<button id="home-loadmore" 
							type="button" 
							class="submit btn btn--primary hidden"
							data-next-page-url="{{ url()->current().'/render?page=1' }}">
							Load More
						</button>
					</div>
					<!-- End Load more -->

		</div>
	</section>
	<!-- End blog section -->
@stop

@section('scripts')
	@include('frontend.themes.philosphy.home.jsCode')
@stop