<template>
    <div class="">
        <Header v-on:headerToChild="onSearchSubmit"></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2 text-capitalize">{{capitalize_subcategory}}</h1>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><router-link :to="{ name: 'dashboard' }">Home</router-link></li>
                            <li class="breadcrumb-item"><router-link :to="{ name: 'categories' }">Categories</router-link></li>
                            <li class="breadcrumb-item text-capitalize active" aria-current="page">{{capitalize_subcategory}}</li>
                        </ol>
                    <!-- End Breadcrumb -->

                    <!-- Builk Action button -->
                    <div class="pull-rights ui-mt-50 pull-right ">
                        <div class="dropdown display-flex-inline">
                            <div class="dropdown ui-mr5">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false" :disabled="bulkLoading">
                                    <span v-if="!bulkLoading">Bulk Actions</span>
                                    <span v-if="bulkLoading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true"></span>Loading...
                                    </span>
                                </button>
                                <div class="dropdown-menu">
                                    <a v-if="status == 'inactive' || status == ''"
                                        @click="multiActiveOrInactive()"
                                        class="dropdown-item" href="javascript:;">Active
                                    </a>
                                    <a v-if="status == 'active' || status == ''"
                                        @click="multiActiveOrInactive()"
                                        class="dropdown-item" href="javascript:;">Inactive
                                    </a>
                                    <a v-if="status != 'trash'"
                                        @click="multiMoveToTrash()"
                                        class="dropdown-item" href="javascript:;">Move to Trash
                                    </a>

                                    <a v-if="status == 'trash'"
                                        @click="multiRestoreFromTrash()"
                                        class="dropdown-item" href="javascript:;">Restore
                                    </a>
                                    <a v-if="status == 'trash'"
                                        @click="multiDeletePermanently()"
                                        class="dropdown-item" href="javascript:;">Delete Permanently
                                    </a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" :disabled="exportLoading"><span v-if="!exportLoading">Export</span>
                                    <span v-if="exportLoading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true"></span>Loading...
                                    </span>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <download-excel
                                        class = "dropdown-item cursor-pointer"
                                        :fetch = "fetchExport"
                                        :fields = "exp.json_fields"
                                        :before-generate = "startDownload"
                                        :before-finish = "finishDownload"
                                        worksheet = "SubCategories"
                                        name = "SubCategories.xls">Excel
                                    </download-excel>
                                    <download-excel
                                        class = "dropdown-item cursor-pointer"
                                        :fetch = "fetchExport"
                                        :fields = "exp.json_fields"
                                        :before-generate = "startDownload"
                                        :before-finish = "finishDownload"
                                        type = "csv"
                                        worksheet = "SubCategories"
                                        name = "SubCategories.xls">CSV
                                    </download-excel>
                                    <a class="dropdown-item" href="javascript:;" v-print="'#printMe'">Print</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Builk Action button -->
                </nav>
                

            <!-- Content  -->
            <div class="row">
                <div class="col-md-8 mb-5">
                    <div class="card">
                        <header class="card-header">
                            <h2 class="h4 card-header-title">

                            <router-link class="pg-hd" 
                                :to="{ name: 'sub-categories' }"
                                :class="(this.status == '') ? 'active' : '' ">All</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_all}}) </span> &nbsp;|&nbsp;

                            <router-link class="pg-hd" 
                                :to="{ name: 'status-sub-category', params:{status: 'active'} }"
                                :class="(this.status == 'active') ? 'active' : '' ">Active</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_active}}) </span> &nbsp;|&nbsp;

                            <router-link class="pg-hd"
                                :to="{ name: 'status-sub-category', params:{status: 'inactive'} }"
                                :class="(this.status == 'inactive') ? 'active' : '' ">Inactive</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_inactive}}) </span> &nbsp;|&nbsp;

                            <router-link class="pg-hd"
                                :to="{ name: 'status-sub-category', params:{status: 'trash'} }"
                                :class="(this.status == 'trash') ? 'active' : '' ">Trash</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_trash}}) </span>

                            <!-- Show Entries -->
                            <div class="dropdown pull-right ui-mt-10">
                                <button type="button" class="btn btn-light btn-sm dropdown-toggle" 
                                    id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>Show</span>
                                    <span v-if="!showLoading"> {{ this.show }}</span>
                                    <span v-if="showLoading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true"></span>
                                    </span>
                                </button>
                                <div class="dropdown-menu ui-min-w100" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item cursor-pointer"
                                        :class="(this.show == 10) ? 'active' : ''"
                                        @click="onShow(10)">10
                                    </a>
                                    <a class="dropdown-item cursor-pointer"
                                        :class="(this.show == 25) ? 'active' : ''"
                                        @click="onShow(25)">25
                                    </a>
                                    <a class="dropdown-item cursor-pointer"
                                        :class="(this.show == 50) ? 'active' : ''"
                                        @click="onShow(50)">50
                                    </a>
                                    <a class="dropdown-item cursor-pointer"
                                        :class="(this.show == 100) ? 'active' : ''"
                                        @click="onShow(100)">100
                                    </a>
                                </div>
                            </div>
                            <!-- End Show Entries -->

                            </h2>
                        </header>


                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table id="printMe" class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input id="expBox0" class="custom-control-input" type="checkbox"
                                                    v-model="selectAll" @click="select">
                                                <label class="custom-control-label" for="expBox0"></label>
                                            </div>
                                        </th>
                                        <th style="width:25%">Name
                                            <span v-if="!orderLoading"
                                                @click="onOrderBy()"
                                                class="cursor-pointer " 
                                                :class="(this.order_by == 'DESC') 
                                                        ? 'ti-arrow-down' 
                                                        :(this.order_by == 'ASC') ? 'ti-arrow-up'
                                                        : 'ti-exchange-vertical'">
                                            </span>
                                            <span v-if="orderLoading">
                                                <span class="spinner-grow spinner-grow-sm mr-1" 
                                                    role="status" aria-hidden="true"></span>
                                            </span>
                                        </th>
                                        <th class="text-center">Parent</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <!-- Loading... -->
                                <tr v-if="dataLoading">
                                    <td colspan="6" class="text-center">
                                        <div class="spinner-grow" role="status">
                                          <span class="sr-only">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                                <!-- End Loading... -->

                                <!-- No data found -->
                                <tr v-if="!dataLoading && !rows.length">
                                    <td colspan="6" class="text-center">
                                        <span>No data found</span>
                                    </td>
                                </tr>
                                <!-- End No data found -->

                                <tr v-if="!dataLoading && rows.length" 
                                    v-for="(row, index) in rows" :key="index" class="f14">

                                    <!-- Checkbox with id -->
                                    <td class="font-weight-semi-bold">
                                        <div class="custom-control custom-checkbox">
                                            <input :id="'expBox'+row.id" class="custom-control-input" type="checkbox" 
                                                v-model="selected" :value="row.id">
                                            <label class="custom-control-label" :for="'expBox'+row.id"></label>
                                        </div>
                                    </td>
                                    <!-- End Checkbox  -->

                                    <!-- Name -->
                                    <td class="font-weight-semi-bold">
                                        <a href="javascript:;"
                                            @click="editRow(row)"
                                            class="default-color text-decoration-hover no-decoration-on-print">
                                        {{row.name}}
                                        </a>
                                    </td>
                                    <!-- End Name -->

                                    <!-- Parent Name -->
                                    <td class="font-weight-semi-bold text-center">
                                        <span class="badge badge-md badge-pill badge-danger-soft no-badge-on-print">
                                            {{row.parent_name}}
                                        </span>
                                    </td>
                                    <!-- End Parent Name -->

                                    <!-- Date -->
                                    <td v-html="(row.deleted_at) ? row.deleted_at : 
                                                (row.updated_at) ? row.updated_at : row.created_at"
                                        class="font-weight-semi-bold text-center f12">
                                    </td>
                                    <!-- End Date -->

                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a id="tableWithImage1MenuInvoker" class="u-icon-sm link-muted" 
                                                href="javascript:;" role="button" aria-haspopup="true" aria-expanded="false"
                                                data-toggle="dropdown"
                                                data-offset="8">
                                                <span v-if="!row.loading" class="ti-more"></span>
                                                <span v-if="row.loading" class="spinner-grow spinner-grow-sm mr-1" 
                                                    role="status" aria-hidden="true">
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" style="width: 150px">
                                                <div class="card border-0 p-3">
                                                    <ul class="list-unstyled mb-0">
                                                        <li v-if="!row.trash">
                                                            <a class="d-block link-dark" href="javascript:;" 
                                                                @click="editRow(row)">Edit
                                                            </a>
                                                        </li>
                                                        <li v-if="!row.trash">
                                                            <a @click="row.loading = true; activeOrInactive(row.id)"
                                                                v-html="(row.status) ? 'Inactive' : 'Active'"
                                                                class="d-block link-dark" href="javascript:;">
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a v-if="!row.trash" 
                                                                @click="row.loading = true; moveToTrash(row.id)"
                                                                class="d-block link-dark" href="javascript:;">Move to Trash
                                                            </a>
                                                            <a v-if="row.trash" 
                                                                @click="row.loading = true; restoreFromTrash(row.id)"
                                                                class="d-block link-dark" href="javascript:;">Restore
                                                            </a>
                                                            <a v-if="row.trash" 
                                                                @click="row.loading = true; deletePermanently(row.id)"
                                                                class="d-block link-dark" href="javascript:;">Delete Permanently
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Actions -->
                                </tr>
                                
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input id="expBox0" class="custom-control-input" type="checkbox"
                                                    v-model="selectAll" @click="select">
                                                <label class="custom-control-label" for="expBox0"></label>
                                            </div>
                                        </th>
                                        <th>Name</th>
                                        <th class="text-center">Parent</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Actions</th>
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

                <form @submit.prevent="addNew">
                    <div class="card-body pt-0">
                        
                        <!-- Name -->
                        <div class="form-group">
                            <label for="inputText1">Name</label>
                            <input class="form-control"
                                    id="inputText1" 
                                    type="text" 
                                    v-model="row.name" 
                                    required="">
                        </div>
                        <!-- End Name -->

                        <!-- Sub -->
                        <div class="form-group">
                            <div class="pull-right f12" 
                                v-if="row.slug"
                                v-text="(row.slug.length)">
                            </div>
                            <label for="inputText2">Slug</label>
                            <input class="form-control text-lowercase"
                                    id="inputText2" 
                                    type="text"  
                                    @keydown.space.prevent 
                                    @paste="onPaste"
                                    v-on:change="onSlugChange"
                                    v-model="row.slug" 
                                    required="">
                        </div>
                        <!-- End Sub -->
                        
                        <!-- Meta title -->
                        <div class="form-group">
                            <div class="pull-right f12"
                                v-if="row.meta_title" 
                                v-text="(row.meta_title.length)">
                            </div>
                            <label for="inputText3">Meta title</label>
                            <input class="form-control"
                                    id="inputText3" 
                                    type="text"
                                    v-model="row.meta_title">
                        </div>
                        <!-- End Meta title -->

                        <!-- Meta keywords -->
                        <div class="form-group">
                            <div class="pull-right f12"
                                v-if="row.meta_keywords" 
                                v-text="(row.meta_keywords.length)">
                            </div>
                            <label for="inputText4">Meta keywords</label>
                            <textarea class="form-control" 
                                    id="inputText4" 
                                    rows="3" 
                                    v-model="row.meta_keywords">
                            </textarea>
                        </div>
                        <!-- End Meta keywords -->

                        <!-- Meta description -->
                        <div class="form-group">
                            <div class="pull-right f12"
                                v-if="row.meta_description"  
                                v-text="(row.meta_description.length)">
                            </div>
                            <label for="inputText6">Meta description</label>
                            <textarea class="form-control" 
                                    id="inputText6" 
                                    rows="3" 
                                    v-model="row.meta_description">
                            </textarea>
                        </div>
                        <!-- End Meta description -->

                        <!-- Meta Image -->
                        <div class="form-group">
                            <label for="exampleInputText2_5">Image</label>
                            <img v-if="row.preview" :src="row.preview" 
                                    style="width: 100%;max-height: 300px">
                            <input id="exampleInputText2_5" class="form-control" type="file" 
                                    ref="myDropify" v-on:change="onImageChange"/>
                        </div>
                       <!-- End Meta Image -->
                        
                        <!-- Button -->
                        <div class="form-group">
                            <button class="btn btn-primary" :disabled="btnLoading">
                                <span v-if="btnLoading">
                                    <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true">
                                    </span>Loading...
                                </span>
                                <span v-if="!btnLoading" class="ti-save"></span>
                                <span v-if="!btnLoading"> {{btn_status}} Category</span>
                            </button>
                        </div>
                        <!-- End Button -->

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

<script>
    import Header from '../layouts/Header.vue';
    import Navigation from '../layouts/Navigation';
    import Footer from '../layouts/Footer.vue';
    import izitoast from 'izitoast';

    export default {
        name: 'SubCategories',
        components: {
            Header,
            Navigation,
            Footer,
        },
        data(){
            return {
                // for Export to Excel, CSV
                exp: {
                   json_fields: {
                        'id': 'id',
                        'name': 'name',
                        'parent_name': 'parent_name',
                        'created_at': 'created_at',
                    }, 
                    json_data: [],
                    json_meta: [
                        [
                            {
                                'key': 'charset',
                                'value': 'utf-8'
                            }
                        ]
                    ],
                },
                // Auth
                auth: { 
                    role: '',
                    accessToken: '',
                },
                // Rows
                row: {
                    id: '',
                    parent_id: 0,
                    preview: '',
                    image: '',

                    name: '',
                    slug: '',
                    meta_title: '',
                    meta_keywords: '',
                    meta_description: '',
                },
                // Elements
                count_all: 0,
                count_active: 0,
                count_inactive: 0,
                count_trash: 0,

                search: '',
                status: '',
                order_by: '',
                subcategory: '',
                capitalize_subcategory: '',

                selected: [],
                selectAll: false,
                plural: '',

                dataLoading: true,
                btnLoading: false,
                bulkLoading: false,
                showLoading: false,
                orderLoading: false,
                exportLoading: false,
                sortLoading: false,
                something_went_wrong: false,
                isSubmit: false,
                rows: [],
                show: 10,
                pagination: {},
                edit: false,
                btn_status: 'Add',
            }
        },
        mounted() {},
        watch: {
          $route() {
            // Status By
            if(this.$route.params.status) {
                this.status = this.$route.params.status;
            } else {
                this.status = '';
            }

            if(this.$route.params.subcategory) {
                this.subcategory = this.$route.params.subcategory;
            }

            this.fetchData('', true);
          }
        },
        created() {
            // AccessToken & Role
            if(localStorage.getItem('accessToken')) {
                this.auth.accessToken = localStorage.getItem('accessToken');
            }
            if(localStorage.getItem('role')) {
                this.auth.role = localStorage.getItem('role');
            }

            // Status By
            if(this.$route.params.status) {
                this.status = this.$route.params.status;
            } else {
                this.status = '';
            }

            if(this.$route.params.subcategory) {
                this.subcategory = this.$route.params.subcategory;
            }

            this.replaceDash();
            this.fetchData('', true);
        },
        methods: {

            onSearchSubmit(value) {
                this.search = value;
                this.fetchData('', true);
            },

            onShow(show){
                this.showLoading = true;
                this.show = show;
                this.fetchData('', true);
            },

            onOrderBy(){
                this.orderLoading = true;
                if(this.order_by == 'DESC') {
                    this.order_by = 'ASC';
                } else {
                    this.order_by = 'DESC';
                }
                this.fetchData('', true);
            },

            // Replace Dash
            replaceDash(){
                let str = this.subcategory;
                this.capitalize_subcategory = str.replace('-',' ');
            },

            // Fetch Data
            fetchData(page_url, loading=false) {
                if(loading) { this.dataLoading = true; }
                this.something_went_wrong = false;
                this.plural = '',
                this.selectAll = false;
                this.selected = [];
                this.edit = false;
                this.btn_status = 'Add';

                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.categories.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let vm = this;
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = this.status;
                params['search'] = this.search;
                params['show'] = this.show;
                params['order_by'] = this.order_by;
                page_url = page_url || '/api/v1/dashboard/categories/sub/show/'+this.subcategory;
                axios.get(page_url, {params:params}, config)
                    .then(res => {
                        this.dataLoading = false;
                        this.sortLoading = false;
                        this.bulkLoading = false;
                        this.showLoading = false;
                        this.orderLoading = false;
                        
                        if (res.data.statusCode == 401) {
                            this.$router.push({name: 'access-denied'});

                        } else if(res.data.statusCode == 200) {
                            this.count_all = res.data.data.all;
                            this.count_active = res.data.data.active;
                            this.count_inactive = res.data.data.inactive;
                            this.count_trash = res.data.data.trash;

                            this.row.parent_id = res.data.data.category_id;
                            this.rows = res.data.data.rows;
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

            // Fetch Export to Excel, CSV
            async fetchExport(){
                const res = 
                await axios.get('/api/v1/dashboard/categories/export/file?ids='+this.selected+'&api_key='+window.api_key);
                return res.data.data;
            },
            startDownload(){
                this.exportLoading = true;
            },
            finishDownload(){
                this.exportLoading = false;
                izitoast.success({
                    icon: 'ti-check',
                    title: 'Great job,',
                    message: 'File Generated Successfully',
                });
            },

            
            // Add Or Update Category
            addNew(){
                this.btnLoading = true;

                let permission = 'admin.categories.create';
                if(this.edit) {
                    permission = 'admin.categories.edit';
                }
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': permission
                };
                const config = { headers: { 'Content-Type': 'application/json' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('locale', 'en');
                formData.append('parent_id', this.row.parent_id);
                formData.append('image', this.row.image);
                formData.append('name', this.row.name);
                formData.append('slug', this.row.slug);
                formData.append('meta_title', this.row.meta_title);
                formData.append('meta_keywords', this.row.meta_keywords);
                formData.append('meta_description', this.row.meta_description);
                    // fell free to add any new languages

                // In case Update
                let req = '/api/v1/dashboard/categories';
                if(this.edit) {
                    formData.append('_method', 'PUT');
                    req = '/api/v1/dashboard/categories/'+this.row.id;
                }

                axios.post(req, formData, config)
                .then(res => {
                    this.btnLoading = false;
                    this.isSubmit = true;
                    this.fetchData('', true);
                    // Clear rows
                    this.clearRows();

                    if(res.data.statusCode == 201) {
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Category Added Successfully',
                        });
                    } else if(res.data.statusCode == 200) {
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Category Updated Successfully',
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
                        message: 'Something went wrong, '+err,
                    });
                });
            },

            // Clear Rows
            clearRows(){
                this.row.preview = '';
                this.row.image = '';
                this.$refs.myDropify.value = '';

                this.row.name = '';
                this.row.slug = '';
                this.row.meta_title = '';
                this.row.meta_keywords = '';
                this.row.meta_description = '';
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


            // Edit
            editRow(row) {
                this.edit = true;
                this.btn_status = 'Update',

                this.row.id = row.id;
                this.row.parent_id = row.parent_id;

                this.row.preview = row.image;
                this.row.image = '';

                this.row.name = row.name;
                this.row.slug = row.slug;
                this.row.meta_title = row.meta_title;
                this.row.meta_keywords = row.meta_keywords;
                this.row.meta_description = row.meta_description;
            },


        /** Bulk Actions **/
            // ON Select
            select() {
                this.selected = [];
                if (!this.selectAll) {
                    for (let i in this.rows) {
                        this.selected.push(this.rows[i].id);
                    }
                }
            },

            // Multi Update Status
            multiActiveOrInactive(){
                if(this.selected.length == 0) {
                    alert('No item(s) selected');
                } else {
                    this.plural = '(s)';
                    this.bulkLoading = true;
                    this.activeOrInactive(this.selected.join());
                }
            },
            // Update Status
            activeOrInactive(id){
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.categories.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method','PUT');
                axios.post('/api/v1/dashboard/categories/status/'+id, formData, config)
                .then(res => {
                    if(res.data.statusCode == 200) {
                        let msg = 'Item'+this.plural+' Activated Successfully';
                        if(!res.data.data.category_status) {
                            msg = 'Item'+this.plural+' Inactivated Successfully';
                        }
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: msg,
                        });
                    } else {
                        izitoast.warning({
                            icon: 'ti-alert',
                            title: 'Wow, man',
                            message: 'Slow down, '+res.data.errors,
                        });
                    }
                    this.fetchData();
                })
                .catch(err => {
                    izitoast.error({
                        icon: 'ti-na',
                        title: 'Wow-wow,',
                        message: 'Something went wrong',
                    });
                });
            },


            // Multi Move to Trash
            multiMoveToTrash(){
                if(this.selected.length == 0) {
                    alert('No item(s) selected');
                } else {
                    this.plural = '(s)';
                    this.bulkLoading = true;
                    this.moveToTrash(this.selected.join());
                }
            },
            // Move to Trash
            moveToTrash(id){
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.categories.destroy'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method', 'DELETE');
                axios.post('/api/v1/dashboard/categories/trash/'+id, formData, config)
                .then(res => {
                    if(res.data.statusCode == 200) {
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Item'+this.plural+' Moved To Trash Successfully',
                        });
                    } else {
                        izitoast.warning({
                            icon: 'ti-alert',
                            title: 'Wow, man',
                            message: 'Slow down, '+res.data.errors,
                        });
                    }
                    this.fetchData();
                })
                .catch(err => {
                    izitoast.error({
                        icon: 'ti-na',
                        title: 'Wow-wow,',
                        message: 'Something went wrong',
                    });
                });
            },



            // Multi Restore from Trash
            multiRestoreFromTrash(){
                if(this.selected.length == 0) {
                    alert('No item(s) selected');
                } else {
                    this.plural = '(s)';
                    this.bulkLoading = true;
                    this.restoreFromTrash(this.selected.join());
                }
            },
            // Restore from Trash
            restoreFromTrash(id){
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.categories.destroy'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method', 'PATCH');
                axios.post('/api/v1/dashboard/categories/restore/'+id, formData, config)
                .then(res => {
                    if(res.data.statusCode == 200) {
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Item'+this.plural+' Restored From Trash Successfully',
                        });
                    } else {
                        izitoast.warning({
                            icon: 'ti-alert',
                            title: 'Wow, man',
                            message: 'Slow down, '+res.data.errors,
                        });
                    }
                    this.fetchData();
                })
                .catch(err => {
                    this.fetchData();   
                    izitoast.error({
                        icon: 'ti-na',
                        title: 'Wow-wow,',
                        message: 'Something went wrong',
                    });
                });
            },



            // Multi Delete Permanently
            multiDeletePermanently(){
                if(this.selected.length == 0) {
                    alert('No item(s) selected');
                } else {
                    this.plural = '(s)';
                    this.bulkLoading = true;
                    this.deletePermanently(this.selected.join());
                }
            },
            // Delete Permanently
            deletePermanently(id) {
                if(confirm('Are You Sure?')) {
                    axios.defaults.headers.common = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': window.csrf_token,
                        'accesstoken': this.auth.accessToken,
                        'permission': 'admin.categories.destroy'
                    };
                    const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                    let formData = new FormData();
                    formData.append('api_key', window.api_key);
                    formData.append('_method', 'DELETE');
                    axios.post('/api/v1/dashboard/categories/'+id, formData, config)
                    .then(res => {
                        if(res.data.statusCode == 200) {
                            izitoast.success({
                                icon: 'ti-check',
                                title: 'Great job,',
                                message: 'Item'+this.plural+' Deleted Permanently Successfully',
                            });
                        } else {
                            izitoast.warning({
                                icon: 'ti-alert',
                                title: 'Wow, man',
                                message: 'Slow down, '+res.data.errors,
                            });
                        }
                        this.fetchData();
                    })
                    .catch(err => {
                        this.fetchData();   
                        izitoast.error({
                            icon: 'ti-na',
                            title: 'Wow-wow,',
                            message: 'Something went wrong',
                        });
                    });
                } else { 
                    // In case Cancel Alert
                    this.fetchData(); 
                }
            },
        /** END Bulk Actions **/
        },
        
        // Before Enter...
        beforeRouteEnter (to, from, next) { 
          next(vm => { 
            //next();
          }) 
        },

        // Before Leaving...
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

<style scoped="">
    @media print{
      .no-decoration-on-print { text-decoration: none !important }
      .no-badge-on-print { background: transparent !important; color: #000 !important; border-style: none !important }
      .no-print { display: none !important }
    }
</style>
