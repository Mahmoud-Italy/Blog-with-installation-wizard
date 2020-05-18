<template>
    <div class="">
        <Header></Header>

        <!-- Main -->
        <main class="u-main">
            <Navigation></Navigation>

            <!-- Content -->
            <div class="u-content">

                <!-- Content Body -->
                <div class="u-body min-h-700">
                    <h1 class="h2 mb-2">Reports</h1>

                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reports</li>
                        </ol>
                        <!-- Build Action button -->
                        <div class="pull-rights ui-mt-50 pull-right ">
                        
                        <div class="dropdown display-flex-inline">
                            <div class="dropdown ui-mr5">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">Bulk Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Publish</a>
                                    <a class="dropdown-item" href="#">Draft</a>
                                    <a class="dropdown-item" href="#">Move to Trash</a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Excel</a>
                                    <a class="dropdown-item" href="#">CSV</a>
                                    <a class="dropdown-item" href="#">Print</a>
                                </div>
                            </div>
                        </div>

                        </div>
                    </nav>
                    <!-- End Breadcrumb -->



                <!-- Card -->
                <div class="card">
                    <!-- Card Header -->
                    <header class="card-header">
                        <h2 class="h4 card-header-title">
                            <a href="#" class="pg-hd active">All</a> 
                                <span class="pg-hd no-decoration f14"> (10) </span> &nbsp;|&nbsp; 
                            <a href="#" class="pg-hd">Trash</a>
                                <span class="pg-hd no-decoration f14"> (0) </span>
                        </h2>
                    </header>
                    <!-- End Card Header -->

                    <!-- Crad Body -->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input id="checkBox0" class="custom-control-input" type="checkbox">
                                                <label class="custom-control-label" for="checkBox0"></label>
                                            </div>
                                        </th>
                                        <th style="width:50%">Name
                                            <span class="ti-exchange-vertical"></span>
                                        </th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Roles</th>
                                        <th class="text-center">Posts</th>
                                        <th class="text-center">Date
                                            <span class="ti-exchange-vertical"></span>
                                        </th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                <tr v-for="i in 1" class="f14">
                                    <td class="font-weight-semi-bold">
                                        <div class="custom-control custom-checkbox">
                                            <input :id="'exampleCheck'+i" class="custom-control-input" type="checkbox">
                                            <label class="custom-control-label" :for="'exampleCheck'+i"></label>
                                        </div>
                                    </td>
                                    <td class="font-weight-semi-bold">
                                        <div class="media align-items-center">
                                            <img class="u-avatar-xs rounded-circle mr-3" 
                                                src="/modules/backend/img-temp/avatars/img1.jpg" alt="Image description">
                                            <span class="media-body">Dale Mack</span>
                                        </div>
                                    </td>
                                    <td class="font-weight-semi-bold text-center">
                                        email@gmail.com
                                    </td>
                                    <td class="font-weight-semi-bold text-center">
                                        <a href="#" class="text-decoration-hover">
                                            <span class="badge badge-md badge-pill badge-danger-soft">
                                                Administrator
                                            </span>
                                        </a>
                                    </td>
                                    <td class="font-weight-semi-bold text-center f12">0</td>
                                    <td class="font-weight-semi-bold text-center f12">Registered<br/>9 min ago</td>
                                    <td class="text-center">
                                    <!-- Actions -->
                                    <div class="dropdown">
                                        <a id="tableWithImage1MenuInvoker" class="u-icon-sm link-muted" href="#" role="button"    aria-haspopup="true" aria-expanded="false"
                                            data-toggle="dropdown"
                                            data-offset="8">
                                            <span class="ti-more"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" style="width: 150px;">
                                            <div class="card border-0 p-3">
                                                <ul class="list-unstyled mb-0">
                                                    <li class="mb-3">
                                                        <a class="d-block link-dark" href="#">Edit</a>
                                                    </li>
                                                    <li class="mb-3">
                                                        <a class="d-block link-dark" href="#">Quick View</a>
                                                    </li>
                                                    <li>
                                                        <a class="d-block link-dark" href="#">Move to Trash</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Actions -->
                                    </td>
                                </tr>
                                
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th class="text-center">Author</th>
                                        <th class="text-center">Categories</th>
                                        <th class="text-center">Tags</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </tfoot>

                        </table>
                    </div>


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
    export default {
        name: 'App',
        components: {
            Header,
            Navigation,
            Footer
        },
        mounted() {
            //
        }
    }
</script>
