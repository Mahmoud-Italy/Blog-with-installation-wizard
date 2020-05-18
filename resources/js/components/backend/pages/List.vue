<template>
    <div class="">
        <Header v-on:headerToChild="onSearchSubmit"></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">

                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">Pages
                        <router-link :to="{ name: 'create-page' }" 
                            class="btn btn-primary btn-sm btn-pill ui-mt-10 ui-mb-2">
                            <span>Add New</span>
                        </router-link>

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
                            <li class="breadcrumb-item active" aria-current="page">Pages</li>
                        </ol>
                    
                    <!-- Build Action button -->
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
                                        worksheet = "Pages"
                                        name = "Pages.xls">Excel
                                    </download-excel>
                                    <download-excel
                                        class = "dropdown-item cursor-pointer"
                                        :fetch = "fetchExport"
                                        :fields = "exp.json_fields"
                                        :before-generate = "startDownload"
                                        :before-finish = "finishDownload"
                                        type = "csv"
                                        worksheet = "Pages"
                                        name = "Pages.xls">CSV
                                    </download-excel>
                                    <a class="dropdown-item" href="javascript:;" v-print="'#printMe'">Print</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- End Breadcrumb -->


                <!-- Card -->
                <div class="card">
                    <header class="card-header">
                        <h2 class="h4 card-header-title">
                            <router-link class="pg-hd"
                                :to="{ name: 'pages' }"
                                :class="(this.status == '') ? 'active' : '' ">All</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_all}}) </span> &nbsp;|&nbsp; 

                            <router-link class="pg-hd"
                                :to="{ name: 'status-page', params:{status: 'active'} }" 
                                :class="(this.status == 'active') ? 'active' : '' ">Active</router-link>
                            <span class="pg-hd no-decoration f14"> ({{count_active}}) </span> &nbsp;|&nbsp; 

                            <router-link class="pg-hd"
                                :to="{ name: 'status-page', params:{status: 'inactive'} }" 
                                :class="(this.status == 'inactive') ? 'active' : '' ">Inactive</router-link>
                            <span class="pg-hd no-decoration f14"> ({{count_inactive}}) </span> &nbsp;|&nbsp; 

                            <router-link class="pg-hd"
                                :to="{ name: 'status-page', params:{status: 'trash'} }" 
                                :class="(this.status == 'trash') ? 'active' : '' ">Trash</router-link>
                            <span class="pg-hd no-decoration f14"> ({{count_trash}}) </span>

                            <!-- Show Entries -->
                            <div class="dropdown pull-right ui-mt-10">
                                <button type="button" class="btn btn-light btn-sm dropdown-toggle" 
                                    id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>Show</span>
                                    <span v-if="!showLoading"> {{ show }}</span>
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
                    <!-- End Card Header -->

                    <!-- Crad Body -->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">
                                            <div class="custom-control custom-checkbox">
                                                <input id="expBox0" class="custom-control-input" type="checkbox"
                                                    v-model="selectAll" @click="select">
                                                <label class="custom-control-label" for="expBox0"></label>
                                            </div>
                                        </th>
                                        <th style="width:20%">Title
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
                                        <th class="text-center" style="width: 15%">Author</th>
                                        <th class="text-center" style="width: 15%">Date</th>
                                        <th class="text-center" style="width: 10%">Actions</th>
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
                                        <span>No data found.</span>
                                    </td>
                                </tr>

                                <tr v-if="!dataLoading && rows.length" v-for="(row, index) in rows" :key="index" class="f14">
                                    
                                    <td class="font-weight-semi-bold">
                                        <div class="custom-control custom-checkbox">
                                            <input :id="'expBox'+row.id" class="custom-control-input" type="checkbox" 
                                                v-model="selected" :value="row.id">
                                            <label class="custom-control-label" :for="'expBox'+row.id"></label>
                                        </div>
                                    </td>

                                    <td class="font-weight-semi-bold">
                                        <router-link :to="{ name: 'edit-page', params:{id: row.encrypt_id} }" 
                                            class="default-color text-decoration-hover">
                                            {{ row.title }}
                                        </router-link>
                                    </td>

                                    <td class="font-weight-semi-bold text-center">
                                        <a href="javascript:;" 
                                            class="text-decoration-hover">
                                            <span v-if="row.author" class="badge badge-md badge-pill badge-danger-soft">
                                                {{ row.author }}
                                            </span>
                                            <span v-if="!row.author"> - </span>
                                        </a>
                                    </td>

                                    <td v-html="(row.deleted_at) ? row.deleted_at : 
                                                (row.updated_at) ? row.updated_at : row.created_at"
                                        class="font-weight-semi-bold text-center f12">
                                    </td>

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
                                                            <router-link class="d-block link-dark"
                                                                :to="{ name: 'edit-page', params:{id: row.encrypt_id} }">
                                                                Edit
                                                            </router-link>
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
                                        <th>Title</th>
                                        <th class="text-center">Author</th>
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
            <!-- End Content Body -->


            <Footer></Footer>
        </div>
        <!-- Content -->

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
        name: 'Pages',
        components: {
            Header,
            Navigation,
            Footer
        },
        data(){
            return {
                exp: {
                   json_fields: {
                        'id': 'id',
                        'title': 'title',
                        'body': 'body',
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
                auth: {
                    role: '',
                    accessToken: '',
                },
                //
                count_all: 0,
                count_active: 0,
                count_inactive: 0,
                count_trash: 0,

                status: '',
                search: '',
                order_by: '',
                selected: [],
                selectAll: false,
                plural: '',

                dataLoading: true,
                bulkLoading: false,
                exportLoading: false,
                showLoading: false,
                orderLoading: false,
                sortLoading: false,
                something_went_wrong: false,
                rows: [],
                show: '',
                pagination: {},
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

            this.fetchData('', true);
          }
        },
        created() {
            // AccessToken & Role
            if(localStorage.getItem('role')) {
                this.auth.role = localStorage.getItem('role');
            }
            if(localStorage.getItem('accessToken')) {
                this.auth.accessToken = localStorage.getItem('accessToken');
            }

            // Status By
            if(this.$route.params.status) {
                this.status = this.$route.params.status;
            }

            //
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

            // Fetch Data
            fetchData(page_url, loading=false) {
                if(loading) { this.dataLoading = true; }
                this.something_went_wrong = false;
                this.plural = '',
                this.selectAll = false;
                this.selected = [];

                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.pages.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let vm = this;
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = this.status;
                params['search'] = this.search;
                params['show'] = this.show;
                params['order_by'] = this.order_by;
                page_url = page_url || '/api/v1/dashboard/pages';
                axios.get(page_url, {params:params}, config)
                    .then(res => {
                        if (res.data.statusCode == 401) {
                            this.$router.push({name: 'access-denied'});

                        } else if(res.data.statusCode == 200) {
                            this.dataLoading = false;
                            this.bulkLoading = false;
                            this.showLoading = false;
                            this.orderLoading = false;

                            this.count_all = res.data.data.all;
                            this.count_active = res.data.data.active;
                            this.count_inactive = res.data.data.inactive;
                            this.count_trash = res.data.data.trash;

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
                await axios.get('/api/v1/dashboard/pages/export/file?ids='+this.selected+'&api_key='+window.api_key);
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


            // Multi Edit
            multiEdit(){
                if(this.selected.length == 0) {
                    alert('No item(s) selected');
                } else {
                    this.$router.push({name: 'edit-page', params:{id: this.selected.join()}});
                }
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
                    'permission': 'admin.pages.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method','PUT');
                axios.post('/api/v1/dashboard/pages/status/'+id, formData, config)
                .then(res => {
                    if(res.data.statusCode == 200) {
                        let msg = 'Item'+this.plural+' Activated Successfully';
                        if(!res.data.data.post_status) {
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
                    this.fetchData(); 
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
                    'permission': 'admin.pages.destroy'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method', 'DELETE');
                axios.post('/api/v1/dashboard/pages/trash/'+id, formData, config)
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
                    this.fetchData(); 
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
                    'permission': 'admin.pages.destroy'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method', 'PATCH');
                axios.post('/api/v1/dashboard/pages/restore/'+id, formData, config)
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
                        'permission': 'admin.pages.destroy'
                    };
                    const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                    let formData = new FormData();
                    formData.append('api_key', window.api_key);
                    formData.append('_method', 'DELETE');
                    axios.post('/api/v1/dashboard/pages/'+id, formData, config)
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
    }
</script>
