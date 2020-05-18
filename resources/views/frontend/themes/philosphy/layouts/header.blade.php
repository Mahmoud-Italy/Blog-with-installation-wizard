	
	<!-- Header -->
	@if(request()->segment(1) == '')
		<section class="s-pageheader s-pageheader--home">
	@else
		<div class="s-pageheader">
	@endif

		<header class="header">
			<div class="header__content row">

				<!-- Logo -->
				<div class="header__logo">
					<a class="logo" href="{{ url('/') }}">
						<img src="{{ url('themes/philosphy/assets/images/logo.svg') }}" alt="Home">
					</a>
				</div> 
				<!-- End Logo -->

				<!-- Social Media -->
				<ul class="header__social">
					@if(AppSetting_manufacture::getManufacture('Social Media', 'twitter'))
						<li>
							<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'twitter') }}"
								title="twitter"
								target="_blank">
								<i class="fa fa-twitter" aria-hidden="true"></i>
							</a>
						</li>
					@endif
					@if(AppSetting_manufacture::getManufacture('Social Media', 'facebook'))
						<li>
							<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'facebook') }}"
								title="facebook"
								target="_blank">
								<i class="fa fa-facebook" aria-hidden="true"></i>
							</a>
						</li>
					@endif
					@if(AppSetting_manufacture::getManufacture('Social Media', 'instagram'))
						<li>
							<a href="{{ AppSetting_manufacture::getManufacture('Social Media', 'instagram') }}"
								title="instagram"
								target="_blank">
								<i class="fa fa-instagram" aria-hidden="true"></i>
							</a>
						</li>
					@endif
				</ul> 
				<!-- End Social Media -->

				<!-- Search -->
				<a class="header__search-trigger" href="#"></a>
				<div class="header__search">
					<form role="search" method="get" class="header__search-form" action="{{ url('search') }}">
						<label>
							<span class="hide-content">Search for:</span>
							<input type="search" 
							       class="search-field" 
							       placeholder="Type Keywords" 
								   value="@if(isset($_GET['q'])){{$_GET['q']}}@endif" 
								   name="q" 
								   autocomplete="off">
						</label>
						<input type="submit" class="search-submit" value="Search">
					</form>
					<a href="#" title="Close Search" class="header__overlay-close">Close</a>
				</div> 
				<!-- End Search -->

				<!-- Menu -->
				<a class="header__toggle-menu" href="#0" title="Menu"><span>Menu</span></a>
					<nav class="header__nav-wrap">
						<h2 class="header__nav-heading h6">Site Navigation</h2>
							<ul class="header__nav">
								<!-- Home -->
								<li class="current"><a href="{{ url('/') }}" title="Home">Home</a></li>
								<!-- End Home -->

								<!-- Categories -->
								@foreach(CategoryRepository::fetchCategories() as $category)
									<li @if($category->childs) class="has-children" @endif>
									<a href="{{ url('category/'.$category->slug) }}" 
										title="{{ $category->name }}">{{ $category->name }}</a>
										@if($category->childs)
											<ul class="sub-menu">
												@include('frontend.themes.philosphy.categories.child',
														['childs'=>$category->childs])
											</ul>
										@endif
									</li>
								@endforeach
								<!-- End Categories -->
							</ul> 
						<a href="#" title="Close Menu" class="header__overlay-close close-mobile-menu">Close</a>
					</nav>

			</div> 
		</header> 

		<!-- Slider -->
	@if(request()->segment(1) == '')
		@include('frontend.themes.philosphy.home.slider')
	@endif
		<!-- End Slider -->
	
	@if(request()->segment(1) == '')
		</section> 
	@else
		</div>
	@endif
	<!-- End Header -->