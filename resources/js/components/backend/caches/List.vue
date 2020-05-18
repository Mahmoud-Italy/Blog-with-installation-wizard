<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">

                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">Cache Management

                        <!-- Role -->
                        <div class="col-md-12">
                            <div class="pull-rights ui-mt-15 pull-right ">
                                <div class="dropdown">
                                    <span class="badge badge-md badge-pill badge-secondary-soft">
                                        {{ auth.role }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Role -->

                    </h1>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Home</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Cache Management</li>
                        </ol>
                    </nav>
                    <!-- End Breadcrumb -->

                    
                <!-- Button Types -->
                <div class="row">


                    <!-- Clear Application Cache -->
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <h4>Clear Application Cache</h4>
                            </div>
                            <div class="card-body pt-0">
                                <button type="button" :disabled="btn1Loading"
                                    @click="func1"
                                    class="btn btn-secondary btn-pill btn-with-icon">
                                    <span class="btn-icon ti-wand mr-2"></span>
                                    <span v-if="!btn1Loading">cache:clear</span>
                                    <span v-if="btn1Loading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                            role="status" aria-hidden="true">
                                        </span>clearing...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End Clear Application Cache -->

                    
                    <!-- Clear Configuration Cache -->
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <h4>Clear Configuration Cache</h4>
                            </div>
                            <div class="card-body pt-0">
                                <button type="button" :disabled="btn2Loading"
                                    @click="func2"
                                    class="btn btn-danger btn-pill btn-with-icon">
                                    <span class="btn-icon ti-wand mr-2"></span>
                                    <span v-if="!btn2Loading">config:clear</span>
                                    <span v-if="btn2Loading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                            role="status" aria-hidden="true">
                                        </span>clearing...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End Clear Configuration Cache -->


                    <!-- Clear Views Cache -->
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-header">
                                <h4>Clear Views Cache</h4>
                            </div>
                            <div class="card-body pt-0">
                                <button type="button" :disabled="btn3Loading"
                                    @click="func3"
                                    class="btn btn-warning btn-pill btn-with-icon">
                                    <span class="btn-icon ti-wand mr-2"></span>
                                    <span v-if="!btn3Loading">view:clear</span>
                                    <span v-if="btn3Loading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                            role="status" aria-hidden="true">
                                        </span>clearing...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End Clear Views Cache -->


                    <!-- Route Cache -->
                    <div class="col-md-4 pt-5">
                        <div class="card text-center">
                            <div class="card-header">
                                <h4>Route Cache</h4>
                            </div>
                            <div class="card-body pt-0">
                                <button type="button" :disabled="btn4Loading"
                                    @click="func4"
                                    class="btn btn-primary btn-pill btn-with-icon">
                                    <span class="btn-icon ti-wand mr-2"></span>
                                    <span v-if="!btn4Loading">route:clear</span>
                                    <span v-if="btn4Loading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                            role="status" aria-hidden="true">
                                        </span>clearing...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End Route Cache -->

                    <!-- Clear Route Cache -->
                    <div class="col-md-4 pt-5">
                        <div class="card text-center">
                            <div class="card-header">
                                <h4>Clear Route Cache</h4>
                            </div>
                            <div class="card-body pt-0">
                                <button type="button" :disabled="btn5Loading"
                                    @click="func5"
                                    class="btn btn-info btn-pill btn-with-icon">
                                    <span class="btn-icon ti-wand mr-2"></span>
                                    <span v-if="!btn5Loading">route:clear</span>
                                    <span v-if="btn5Loading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                            role="status" aria-hidden="true">
                                        </span>clearing...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- End Clear Route Cache -->


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
        name: 'CacheManagement',
        components: {
            Header,
            Navigation,
            Footer
        },
        data(){
            return {
                auth: {
                    role: '',
                    accessToken: '',
                },
                btn1Loading: false,
                btn2Loading: false,
                btn3Loading: false,
                btn4Loading: false,
                btn5Loading: false,
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

        },
        methods: {

            // func 1
            func1(){
                this.btn1Loading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                axios.post('/api/v1/dashboard/artisan/cache-clear', formData, config)
                    .then(res => {
                        this.btn1Loading = false;
                        if(res.data.statusCode == 200) {
                            izitoast.success({
                                icon: 'ti-check',
                                title: 'Great job,',
                                message: 'Cache Cleared Successfully',
                            });
                        } else {
                            izitoast.warning({
                                icon: 'ti-alert',
                                title: 'Wow, man',
                                message: 'Slow down, '+res.data.errors,
                            });
                        }
                    })
                    .catch(err => {
                        this.btn1Loading = false;
                        izitoast.error({
                            icon: 'ti-na',
                            title: 'Wow-wow,',
                            message: 'Something went wrong',
                        });
                    });
            },




            // func 2
            func2(){
                this.btn2Loading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', this.api_key);
                axios.post('/api/v1/dashboard/artisan/config-clear', formData, config)
                    .then(res => {
                        this.btn2Loading = false;
                        if(res.data.statusCode == 200) {
                            izitoast.success({
                                icon: 'ti-check',
                                title: 'Great job,',
                                message: 'Config Cleared Successfully',
                            });
                        } else {
                            izitoast.warning({
                                icon: 'ti-alert',
                                title: 'Wow, man',
                                message: 'Slow down, '+res.data.errors,
                            });
                        }
                    })
                    .catch(err => {
                        this.btn2Loading = false;
                        izitoast.error({
                            icon: 'ti-na',
                            title: 'Wow-wow,',
                            message: 'Something went wrong',
                        });
                    });
            },



            // func 3
            func3(){
                this.btn3Loading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', this.api_key);
                axios.post('/api/v1/dashboard/artisan/view-clear', formData, config)
                    .then(res => {
                        this.btn3Loading = false;
                        if(res.data.statusCode == 200) {
                            izitoast.success({
                                icon: 'ti-check',
                                title: 'Great job,',
                                message: 'View Cleared Successfully',
                            });
                        } else {
                            izitoast.warning({
                                icon: 'ti-alert',
                                title: 'Wow, man',
                                message: 'Slow down, '+res.data.errors,
                            });
                        }
                    })
                    .catch(err => {
                        this.btn3Loading = false;
                        izitoast.error({
                            icon: 'ti-na',
                            title: 'Wow-wow,',
                            message: 'Something went wrong',
                        });
                    });
            },



            // func 4
            func4(){
                this.btn4Loading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', this.api_key);
                axios.post('/api/v1/dashboard/artisan/route-cache', formData, config)
                    .then(res => {
                        this.btn4Loading = false;
                        if(res.data.statusCode == 200) {
                            izitoast.success({
                                icon: 'ti-check',
                                title: 'Great job,',
                                message: 'Route Cached Successfully',
                            });
                        } else {
                            izitoast.warning({
                                icon: 'ti-alert',
                                title: 'Wow, man',
                                message: 'Slow down, '+res.data.errors,
                            });
                        }
                    })
                    .catch(err => {
                        this.btn4Loading = false;
                        izitoast.error({
                            icon: 'ti-na',
                            title: 'Wow-wow,',
                            message: 'Something went wrong',
                        });
                    });
            },




            // func 5
            func5(){
                this.btn5Loading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', this.api_key);
                axios.post('/api/v1/dashboard/artisan/route-clear', formData, config)
                    .then(res => {
                        this.btn5Loading = false;
                        if(res.data.statusCode == 200) {
                            izitoast.success({
                                icon: 'ti-check',
                                title: 'Great job,',
                                message: 'Route Cleared Successfully',
                            });
                        } else {
                            izitoast.warning({
                                icon: 'ti-alert',
                                title: 'Wow, man',
                                message: 'Slow down, '+res.data.errors,
                            });
                        }
                    })
                    .catch(err => {
                        this.btn5Loading = false;
                        izitoast.error({
                            icon: 'ti-na',
                            title: 'Wow-wow,',
                            message: 'Something went wrong',
                        });
                    });
            },

        },
    }
</script>
