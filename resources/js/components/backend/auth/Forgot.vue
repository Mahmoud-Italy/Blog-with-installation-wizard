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
                            <h2 class="text-center mb-4">Recover password</h2>

                            <!-- Frogot Form -->
                            <form @submit.prevent="forgot">
                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Your email</label>
                                    <input id="email" class="form-control" type="email" placeholder="Enter your email">
                                </div>
                                <!-- End Email -->

                                <div class="my-4">
                                    <button type="submit" class="btn btn-block btn-wide btn-primary text-uppercase" 
                                    :disabled="btnLoading">
                                        <span v-if="btnLoading">
                                            <span class="spinner-grow spinner-grow-sm mr-1"
                                                role="status" aria-hidden="true"></span>Loading...
                                        </span>
                                        <span v-if="!btnLoading">Send confirmation</span>
                                    </button>
                                </div>

                                <p class="text-center mb-0">
                                    Remembered your password?
                                    <router-link :to="{ name: 'login' }" class="font-weight-semi-bold">Log in</router-link>
                                </p>
                            </form>
                            <!-- End Forgot Form -->
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
        name: 'Forgot',
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
            forgot(){
                //
            },

        },
    }
</script>
