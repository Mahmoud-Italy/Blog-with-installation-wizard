<template>
    <div class="">

    	<!-- Header (Topbar) -->
		<header class="u-header">
			<div class="u-header-left">
				<a class="u-header-logo" href="javascript:;">
					<img class="u-header-logo__icon" src="/modules/backend/svg/logo-mini.svg" alt="">
					<img class="u-header-logo__text" src="/modules/backend/svg/logo-text-light.svg" alt="">
				</a>
			</div>

			<!-- Header Middle Section -->
			<div class="u-header-middle">
				<!-- Sidebar Invoker -->
				<div class="u-header-section">
					<a class="js-sidebar-invoker u-header-invoker u-sidebar-invoker"
						href="javascript:;" 
					    data-is-close-all-except-this="true"
					    data-target="#sidebar">
						    <span class="ti-align-left u-header-invoker__icon u-sidebar-invoker__icon--open"></span>
							<span class="ti-align-justify u-header-invoker__icon u-sidebar-invoker__icon--close"></span>
					</a>
				</div>
				<!-- End Sidebar Invoker -->

				<!-- Header Search -->
				<div class="u-header-section justify-content-sm-start flex-grow-1 py-0">
					<div class="u-header-search"
					     data-search-mobile-invoker="#headerSearchMobileInvoker"
					     data-search-target="#headerSearch">
						<!-- Header Search Invoker (Mobile Mode) -->
						<a id="headerSearchMobileInvoker" class="u-header-search__mobile-invoker align-items-center" 
							href="javascript:;">
							<span class="ti-search"></span>
						</a>
						<!-- End Header Search Invoker (Mobile Mode) -->

						<!-- Header Search Form -->
						<div id="headerSearch" class="u-header-search-form">
							<form @submit.prevent="onSearch" class="w-100">
								<div class="input-group h-100">
									<button class="btn-link input-group-prepend u-header-search__btn" type="submit">
										<span class="ti-search"></span>
									</button>
									<input class="form-control u-header-search__field" 
										type="search"
										v-model="search"
										placeholder="Type to search…">
								</div>
							</form>
						</div>
						<!-- End Header Search Form -->
					</div>
				</div>
				<!-- End Header Search -->

				<!-- Messages -->
				<div class="u-header-section">
					<div class="u-header-dropdown dropdown pt-1">
						<router-link :to="{ name: 'messages' }" class="u-header-invoker d-flex align-items-center">
						  	<span class="position-relative">
								<span class="ti-email u-header-invoker__icon"></span>
								<!-- <span class="u-indicator u-indicator-top-right u-indicator-xxs bg-danger"></span> -->
							</span>
						</router-link>
					</div>
				</div>
				<!-- End Messages -->

				<!-- Logs -->
				<div class="u-header-section">
					<div class="u-header-dropdown dropdown pt-1">
						<router-link :to="{ name: 'logs' }" class="u-header-invoker d-flex align-items-center">
						    <span class="position-relative">
								<span class="ti-brush-alt u-header-invoker__icon"></span>
								<!-- <span class="u-indicator u-indicator-top-right u-indicator-xxs bg-danger"></span> -->
							</span>
						</router-link>
					</div>
				</div>
				<!-- End Logs -->

				<!-- App Settings -->
				<div class="u-header-section">
					<div class="u-header-dropdown dropdown pt-1">
						<router-link :to="{ name: 'settings' }" class="u-header-invoker d-flex align-items-center">
						  <span class="position-relative">
								<span class="ti-layout-grid2 u-header-invoker__icon"></span>
							</span>
						</router-link>
					</div>
				</div>
				<!-- End App Settings -->

				<!-- User Profile -->
				<div class="u-header-section u-header-section--profile">
					<div class="u-header-dropdown dropdown">
						<a class="link-muted d-flex align-items-center" href="#" role="button" id="userProfileInvoker" aria-haspopup="true" aria-expanded="false"
						   data-toggle="dropdown"
						   data-offset="0">
							<img class="u-header-avatar img-fluid rounded-circle mr-md-3" 
								:src="auth.avatar" alt="User Profile">
							<span class="text-dark d-none d-md-inline-flex align-items-center">
								{{ auth.username }}
								<span class="ti-angle-down text-muted ml-4"></span>
							</span>
						</a>

						<div class="u-header-dropdown__menu dropdown-menu dropdown-menu-right" 
							aria-labelledby="userProfileInvoker">
							<div class="card p-3">
								<div class="card-body p-0">
									<ul class="list-unstyled mb-0">
										<li class="mb-3">
											<router-link :to="{ name: 'edit-user', params:{id: auth.user_id} }" 
													class="link-dark">Update Profile
											</router-link>
										</li>
										<li>
											<a class="link-dark" href="javascript:;" @click="logout">Logout</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End User Profile -->


			</div>
			<!-- End Header Middle Section -->
		</header>
		<!-- End Header (Topbar) -->
        
    </div>
</template>

<script>
    export default {
    	name: 'Header',
    	data(){
    		return {
    			auth: {
    				user_id: '',
    				avatar: '',
    				username: '',
    				accessToken: '',
    			},
    			search: '',
    		}
    	},
    	mounted() {},
        created() {
            //
            if(localStorage.getItem('user_id')) {
            	this.auth.user_id = localStorage.getItem('user_id');
            }
            if(localStorage.getItem('avatar')) {
            	this.auth.avatar = localStorage.getItem('avatar');
            }
            if(localStorage.getItem('username')) {
            	this.auth.username = localStorage.getItem('username');
            }
            if(localStorage.getItem('accessToken')) {
            	this.auth.accessToken = localStorage.getItem('accessToken');
            }
            
            
        },
        methods: {
        	//
        	onSearch(){
        		this.$emit("headerToChild", this.search);
        	},

        	// Logout
        	logout(){
        		localStorage.removeItem('accessToken');
        		localStorage.removeItem('avatar');
        		localStorage.removeItem('username');
        		localStorage.removeItem('user_id');
        		localStorage.removeItem('role');

        		// clear default Navigation
        		localStorage.removeItem('nav_roles');
        		localStorage.removeItem('nav_sliders');
                localStorage.removeItem('nav_themes');
                localStorage.removeItem('nav_reports');
                localStorage.removeItem('nav_activity_logs');
                localStorage.removeItem('nav_cache_management');
        		this.$router.push({ name: 'login' });
        	},
        },

        //
        opnSideNav(){
        	console.log('====');
        	let el = document.querySelector("body");
            if(el.classList.has('side-nav-on-action')) {
                el.classList.remove('side-nav-on-action');
            } else {
                el.classList.add('side-nav-on-action');
            }
        },


    }
</script>
