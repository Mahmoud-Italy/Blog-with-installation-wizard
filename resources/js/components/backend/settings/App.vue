<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation :nav_roles='nv.nav_roles'
                        :nav_sliders='nv.nav_sliders' 
                        :nav_themes='nv.nav_themes'
                        :nav_reports='nv.nav_reports'
                        :nav_activity_logs='nv.nav_activity_logs'
                        :nav_cahce_management='nv.nav_cahce_management'></Navigation>

            <div class="u-content">
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2 text-capitalize">{{ app_name }}
                        <button v-if="!dataLoading" :disabled="setupLoading"
                            @click="appSetup()"
                            class="btn btn-sm btn-pill ui-mt-10 ui-mb-2 btn-with-icon "
                            :class="(setup) ? 'btn-danger' : 'btn-secondary'">
                            <span v-if="setupLoading">
                                <span class="spinner-grow spinner-grow-sm mr-1" 
                                    role="status" aria-hidden="true">
                                </span>
                            </span>
                            <span v-if="!setupLoading" :class="'btn-icon mr-2 '+icon"></span>
                            <span v-html="(setup) ? 'DISABLED' : 'SET UP'"></span>
                        </button>

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
                            <li class="breadcrumb-item"><router-link :to="{ name: 'settings' }">App Settings</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ app_name }}</li>
                        </ol>
                    <!-- End Breadcrumb -->

                    <!-- Builk Action button -->
                    <div v-if="setup" class="pull-rights ui-mt-50 pull-right ">
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
                                        worksheet = "AppSetting"
                                        name = "AppSettingsManufactures.xls">Excel
                                    </download-excel>
                                    <download-excel
                                        class = "dropdown-item cursor-pointer"
                                        :fetch = "fetchExport"
                                        :fields = "exp.json_fields"
                                        :before-generate = "startDownload"
                                        :before-finish = "finishDownload"
                                        type = "csv"
                                        worksheet = "AppSetting"
                                        name = "AppSettingsManufactures.xls">CSV
                                    </download-excel>
                                    <a class="dropdown-item" href="javascript:;" v-print="'#printMe'">Print</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Builk Action button -->
                </nav>
                

            <!-- Content  -->
            <div class="row min-h-400" v-if="!setup && !dataLoading"></div>
            <div class="row" v-if="setup">
                <div class="col-md-8 mb-5">
                    <div class="card">
                        <header class="card-header">
                            <h2 class="h4 card-header-title">

                            <router-link class="pg-hd" 
                                :to="{ name: 'app-settings', params: {appid: this.row.encrypt_app_id} }"
                                :class="(this.status == '') ? 'active' : '' ">All</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_all}}) </span> &nbsp;|&nbsp;

                            <router-link class="pg-hd" 
                                :to="{ name: 'app-status-setting', 
                                    params:{appid: this.row.encrypt_app_id, status: 'active'} }"
                                :class="(this.status == 'active') ? 'active' : '' ">Active</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_active}}) </span> &nbsp;|&nbsp;

                            <router-link class="pg-hd"
                                :to="{ name: 'app-status-setting', 
                                    params:{appid: this.row.encrypt_app_id, status: 'inactive'} }"
                                :class="(this.status == 'inactive') ? 'active' : '' ">Inactive</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_inactive}}) </span> &nbsp;|&nbsp;

                            <router-link class="pg-hd"
                                :to="{ name: 'app-status-setting', 
                                    params:{appid: this.row.encrypt_app_id, status: 'trash'} }"
                                :class="(this.status == 'trash') ? 'active' : '' ">Trash</router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_trash}}) </span>

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
                                        <th style="width: 15%" class="text-center">Key</th>
                                        <th style='width: 35%' class="text-center">Value</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <tr v-if="dataLoading">
                                    <td colspan="4" class="text-center">
                                        <div class="spinner-grow" role="status">
                                          <span class="sr-only">Loading...</span>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="!dataLoading && !rows.length">
                                    <td colspan="4" class="text-center">
                                        <span>No data found</span>
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

                                    <td class="font-weight-semi-bold text-center">
                                        <a href="javascript:;"
                                            @click="editRow(row)"
                                            class="default-color text-decoration-hover no-decoration-on-print">
                                            {{row.index}}
                                        </a>
                                    </td>

                                    <td class="font-weight-semi-bold text-center f12">
                                        {{ row.value }}
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
                                        <th class="text-center">Key</th>
                                        <th class="text-center">Value</th>
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
                        <h2 class="h4 card-header-title">Add Manufacture</h2>
                    </header>

                <form @submit.prevent="addNew">
                    <div class="card-body pt-0">
                        <div class="tab-content">

                        <div id="panelTab1-1" class="tab-pane fade active show" role="tabpanel" aria-labelledby="panelTabInvoker1">
                            <div class="form-group">
                                <label for="inputText1">Key</label>
                                <input class="form-control"
                                        id="inputText1" 
                                        type="text"  
                                        v-model="row.index" 
                                        required="">
                            </div>
                            <div class="form-group">
                                <label for="inputText2">Value</label>
                                <textarea class="form-control"
                                        id="inputText2" 
                                        rows="5"
                                        type="text" 
                                        v-model="row.value" 
                                        required="">
                                </textarea>
                            </div>
                        </div>

                        <div id="panelTab1-2" class="tab-pane fade" role="tabpanel" aria-labelledby="panelTabInvoker2">
                            <!-- <div class="form-group">
                                <label for="inputText4">Slug [ar]</label>
                                <input id="inputText4" type="text" class="form-control text-lowercase" 
                                    @keydown.space.prevent v-model="row.slug_ar" required="">
                            </div>
                            <div class="form-group">
                                <label for="inputText5">Name [ar]</label>
                                <input id="inputText5" type="text" class="form-control" v-model="row.name_ar" required="">
                            </div> -->
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-primary" :disabled="btnLoading">
                                <span v-if="btnLoading">
                                    <span class="spinner-grow spinner-grow-sm mr-1" 
                                        role="status" aria-hidden="true">
                                    </span>Loading...
                                </span>
                                <span v-if="!btnLoading" class="ti-save"></span>
                                <span v-if="!btnLoading"> {{btn_status}} Manufacture</span>
                            </button>
                        </div>

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

<script>
    import Header from '../layouts/Header.vue';
    import Navigation from '../layouts/Navigation';
    import Footer from '../layouts/Footer.vue';
    import izitoast from 'izitoast';

    export default {
        name: 'App',
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
                        'index': 'index',
                        'value': 'value',
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
                    encrypt_app_id: '',

                    index: '',
                    value: '',
                },
                nv: {
                    nav_roles: '',
                    nav_sliders: '',
                    nav_themes: '',
                    nav_reports: '',
                    nav_activity_logs: '',
                    nav_cache_management: '',
                },
                //
                count_all: 0,
                count_active: 0,
                count_inactive: 0,
                count_trash: 0,

                search: '',
                status: '',

                selected: [],
                selectAll: false,
                plural: '',

                dataLoading: true,
                btnLoading: false,
                bulkLoading: false,
                exportLoading: false,
                something_went_wrong: false,
                setupLoading: false,
                rows: [],
                app: [],
                app_name: '',
                icon: '',
                setup: '',
                app_id: '',
                pagination: {},
                edit: false,
                btn_status: 'Create',
            }
        },
        mounted() {},
        watch: {
          $route() {
            // get App id
            if(this.$route.params.appid) {
                this.row.encrypt_app_id = this.$route.params.appid;
            }

             // Status By
            if(this.$route.params.status) {
                this.status = this.$route.params.status;
            } else {
                this.status = '';
            }

            // Navigations 
            if(localStorage.getItem('nav_roles')) {
               this.nv.nav_roles = localStorage.getItem('nav_roles');
            }
            if(localStorage.getItem('nav_sliders')) {
               this.nv.nav_sliders = localStorage.getItem('nav_sliders');
            }
            if(localStorage.getItem('nav_themes')) {
               this.nv.nav_themes = localStorage.getItem('nav_themes');
            }
            if(localStorage.getItem('nav_reports')) {
               this.nv.nav_reports = localStorage.getItem('nav_reports');
            }
            if(localStorage.getItem('nav_activity_logs')) {
               this.nv.nav_activity_logs = localStorage.getItem('nav_activity_logs');
            }
            if(localStorage.getItem('nav_cahce_managements')) {
               this.nv.nav_cahce_managements = localStorage.getItem('nav_cahce_managements');
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

            // get App id
            if(this.$route.params.appid) {
                this.row.encrypt_app_id = this.$route.params.appid;
            }

             // Status By
            if(this.$route.params.status) {
                this.status = this.$route.params.status;
            } else {
                this.status = '';
            }

            // Navigations 
            if(localStorage.getItem('nav_roles')) {
               this.nv.nav_roles = localStorage.getItem('nav_roles');
            }
            if(localStorage.getItem('nav_sliders')) {
               this.nv.nav_sliders = localStorage.getItem('nav_sliders');
            }
            if(localStorage.getItem('nav_themes')) {
               this.nv.nav_themes = localStorage.getItem('nav_themes');
            }
            if(localStorage.getItem('nav_reports')) {
               this.nv.nav_reports = localStorage.getItem('nav_reports');
            }
            if(localStorage.getItem('nav_activity_logs')) {
               this.nv.nav_activity_logs = localStorage.getItem('nav_activity_logs');
            }
            if(localStorage.getItem('nav_cahce_managements')) {
               this.nv.nav_cahce_managements = localStorage.getItem('nav_cahce_managements');
            }

            this.fetchData('', true);
        },
        methods: {

            // Fetch Data
            fetchData(page_url, loading=false) {
                if(loading) { this.dataLoading = true; }
                this.something_went_wrong = false;
                this.plural = '',
                this.selectAll = false;
                this.selected = [];
                this.edit = false;
                this.btn_status = 'Create';

                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.app_settings.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let vm = this;
                let params = {};
                params['api_key'] = window.api_key;
                params['app_id'] = this.row.encrypt_app_id;
                params['status'] = this.status;
                params['search'] = this.search;
                page_url = page_url || '/api/v1/dashboard/appSetting/manufacture';
                axios.get(page_url, {params:params}, config)
                    .then(res => {
                        this.dataLoading = false;
                        this.bulkLoading = false;
                        this.setupLoading = false;

                        if(res.data.statusCode == 200) {
                            this.count_all = res.data.data.all;
                            this.count_active = res.data.data.active;
                            this.count_inactive = res.data.data.inactive;
                            this.count_trash = res.data.data.trash;

                            this.rows = res.data.data.rows;

                            this.app[0] = res.data.data.app;
                            this.app_name = this.app[0].app_name;
                            this.icon = this.app[0].icon;
                            this.setup = this.app[0].setup;
                            this.app_id = this.app[0].id;

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
    await axios.get('/api/v1/dashboard/appSetting/manufacture/export/file?ids='+this.selected+'&api_key='+window.api_key);
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
                formData.append('app_id', this.app_id);
                formData.append('index', this.row.index);
                formData.append('value', this.row.value);

                // In case Update
                let req = '/api/v1/dashboard/appSetting/manufacture';
                if(this.edit) {
                    formData.append('_method', 'PUT');
                    req = '/api/v1/dashboard/appSetting/manufacture/'+this.row.id;
                }

                axios.post(req, formData, config)
                .then(res => {
                    this.btnLoading = false;
                    this.fetchData();
                    // Clear rows
                    this.row.index = '',
                    this.row.value = '';

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
                        message: 'Something went wrong, '+err,
                    });
                });
            },


            // Edit
            editRow(row) {
                this.edit = true;
                this.btn_status = 'Update',

                this.row.id = row.id;
                this.row.index = row.index;
                this.row.value = row.value;
            },

            // Change App Status
            appSetup(){
                this.setupLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.app_settings.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method','PUT');
                axios.post('/api/v1/dashboard/appSetting/manufacture/setup/'+this.app_id, formData, config)
                .then(res => {
                    if(res.data.statusCode == 200) {
                        // 
                        localStorage.setItem(res.data.data.app_name, res.data.data.app_status);
                        this.nv[res.data.data.app_name] = res.data.data.app_status;
                        if(res.data.data.app_status == 'show') {
                            var msg = 'App Set up Successfully';
                        } else {
                            var msg = 'App Disabled Successfully';
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
                        message: 'Something went wrong, '+err,
                    });
                });
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
                    'permission': 'admin.app_settings.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method','PUT');
                axios.post('/api/v1/dashboard/appSetting/manufacture/status/'+id, formData, config)
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
                        message: 'Something went wrong, '+err,
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
                    'permission': 'admin.app_settings.destroy'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method', 'DELETE');
                axios.post('/api/v1/dashboard/appSetting/manufacture/trash/'+id, formData, config)
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
                        message: 'Something went wrong, '+err,
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
                    'permission': 'admin.app_settings.destroy'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('_method', 'PATCH');
                axios.post('/api/v1/dashboard/appSetting/manufacture/restore/'+id, formData, config)
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
                        message: 'Something went wrong, '+err,
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
                        'permission': 'admin.app_settings.destroy'
                    };
                    const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                    let formData = new FormData();
                    formData.append('api_key', window.api_key);
                    formData.append('_method', 'DELETE');
                    axios.post('/api/v1/dashboard/appSetting/manufacture/'+id, formData, config)
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
                            message: 'Something went wrong, '+err,
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
