	
	@foreach($data as $key => $row)	
		<article class="masonry__brick opacity-0 entry 
			@if($key == 1 && $page == 1) format-quote @else format-standard @endif"
			data-aos="fade-up">
			<div class="entry__thumb">
				@if($key == 1 && $page == 1)
					<blockquote>
						<a href="{{ url('post/'.$row->slug_url) }}"
							title="{{ $row->title }}">
							<p>{{ substr($row->title, 0, 90) }}{{ (strlen($row->title) > 90) ? '...' : '' }}</p>
						</a>
						<cite>
							<a href="{{ url('category/'.$row->category_slug) }}"
								title="{{ $row->category }}">{{ $row->category }}
							</a>
						</cite>
					</blockquote>
				@else
					<a href="{{ url('post/'.$row->slug_url) }}"
							title="{{ $row->title }}"
							class="entry__thumb-link">
						<img src="{{ $row->image }}" 
							alt="{{ $row->title }}"
							style="height: 285px">
					</a>
				@endif
			</div>
			@if($key != 1 || $page != 1)
				<div class="entry__text">
					<div class="entry__header">
						<div class="entry__date">
							<span>{{ $row->date }}</span>
							<span> &nbsp;|&nbsp; </span>
							<span>{{ $row->estimate_reading_time }}</span>
						</div>
						<h1 class="entry__title">
							<a href="{{ url('post/'.$row->slug_url) }}"
								title="{{ $row->title }}">
								{{ substr($row->title, 0, 25) }}{{ (strlen($row->title) > 25) ? '...' : '' }}
							</a>
						</h1>
					</div>
					<div class="entry__meta">
						<span class="entry__meta-links">
							<a href="{{ url('category/'.$row->category_slug) }}"
								title="{{ $row->category }}">{{ $row->category }}
							</a>
						</span>
					</div>
				</div>
			@endif
		</article> 
	@endforeach