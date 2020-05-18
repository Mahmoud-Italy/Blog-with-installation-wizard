<template>
    <div class="">
        <Header v-on:headerToChild="onSearchSubmit"></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <!-- Content -->
            <div class="u-content">

                <!-- Content Body -->
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">Activity Logs

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
                            <li class="breadcrumb-item active" aria-current="page">Activity Logs</li>
                        </ol>
                    <!-- End Breadcrumb -->

                    <!-- Builk Action button -->
                    <div class="pull-rights ui-mt-50 pull-right ">
                        <div class="dropdown display-flex-inline">

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
                                        worksheet = "Logs"
                                        name = "Logs.xls">Excel
                                    </download-excel>
                                    <download-excel
                                        class = "dropdown-item cursor-pointer"
                                        :fetch = "fetchExport"
                                        :fields = "exp.json_fields"
                                        :before-generate = "startDownload"
                                        :before-finish = "finishDownload"
                                        type = "csv"
                                        worksheet = "Logs"
                                        name = "Logs.xls">CSV
                                    </download-excel>
                                    <a class="dropdown-item" href="javascript:;" v-print="'#printMe'">Print</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Builk Action button -->
                </nav>



                <!-- Card -->
                <div class="card">
                     <header class="card-header">
                            
                            <router-link class="pg-hd"
                                    :to="{ name: 'logs' }">All
                            </router-link> 
                            <span class="pg-hd no-decoration f14"> ({{count_all}}) </span>

                            <!-- Show Entries -->
                            <div class="dropdown pull-right ui-mt-10">
                                <button type="button" class="btn btn-light btn-sm dropdown-toggle" 
                                    id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span>Show </span>
                                    <span v-if="!showLoading"> {{this.show}}</span>
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

                     </header>

                    <!-- Crad Body -->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table id="printMe" class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">
                                            <div class="custom-control custom-checkbox">
                                                <input id="expBox0" class="custom-control-input" type="checkbox"
                                                    v-model="selectAll" @click="select">
                                                <label class="custom-control-label" for="expBox0"></label>
                                            </div>
                                        </th>
                                        <th style="width:40%">Log</th>
                                        <th class="text-center">Action</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <!-- Loading... -->
                                <tr v-if="dataLoading">
                                    <td colspan="5" class="text-center">
                                        <div class="spinner-grow" role="status">
                                          <span class="sr-only">Loading...</span>
                                        </div>
                                    </td>
                                </tr>
                                <!-- End Loading... -->

                                <!-- No data found -->
                                <tr v-if="!dataLoading && !rows.length">
                                    <td colspan="5" class="text-center">
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

                                   <!-- Log -->
                                   <td class="font-weight-semi-bold">
                                        {{ row.log }}
                                    </td>
                                   <!-- End Log -->

                                   <!-- Action -->
                                   <td class="font-weight-semi-bold text-center">
                                        <span class="badge badge-md badge-pill badge-danger-soft">
                                            {{ row.action }}
                                        </span>
                                    </td>
                                   <!-- End Action -->

                                   <!-- User -->
                                   <td class="font-weight-semi-bold text-center">
                                        <span v-if="!row.user_id" class="text-center"> - </span>
                                        <div v-if="row.user_id" class="align-items-center">
                                            <img class="u-avatar-xs rounded-circle mr-2"
                                                :src="row.avatar">
                                            <span class="media-body">{{ row.username }}</span>
                                        </div>
                                    </td>
                                   <!-- End User -->
                                    
                                   <!-- Date -->
                                    <td v-html="(row.deleted_at) ? row.deleted_at : 
                                                (row.updated_at) ? row.updated_at : row.created_at"
                                        class="font-weight-semi-bold text-center f12">
                                    </td>
                                    <!-- End Date -->
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
                                        <th>Log</th>
                                        <th class="text-center">Action</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Date</th>
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
        name: 'Logs',
        components: {
            Header,
            Navigation,
            Footer
        },
        data(){
            return {
                // for Export to Excel, CSV
                exp: {
                   json_fields: {
                        'id': 'id',
                        'log': 'log',
                        'action': 'action',
                        'username': 'username',
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

                count_all: 0,

                selected: [],
                selectAll: false,
                plural: '',

                dataLoading: true,
                showLoading: false,
                exportLoading: false,
                something_went_wrong: false,
                rows: [],
                show: 10,
                pagination: {},
            }
        },
        mounted() {},
        created() {
            // AccessToken & Roles
            if(localStorage.getItem('accessToken')) {
                this.auth.accessToken = localStorage.getItem('accessToken');
            }
            if(localStorage.getItem('role')) {
                this.auth.role = localStorage.getItem('role');
            }

            //
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

            // Fetch Data
            fetchData(page_url, loading=false) {
                if(loading) { this.dataLoading = true; }
                this.something_went_wrong = false;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.logs.index'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};  
                let vm = this;
                let params = {};
                params['api_key'] = window.api_key;
                params['search'] = this.search;
                params['show'] = this.show;
                page_url = page_url || '/api/v1/dashboard/logs';
                axios.get(page_url, {params:params}, config)
                    .then(res => {
                        this.dataLoading = false;
                        this.showLoading = false;
                        this.exportLoading = false;

                        if (res.data.statusCode == 401) {
                            this.$router.push({name: 'access-denied'});

                        } else if(res.data.statusCode == 200) {
                            this.count_all = res.data.data.all;
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
                await axios.get('/api/v1/dashboard/logs/export/file?ids='+this.selected+'&api_key='+window.api_key);
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


            // ON Select
            select() {
                this.selected = [];
                if (!this.selectAll) {
                    for (let i in this.rows) {
                        this.selected.push(this.rows[i].id);
                    }
                }
            },


        },
    }
</script>
