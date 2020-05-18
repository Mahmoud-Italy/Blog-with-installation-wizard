<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">Messages
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
                            <li class="breadcrumb-item"><router-link :to="{ name: 'messages' }">Messages</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Show</li>
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

                <div v-if="!pgLoading">

                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <div class="card">
                                <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="inputText1">Name</label>
                                                <input class="form-control"
                                                    id="inputText1"  
                                                    type="text" 
                                                    v-model="row.name" 
                                                    disabled="">
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label for="inputText2">Email</label>
                                                <input class="form-control text-lowercase"
                                                    id="inputText2"  
                                                    type="text" 
                                                    v-model="row.email" 
                                                    disabled="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputText6">Message</label>
                                            <textarea class="form-control" rows="20" v-model="row.message" disabled=""></textarea>
                                        </div>
                                    
                                </div>
                                <!-- End Card Body -->

                                
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
    import Editor from '@tinymce/tinymce-vue';
    import izitoast from 'izitoast';

    export default {
        name: 'CreatePage',
        components: {
            Header,
            Navigation,
            Footer,
            Editor
        },
        data(){
            return {
                //
                auth: {
                    role: '',
                    accesstoken: '',
                },
                row: {
                    id: '',
                    name: '',
                    email: '',
                    message: '',
                },

                pgLoading: true,
                something_went_wrong: false,
            }
        },
        mounted() {},
        computed: {},
        created() {
            // AccessToken & Role
            if(localStorage.getItem('role')) {
                this.auth.role = localStorage.getItem('role');
            }
            if(localStorage.getItem('accessToken')) {
                this.auth.accessToken = localStorage.getItem('accessToken');
            }
            //
            if(this.$route.params.id) {
                this.row.id = this.$route.params.id;
            }

            //
            this.fetchRow();
        },
        methods: {
            
            // Fetch Row
            fetchRow(){
                this.pgLoading = true;
                this.something_went_wrong = false;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.messages.show'
                };
               const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
               let params = {};
               params['api_key'] = window.api_key;
               axios.get('/api/v1/dashboard/messages/'+this.row.id, {params:params}, config)
                    .then(res => {
                        if(res.data.statusCode == 200) {
                            this.pgLoading = false;
                            this.row.name = res.data.data.rows.name;
                            this.row.email = res.data.data.rows.email;
                            this.row.message = res.data.data.rows.message;
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
