	
	<!-- Header -->
	<header class="clearfix">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">

				<!-- Logo -->
				<a class="navbar-brand" href="{{ url('/') }}">
					@if(AppSetting_manufacture::getManufacture('Logo', 'logo-footer'))
						<img src="{{ AppSetting_manufacture::getManufacture('Logo', 'logo-footer') }}">
					@endif
				</a>
				<!-- End Logo -->

				<!-- Toggler -->
				<button class="navbar-toggler" 
						type="button" 
						data-toggle="collapse" 
						data-target="#navbarSupportedContent" 
						aria-controls="navbarSupportedContent" 
						aria-expanded="false" 
						aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<!-- End Toggler -->

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav m-auto">
						<!-- Home -->
						<li><a href="{{ url('/') }}" title="Home">Home</a></li>
						<!-- End Home -->

						<!-- Categories -->
						@foreach(CategoryRepository::fetchCategories() as $category)
							<li class="drop-link">
								<a class="" 
									href="{{ url('category/'.$category->slug) }}" 
									title="{{ $category->name }} ">{{ $category->name }} 
									@if($category->childs)
										<i class="fa fa-angle-down" aria-hidden="true"></i>
									@endif
								</a>
								@if($category->childs)
									<ul class="dropdown">
										@include('frontend.themes.mitte.categories.child',
										['childs'=>$category->childs])
									</ul>
								@endif
							</li>
						@endforeach
						<!-- End Categories -->
					</ul>
				</div>
				
				<!-- Search -->
				<a class="search-button" href="#"><i class="fa fa-search"></i></a>
				<form action="{{ url('search') }}" method="get" class="form-search">
					<input type="search" 
							name="q" 
							placeholder="Search" 
						 	value="@if(isset($_GET['q'])){{$_GET['q']}}@endif" />
				</form>
				<!-- End Search -->

			</div>
		</nav>
	</header>
	<!-- End Header -->