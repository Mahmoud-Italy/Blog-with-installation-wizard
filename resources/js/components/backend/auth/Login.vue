<template>
    <div class="">
        
        <!-- Main -->
        <main class="d-flex flex-column u-hero u-hero--end mnh-100vh" 
            style="background-image: url(/modules/backend/img-temp/bg/bg-1.png)">

            <div class="container py-7 my-auto">
                <div class="d-flex align-items-center justify-content-center">
                    
                    <!-- Card -->
                    <div class="card my-7" style="width: 460px; max-width: 100%;">
                        <div class="card-body p-4 p-lg-7">
                            <h2 class="text-center mb-4">Log in</h2>

                            <!-- Log in Form -->
                            <form @submit.prevent="login">
                                <!-- Email Address -->
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" class="form-control" type="email" v-model="email" placeholder="Your email">
                                </div>
                                <!-- End Email Address -->
                                
                                <!-- Password -->
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" class="form-control" type="password" v-model="password" placeholder="Your password" autocomplete="off">
                                </div>
                                <!-- End Password -->

                                <!-- Remember Me -->
                                <div class="d-flex align-items-center justify-content-between my-4">
                                    <div class="custom-control custom-checkbox">
                                        <input id="rememberMe" class="custom-control-input" type="checkbox">
                                        <label class="custom-control-label text-dark" for="rememberMe">Remember me</label>
                                    </div>
                                    <!-- Forgot Password -->
                                    <!-- <router-link :to="{ name: 'forgot' }" class="font-weight-semi-bold">Forgot password?</router-link> -->
                                </div>
                                <!-- End Remember Me -->

                                <!-- Form button -->
                                <button type="submit" class="btn btn-block btn-wide btn-primary text-uppercase" 
                                    :disabled="btnLoading">
                                    <span v-if="btnLoading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true"></span>Loading...
                                    </span>
                                    <span v-if="!btnLoading">Login</span>
                                </button>
                                <!-- End Form button -->

                            </form>
                            <!-- End Log in Form -->
                        </div>
                    </div>
                    <!-- End Card -->

                </div>
            </div>

            <Footer></Footer>
        </main>
        <!-- End Main -->

    </div>
</template>

<script>
    import Footer from '../layouts/Footer.vue';
    import izitoast from 'izitoast';

    export default {
        name: 'Login',
        components: {
            Footer
        },
        data(){
            return {
                email: '',
                password: '',
                btnLoading: false,
            }
        },
        mounted() {},
        methods: {
            //
            login(){
                this.btnLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('email', this.email);
                formData.append('password', this.password);
                axios.post('/api/v1/dashboard/login', formData, config)
                .then(res => {
                    this.btnLoading = false;
                    this.password = '';
                    if(res.data.statusCode == 200) {
                        localStorage.setItem('accessToken', res.data.accessToken);
                        localStorage.setItem('username', res.data.username);
                        localStorage.setItem('user_id', res.data.user_id);
                        localStorage.setItem('avatar', res.data.avatar);
                        localStorage.setItem('role', res.data.role);

                        // Set default Navigation
                        localStorage.setItem('nav_roles', 'show');
                        localStorage.setItem('nav_sliders', 'show');
                        localStorage.setItem('nav_themes', 'show');
                        localStorage.setItem('nav_reports', 'hide');
                        localStorage.setItem('nav_activity_logs', 'show');
                        localStorage.setItem('nav_cache_management', 'show');

                        this.$router.push({ name: 'dashboard' })
                    } else {

                        izitoast.warning({
                            icon: 'ti-alert',
                            title: 'Wow, man',
                            message: 'Slow down, '+res.data.err,
                        });
                    }
                })
                .catch(err => {
                    this.btnLoading = false;
                    this.password = '';

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
