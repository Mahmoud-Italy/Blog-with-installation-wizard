	
	<!-- Sliders -->
	@if(AppSetting::isSetup('Sliders'))	
		@if(SliderRepository::fetchSliders())
			<style>
			  .img-slider {
			  	width: {{ AppSetting_manufacture::getManufacture('Sliders','width') }} !important;
			  	height: {{ AppSetting_manufacture::getManufacture('Sliders','height') }} !important;
			  }
			  .slider-font-color{
			  	color: {{ AppSetting_manufacture::getManufacture('Sliders','font-color') }} !important;
			  }
			</style>
			<section class="top-slider-section fullscreen-slider">
				<div class="top-slider-box text-center">
					<div class="owl-wrapper">
						<div class="owl-carousel" data-num="1">
							
							@foreach(SliderRepository::fetchSliders() as $row)
								<div class="item">
									<div class="news-post image-post">
										@if($row->call_to_action) <a href="{{ $row->call_to_action }}"> @endif
											<img src="{{ url($row->image) }}" alt="{{ $row->title }}" class="img-slider">
											<div class="hover-post">
												@if($row->title) <h2 class="slider-font-color">{{ $row->title }}</h2> @endif
												@if($row->body)
													<ul class="post-tags">
														<li class="slider-font-color">{{ $row->body }}</li>
													</ul>
												@endif
											</div>
										@if($row->call_to_action) </a> @endif
									</div>
								</div>
							@endforeach

						</div>
					</div>
				</div>
			</section>
		@endif
	@endif
	<!-- End Sliders -->
	