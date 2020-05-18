<template>
    <div class="">
        
        <!-- Sidebar -->
		<aside id="sidebar" class="u-sidebar">
			<div class="u-sidebar-inner">

				<!-- Sidebar Header -->
				<header class="u-sidebar-header">
					<router-link :to="{ name: 'dashboard'}" class="u-sidebar-logo">
						<img class="u-sidebar-logo__icon" src="/modules/backend/svg/logo-mini.svg" alt="">
						<img class="u-sidebar-logo__text" src="/modules/backend/svg/logo-text-light.svg" alt="">
					</router-link>
				</header>
				<!-- End Sidebar Header -->

				<!-- Sidebar Nav -->
				<nav class="u-sidebar-nav">
					<!-- Sidebar Nav Menu -->

					<ul class="u-sidebar-nav-menu u-sidebar-nav-menu--top-level">
						<!-- Dashboard -->
						<li class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'dashboard' }" 
								class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/explore') ? 'active' : '' ">
								<span class="ti-dashboard u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Dashboard</span>
							</router-link>
						</li>
						<!-- End Dashboard -->
						

						<!-- Posts -->
						<li class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'posts' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/posts' ||
										this.$route.path == '/dashboard/posts/status/published' ||
										this.$route.path == '/dashboard/posts/status/draft' || 
										this.$route.path == '/dashboard/posts/status/trash' ||
										this.$route.path == '/dashboard/posts/create') ? 'active' : '' ">
								<span class="ti-pin-alt u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Posts</span>
							</router-link>
						</li>
						<!-- End Posts -->

						<!-- Categories -->
						<li class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'categories' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/categories' || 
										this.$route.path == '/dashboard/categories/status/active' ||
										this.$route.path == '/dashboard/categories/status/inactive' ||
										this.$route.path == '/dashboard/categories/status/trash') ? 'active' : '' ">
								<span class="ti-harddrives u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Categories</span>
							</router-link>
						</li>
						<!-- End Categories -->

						<!-- Tags -->
						<li class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'tags' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/tags' || 
										this.$route.path == '/dashboard/tags/status/active' ||
										this.$route.path == '/dashboard/tags/status/inactive' ||
										this.$route.path == '/dashboard/tags/status/trash' ||
										this.$route.path == '/dashboard/tags/create') ? 'active' : '' ">
								<span class="ti-tag u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Tags</span>
							</router-link>
						</li>
						<!-- End Tags -->

						<!-- Media -->
						<li class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'media' }" class="u-sidebar-nav-menu__link"
									:class="(this.$route.path == '/dashboard/media') ? 'active' : '' ">
								<span class="ti-cloud-up u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Media</span>
							</router-link>
						</li>
						<!-- End Media -->

						<!-- Pages -->
						<li class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'pages' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/pages' || 
										this.$route.path == '/dashboard/pages/status/active' ||
										this.$route.path == '/dashboard/pages/status/inactive' ||
										this.$route.path == '/dashboard/pages/status/trash' ||
										this.$route.path == '/dashboard/pages/create') ? 'active' : '' ">
								<span class="ti-files u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Pages</span>
							</router-link>
						</li>
						<!-- End Pages -->

						<!-- Users -->
						<li class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'users' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/users' || 
										this.$route.path == '/dashboard/users/status/administrator' ||
										this.$route.path == '/dashboard/users/status/contributor' ||
										this.$route.path == '/dashboard/users/status/author' ||
										this.$route.path == '/dashboard/users/status/editor' ||
										this.$route.path == '/dashboard/users/status/subscriber' ||
										this.$route.path == '/dashboard/users/status/trash' ||
										this.$route.path == '/dashboard/users/create') ? 'active' : '' ">
								<span class="ti-user u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Users</span>
							</router-link>
						</li>
						<!-- End Users -->

						<!-- Roles -->
						<li v-if="nav_roles == 'show'"
							class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'roles' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/roles' || 
										this.$route.path == '/dashboard/roles/status/active' ||
										this.$route.path == '/dashboard/roles/status/inactive' ||
										this.$route.path == '/dashboard/roles/trash') ? 'active' : '' ">
								<span class="ti-lock u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Roles</span>
							</router-link>
						</li>
						<!-- End Roles -->


						<!-- Sliders -->
						<li v-if="nav_sliders == 'show'"
							class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'sliders' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/sliders' || 
										this.$route.path == '/dashboard/sliders/status/active' ||
										this.$route.path == '/dashboard/sliders/status/inactive' ||
										this.$route.path == '/dashboard/sliders/trash') ? 'active' : '' ">
								<span class="ti-gallery u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Sliders</span>
							</router-link>
						</li>
						<!-- End Sliders -->


						<!-- Thermes -->
						<li v-if="nav_themes == 'show'"
							class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'themes' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/themes') ? 'active' : '' ">
								<span class="ti-palette u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Themes</span>
							</router-link>
						</li>
						<!-- End Themes -->

						<!-- Reports -->
						<li v-if="nav_reports == 'show'"
							class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'reports' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/reports') ? 'active' : '' ">
								<span class="ti-stats-up u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Reports</span>
							</router-link>
						</li>
						<!-- End Reports -->

						<!-- Activity Log -->
						<li v-if="nav_activity_logs == 'show'"
							class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'logs' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/activity-logs') ? 'active' : '' ">
								<span class="ti-brush-alt u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Activity Logs</span>
							</router-link>
						</li>
						<!-- End Activity Log -->

						<!-- Cache Management -->
						<li v-if="nav_cache_management == 'show'"
							class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'cache-management' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/cache-management') ? 'active' : '' ">
								<span class="ti-pulse u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Cache Management</span>
							</router-link>
						</li>
						<!-- End Cache Management -->

						<!-- Settings -->
						<li class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'settings' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/settings') ? 'active' : '' ">
								<span class="ti-layout-grid2 u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">App Settings</span>
							</router-link>
						</li>
						<!-- End Settings -->


						<!-- Settings -->
						<li class="u-sidebar-nav-menu__item">
							<router-link :to="{ name: 'settings' }" class="u-sidebar-nav-menu__link"
								:class="(this.$route.path == '/dashboard/settings') ? 'active' : '' ">
								<span class="ti-agenda u-sidebar-nav-menu__item-icon"></span>
								<span class="u-sidebar-nav-menu__item-title">Documention</span>
							</router-link>
						</li>
						<!-- End Settings -->

						<br/><br/><br/><br/><br/><br/>

					</ul>
					<!-- End Sidebar Nav Menu -->
				</nav>
				<!-- End Sidebar Nav -->

			</div>
		</aside>
		<!-- End Sidebar -->

    </div>
</template>

<script>
    export default {
    	name: 'Navigation',
    	props: {
    		nav_roles: {
    			default: (localStorage.getItem('nav_roles')) ? localStorage.getItem('nav_roles') : 'show',
    		},
    		nav_sliders: {
    			default: (localStorage.getItem('nav_sliders')) ? localStorage.getItem('nav_sliders') : 'show',
    		},
    		nav_themes: {
    			default: (localStorage.getItem('nav_themes')) ? localStorage.getItem('nav_themes') : 'show',
    		},
    		nav_reports: {
    			default: (localStorage.getItem('nav_reports')) ? localStorage.getItem('nav_reports') : 'hide',
    		},
    		nav_activity_logs: {
    			default: (localStorage.getItem('nav_activity_logs')) ? localStorage.getItem('nav_activity_logs') : 'show',
    		},
    		nav_cache_management: {
    			default: (localStorage.getItem('nav_cache_management')) ? localStorage.getItem('nav_cache_management') : 'show',
    		},
    	},
    	data(){
    		return {
    			//
    		}
    	},
        mounted() {},
        watch: {
          $route() {
          	//
          	if(localStorage.getItem('nav_roles')) {
        		this.$props.nav_roles = localStorage.getItem('nav_roles');
        	}
        	if(localStorage.getItem('nav_sliders')) {
        		this.$props.nav_sliders = localStorage.getItem('nav_sliders');
        	}
        	if(localStorage.getItem('nav_themes')) {
        		this.$props.nav_themes = localStorage.getItem('nav_themes');
        	}
        	if(localStorage.getItem('nav_reports')) {
        		this.$props.nav_reports = localStorage.getItem('nav_reports');
        	}
        	if(localStorage.getItem('nav_activity_logs')) {
        		this.$props.nav_activity_logs = localStorage.getItem('nav_activity_logs');
        	}
        	if(localStorage.getItem('nav_cache_management')) {
        		this.$props.nav_cache_management = localStorage.getItem('nav_cache_management');
        	}

          }
      	},
        created() {
        	//
        	if(localStorage.getItem('nav_roles')) {
        		this.$props.nav_roles = localStorage.getItem('nav_roles');
        	}
        	if(localStorage.getItem('nav_sliders')) {
        		this.$props.nav_sliders = localStorage.getItem('nav_sliders');
        	}
        	if(localStorage.getItem('nav_themes')) {
        		this.$props.nav_themes = localStorage.getItem('nav_themes');
        	}
        	if(localStorage.getItem('nav_reports')) {
        		this.$props.nav_reports = localStorage.getItem('nav_reports');
        	}
        	if(localStorage.getItem('nav_activity_logs')) {
        		this.$props.nav_activity_logs = localStorage.getItem('nav_activity_logs');
        	}
        	if(localStorage.getItem('nav_cache_management')) {
        		this.$props.nav_cache_management = localStorage.getItem('nav_cache_management');
        	}
        },
        methods: {
        	//
       //  	requestPropChange: function (path, value) {
		     //    if (this.is(path, 'String') && this.isDefined(value) && (new RegExp('^[a-zA-Z_\\$]\\w*(\\.([a-zA-Z_\\$]\\w*|[0-9]+))*$')).test(path)) {
		     //      this.$emit('propChange', {path: path.split('.'), value: value})
		     //    }
		     // },

        	onChangeNav(e){
        		if(this.$refs['itemComponent'+e].classList.contains('u-sidebar-nav--opened')) {
        			this.$refs['itemComponent'+e].classList.remove('u-sidebar-nav--opened');
        			this.$refs['menuItemComponent'+e].style.display = 'none';
        		} else {
        			this.$refs['itemComponent'+e].classList.add('u-sidebar-nav--opened');
        			this.$refs['menuItemComponent'+e].style.display = 'block';
        		}
        	},

        },
    }
</script>
