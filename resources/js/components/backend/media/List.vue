<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2 text-capitalize">Media
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
                            <li class="breadcrumb-item active" aria-current="page">Media</li>
                        </ol>
                    <!-- End Breadcrumb -->
                </nav>
                

            <!-- Content  -->
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-rights ui-mt-50 pull-right ">
                        <div class="dropdown ui-mr5">
                            <span class="badge badge-md badge-pill badge-danger-soft">
                                File Size 
                                <span v-if="dataLoading">
                                    <div class="spinner-grow spinner-grow-sm mr-1" role="status">
                                          <span class="sr-only">Loading...</span>
                                    </div>
                                </span>
                                <span v-if="!dataLoading">{{fileSize}}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 mb-5">
                    <div class="card">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="printMe" class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>File</th>
                                        <th class="text-center">Path</th>
                                        <th class="text-center">mimeType</th>
                                        <th class="text-center">Size</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <tr v-if="dataLoading">
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-grow" role="status">
                                          <span class="sr-only">Loading...</span>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!dataLoading && !rows.length">
                                    <td colspan="5" class="text-center">
                                        <span>No data found</span>
                                    </td>
                                </tr>

                                <tr v-if="!dataLoading && rows.length" 
                                    v-for="(row, index) in rows" :key="index" class="f14">

                                    <td class="font-weight-semi-bold">{{index+1}}</td>

                                    <td class="font-weight-semi-bold">
                                        <a :href="row.aws_bucket+row.file" target="_blank">
                                            <img :src="row.aws_bucket+row.file" style="width:72px;height: 72px">
                                        </a>
                                    </td>

                                    <td class="font-weight-semi-bold text-center">
                                        <a href="javascript:;" 
                                            title="Copy to clipboard"
                                            v-clipboard:copy="row.aws_bucket+row.file"
                                            v-clipboard:success="onCopy"
                                            v-clipboard:error="onError">
                                            Copy to clipboard
                                        </a>
                                    </td>

                                    <td class="font-weight-semi-bold text-center">{{ row.mimeType }}</td>
                                    <td class="font-weight-semi-bold text-center">{{ row.size }}</td>
                                </tr>
                                
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>File</th>
                                        <th class="text-center">Path</th>
                                        <th class="text-center">mimeType</th>
                                        <th class="text-center">Size</th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                        <nav  v-if="rows.length !== 0" aria-label="Page navigation example ui-mt20">
                            <ul class="pagination">
                                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                                    <a class="page-link" href="javascript:" 
                                        @click="fetchData(pagination.prev_page_url)">Previous</a>
                                </li>
                                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                                    <a class="page-link" href="javascript:" 
                                        @click="fetchData(pagination.next_page_url)">Next</a>
                                </li>
                            </ul>
                            <p class="pull-right ui-mt-50 f13">
                                <i>Page {{ pagination.current_page }} or {{ pagination.last_page }}</i>
                            </p>
                        </nav>

                    </div>
                </div>
            </div>



            <div class="col-md-4 mb-5">
                <div class="card">
                    <header class="card-header">
                        <h2 class="h4 card-header-title">Add New</h2>
                    </header>

                <form @submit.prevent="addNew" enctype="multipart/form-data">
                    <div class="card-body pt-0">
                        
                        <div class="form-group">
                            <img v-if="row.preview" 
                                :src="row.preview" 
                                style="width: 100%;max-height: 300px;padding:10px">
                            <input class="form-control" 
                                    type="file"
                                    ref="myDropify" 
                                    v-on:change="onImageChange" 
                                    required="">
                        </div>
                        
                        
                        <div class="form-group">
                            <button class="btn btn-primary" :disabled="btnLoading">
                                <span v-if="btnLoading">
                                    <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true">
                                    </span>Uploading...
                                </span>
                                <span v-if="!btnLoading" class="ti-upload"></span>
                                <span v-if="!btnLoading"> Upload to AWS</span>
                            </button>
                        </div>

                    </div>
                </form>

                </div>
            </div>

            </div>
        </div>
        <!-- End Content Body -->

            <Footer></Footer>
        </div>

        </main>
        <!-- End Main -->
        
    </div>
</template>

<script src="vue.min.js"></script>
<script src="dist/vue-clipboard.min.js"></script>
<script>
    import Header from '../layouts/Header.vue';
    import Navigation from '../layouts/Navigation';
    import Footer from '../layouts/Footer.vue';
    import izitoast from 'izitoast';

    export default {
        name: 'Media',
        components: {
            Header,
            Navigation,
            Footer,
        },
        data(){
            return {
                auth: { 
                    role: '',
                    accessToken: '',
                },
                row: {
                    preview: '',
                    image: '',
                },
                //
                fileSize: '',

                dataLoading: true,
                btnLoading: false,
                something_went_wrong: false,
                rows: [],
                pagination: {},
            }
        },
        mounted() {},
        created() {
            // AccessToken & Role
            if(localStorage.getItem('accessToken')) {
                this.auth.accessToken = localStorage.getItem('accessToken');
            }
            if(localStorage.getItem('role')) {
                this.auth.role = localStorage.getItem('role');
            }

            this.fetchData('', true);
        },
        methods: {

            // Fetch Data
            fetchData(page_url, loading=false) {
                if(loading) { this.dataLoading = true; }
                this.something_went_wrong = false;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.media.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let vm = this;
                let params = {};
                params['api_key'] = window.api_key;
                page_url = page_url || '/api/v1/dashboard/media';
                axios.get(page_url, {params:params}, config)
                    .then(res => {
                        this.dataLoading = false;

                        if (res.data.statusCode == 401) {
                            this.$router.push({name: 'access-denied'});

                        } else if(res.data.statusCode == 200) {

                            this.rows = res.data.data.rows;
                            this.fileSize = res.data.data.fileSize;
                            if(res.data.data.pagination.total) {
                                this.total_data = res.data.data.pagination.total;
                                vm.makePagination(res.data.data.pagination)
                            }
                        } else {
                            izitoast.warning({
                                icon: 'ti-alert',
                                title: 'Wow, man',
                                message: 'Slow down, '+res.data.errors,
                            });
                        }
                    })
                    .catch(err => {
                        this.dataLoading = false;
                        this.something_went_wrong = true;
                    });
            },

            // Pagination
            makePagination(meta) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: meta.next_page_url,
                    prev_page_url: meta.prev_page_url
                }
                this.pagination = pagination;
            },

            // Upload Featured image
            onImageChange(e){
              const file = e.target.files[0];
              this.row.preview = URL.createObjectURL(file);
              this.row.image = file;
            },

            // Add Or Update Category
            addNew(){
                this.btnLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.media.create'
                };
                const config = { headers: { 'Content-Type': 'application/json' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('file', this.row.image);
                axios.post('/api/v1/dashboard/media', formData, config)
                .then(res => {
                    this.btnLoading = false;
                    this.row.preview = '';
                    this.row.image = '';
                    this.fetchData('', true);

                    if(res.data.statusCode == 201) {
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'File Uploaded Successfully',
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
                    this.btnLoading = false;
                    izitoast.error({
                        icon: 'ti-na',
                        title: 'Wow-wow,',
                        message: 'Something went wrong',
                    });
                });
            },


            // Edit
            editRow(row) {
                //this.edit = true;
                this.row.id = row.id;
                this.row.preview = row.preview;
            },

            // Copy to Clipboard
            onCopy: function (e) {
                izitoast.success({
                    icon: 'ti-check',
                    title: 'Great job,',
                    message: 'Copy to Clipboard Successfully',
                });
            },
            onError: function (e) {
                izitoast.error({
                    icon: 'ti-na',
                    title: 'Wow-wow,',
                    message: 'Something went wrong, try again',
                });
            },
    
        },
    }
</script>

