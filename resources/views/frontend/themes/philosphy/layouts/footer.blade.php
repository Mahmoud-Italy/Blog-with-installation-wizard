	
	<!-- footer  -->
	<footer class="s-footer">
		<div class="s-footer__main">
			<div class="row">

				<div class="col-two md-four mob-full s-footer__sitelinks">
					<h4>Categories</h4>
					<ul class="s-footer__linklist">
						@foreach(CategoryRepository::fetchCategories() as $category)
							<li>
								<a href="{{ url('category/'.$category->slug) }}" 
									title="{{ $category->name }}">{{ $category->name }}</a>
							</li>
						@endforeach
					</ul>
				</div> 

				<div class="col-two md-four mob-full s-footer__archives">
					<h4>Pages</h4>
					<ul class="s-footer__linklist">
						@foreach(PageRepository::fetchPages() as $page)
							<li>
								<a href="{{ url($page->slug) }}"
									title="{{ $page->title }}">{{ $page->title }}
								</a>
							</li>
						@endforeach
					</ul>
				</div> 

				<div class="col-two md-four mob-full s-footer__social">
					<h4>Social</h4>
					<ul class="s-footer__linklist">
						@if(AppSetting_manufacture::getManufacture('Social Media', 'twitter'))
							<li>
								<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'twitter') }}"
									title="twitter"
									target="_blank">Twitter
								</a>
							</li>
						@endif
						@if(AppSetting_manufacture::getManufacture('Social Media', 'facebook'))
							<li>
								<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'facebook') }}"
									title="facebook"
									target="_blank">Facebook
								</a>
							</li>
						@endif
						@if(AppSetting_manufacture::getManufacture('Social Media', 'instagram'))
							<li>
								<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'instagram') }}"
									title="instagram"
									target="_blank">Instagram
								</a>
							</li>
						@endif
					</ul>
				</div> 

				<div class="col-five md-full end s-footer__subscribe">
					<h4>Our Newsletter</h4>
					<p>Sit vel delectus amet officiis repudiandae est voluptatem. Tempora maxime provident nisi et fuga et enim exercitationem ipsam. Culpa consequatur occaecati.</p>
					<!-- <div class="subscribe-form">
						<form id="mc-form" class="group" novalidate="true">
							<input type="email" value="" name="EMAIL" class="email" id="mc-email" placeholder="Email Address" required="">
							<input type="submit" name="subscribe" value="Send">
							<label for="mc-email" class="subscribe-message"></label>
						</form>
					</div> -->
				</div> 

			</div>
		</div> 

		<div class="s-footer__bottom">
			<div class="row">
				<div class="col-full">
					<div class="s-footer__copyright">
						<span>© Copyright 2020 - All rights reserved.</span>
					</div>
					<div class="go-top">
						<a class="smoothscroll" title="Back to Top" href="#top"></a>
					</div>
				</div>
			</div>
		</div> 
	</footer> 
	<!-- End footer -->
	