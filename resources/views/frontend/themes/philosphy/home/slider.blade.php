	
	<!-- Slider -->
	@if(AppSetting::isSetup('Sliders'))
		<style>
			.img-slider {
			  	width: {{ AppSetting_manufacture::getManufacture('Sliders','width') }} !important;
			  	height: {{ AppSetting_manufacture::getManufacture('Sliders','height') }} !important;
			}
			.slider-font-color{
			  	color: {{ AppSetting_manufacture::getManufacture('Sliders','font-color') }} !important;
			}
		</style>

		@if(AppSetting_manufacture::getManufacture('sliders', 'autoload_from_posts') == 'on')

			<div class="pageheader-content row">
				<div class="col-full">
					<div class="featured mt-20">

					@foreach(PostRepository::fetchSliders('sliders', 3) as $key => $row)
						@if($key == 0)
							<div class="featured__column b20trans featured__column--big">
						@else
							<div class="featured__column b20trans featured__column--small">
						@endif

							<div class="entry @if($key == 0) h545 @else h250 @endif" 
									style="background-image:url({{ $row->image }})">
								<div class="entry__content">
									<span class="entry__category">
										<a href="{{ url('category/'.$row->category_slug) }}"
											title="{{ $row->category }}">{{ $row->category }}
										</a>
									</span>
									<h1>
									    <a href="{{ url('post/'.$row->slug_url) }}"
											title="{{ $row->title }}">
									@if($key == 0)
										{{ substr($row->title, 0, 50) }}{{ (strlen($row->title) > 50) ? '...' : '' }}
									@else
										{{ substr($row->title, 0, 25) }}{{ (strlen($row->title) > 25) ? '...' : '' }}
									@endif
										</a>
									</h1>

									<ul class="entry__meta">
										<li>{{ $row->date }}</li>
										<li>{{ $row->estimate_reading_time }}</li>
									</ul>

								</div> 
							</div> 
						</div> 

					@endforeach

					</div>
				</div>
			</div>


		@else

			@if(SliderRepository::fetchSliders())
			
			<div class="pageheader-content row">
				<div class="col-full">
					<div class="featured">

					@foreach(SliderRepository::fetchSliders() as $key => $row)
						@if($key == 0)
							<div class="featured__column featured__column--big">
						@else
							<div class="featured__column featured__column--small">
						@endif

							<div class="entry" style="background-image:url({{ $row->image }})">
								<div class="entry__content">
									<!-- <span class="entry__category">
										<a href="#0">Music</a>
									</span> -->
									<h1>
										@if($row->call_to_action) 
											<a href="{{ $row->call_to_action }}" 
												@if($row->title) title="{{$row->title}}" @endif>
										@endif

											@if($row->title) 
												<h2 class="slider-font-color">{{ $row->title }}</h2> 
											@endif

										@if($row->call_to_action) 
									       </a>
									    @endif
									</h1>
									<!-- <div class="entry__info">
										<a href="#0" class="entry__profile-pic">
											<img class="avatar" src="images/avatars/user-03.jpg" alt="">
										</a>
										<ul class="entry__meta">
											<li><a href="#0">John Doe</a></li>
											<li>December 29, 2017</li>
										</ul>
									</div> -->
								</div> 
							</div> 
						</div> 

					@endforeach

					</div>
				</div>
			</div>
		@endif

		@endif
	@endif
	<!-- End Sliders -->
	