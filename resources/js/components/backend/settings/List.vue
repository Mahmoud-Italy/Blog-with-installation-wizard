<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">

                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">App Settings
                        <div class="pull-rights ui-mt-15 pull-right ">
                            <div class="dropdown">
                                <span class="badge badge-md badge-pill badge-secondary-soft">
                                    {{ auth.role }}
                                </span>
                            </div>
                        </div>
                    </h1>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Home</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">App Settings</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                <div v-if="pgLoading" class="row h-100">
                    <div class="container text-center">
                        <p><br/></p>
                        <div class="spinner-grow" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p><br/></p>
                    </div>
                </div>

                <!-- Button Types -->
                <div v-if="!pgLoading" class="row">

                    <!-- Start App -->
                    <div class="col-md-3 " 
                            v-for="(row, index) in rows"
                            :key="index"
                            :class="(index > 3) ? 'pt-5' : ''">
                        <div class="card text-center">
                            <div class="card-header">
                                <h4>{{ row.app_name }}</h4>
                            </div>
                            <div class="card-body pt-0">
                                <router-link :to="{ name: 'app-settings', params:{appid: row.encrypt_id} }"
                                    class="btn btn-pill btn-with-icon text-uppercase"
                                    :class="(row.setup) ? ' btn-secondary ' : ' btn-danger ' ">
                                    <span :class="'btn-icon mr-2 '+row.icon"></span>
                                    <span v-html="(row.setup) ? 'Update' : 'Set Up'">Update</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <!-- End App -->
                    
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
    import izitoast from 'izitoast';

    export default {
        name: 'AppSetting',
        components: {
            Header,
            Navigation,
            Footer
        },
        data(){
            return {
                //
                auth: {
                    role: '',
                    accesstoken: '',
                },
                pgLoading: true,
                something_went_wrong: false,
                rows: [],
            }
        },
        mounted() {},
        created(){
            // AccessToken & Roles
            if(localStorage.getItem('accessToken')) {
                this.auth.accessToken = localStorage.getItem('accessToken');
            }
            if(localStorage.getItem('role')) {
                this.auth.role = localStorage.getItem('role');
            }
            
            //
            this.fetchData();
        },
        methods: {

            // Fetch Data
            fetchData() {
                this.pgLoading = true;
                this.something_went_wrong = false;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.app_settings.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = 'active';
                params['search'] = this.search;
                axios.get('/api/v1/dashboard/appSettings', {params:params}, config)
                    .then(res => {
                        this.pgLoading = false;
                        if (res.data.statusCode == 401) {
                            this.$router.push({name: 'access-denied'});

                        } else if(res.data.statusCode == 200) {
                            this.rows = res.data.data.rows;
                            
                        } else {
                            izitoast.warning({
                                icon: 'ti-alert',
                                title: 'Wow, man',
                                message: 'Slow down, '+res.data.errors,
                            });
                        }
                    })
                    .catch(err => {
                        this.pgLoading = false;
                        this.something_went_wrong = true;
                    });
            },


        },
    }
</script>
