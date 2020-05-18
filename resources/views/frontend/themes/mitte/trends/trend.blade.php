	<!-- top-home-section  -->
		<section class="top-home-section">
			<div class="container">
				<div class="title-section text-center">
					<h1>Trending Posts</h1>
				</div>
				<div class="top-home-box">
					<div class="row">

					@foreach($data as $key => $row)
						@if($key == 1)
						<div class="col-lg-6 col-md-12">
								<div class="row">
						@endif

							<div class="col-lg-6 col-md-12">
								<div class="news-post image-post">
									<a href="{{ url('post/'.$row->slug_url) }}">
										<img src="{{ $row->image }}" 
												alt="{{ $row->title }}" 
												@if($key == 0) style="height: 430px" @else style="height:200px" @endif>
									</a>
									<div class="hover-post">
										<a class="category-link" 
												href="{{ url('category/'.$row->category_slug) }}"
												title="{{ $row->category }}">{{ $row->category }}
										</a>
										<h2 @if($key != 0) class="f15" @endif>
											<a href="{{ url('post/'.$row->slug_url) }}"
												title="{{ $row->title }}">
											{{ substr($row->title, 0, 25) }}{{ (strlen($row->title) > 25) ? '...' : '' }}
											</a>
										</h2>
										<ul class="post-tags">
											<li>{{ $row->date }}</li>
											<li>{{ $row->estimate_reading_time }}</li>
										</ul>
									</div>
								</div>
							</div>

						@if($key == 4)
								</div>
							</div>
						@endif

					@endforeach

							
							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
	<!-- End top-home section -->