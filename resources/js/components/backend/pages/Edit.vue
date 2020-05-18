<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">Pages
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
                            <li class="breadcrumb-item"><router-link :to="{ name: 'pages' }">Pages</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Page</li>
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

                <form v-if="!pgLoading" @submit.prevent="updateRow" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-md-8 mb-5">
                            <div class="card">

                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="form-group">
                                            <label for="inputText1">Title</label>
                                            <input class="form-control" 
                                                    id="inputText1" 
                                                    type="text" 
                                                    v-model="row.title" 
                                                    required="">
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 form-group">
                                                <label for="inputText2">Meta title</label>
                                                <input class="form-control"
                                                    id="inputText2"  
                                                    type="text" 
                                                    v-model="row.meta_title" 
                                                    required="">
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label for="inputText3">Slug</label>
                                                <input class="form-control text-lowercase"
                                                    id="inputText3"  
                                                    type="text" 
                                                    v-model="row.slug" 
                                                    @keydown.space.prevent 
                                                    @paste="onPaste"
                                                    v-on:change="onSlugChange"
                                                    required="">
                                            </div>
                                        
                                            <div class="col-md-6 form-group">
                                                <label for="inputText4">Meta keywords</label>
                                                <textarea class="form-control"
                                                        id="inputText4" 
                                                        rows="5"  
                                                        v-model="row.meta_keywords" 
                                                        required=""></textarea>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="inputText5">Meta description</label>
                                                <textarea class="form-control" 
                                                        id="inputText5" 
                                                        rows="5" 
                                                        v-model="row.meta_description" 
                                                        required=""></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputText6">Body</label>
                                            <editor
                                                id="inputText6"
                                                v-model="row.body"
                                                api-key="xahz1dg338xnac8il0tkxph26xcaxqaewi3bd9cw9t4e6j7b"
                                                :init="{
                                                    height: 700,
                                                    menubar: 'file edit view insert format tools table tc help',
                                                    plugins: [
                                                        'advlist autolink lists link image charmap print preview anchor',
                                                        'searchreplace visualblocks code fullscreen',
                                                        'insertdatetime media table paste code help wordcount'
                                                    ],
                                                    toolbar:
                                                        'undo redo | formatselect | bold italic backcolor | \
                                                        alignleft aligncenter alignright alignjustify | \
                                                        bullist numlist outdent indent | removeformat | help'
                                                }"
                                             />
                                        </div>
                                                                       
                                    </div>
                                </div>
                                <!-- End Card Body -->

                                
                            </div>
                        </div>

                        <div class="col-md-4 mb-5">
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="InputImage">Meta Image</label>
                                        <img v-if="row.preview" 
                                                :src="row.preview" 
                                                style="width: 100%;
                                                max-height: 300px;
                                                padding:10px">
                                        <input class="form-control" 
                                                id="InputImage" 
                                                type="file" 
                                                ref="myDropify" 
                                                v-on:change="onImageChange"/>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 row">
                        <div class="form-group">

                            <button class="btn btn-primary" :disabled="btnLoading">
                                <span v-if="btnLoading">
                                    <span class="spinner-grow spinner-grow-sm mr-1" 
                                            role="status" 
                                            aria-hidden="true">
                                    </span>Loading...
                                </span>
                                <span v-if="!btnLoading" class="ti-save"></span>
                                <span v-if="!btnLoading"> Update Page</span>
                            </button>

                            <button type="button" class="btn btn-warning" 
                                    :disabled="btnLoading" 
                                    @click="cancel">
                                <span class="ti-back-left"></span>
                                <span>Cancel</span>
                            </button>

                        </div>
                    </div>
                </form>

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
        name: 'Edit',
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
                    pd_id: '',
                    preview: '',
                    image: '',

                    slug: '',
                    title: '',
                    body: '',
                    meta_title: '',
                    meta_keywords: '',
                    meta_description: '',
                },

                pgLoading: true,
                btnLoading: false,
                isSubmit: false,
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
                this.row.pg_id = this.$route.params.id;
            }

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
                    'permission': 'admin.pages.edit'
                };
               const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
               let params = {};
               params['api_key'] = window.api_key;
               axios.get('/api/v1/dashboard/pages/'+this.row.pg_id, {params:params}, config)
                    .then(res => {
                        if(res.data.statusCode == 200) {
                            this.pgLoading = false;
                            this.row.preview = res.data.data.rows.image;
                            
                            this.row.slug = res.data.data.rows.slug;
                            this.row.title = res.data.data.rows.title;
                            this.row.body = res.data.data.rows.body;
                            this.row.meta_title = res.data.data.rows.meta_title;
                            this.row.meta_keywords = res.data.data.rows.meta_keywords;
                            this.row.meta_description = res.data.data.rows.meta_description;
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

            // Add New
            updateRow(){
                this.btnLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.pages.edit'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('locale', 'en');
                formData.append('image', this.row.image);
                formData.append('title', this.row.title);
                formData.append('body', this.row.body);
                formData.append('slug', this.row.slug);
                formData.append('meta_title', this.row.meta_title);
                formData.append('meta_keywords', this.row.meta_keywords);
                formData.append('meta_description', this.row.meta_description);
                   // fell free to add any new value

                formData.append('_method', 'PUT');
                axios.post('/api/v1/dashboard/pages/'+this.row.pg_id, formData, config)
                .then(res => {
                    this.btnLoading = false;
                    this.isSubmit = true;

                    if(res.data.statusCode == 201) {

                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Page Added Successfully',
                        });
                        this.$router.push({ name: 'pages' });

                    } else if(res.data.statusCode == 200) {

                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Page Updated Successfully',
                        });
                        this.$router.push({ name: 'pages' });
                        
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
                    izitoast.error({
                        icon: 'ti-na',
                        title: 'Wow-wow,',
                        message: 'Something went wrong',
                    });

                });
            },

            // Upload Featured image
            onImageChange(e){
              const file = e.target.files[0];
              this.row.preview = URL.createObjectURL(file);
              this.row.image = file;
            },

            // On Paste
            onPaste(evt){
                this.onSlugChange();
            },

            onSlugChange(){
                let str = this.row.slug;
                this.row.slug = str.replace(/\s+/g, '-');
            },

            // Cancel
            cancel(){
                if(confirm('Are You Sure?')) {
                    this.$router.push({ name: 'pages' });
                    this.isSubmit = true;
                }
            },

        },
        //
        beforeRouteLeave (to, from, next) { 
            if(this.row.title_en && !this.isSubmit) {
                const answer = window.confirm('Do you really want to leave? you have unsaved changes!')
                if (answer) {
                    next()
                } else {
                    next(false)
                }
            } else { next() }
        },

    }
</script>
