@extends('frontend.themes.mitte.layouts.layout')
@section('meta')
	@include('frontend.themes.mitte.layouts.meta', ['meta'=>$row])
@stop

@section('content')
	
	<!-- Sliders -->
	@include('frontend.themes.mitte.home.slider')
	<!-- End Sliders -->

	<!-- Trending -->
	@include('frontend.themes.mitte.trends.list')
	<!-- End Trending -->

	<!-- blog section  -->
	<section class="blog-section">
		<div class="container">

			<div class="title-section">
				<h1>Latest Articles</h1>
			</div>

			<div class="row">
				<div class="col-lg-8">
					<div class="blog-box list-style">

						<!-- No Data Found -->
						<div id="home-nofound" class="col-md-12 bg-light pd-15 hidden">
							<h2>Nothing Found.</h2>
							<p>Sorry, but nothing found at latest articles.</p>
						</div>
                        <!-- End No Data Found -->

                        <!-- Loading Webkit animation -->
                        <div id="home-webkit">
                            @for($i=1; $i <= 3; $i++)
                                @include('frontend.themes.mitte.common.post-list-webkit')
                            @endfor
                        </div>
                        <!-- End Loading Webkit animation -->

                        <!-- Data Containter -->
                        <div id="home-container"></div>
                        <!-- End Data Container -->


						<!-- Load more -->
						<div class="center-button">
							<button id="home-loadmore" 
								type="button" 
								class="button-one hidden"
								data-next-page-url="{{ url()->current().'/render?page=1' }}">
								Load More
							</button>
						</div>
						<!-- End Load more -->

					</div>
				</div>

				<!-- Sidebar -->
				<div class="col-lg-4">
					@include('frontend.themes.mitte.home.sidebar')
				</div>
				<!-- End Sidebar -->

			</div>
		</div>
	</section>
	<!-- End blog section -->
@stop

@section('scripts')
	@include('frontend.themes.mitte.home.jsCode')
	@include('frontend.themes.mitte.trends.jsCode')
@stop