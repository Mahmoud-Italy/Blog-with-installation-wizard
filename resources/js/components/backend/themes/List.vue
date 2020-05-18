<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">Themes

                        <!-- Role -->
                        <div class="pull-rights ui-mt-15 pull-right ">
                            <div class="dropdown">
                                <span class="badge badge-md badge-pill badge-secondary-soft">
                                    {{ auth.role }}
                                </span>
                            </div>
                        </div>
                        <!-- End Role -->

                    </h1>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Home</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Themes</li>
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
                    <div class="col-md-3 pt-5" v-for="(row, index) in rows" :key="index">
                        <div class="card text-center">
                             <img :src="row.image" style="width: 100%;height: 220px;border-bottom: 1px solid #eee">
                            <div class="card-header">
                                <h4>{{ row.name }}</h4>
                            </div>
                            <div class="card-body pt-0">
                                <button type="button" 
                                        @click="changeTheme(row.id); row.loading = true"
                                        :disabled="btnLoading"
                                    class="btn btn-pill btn-with-icon text-uppercase"
                                    :class="(row.active) ? ' btn-secondary ' : ' btn-danger ' ">
                                    <span class="btn-icon ti-palette mr-2"></span>
                                    <span v-if="!row.loading" 
                                          v-html="(row.active) ? 'Activated' : 'Active'">
                                    </span>
                                    <span v-if="row.loading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true"></span>Activating...
                                    </span>
                                </button>
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
        name: 'Themes',
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
                btnLoading: false,
                pgLoading: true,
                something_went_wrong: false,
                rows: [],
            }
        },
        mounted() {},
        created(){
            //
            if(localStorage.getItem('accessToken')) {
                this.auth.accessToken = localStorage.getItem('accessToken');
            }
            if(localStorage.getItem('role')) {
                this.auth.role = localStorage.getItem('role');
            }

            //
            this.fetchData(true);
        },
        methods: {

            // Fetch Data
            fetchData(loading=false) {
                if(loading) { this.pgLoading = true; }
                this.something_went_wrong = false;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.themes.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = 'active';
                params['search'] = this.search;
                axios.get('/api/v1/dashboard/themes', {params:params}, config)
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


            // Change Theme
            changeTheme(id){
                this.btnLoading = true;
                this.something_went_wrong = false;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.themes.edit'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('theme_id', id);
                axios.post('/api/v1/dashboard/themes', formData, config)
                    .then(res => {
                        this.btnLoading = false;
                        if (res.data.statusCode == 401) {
                            this.$router.push({name: 'access-denied'});

                        } else if(res.data.statusCode == 200) {
                           this.fetchData(false);
                            
                        } else {
                            izitoast.warning({
                                icon: 'ti-alert',
                                title: 'Wow, man',
                                message: 'Slow down, '+res.data.errors,
                            });
                        }
                    })
                    .catch(err => {
                        this.btnLoading = false;
                        this.something_went_wrong = true;
                    });
            },


        },
    }
</script>
