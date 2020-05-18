	
	<!-- footer  -->
	<footer>
		<div class="container">
			@if(AppSetting_manufacture::getManufacture('Logo', 'logo-footer'))
				<img src="{{ AppSetting_manufacture::getManufacture('Logo', 'logo-footer') }}">
			@endif

			<ul class="social-list mt10">
				@foreach(PageRepository::fetchPages() as $page)
					<li>
						<a href="{{ url($page->slug) }}"
							title="{{ $page->title }}">{{ $page->title }}
						</a>
					</li>
				@endforeach
			</ul>

			<ul class="social-list mt10">

				@if(AppSetting_manufacture::getManufacture('Social Media', 'twitter'))
					<li>
						<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'twitter') }}"
							target="_blank"
							title="{{ AppSetting_manufacture::getManufacture('Social Media', 'twitter') }}">
							<i class="fa fa-twitter"></i>
						</a>
					</li>
				@endif

				@if(AppSetting_manufacture::getManufacture('Social Media', 'facebook'))
					<li>
						<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'facebook') }}"
							target="_blank"
							title="{{ AppSetting_manufacture::getManufacture('Social Media', 'facebook') }}">
							<i class="fa fa-facebook"></i>
						</a>
					</li>
				@endif

				@if(AppSetting_manufacture::getManufacture('Social Media', 'instagram'))
					<li>
						<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'instagram') }}"
							target="_blank"
							title="{{ AppSetting_manufacture::getManufacture('Social Media', 'instagram') }}">
							<i class="fa fa-instagram"></i>
						</a>
					</li>
				@endif

				@if(AppSetting_manufacture::getManufacture('Social Media', 'pinterest'))
					<li>
						<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'pinterest') }}"
							target="_blank"
							title="{{ AppSetting_manufacture::getManufacture('Social Media', 'pinterest') }}">
							<i class="fa fa-pinterest"></i>
						</a>
					</li>
				@endif

				@if(AppSetting_manufacture::getManufacture('Social Media', 'linkedin'))
					<li>
						<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'linkedin') }}"
							target="_blank"
							title="{{ AppSetting_manufacture::getManufacture('Social Media', 'linkedin') }}">
							<i class="fa fa-linkedin"></i>
						</a>
					</li>
				@endif

				
			</ul>

			<p class="copyright-line mt10">© Copyright 2020 - All rights reserved.</p>
		</div>
	</footer>
	<!-- End footer -->
	