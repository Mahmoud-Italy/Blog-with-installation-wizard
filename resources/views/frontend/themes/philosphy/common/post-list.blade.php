	<!-- Post Webkit -->
	@foreach($data as $key => $row)
		<div class="news-post article-post2">
			<div class="row">
				<div class="col-md-6">
					<div class="image-holder">
						<a href="{{ url('post/'.$row->slug_url) }}"
							title="{{ $row->title }}">
							<img src="{{ $row->image }}" alt="{{ $row->title }}" style="height: 245px">
						</a>
					</div>
				</div>
				<div class="col-md-6">
					<a class="text-link" 
						href="{{ url('category/'.$row->category_slug) }}"
						title="{{ $row->category }}">{{ $row->category }}
					</a>
					<h2>
						<a href="{{ url('post/'.$row->slug_url) }}"
							title="{{ $row->title }}">
							{{ substr($row->title, 0, 25) }}{{ (strlen($row->title) > 25) ? '...' : '' }}
						</a>
					</h2>
					<ul class="post-tags">
						<li>{{ $row->date }}</li>
						<li>{{ $row->estimate_reading_time }}</li>
					</ul>
					<p>
						{!! substr(strip_tags(preg_replace(["/<img[^>]+>/","/<iframe[^>]+>/"], ["",""], $row->body)), 0, 165) !!}{{ (strlen($row->body) > 165) ? '...' : '' }}
					</p>
					<a class="text-link" 
						href="{{ url('post/'.$row->slug_url) }}"
						title="Read More">Read More
					</a>
				</div>
			</div>
		</div>
	@endforeach
	<!-- End Webkit -->
	