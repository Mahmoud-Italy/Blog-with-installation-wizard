<template>
    <div class="">
        <Header v-on:headerToChild="onSearchSubmit"></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <div class="u-content">
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2 text-capitalize">Tags

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
                            <li class="breadcrumb-item active" aria-current="page">Tags</li>
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
                                        worksheet = "Tags"
                                        name = "Tags.xls">Excel
                                    </download-excel>
                                    <download-excel
                                        class = "dropdown-item cursor-pointer"
                                        :fetch = "fetchExport"
                                        :fields = "exp.json_fields"
                                        :before-generate = "startDownload"
                                        :before-finish = "finishDownload"
                                        type = "csv"
                                        worksheet = "Tags"
                                        name = "Tags.xls">CSV
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
                                :to="{ name: 'tags' }"
                                :class="(status == '') ? 'active' : '' ">All</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_all}}) </span> &nbsp;|&nbsp;

                            <router-link class="pg-hd" 
                                :to="{ name: 'status-tag', params:{status: 'active'} }"
                                :class="(status == 'active') ? 'active' : '' ">Active</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_active}}) </span> &nbsp;|&nbsp;

                            <router-link class="pg-hd"
                                :to="{ name: 'status-tag', params:{status: 'inactive'} }"
                                :class="(status == 'inactive') ? 'active' : '' ">Inactive</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_inactive}}) </span> &nbsp;|&nbsp;

                            <router-link class="pg-hd"
                                :to="{ name: 'status-tag', params:{status: 'trash'} }"
                                :class="(status == 'trash') ? 'active' : '' ">Trash</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_trash}}) </span>

                            <!-- Show Entries -->
                            <div class="dropdown pull-right ui-mt-10">
                                <button type="button" class="btn btn-light btn-sm dropdown-toggle" 
                                    id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>Show</span>
                                    <span v-if="!showLoading"> {{ show }} </span>
                                    <span v-if="showLoading">
                                        <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true"></span>
                                    </span>
                                </button>
                                <div class="dropdown-menu ui-min-w100" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item cursor-pointer"
                                        :class="(show == 10) ? 'active' : ''"
                                        @click="onShow(10)">10
                                    </a>
                                    <a class="dropdown-item cursor-pointer"
                                        :class="(show == 25) ? 'active' : ''"
                                        @click="onShow(25)">25
                                    </a>
                                    <a class="dropdown-item cursor-pointer"
                                        :class="(show == 50) ? 'active' : ''"
                                        @click="onShow(50)">50
                                    </a>
                                    <a class="dropdown-item cursor-pointer"
                                        :class="(show == 100) ? 'active' : ''"
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
                                                :class="(order_by == 'DESC') 
                                                        ? 'ti-arrow-down' 
                                                        :(order_by == 'ASC') ? 'ti-arrow-up'
                                                        : 'ti-exchange-vertical'">
                                            </span>
                                            <span v-if="orderLoading">
                                                <span class="spinner-grow spinner-grow-sm mr-1" 
                                                    role="status" aria-hidden="true"></span>
                                            </span>
                                        </th>
                                        <th class="text-center">Posts</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <tr v-if="dataLoading">
                                    <td colspan="6" class="text-center">
                                        <div class="spinner-grow" role="status">
                                          <span class="sr-only">Loading...</span>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!dataLoading && !rows.length">
                                    <td colspan="6" class="text-center">
                                        <span><i>No data found</i></span>
                                    </td>
                                </tr>

                                <tr v-if="!dataLoading && rows.length" 
                                    v-for="(row, index) in rows" :key="index" class="f14">

                                    <td class="font-weight-semi-bold">
                                        <div class="custom-control custom-checkbox">
                                            <input :id="'expBox'+row.id" class="custom-control-input" type="checkbox" 
                                                v-model="selected" :value="row.id">
                                            <label class="custom-control-label" :for="'expBox'+row.id"></label>
                                        </div>
                                    </td>

                                    <td class="font-weight-semi-bold">
                                        <span class="badge badge-md badge-pill badge-danger-soft no-badge-on-print">
                                            {{row.tag}}
                                        </span>
                                    </td>

                                    <td class="font-weight-semi-bold text-center f12">
                                        {{row.posts}}
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
                                        <th class="text-center">Posts</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                        <nav  v-if="rows.length !== 0 && rows.length > show" aria-label="Page navigation example ui-mt20">
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

                        <!-- Tag -->
                        <div class="form-group">
                            <label for="inputText3">Name</label>
                            <input class="form-control text-lowercase"
                                    id="inputText3" 
                                    type="text"  
                                    @keydown.space.prevent 
                                    v-model="row.tag" 
                                    required="">
                        </div>
                        <!-- End Tag -->
                        
                        <!-- Button -->                        
                        <div class="form-group">
                            <button class="btn btn-primary" :disabled="btnLoading">
                                <span v-if="btnLoading">
                                    <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true">
                                    </span>Loading...
                                </span>
                                <span v-if="!btnLoading" class="ti-save"></span>
                                <span v-if="!btnLoading"> {{btn_status}} Tag</span>
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
        name: 'Tags',
        components: {
            Header,
            Navigation,
            Footer,
        },
        data(){
            return {
                exp: {
                   json_fields: {
                        'id': 'id',
                        'tag': 'tag',
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
                row: {
                    id: '',
                    tag: '',
                },
                //
                count_all: 0,
                count_active: 0,
                count_inactive: 0,
                count_trash: 0,

                search: '',
                status: '',
                order_by: '',

                selected: [],
                selectAll: false,
                plural: '',

                dataLoading: true,
                btnLoading: false,
                bulkLoading: false,
                showLoading: false,
                orderLoading: false,
                exportLoading: false,
                something_went_wrong: false,
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

            this.fetchData('', true);
          }
        },
        created() {
            // AccessToken
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

            this.fetchData('', true);
        },
        methods: {

            // on Search
            onSearchSubmit(value) {
                this.search = value;
                this.fetchData('', true);
            },

            // on Show
            onShow(show){
                this.showLoading = true;
                this.show = show;
                this.fetchData('', true);
            },

            // on OrderBy
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
                this.edit = false;
                this.btn_status = 'Add';
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.tags.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let vm = this;
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = this.status;
                params['search'] = this.search;
                params['show'] = this.show;
                params['order_by'] = this.order_by;
                page_url = page_url || '/api/v1/dashboard/tags';
                axios.get(page_url, {params:params}, config)
                    .then(res => {
                        this.dataLoading = false;
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
                await axios.get('/api/v1/dashboard/tags/export/file?ids='+this.selected+'&api_key='+window.api_key);
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

            // Add Or Update Tag
            addNew(){
                this.btnLoading = true;
                let permission = 'admin.tags.create';
                if(this.edit) {
                    permission = 'admin.tags.edit';
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
                formData.append('tag', this.row.tag);
                   // fell free to add any new languages

                // In case Update
                let req = '/api/v1/dashboard/tags';
                if(this.edit) {
                    formData.append('_method', 'PUT');
                    req = '/api/v1/dashboard/tags/'+this.row.id;
                }

                axios.post(req, formData, config)
                .then(res => {
                    this.btnLoading = false;
                    this.fetchData();
                    // Clear rows
                    this.row.tag = '';

                    if(res.data.statusCode == 201) {
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Item Added Successfully',
                        });
                    } else if(res.data.statusCode == 200) {
                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'Item Updated Successfully',
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
                this.edit = true;
                this.btn_status = 'Update',

                this.row.id = row.id;
                this.row.tag = row.tag;
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
                    'permission': 'admin.tags.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method','PUT');
                axios.post('/api/v1/dashboard/tags/status/'+id, formData, config)
                .then(res => {
                    if(res.data.statusCode == 200) {
                        let msg = 'Item'+this.plural+' Activated Successfully';
                        if(!res.data.data.tag_status) {
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
                    'permission': 'admin.tags.destroy'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method', 'DELETE');
                axios.post('/api/v1/dashboard/tags/trash/'+id, formData, config)
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
                    'permission': 'admin.tags.destroy'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method', 'PATCH');
                axios.post('/api/v1/dashboard/tags/restore/'+id, formData, config)
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
                        'permission': 'admin.tags.destroy'
                    };
                    const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                    let formData = new FormData();
                    formData.append('api_key', window.api_key);
                    formData.append('_method', 'DELETE');
                    axios.post('/api/v1/dashboard/tags/'+id, formData, config)
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

<style scoped="">
    @media print{
      .no-decoration-on-print { text-decoration: none !important }
      .no-badge-on-print { background: transparent !important; color: #000 !important; border-style: none !important }
      .no-print { display: none !important }
    }
</style>
