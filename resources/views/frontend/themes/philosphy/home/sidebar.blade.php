		
		<div class="sidebar">

			<!-- Social Media -->
			<div class="widget social-widget">
				<ul class="social-list">
					<li>
						<a href="#">
							<i class="fa fa-facebook"></i> facebook
							<span>0 likes</span>
						</a>
					</li>
					<li>
						<a href="#">
							<i class="fa fa-twitter"></i> twitter
							<span>0 followers</span>
						</a>
					</li>
					<li>
						<a href="#">
							<i class="fa fa-instagram"></i> instagram
							<span>0 followers</span>
						</a>
					</li>
				</ul>
			</div>
			<!-- End Social Media -->


			<!-- Categories -->
			<div class="widget category-widget">
				<h2>Categories</h2>
				<ul class="category-list">
					@foreach(CategoryRepository::fetchCategories() as $category)
						<li>
							<a href="{{ url('category/'.$category->slug) }}" title="{{ $category->name }}">
								{{ $category->name }} <span>{{ $category->posts }}</span> 
							</a>
						</li>
					@endforeach
				</ul>
			</div>
			<!-- End Categories -->

		</div>