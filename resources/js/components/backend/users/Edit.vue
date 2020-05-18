<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <!-- Content -->
            <div class="u-content">
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">Users

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
                            <li class="breadcrumb-item"><router-link :to="{ name: 'users' }">Users</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="inputText2">Email</label>
                                            <input class="form-control" 
                                                    id="inputText2" 
                                                    type="email" 
                                                    v-model="row.email"
                                                    required="">
                                        </div>
                                        <!-- End Email -->

                                        <!-- Password -->
                                        <div class="form-group">
                                            <label for="inputText3">Password</label>
                                            <input class="form-control" 
                                                    id="inputText3" 
                                                    type="password"
                                                    autocomplete="off"
                                                    placeholder="*******************" 
                                                    v-model="row.password">
                                        </div>
                                        <!-- End Password -->

                                        <!-- Confirm Password -->
                                        <div class="form-group">
                                            <label for="inputText4">Confirm Password</label>
                                            <input class="form-control" 
                                                    id="inputText4" 
                                                    type="password"
                                                    placeholder="*******************" 
                                                    autocomplete="off">
                                        </div>
                                        <!-- End Confirm Password -->

                                        <!-- Roles -->
                                        <div class="form-group">
                                            <label for="inputText5">Role</label>
                                            <select class="form-control ui-h60"
                                                id="inputText5"
                                                v-model="row.role_id"
                                                required="">
                                                <option v-if="roleLoading" value="">Loading...</option>
                                                <option v-if="!roleLoading" v-for="(ro, index) in roles" 
                                                        :key="index" 
                                                        :value="ro.id">
                                                    {{ ro.role }}
                                                </option>
                                            </select>
                                        </div>
                                        <!-- End Roles -->

                                    </div>
                                    <!-- End Card Body -->

                                    <!-- Buttons -->
                                    <div class="card-footer">
                                        <div class="form-group">

                                            <button class="btn btn-primary" :disabled="btnLoading">
                                                <span v-if="btnLoading">
                                                    <span class="spinner-grow spinner-grow-sm mr-1" 
                                                        role="status" aria-hidden="true">
                                                    </span>Loading...
                                                </span>
                                                <span v-if="!btnLoading" class="ti-save"></span>
                                                <span v-if="!btnLoading"> Update User</span>
                                            </button>

                                            <button type="button" class="btn btn-warning" 
                                                :disabled="btnLoading" 
                                                @click="cancel">
                                                <span class="ti-back-left"></span>
                                                <span>Cancel</span>
                                            </button>

                                        </div>
                                    </div>
                                    <!-- End Buttons -->
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card mb-5">
                                    <div class="card-body">

                                        <!-- Image -->
                                        <div class="form-group">
                                            <label for="InputImage">Profile Image</label>
                                            <img v-if="row.preview" 
                                                    :src="row.preview" 
                                                    class="ui-image">
                                            <input class="form-control" 
                                                    id="InputImage" 
                                                    type="file" 
                                                    ref="myDropify" 
                                                    v-on:change="onImageChange" />
                                        </div>
                                        <!-- End Image -->

                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">

                                        <!-- Permissions -->
                                        <div v-if="permissionLoading" class="text-center">
                                            <div class="spinner-grow" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                        <div v-if="!permissionLoading" 
                                            class="form-group" 
                                            v-for="(row, index) in permissions" :key="index">
                                            <h4 class="text-capitalize">{{index.replace('-','\/s+/g')}}</h4>
                                            <p></p>
                                            <label v-for="(semi, indx) in row"
                                                    :key="indx">
                                            <input type="checkbox"
                                                    @change="isChecked($event, row[indx].id)"
                                                    :id="row[indx].id"
                                                    :value="row[indx].id"
                                                    :checked="(permission_ids.indexOf(row[indx].id) !== -1) ? true : ''">
                                                    {{row[indx].name}}&nbsp;&nbsp;&nbsp;&nbsp;
                                            </label>
                                            <p><hr></p>
                                        </div>
                                        <!-- End Permissions -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
        name: 'EditUser',
        components: {
            Header,
            Navigation,
            Footer
        },
        data(){
            return {
                // Auth
                auth: {
                    role: '',
                    accessToken: '',
                },
                //
                row: {
                    user_id: '',
                    preview: '',
                    image: '',

                    name: '',
                    email: '',
                    password: '',
                    role_id: '',
                    permission_id: [],
                },
                permission_ids: [],
                roles: [],
                roleLoading: false,
                permissions: [],
                permissionLoading: false,
                btnLoading: false,
                pgLoading: false,
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
                this.row.user_id = this.$route.params.id;
            }

            this.fetchRow();
            this.fetchRoles();
            this.fetchPermissions();
        },
        methods: {

            // fetch Row
            fetchRow(){
                this.pgLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.users.edit'
                };
                const config = { headers: { 'Content-Type': 'application/json' }};
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = 'active';
                axios.get('/api/v1/dashboard/users/'+this.row.user_id, {params:params}, config)
                    .then(res => {
                        this.pgLoading = false;
                        this.row.preview = res.data.data.rows.image;
                        this.row.name = res.data.data.rows.name;
                        this.row.email = res.data.data.rows.email;
                        this.row.role_id = res.data.data.rows.role_id;
                        this.row.permission_id = res.data.data.permission_ids;
                        this.permission_ids = res.data.data.permission_ids;
                    })
                    .catch(err => {});
            },

            // fetch Roles
            fetchRoles(){
                this.roleLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.roles.index'
                };
                const config = { headers: { 'Content-Type': 'application/json' }};
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = 'active';
                axios.get('/api/v1/dashboard/roles', {params:params}, config)
                    .then(res => {
                        this.roleLoading = false;
                        this.roles = res.data.data;
                    })
                    .catch(err => {});
            },

            // fetch Permissions
            fetchPermissions(){
                this.permissionLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken
                };
                const config = { headers: { 'Content-Type': 'application/json' }};
                let params = {};
                params['api_key'] = window.api_key;
                params['status'] = 'active';
                axios.get('/api/v1/dashboard/users/get/permissions', {params:params}, config)
                    .then(res => {
                        this.permissionLoading = false;
                        this.permissions = res.data.data;
                    })
                    .catch(err => {});
            },

            // Update
            updateRow(){
                this.btnLoading = true;
                axios.defaults.headers.common = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrf_token,
                    'accesstoken': this.auth.accessToken,
                    'permission': 'admin.users.edit'
                };
                const config = { headers: { 'Content-Type': 'multipart/form-data' }};
                let formData = new FormData();
                formData.append('api_key', window.api_key);
                formData.append('image', this.row.image);
                formData.append('name', this.row.name);
                formData.append('email', this.row.email);
                formData.append('password', this.row.password);
                formData.append('role_id', this.row.role_id);
                formData.append('permission_id', this.row.permission_id);
                    // fell free to add any new value

                formData.append('_method', 'PUT');
                axios.post('/api/v1/dashboard/users/'+this.row.user_id, formData, config)
                .then(res => {
                    this.btnLoading = false;
                    this.isSubmit = true;

                    if(res.data.statusCode == 201) {

                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'User Added Successfully',
                        });
                        this.$router.push({ name: 'users' });

                    } else if(res.data.statusCode == 200) {

                        izitoast.success({
                            icon: 'ti-check',
                            title: 'Great job,',
                            message: 'User Updated Successfully',
                        });
                        this.$router.push({ name: 'users' });
                        
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

            // get id from Box Checked
            isChecked($event, id){
                if($event.target.checked) {
                    this.row.permission_id.push(id)

                } else {
                    // remove exists array id instaed of event
                    if(this.row.permission_id.indexOf(id) !== -1) {
                        this.row.permission_id.splice(this.row.permission_id.indexOf(id), 1)
                    } else {
                        this.row.permission_id.splice(this.row.permission_id.indexOf(event), 1)
                    }
                }
            },

            // Cancel
            cancel(){
                if(confirm('Are You Sure?')) {
                    this.$router.push({ name: 'users' });
                    this.isSubmit = true;
                }
            },

        },

        //
        beforeRouteLeave (to, from, next) { 
            if(this.row.name && !this.isSubmit) {
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
