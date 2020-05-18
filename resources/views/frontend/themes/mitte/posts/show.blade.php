@extends('frontend.themes.mitte.layouts.layout')
@section('meta')
	@include('frontend.themes.mitte.layouts.meta', ['meta'=>$row])
@stop

@section('content')
	
	<!-- page-banner section  -->
	<section class="page-banner-section">
		<div class="container">
			<span class="pull-right mtop-10">
				<a href="{{ url('/') }}" class="text-muted">Home</a>
				<span>&nbsp; / &nbsp;</span>
				<a href="{{ url('category/'.$row->category_slug) }}" class="text-muted">{{ $row->category }}</a>
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

							<a class="text-link" 
								href="{{ url('category/'.$row->category_slug) }}"
								title="{{ $row->category }}">{{ $row->category }}
							</a>
							<h1>{{ $row->title }}</h1>
							<ul class="post-tags">
								<li>{{ $row->date }}</li>
								<li>{{ $row->estimate_reading_time }}</li>
							</ul>

							<img src="{{ $row->image }}" 
									alt="{{ $row->title }}"
									style="height: 600px">

								<div class="post-content">
									
									<div class="post-social">
										<span>Share</span>
										<ul class="share-post">
											<li>
												<a href="http://www.facebook.com/sharer.php?s=100&p[title]={{$row->title}}&p[url]={{url()->current()}}&p[images][0]={{$row->image}}"
													target="_blank" 
													class="facebook">
													<i class="fa fa-facebook"></i>
												</a>
											</li>
											<li>
												<a href="https://twitter.com/share?url={{url()->current()}}&amp;text={{$row->title}}"
													target="_blank" 
													class="twitter">
													<i class="fa fa-twitter"></i>
												</a>
											</li>
											<li>
												<a href="https://pinterest.com/pin/create/bookmarklet/?&url={{url()->current()}}&description={{$row->title}}"
													target="_blank" 
													class="pinterest">
													<i class="fa fa-pinterest"></i>
												</a>
											</li>
										</ul>
									</div>

									<div class="post-content-text">
										
										<p>{!! $row->body !!}</p>
										

										@if($row->tags)
											<div class="share-tags-box">
												<ul class="tags">
													@foreach($row->tags as $tag)
														<li>
		                                                    <a href="{{ url('tag/'.$tag) }}" 
		                                                        title="{{ $tag }}"> #{{$tag}} 
		                                                    </a> 
		                                                </li>
	                                                @endforeach
												</ul>
											</div>
										@endif

									</div>	
								</div>


								<div class="prev-next-box">
									<div class="prev-box">
										@if($prev)
											<a class="text-link"
												href="{{ url('post/'.$prev->slug_url) }}"
												title="{{ $prev->title }}">
												<i class="fa fa-angle-left"></i> Previous Post
											</a>
											<h2>
												<a href="{{ url('post/'.$prev->slug_url) }}"
													title="{{ $prev->title }}">{{ $prev->title }}
												</a>
											</h2>
										@endif
									</div>
									
									<div class="next-box">
										@if($next)
											<a class="text-link next-link" 
												href="{{ url('post/'.$next->slug_url) }}"
												title="{{ $next->title }}">
												Next Post <i class="fa fa-angle-right"></i>
											</a>
											<h2>
												<a href="{{ url('post/'.$next->slug_url) }}"
													title="{{ $next->title }}">{{ $next->title }}
												</a>
											</h2>
										@endif
									</div>
								</div>


								@if($related)
									<div class="related-box">
										<h2>Related Posts</h2>
										<div class="row">

										@foreach($related as $rel)
											<div class="col-lg-4 col-md-4">
												<div class="news-post standard-post text-left">
													<div class="image-holder">
														<a href="{{ url('post/'.$rel->slug_url) }}"
															title="{{ $rel->title }}">
															<img src="{{ $rel->image }}" 
																alt="{{ $rel->title }}" 
																style="height: 285px">
														</a>
													</div>
													<a class="text-link" 
														href="{{ url('category/'.$rel->category_slug) }}"
														title="{{ $rel->category }}">{{ $rel->category }}
													</a>
													<h2>
														<a href="{{ url('post/'.$rel->slug_url) }}"
															title="{{ $rel->title }}">
															{{ substr($rel->title, 0, 25) }}{{ (strlen($rel->title) > 25) ? '...' : '' }}
														</a>
													</h2>
													<ul class="post-tags">
														<li>{{ $rel->date }}</li>
														<li>{{ $rel->estimate_reading_time }}</li>
													</ul>
												</div>
											</div>
										@endforeach
											
										</div>
									</div>
								@endif
							</div>

					</div>
				</div>
					
			</div>
		</div>
	</section>
	<!-- End blog section -->
@stop

@section('scripts')
	@include('frontend.themes.mitte.posts.jsCode')
@stop