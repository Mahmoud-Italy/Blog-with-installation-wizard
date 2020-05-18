<template>
    <div class="">
        <Header></Header>

        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">
                <div class="u-body min-h-700">
                    <div class="row">

                        <!-- Check Update -->
                        <div class="col-12 mb-3">
                            <button type="button" 
                                class="pull-right btn btn-primary btn-sm btn-pill ui-mt-10 ui-mb-2 btn-with-icon">
                                <span class="btn-icon ti-download mr-2"></span>
                                <span> No new updates available! </span>
                            </button>
                        </div>
                        <!-- End Check Update -->


                        <!-- Total Posts -->
                        <div class="col-sm-6 col-xl-3 mb-5">
                            <div class="card">
                                <div class="card-body">

                                    <div class="dropdown show">
                                        <span class="ti-more pull-right cursor-pointer" 
                                            id="dropMenuPosts" 
                                            data-toggle="dropdown" 
                                            aria-haspopup="true" 
                                            aria-expanded="false"></span>
                                        <div class="dropdown-menu" aria-labelledby="dropMenuPosts">
                                            <span class="dropdown-item " 
                                                :class="(post_days == '0') ? 'active' : ''"
                                                @click="fetchTotalPosts(0)">Today</span>
                                            <span class="dropdown-item "
                                                :class="(post_days == '1') ? 'active' : ''"
                                                @click="fetchTotalPosts(1)">Yesterday</span>
                                            <span class="dropdown-item "
                                                :class="(post_days == '7') ? 'active' : ''"
                                                @click="fetchTotalPosts(7)">Last 7 days</span>
                                            <span class="dropdown-item "
                                                :class="(post_days == '28') ? 'active' : ''"
                                                @click="fetchTotalPosts(28)">Last 28 days</span>
                                            <span class="dropdown-item "
                                                :class="(post_days == '90') ? 'active' : ''"
                                                @click="fetchTotalPosts(90)">Last 90 days</span>
                                            <span class="dropdown-item "
                                                :class="(post_days == '180') ? 'active' : ''"
                                                @click="fetchTotalPosts(180)">Last 180 days</span>
                                            <span class="dropdown-item "
                                                :class="(post_days == 'infinity') ? 'active' : ''"
                                                @click="fetchTotalPosts('infinity')">Last calendar year</span>
                                        </div>
                                    </div>

                                    <div class="media align-items-center py-2">
                                        <div class="media-body">
                                            <h5 class="h5 text-muted mb-2">Total Posts</h5>
                                            <span v-if="postLoading">
                                                <div class="spinner-grow spinner-grow-sm mr-1" role="status">
                                                      <span class="sr-only">Loading...</span>
                                                </div>
                                            </span>
                                            <span v-if="!postLoading" 
                                                class="h2 font-weight-normal mb-0">{{ total_posts }}
                                            </span>
                                        </div>
                                        <div v-if="!postLoading" 
                                                class="text-right ml-2" 
                                                style="max-width: 70px">
                                            <div class="mb-2"></div>
                                            <span class="text-success">{{ percentage_posts }}
                                                <span :class="arrow_posts"></span>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Total Posts -->


                        <!-- Total Posts Views -->
                        <div class="col-sm-6 col-xl-3 mb-5">
                            <div class="card">
                                <div class="card-body">

                                    <div class="dropdown show">
                                        <span class="ti-more pull-right cursor-pointer" 
                                            id="dropMenuPosts" 
                                            data-toggle="dropdown" 
                                            aria-haspopup="true" 
                                            aria-expanded="false"></span>
                                        <div class="dropdown-menu" aria-labelledby="dropMenuPosts">
                                            <span class="dropdown-item " 
                                                :class="(view_days == '0') ? 'active' : ''"
                                                @click="fetchTotalViews(0)">Today</span>
                                            <span class="dropdown-item "
                                                :class="(view_days == '1') ? 'active' : ''"
                                                @click="fetchTotalViews(1)">Yesterday</span>
                                            <span class="dropdown-item "
                                                :class="(view_days == '7') ? 'active' : ''"
                                                @click="fetchTotalViews(7)">Last 7 days</span>
                                            <span class="dropdown-item "
                                                :class="(view_days == '28') ? 'active' : ''"
                                                @click="fetchTotalViews(28)">Last 28 days</span>
                                            <span class="dropdown-item "
                                                :class="(view_days == '90') ? 'active' : ''"
                                                @click="fetchTotalViews(90)">Last 90 days</span>
                                            <span class="dropdown-item "
                                                :class="(view_days == '180') ? 'active' : ''"
                                                @click="fetchTotalViews(180)">Last 180 days</span>
                                            <span class="dropdown-item "
                                                :class="(view_days == 'infinity') ? 'active' : ''"
                                                @click="fetchTotalViews('infinity')">Last calendar year</span>
                                        </div>
                                    </div>

                                    <div class="media align-items-center py-2">
                                        <div class="media-body">
                                            <h5 class="h5 text-muted mb-2">Total Views</h5>
                                            <span v-if="viewLoading">
                                                <div class="spinner-grow spinner-grow-sm mr-1" role="status">
                                                      <span class="sr-only">Loading...</span>
                                                </div>
                                            </span>
                                            <span v-if="!viewLoading" 
                                                class="h2 font-weight-normal mb-0">{{ total_views }}
                                            </span>
                                        </div>
                                        <div v-if="!viewLoading" class="text-right ml-2" style="max-width: 70px">
                                            <div class="mb-2"></div>
                                            <span class="text-success">{{ percentage_views }}
                                                <span :class="arrow_views"></span>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Total Posts Views -->


                        <!-- Total Posts Views -->
                        <div class="col-sm-6 col-xl-3 mb-5">
                            <div class="card">
                                <div class="card-body">

                                    <div class="dropdown show">
                                        <span class="ti-more pull-right cursor-pointer" 
                                            id="dropMenuPosts" 
                                            data-toggle="dropdown" 
                                            aria-haspopup="true" 
                                            aria-expanded="false"></span>
                                        <div class="dropdown-menu" aria-labelledby="dropMenuPosts">
                                            <span class="dropdown-item " 
                                                :class="(user_days == '0') ? 'active' : ''"
                                                @click="fetchTotalUsers(0)">Today</span>
                                            <span class="dropdown-item "
                                                :class="(user_days == '1') ? 'active' : ''"
                                                @click="fetchTotalUsers(1)">Yesterday</span>
                                            <span class="dropdown-item "
                                                :class="(user_days == '7') ? 'active' : ''"
                                                @click="fetchTotalUsers(7)">Last 7 days</span>
                                            <span class="dropdown-item "
                                                :class="(user_days == '28') ? 'active' : ''"
                                                @click="fetchTotalUsers(28)">Last 28 days</span>
                                            <span class="dropdown-item "
                                                :class="(user_days == '90') ? 'active' : ''"
                                                @click="fetchTotalUsers(90)">Last 90 days</span>
                                            <span class="dropdown-item "
                                                :class="(user_days == '180') ? 'active' : ''"
                                                @click="fetchTotalUsers(180)">Last 180 days</span>
                                            <span class="dropdown-item "
                                                :class="(user_days == 'infinity') ? 'active' : ''"
                                                @click="fetchTotalUsers('infinity')">Last calendar year</span>
                                        </div>
                                    </div>

                                    <div class="media align-items-center py-2">
                                        <div class="media-body">
                                            <h5 class="h5 text-muted mb-2">Total Users</h5>
                                            <span v-if="userLoading">
                                                <div class="spinner-grow spinner-grow-sm mr-1" role="status">
                                                      <span class="sr-only">Loading...</span>
                                                </div>
                                            </span>
                                            <span v-if="!userLoading" 
                                                class="h2 font-weight-normal mb-0">{{ total_users }}
                                            </span>
                                        </div>
                                        <div v-if="!userLoading" class="text-right ml-2" style="max-width: 70px">
                                            <div class="mb-2"></div>
                                            <span class="text-success">{{ percentage_users }}
                                                <span :class="arrow_users"></span>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Total Subscribers -->

                        <!-- Total Visitors -->
                        <div class="col-sm-6 col-xl-3 mb-5">
                            <div class="card">
                                <div class="card-body">

                                    <div class="dropdown show">
                                        <span class="ti-more pull-right cursor-pointer" 
                                            id="dropMenuPosts" 
                                            data-toggle="dropdown" 
                                            aria-haspopup="true" 
                                            aria-expanded="false"></span>
                                        <div class="dropdown-menu" aria-labelledby="dropMenuPosts">
                                            <span class="dropdown-item " 
                                                :class="(visitor_days == '0') ? 'active' : ''"
                                                @click="fetchTotalVisitors(0)">Today</span>
                                            <span class="dropdown-item "
                                                :class="(visitor_days == '1') ? 'active' : ''"
                                                @click="fetchTotalVisitors(1)">Yesterday</span>
                                            <span class="dropdown-item "
                                                :class="(visitor_days == '7') ? 'active' : ''"
                                                @click="fetchTotalVisitors(7)">Last 7 days</span>
                                            <span class="dropdown-item "
                                                :class="(visitor_days == '28') ? 'active' : ''"
                                                @click="fetchTotalVisitors(28)">Last 28 days</span>
                                            <span class="dropdown-item "
                                                :class="(visitor_days == '90') ? 'active' : ''"
                                                @click="fetchTotalVisitors(90)">Last 90 days</span>
                                            <span class="dropdown-item "
                                                :class="(visitor_days == '180') ? 'active' : ''"
                                                @click="fetchTotalVisitors(180)">Last 180 days</span>
                                            <span class="dropdown-item "
                                                :class="(visitor_days == 'infinity') ? 'active' : ''"
                                                @click="fetchTotalVisitors('infinity')">Last calendar year</span>
                                        </div>
                                    </div>

                                    <div class="media align-items-center py-2">
                                        <div class="media-body">
                                            <h5 class="h5 text-muted mb-2">Total Visitors</h5>
                                            <span v-if="visitorLoading">
                                                <div class="spinner-grow spinner-grow-sm mr-1" role="status">
                                                      <span class="sr-only">Loading...</span>
                                                </div>
                                            </span>
                                            <span v-if="!visitorLoading" 
                                                class="h2 font-weight-normal mb-0">{{ total_visitors }}
                                            </span>
                                        </div>
                                        <div v-if="!visitorLoading" class="text-right ml-2" style="max-width: 70px">
                                            <div class="mb-2"></div>
                                            <span class="text-success">{{ percentage_visitors }}
                                                <span :class="arrow_visitors"></span>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Total Visitors -->
                        
                    </div>






                    <div class="row">

                        <div class="col-md-8 mb-5">
                            <div class="card ui-h-480">
                                <header class="card-header 
                                        d-flex align-items-center justify-content-between">
                                    <h2 class="h4 card-header-title">Posts Views During the year</h2>
                                    
                                    <!-- Dropdown -->
                                    <span class="ti-more pull-right cursor-pointer"
                                            id="dropMenuCharts" 
                                            data-toggle="dropdown" 
                                            aria-haspopup="true" 
                                            aria-expanded="false"></span>
                                    
                                     <div class="dropdown-menu" aria-labelledby="dropMenuCharts">
                                        <span class="dropdown-item " 
                                                :class="(line_type == 'weekly') ? 'active' : ''"
                                                @click="fetchLineChart('weekly')">Weekly
                                        </span>
                                        <span class="dropdown-item "
                                                :class="(line_type == 'monthly') ? 'active' : ''"
                                                @click="fetchLineChart('monthly')">Monthly
                                        </span>
                                        <span class="dropdown-item "
                                                :class="(line_type == 'quarter') ? 'active' : ''"
                                                @click="fetchLineChart('quarter')">Quarter
                                        </span>
                                        <span class="dropdown-item "
                                                :class="(line_type == 'yearly') ? 'active' : ''"
                                                @click="fetchLineChart('yearly')">Yearly
                                        </span>
                                    </div>
                                    <!-- End Dropdown -->

                                </header>

                                <div class="card-body pt-0 text-center">
                                    <span v-if="chartLoading">
                                        <div class="spinner-grow spinner-grow-md mr-1 ui-mt150" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </span>
                                    <apexchart v-if="!chartLoading" 
                                                class="mt-md-3 mt-xl-0"
                                                type=area 
                                                :options="chartOptions1" 
                                                :series="series1"  
                                                height="380" />
                                </div>
                            </div>
                        </div>




                        <div class="col-md-4 mb-5">
                            <div class="card ui-h-300">
                                <header class="card-header u-header-dropdown dropdown 
                                    d-flex align-items-center justify-content-between">

                                    <h2 class="h4 card-header-title">Where your users located</h2>

                                    <!-- Dropdown -->
                                    <a class="link-muted d-flex pull-right" 
                                            role="button" 
                                            id="dropMenuCountries" 
                                            aria-haspopup="true" 
                                            aria-expanded="false"
                                            data-toggle="dropdown">
                                            <span class="ti-more pull-right cursor-pointer"></span>
                                   </a>
                                    
                                    <div class="u-header-dropdown__menu dropdown-menu dropdown-menu-right" 
                                            aria-labelledby="dropMenuCountries">
                                        <span class="dropdown-item " 
                                                :class="(pie_days == '0') ? 'active' : ''"
                                                @click="fetchCountries(0)">Today
                                        </span>
                                        <span class="dropdown-item "
                                                :class="(pie_days == '1') ? 'active' : ''"
                                                @click="fetchCountries(1)">Yesterday
                                        </span>
                                        <span class="dropdown-item "
                                                :class="(pie_days == '7') ? 'active' : ''"
                                                @click="fetchCountries(7)">Last 7 days
                                        </span>
                                        <span class="dropdown-item "
                                                :class="(pie_days == '28') ? 'active' : ''"
                                                @click="fetchCountries(28)">Last 28 days
                                        </span>
                                        <span class="dropdown-item "
                                                :class="(pie_days == '90') ? 'active' : ''"
                                                @click="fetchCountries(90)">Last 90 days
                                        </span>
                                        <span class="dropdown-item "
                                                :class="(pie_days == '180') ? 'active' : ''"
                                                @click="fetchCountries(180)">Last 180 days
                                        </span>
                                        <span class="dropdown-item "
                                                :class="(pie_days == 'infinity') ? 'active' : ''"
                                                @click="fetchCountries('infinity')">Last calendar year
                                        </span>
                                    </div>
                                    <!-- End Dropdown -->

                                </header>

                                <div class="card-body text-center">
                                    <span v-if="pieLoading">
                                        <div class="spinner-grow spinner-grow-md ui-mt50 mr-1" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>    
                                    </span>
                                    <apexchart v-if="!pieLoading" 
                                                class="mt-md-3 mt-xl-0"
                                                type=pie 
                                                :options="chartOptions2" 
                                                :series="series2"  
                                                height="190" />
                                </div>
                            </div>
                        </div>



                        <div class="col-md-8 mb-5">
                            <div class="card">
                                <header class="card-header d-flex align-items-center justify-content-between">
                                    <h2 class="h4 card-header-title">Trending Posts</h2>
                                    <span v-if="!dataLoading" class="ti-reload cursor-pointer" 
                                        @click="fetchTrendingPosts()">
                                    </span>
                                    <span v-if="dataLoading">
                                        <div class="spinner-grow spinner-grow-sm" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </span>
                                </header>

                                <div class="card-body">

                                    <div v-if="dataLoading" class="text-center">
                                        <div class="spinner-grow" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>

                                    <div v-if="!dataLoading" class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="width:35%">Title</th>
                                                    <th class="text-center">Author</th>
                                                    <th class="text-center">Category</th>
                                                    <th class="text-center">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            <tr v-if="!dataLoading && !rows.length">
                                                <td colspan="5" class="text-center">
                                                    <span>No data found.</span>
                                                </td>
                                            </tr>

                                            <tr v-if="!dataLoading  && rows.length" 
                                                v-for="(row, index) in rows" 
                                                :key="index"
                                                class="f14">

                                                <!-- Title -->
                                                <td class="font-weight-semi-bold">
                                                    <router-link :to="{ name: 'edit-post', 
                                                            params:{id: row.encrypt_id} }" 
                                                            class="default-color text-decoration-hover">
                                                            {{ row.title }}
                                                    </router-link>
                                                    <span v-if="row.views > 0" class="f12">
                                                        &nbsp;|&nbsp; <span class="ti-eye"></span> {{row.views}}
                                                    </span>
                                                </td>
                                                <!-- End Title -->

                                                <!-- Author -->
                                                <td class="font-weight-semi-bold text-center">
                                                    <span v-if="row.author" 
                                                        class="badge badge-md badge-pill badge-success-soft">
                                                        {{ row.author }}
                                                    </span>
                                                    <span v-if="!row.author"> - </span>
                                                </td>
                                                <!-- End Author -->

                                                <!-- Category -->
                                                <td class="font-weight-semi-bold text-center">
                                                    <router-link :to="{ name: 'filter-post', 
                                                        params:{filter_by: 'category', 'filter':row.category} }" 
                                                        class="text-decoration-hover">
                                                        <span class="badge badge-md badge-pill badge-danger-soft">
                                                            {{ row.category }}
                                                        </span>
                                                    </router-link>
                                                </td>
                                                <!-- End Category -->

                                                <!-- Date -->
                                                <td v-html="(row.deleted_at) ? row.deleted_at : 
                                                    (row.updated_at) ? row.updated_at : row.created_at"
                                                    class="font-weight-semi-bold text-center f12">
                                                </td>
                                                <!-- End Date -->

                                            </tr>
                                            
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Title</th>
                                                    <th class="text-center">Author</th>
                                                    <th class="text-center">Category</th>
                                                    <th class="text-center">Date</th>
                                                </tr>
                                            </tfoot>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-5 ui-mt-180">
                            <div class="card">
                                <header class="card-header d-flex align-items-center justify-content-between">
                                    <h2 class="h4 card-header-title">Top Categories</h2>
                                    <span v-if="!catLoading" class="ti-reload cursor-pointer" 
                                        @click="fetchTopCategories()">
                                    </span>
                                    <span v-if="catLoading">
                                        <div class="spinner-grow spinner-grow-sm" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </span>
                                </header>

                                <div class="card-body text-center">
                                    <span v-if="catLoading">
                                        <div class="spinner-grow" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </span>
                                    <apexchart class="mt-md-3 mt-xl-0"
                                                v-if="!catLoading"
                                                type=bar 
                                                :options="chartOptions3" 
                                                :series="series3"  
                                                height="300" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>




                <Footer></Footer>
            </div>
        </main>
        <!-- End Main -->
        
    </div>
</template>

<script>
    import Header from '../layouts/Header.vue';
    import Navigation from '../layouts/Navigation';
    import Footer from '../layouts/Footer.vue';
    export default {
        name: 'App',
        components: {
            Header,
            Navigation,
            Footer
        },
        data(){
            return {
                // Chart
                line_type: 'monthly',
                chartOptions1: {
                    chart: { height: 450, zoom: { enabled: false }},
                    animations: { enabled: true },
                    dataLabels: {  enabled: false },
                    stroke: {  curve: 'straight' },
                    title: { text: '', align: 'center' },
                    grid: {  row: { colors: ['#f3f3f3', 'transparent'],  opacity: 0.5 }},
                    markers: { size: 5, align:top,  hover: {  sizeOffset: 5  }},
                },

                // Pie
                pieLoading: true,
                pie_days: 7,
                series2: [],
                chartOptions2: {
                    chart: {
                      width: 380,
                      type: 'pie',
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                              width: 200
                            },
                        legend: {
                            position: 'bottom'
                        }
                      }
                    }]
                },

              // Categories
              chartOptions3: {
                    chart: { height: 280, zoom: { enabled: false }},
                    animations: { enabled: true },
                    dataLabels: {  enabled: false },
                    stroke: {  curve: 'straight' },
                    title: { text: '', align: 'center' },
                    grid: {  row: { colors: ['#f3f3f3', 'transparent'],  opacity: 0.5 }},
                    markers: { size: 5, align:top,  hover: {  sizeOffset: 5  }},
                },
                series3: [],

                auth: {
                    role: '',
                    accesstoken: '',
                },
                mostVisited: [],


                // Total Posts
                post_days: 7,
                total_posts: 0,
                percentage_posts: '',
                arrow_posts: '',
                postLoading: true,

                // Total Views
                view_days: 7,
                total_views: 0,
                percentage_views: '',
                arrow_views: '',
                viewLoading: true,

                // Total Users
                user_days: 7,
                total_users: 0,
                percentage_users: '',
                arrow_users: '',
                userLoading: true,

                // Total visitors
                visitor_days: 7,
                total_visitors: 0,
                percentage_visitors: '',
                arrow_visitors: '',
                visitorLoading: true,

                 // line Chart
                series1: [],
                chartLoading: true,

                // Posts Trend
                rows: [],
                dataLoading: true,

                // Categories
                catLoading: true,

            }
        },
        mounted() {},
        created() {
            // AccessToken & Roles
            if(localStorage.getItem('accessToken')) {
                this.auth.accessToken = localStorage.getItem('accessToken');
            }
            if(localStorage.getItem('role')) {
                this.auth.role = localStorage.getItem('role');
            }

            this.fetchTotalPosts(this.post_days, true);
        },
        methods: {

            // fetch Total Posts
            fetchTotalPosts(days, next=false){
                this.postLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let params = {};
                params['api_key'] = window.api_key;
                params['days'] = days;
                    this.post_days = days;
                axios.get('/api/v1/dashboard/explore/totalPosts', {params:params}, config)
                    .then(res => {
                        this.postLoading = false;
                        if(res.data.statusCode == 200) {
                            this.total_posts = res.data.data.total;
                            this.percentage_posts = res.data.data.percentage;
                            this.arrow_posts = res.data.data.arrow_posts;

                            // Call Next Func
                            if(next) { this.fetchTotalViews(this.view_days, true); }
                        }
                    })
                    .catch(err => {
                        //
                    });
            },


            // fetch Total Views
            fetchTotalViews(days, next=false){
                this.viewLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let params = {};
                params['api_key'] = window.api_key;
                params['days'] = days;
                    this.view_days = days;
                axios.get('/api/v1/dashboard/explore/totalViews', {params:params}, config)
                    .then(res => {
                        this.viewLoading = false;
                        if(res.data.statusCode == 200) {
                            this.total_views = res.data.data.total;
                            this.percentage_views = res.data.data.percentage;
                            this.arrow_views = res.data.data.arrow_posts;

                            // Call Next Func
                            if(next) { this.fetchTotalUsers(this.user_days, true); }
                        }
                    })
                    .catch(err => {
                        // 
                    });
            },


            // fetch Total Users
            fetchTotalUsers(days, next=false){
                this.userLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let params = {};
                params['api_key'] = window.api_key;
                params['days'] = days;
                    this.user_days = days;
                axios.get('/api/v1/dashboard/explore/totalUsers', {params:params}, config)
                    .then(res => {
                        this.userLoading = false;
                        if(res.data.statusCode == 200) {
                            this.total_users = res.data.data.total;
                            this.percentage_users = res.data.data.percentage;
                            this.arrow_users = res.data.data.arrow_posts;

                            // Call Next Func
                            if(next) { this.fetchTotalVisitors(this.visitor_days, true); }
                        }
                    })
                    .catch(err => {
                        // 
                    });
            },


            // fetch Total Visitors
            fetchTotalVisitors(days, next=false){
                this.visitorLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let params = {};
                params['api_key'] = window.api_key;
                params['days'] = days;
                this.visitor_days = days;
                axios.get('/api/v1/dashboard/explore/totalVisitors', {params:params}, config)
                    .then(res => {
                        this.visitorLoading = false;
                        if(res.data.statusCode == 200) {
                            this.total_visitors = res.data.data.total;
                            this.percentage_visitors = res.data.data.percentage;
                            this.arrow_visitors = res.data.data.arrow_posts;

                            // Call Next Func
                            if(next) { this.fetchLineChart(this.line_type, true); }
                        }
                    })
                    .catch(err => {
                        // 
                    });
            },


            // fetch Chart
            fetchLineChart(type, next=false){
                this.chartLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let vm = this;
                let params = {};
                params['api_key'] = window.api_key;
                params['type'] = type;
                this.line_type = type;
                axios.get('/api/v1/dashboard/explore/lineChart', {params:params}, config)
                    .then(res => {
                        this.chartLoading = false;
                        if(res.data.statusCode == 200) {
                            this.series1 = [{name:'Views', data: res.data.data.rows.series}];
                            this.chartOptions1.xaxis = {categories: res.data.data.rows.xaxis};
                        }

                        // Call Next Func
                        if(next) { this.fetchCountries(this.pie_days, true); }
                           
                    })
                    .catch(err => {
                        //
                    });
            },

            // fetch Countries
            fetchCountries(days, next=false){
                this.pieLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let vm = this;
                let params = {};
                params['api_key'] = window.api_key;
                params['days'] = days;
                this.pie_days = days;
                axios.get('/api/v1/dashboard/explore/pieChart', {params:params}, config)
                    .then(res => {
                        this.pieLoading = false;
                        if(res.data.statusCode == 200) {
                            this.series2 = res.data.data.rows.total;
                            this.chartOptions2.labels = res.data.data.rows.countries;
                        }

                        // Call Next Func
                        if(next) { this.fetchTrendingPosts(true); }
                           
                    })
                    .catch(err => {
                        //
                    });
            },

            // Fetch Top Five Posts
            fetchTrendingPosts(next=false){
                this.dataLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let vm = this;
                let params = {};
                params['api_key'] = window.api_key;
                axios.get('/api/v1/dashboard/explore/trendingPosts', {params:params}, config)
                    .then(res => {
                        this.dataLoading = false;
                        if(res.data.statusCode == 200) {
                            this.rows = res.data.data.rows;
                        }

                        // Call Next Func
                        if(next) { this.fetchTopCategories(); }
                    })
                    .catch(err => {
                        //
                    });
            },


            // Top Categories
            fetchTopCategories(){
                this.catLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let params = {};
                params['api_key'] = window.api_key;
                axios.get('/api/v1/dashboard/explore/topCategories', {params:params}, config)
                    .then(res => {
                        this.catLoading = false;
                        if(res.data.statusCode == 200) {
                            this.chartOptions3 = { xaxis: { categories: res.data.data.xaxis}};
                            this.series3 = [{ name:'Posts', data: res.data.data.series }];
                        }
                    })
                    .catch(err => {
                        //
                    });
            },



        },
    }
</script>
