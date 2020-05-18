	
	@foreach($data as $key => $row)	
		<div class="col-md-4">
			<div class="news-post article-post">
				<div class="image-holder">
					<a href="{{ url('post/'.$row->slug_url) }}"
						title="{{ $row->title }}">
						<img src="{{ $row->image }}" alt="{{ $row->title }}" style="height: 285px">
					</a>
				</div>
				<a class="text-link" 
					href="{{ url('category/'.$row->category_slug) }}"
					title="{{ $row->category }}">{{ $row->category }}
				</a>
				<h2>
					<a href="{{ url('post/'.$row->slug_url) }}"
						title="{{ $row->title }}">
						{{ substr($row->title, 0, 20) }}{{ (strlen($row->title) > 20) ? '...' : '' }}
					</a>
				</h2>
				<ul class="post-tags">
					<li>{{ $row->date }}</li>
					<li>{{ $row->estimate_reading_time }}</li>
				</ul>
			</div>
		</div>
	@endforeach