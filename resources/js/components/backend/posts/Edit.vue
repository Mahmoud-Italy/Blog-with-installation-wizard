<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">Posts

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
                            <li class="breadcrumb-item"><router-link :to="{ name: 'posts' }">Posts</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Post</li>
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

                <form v-if="!pgLoading" @submit.prevent="updateRow" enctype="multipart/form-data" class="h-100">
                    <div class="row">
                        <div class="col-md-8 mb-5">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <!-- Title -->
                                    <div class="form-group">
                                        <div class="pull-right f12"
                                            v-if="row.title"
                                            v-text="(row.title.length)">
                                        </div>
                                        <label for="inputText1">Title</label>
                                        <input class="form-control" 
                                                id="inputText1" 
                                                type="text" 
                                                v-model="row.title" 
                                                required="">
                                    </div>
                                    <!-- End Title -->


                                    <div class="row">
                                        <!-- Slug -->
                                        <div class="col-md-6 form-group">
                                            <div class="pull-right f12" 
                                                v-if="row.slug"
                                                v-text="(row.slug.length)">
                                            </div>
                                            <label for="inputText2">Slug</label>
                                            <input class="form-control text-lowercase"
                                                    id="inputText2"  
                                                    type="text" 
                                                    v-model="row.slug" 
                                                    @keydown.space.prevent 
                                                    @paste="onPaste"
                                                    v-on:change="onSlugChange"
                                                    required="">
                                        </div>
                                        <!-- End Slug -->

                                        <!-- Meta title -->
                                        <div class="col-md-6 form-group">
                                            <div class="pull-right f12" 
                                                v-if="row.meta_title"
                                                v-text="(row.meta_title.length)">
                                            </div>
                                            <label for="inputText3">Meta title</label>
                                            <input class="form-control"
                                                    id="inputText3"  
                                                    type="text" 
                                                    v-model="row.meta_title" 
                                                    required="">
                                        </div>
                                        <!-- End Meta title -->
                                            
                                        <!-- Meta keywords -->
                                        <div class="col-md-6 form-group">
                                            <div class="pull-right f12"
                                                v-if="row.meta_keywords" 
                                                v-text="(row.meta_keywords.length)">
                                            </div>
                                            <label for="inputText4">Meta keywords</label>
                                            <textarea class="form-control"
                                                    id="inputText4" 
                                                    rows="5"  
                                                    v-model="row.meta_keywords">
                                            </textarea>
                                        </div>
                                        <!-- End Meta keywords -->

                                        <!-- Meta description -->
                                        <div class="col-md-6 form-group">
                                            <div class="pull-right f12" 
                                                v-if="row.meta_description" 
                                                v-text="(row.meta_description.length)">
                                            </div>
                                            <label for="inputText5">Meta description</label>
                                            <textarea class="form-control" 
                                                    id="inputText5" 
                                                    rows="5" 
                                                    v-model="row.meta_description">
                                            </textarea>
                                        </div>
                                        <!-- End Meta description -->
                                    </div>

                                    <!-- Body -->
                                    <div class="form-group">
                                        <label for="inputText6">Body</label>
                                        <editor
                                            id="inputText6"
                                            v-model="row.body"
                                            api-key="xahz1dg338xnac8il0tkxph26xcaxqaewi3bd9cw9t4e6j7b"
                                            :init="{
                                                height: 800,
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
                                    <!-- End Body -->

                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-5">
                            <div class="card">
                                <div class="card-body">

                                    <!-- Main Categories -->
                                    <div class="form-group">
                                        <label for="exampleInputText2_2">Categories</label>
                                        <select id="exampleInputText2_2" class="form-control ui-h50" 
                                                v-model="row.cat_id"
                                                v-on:change="onCategoryChange">
                                            <option v-if="catLoading" value="">Loading...</option>
                                            <option v-if="!catLoading" 
                                                    v-for="(cat, index) in categories" 
                                                    :key="index" 
                                                    :value="cat.id">
                                                {{cat.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <!-- End Main Categories -->

                                    <!-- Sub Categories -->
                                    <div class="form-group" v-if="subCategories.length">
                                        <label for="exampleInputText2_3">SubCategories</label>
                                        <select id="exampleInputText2_3" class="form-control ui-h50" 
                                                v-model="row.subcat_id">
                                            <option v-if="subcatLoading" value="">Loading...</option>
                                            <option v-if="!subcatLoading" 
                                                    v-for="(subcat, index) in subCategories" 
                                                    :key="index" 
                                                    :value="subcat.id">
                                                {{subcat.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <!-- End Sub Categories -->

                                    <hr class="my-4">

                                    <!-- Tags -->
                                    <div class="form-group">
                                        <label for="exampleInputText2_4">Tags</label>
                                        <multiselect 
                                            id="multiselect"
                                            ref="multiselectRef"
                                            autocomplete="off"
                                            v-model="tagsValue" 
                                            :options="tagsOptions" 
                                            :multiple="true"
                                            :close-on-select="false" 
                                            :clear-on-select="false" 
                                            :hide-selected="true" 
                                            :preserve-search="true" 
                                            :taggable="true"
                                            @tag="addTag"
                                            tag-placeholder="Add this as new tag"
                                            placeholder="Type to search or add tag"
                                            :preselect-first="true">
                                        </multiselect>
                                    </div>
                                    <!-- End Tags -->

                                    <hr class="my-4">

                                    <!-- Featured Image -->
                                    <div class="form-group">
                                        <label for="exampleInputText2_5">Featured Image</label>
                                        <img v-if="row.preview" :src="row.preview" class="feature-image">
                                        <input class="form-control"
                                                id="exampleInputText2_5"  
                                                type="file" 
                                                ref="myDropify" 
                                                v-on:change="onImageChange" />
                                    </div>
                                    <!-- End Featured Image -->

                                    <!-- <hr class="my-4"> -->

                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-12 row">
                        <div class="form-group">
                            <button class="btn btn-primary" :disabled="btnLoading">
                                <span v-if="btnLoading">
                                    <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true">
                                    </span>Loading...
                                </span>
                                <span v-if="!btnLoading" class="ti-save"></span>
                                <span v-if="!btnLoading"> Update Post</span>
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
    import Multiselect from 'vue-multiselect';

    export default {
        name: 'EditPost',
        components: {
            Header,
            Navigation,
            Footer,
            Editor,
            Multiselect
        },
        data(){
            return {
                //
                auth: {
                    role: '',
                    accessToken: '',
                },
                row: {
                    post_id: '',
                    cat_id: '',
                    subcat_id: '',
                    preview: '',
                    image: '',

                    title: '',
                    slug: '',
                    body: '',
                    meta_title: '',
                    meta_keywords: '',
                    meta_description: '',
                },

                //selected: null,
                tagsValue: [],
                tagsOptions: [],

                categories: [],
                subCategories: [],

                catLoading: true,
                subcatLoading: false,
                tagLoading: true,
                btnLoading: false,
                pgLoading: true,
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
                this.row.post_id = this.$route.params.id;
            }

            this.fetchRow();
            this.fetchCategories();
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
                    'permission': 'admin.posts.edit'
                };
               const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
               let params = {};
               params['api_key'] = window.api_key;
               axios.get('/api/v1/dashboard/posts/'+this.row.post_id, {params:params}, config)
                    .then(res => {
                        if(res.data.statusCode == 200) {
                            this.pgLoading = false;
                            this.row.category = res.data.data.rows.cat_slug;
                            this.fetchSubCategories();
                            this.row.cat_id = res.data.data.rows.cat_id;
                            this.row.subcat_id = res.data.data.rows.subcat_id;
                            this.row.preview = res.data.data.rows.image;
                            this.tagsValue = res.data.data.rows.tags;
                            this.row.title = res.data.data.rows.title;
                            this.row.slug = res.data.data.rows.slug;
                            this.row.meta_title = res.data.data.rows.meta_title;
                            this.row.meta_keywords = res.data.data.rows.meta_keywords;
                            this.row.meta_description = res.data.data.rows.meta_description;
                            this.row.body = res.data.data.rows.body;
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

            // Fetch Categories
            fetchCategories(){
                this.catLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.categories.index'
                };
                const config = { headers: { 'Content-Type': 'application/json' }};
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = 'active';
                axios.get('/api/v1/dashboard/categories', {params:params}, config)
                    .then(res => {
                        this.catLoading = false;
                        this.categories = res.data.data.rows;
                        this.fetchTags();
                    })
                    .catch(err => {});
            },

            // On Category Change
            onCategoryChange() {
                this.fetchSubCategories();
            },

            // Fetch SubCategories
            fetchSubCategories(){
                this.subcatLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.categories.index'
                };
                const config = { headers: { 'Content-Type': 'application/json' }};
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = 'active';
                axios.get('/api/v1/dashboard/categories/sub/show/'+this.row.cat_id, {params:params}, config)
                    .then(res => {
                        this.subcatLoading = false;
                        this.subCategories = res.data.data.rows;
                    })
                    .catch(err => {});
            },

            // Fetch Tags
            fetchTags(){
                this.tagLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.tags.index'
                };
                const config = { headers: { 'Content-Type': 'application/json' }};
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = 'active';
                axios.get('/api/v1/dashboard/tags', {params:params}, config)
                    .then(res => {
                        this.tagsOptions = res.data.data.tags;
                    })
                    .catch(err => {});
            },

            // Add New Tags
            addTag (tagName) {
                //const tag = tagName;
                this.tagsOptions.push(tagName);
                this.tagsValue.push(tagName);

                // Then insert it
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.tags.create'
                };
                const config = { headers: { 'Content-Type': 'application/json' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('locale', 'en');
                formData.append('tag',tagName);
                axios.post('/api/v1/dashboard/tags', formData, config)
                .then(res => {
                    if (res.data.statusCode == 401) {
                        izitoast.warning({
                            icon: 'ti-alert',
                            title: 'Wow, man',
                            message: 'Slow down, '+res.data.errors,
                        });
                    }
                })
                .catch(err => {
                    izitoast.warning({
                        icon: 'ti-alert',
                        title: 'Wow, man',
                        message: 'Slow down, '+res.data.errors,
                    });
                });
            },

            // Upload Featured image
            onImageChange(e){
              const file = e.target.files[0];
              this.row.preview = URL.createObjectURL(file);
              this.row.image = file;
            },

            // Update
            updateRow(){
                this.btnLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.posts.edit'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('cat_id', this.row.cat_id);
                formData.append('subcat_id', this.row.subcat_id);
                formData.append('image', this.row.image);
                formData.append('tags', this.tagsValue);
                formData.append('locale', 'en');
                formData.append('title', this.row.title);
                formData.append('body', this.row.body);
                formData.append('slug', this.row.slug);
                formData.append('meta_title', this.row.meta_title);
                formData.append('meta_keywords', this.row.meta_keywords);
                formData.append('meta_description', this.row.meta_description);
                   // fell free to add any new scope

                formData.append('_method', 'PUT');
                axios.post('/api/v1/dashboard/posts/'+this.row.post_id, formData, config)
                .then(res => {
                    this.btnLoading = false;
                    this.isSubmit = true;

                    if(res.data.statusCode == 201) {
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Post Added Successfully',
                        });
                        this.$router.push({ name: 'posts' });

                    } else if(res.data.statusCode == 200) {
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Post Updated Successfully',
                        });
                        this.$router.push({ name: 'posts' });
                        
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
                        message: 'Something went wrong, '+err,
                    });
                });
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
                    this.$router.push({ name: 'posts' });
                    this.isSubmit = true;
                }
            },
        },
        
        // Before Leaving
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
