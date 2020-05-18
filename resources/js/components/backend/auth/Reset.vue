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
                            <h2 class="text-center mb-4">Reset your password</h2>

                            <!-- Frogot Form -->
                            <form @submit.prevent="reset">
                                <!-- Email -->
                                <div class="form-group">
                                    <label for="text">Key</label>
                                    <input id="text" class="form-control" type="text" placeholder="Enter the key">
                                </div>
                                <!-- End Email -->

                                <!-- Password -->
                                <div class="form-group">
                                    <label for="password">New Password</label>
                                    <input id="password" class="form-control" type="password" v-model="new_password" placeholder="Enter new password" autocomplete="off">
                                </div>
                                <!-- End Password -->

                                <!-- Password -->
                                <div class="form-group">
                                    <label for="conPassword">Confirm New Password</label>
                                    <input id="conPassword" class="form-control" type="password" v-model="confirm_password" placeholder="Confirm new password" autocomplete="off">
                                </div>
                                <!-- End Password -->

                                <div class="my-4">
                                    <button type="submit" class="btn btn-block btn-wide btn-primary text-uppercase" 
                                    :disabled="btnLoading">
                                        <span v-if="btnLoading">
                                            <span class="spinner-grow spinner-grow-sm mr-1"
                                                role="status" aria-hidden="true"></span>Loading...
                                        </span>
                                        <span v-if="!btnLoading">Update Password</span>
                                    </button>
                                </div>

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
                key: '',
                new_password: '',
                confirm_password: '',

                btnLoading: false,
            }
        },
        mounted() {},
        methods: {
            //
            reset(){
                //
            },

        },
    }
</script>
