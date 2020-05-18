	<!-- top-home-section  -->
		<section class="top-home-section">
			<div class="container">
				<div class="title-section text-center"></div>
				<div class="top-home-box">
					<div class="row">

					@for($i = 0; $i <=4; $i++)
						@if($i == 1)
						<div class="col-lg-6 col-md-12">
								<div class="row">
						@endif

							<div class="col-lg-6 col-md-12">
								<div class="news-post image-post">
									@if($i == 0)
										<div class="webkit-animation webkit-100-430"></div>
									@else
										<div class="webkit-animation webkit-100-200"></div>
									@endif
									<div class="hover-post">
										<div class="webkit-animation webkit-80-20"></div>
										<h2 class="webkit-animation webkit-100-60 mt10"></h2>
										
										<div class="row col-md-12 mt10">
											<div class="webkit-animation webkit-70-20 mr10"></div>
											<div class="webkit-animation webkit-70-20 mr10"></div>
										</div>
									</div>
								</div>
							</div>

						@if($i == 4)
								</div>
							</div>
						@endif

					@endfor

							</div>
						</div>
					</div>

				</div>
			</div>
		</section>
	<!-- End top-home section -->