@extends('frontend.themes.philosphy.layouts.layout')
@section('meta')
	@include('frontend.themes.philosphy.layouts.meta', ['meta'=>$row])
@stop

@section('content')
	
	<section class="s-content s-content--narrow s-content--no-padding-bottom">
		<article class="row format-standard">
			<div class="s-content__header col-full">
				<h1 class="s-content__header-title">
					{{ $row->title }}
				</h1>
				<ul class="s-content__header-meta">
					<li>{{ $row->date }}</li>
					<li> &nbsp;|&nbsp; </li>
					<li>{{ $row->estimate_reading_time }}</li>
				</ul>
			</div>

			<div class="s-content__media col-full">
				<div class="s-content__post-thumb">
					<img src="{{ $row->image }}" 
						srcset="{{ $row->image }} 2000w,
                                {{ $row->image }} 1000w,
                                {{ $row->image }} 500w" 
                        sizes="(max-width: 2000px) 100vw, 2000px" 
                        alt="{{ $row->title }}">
				</div>
			</div> 

			<div class="s-content__media col-full">
				<p class="lead">{!! $row->body !!}</p>

				<p class="s-content__tags">
					@if($row->tags)
					<span>Post Tags</span>
						<span class="s-content__tag-list">
		                    @foreach($row->tags as $tag)                         
								<a href="{{ url('tag/'.$tag) }}" 
			                        title="{{ $tag }}">#{{$tag}}
			                	</a>
							@endforeach
						</span>
					@endif
				</p> 

				<div class="s-content__pagenav">
					<div class="s-content__nav row">

						<div class="s-content__prev br-trans">
							@if($prev)
								<a href="{{ url('post/'.$prev->slug_url) }}"
									title="{{ $prev->title }}" 
									rel="prev">
									<span>Previous Post</span>
									{{ $prev->title }}
								</a>
							@endif
						</div>

						<div class="s-content__next br-trans">
							@if($next)
								<a href="{{ url('post/'.$next->slug_url) }}"
									title="{{ $next->title }}"
									rel="next">
									<span>Next Post</span>
									{{ $next->title }}
								</a>
							@endif
						</div>

					</div>
				</div> 

			</div> 
		</article>
		<p><br/></p>
	</section> 

@stop

@section('scripts')
	@include('frontend.themes.philosphy.posts.jsCode')
@stop