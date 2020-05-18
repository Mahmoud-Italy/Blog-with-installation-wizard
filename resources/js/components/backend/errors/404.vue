<template>
    <div class="">
        
        <!-- Header (Topbar) -->
        <header class="u-header u-header--error-page">
            <!-- Header Logo -->
            <router-link :to="{ name: 'dashboard' }" class="u-header-logo">
                <img class="u-header-logo__icon" src="/assets/backend/svg/logo-mini.svg" alt="404">
                <img class="u-header-logo__text ml-1" src="/assets/backend/svg/logo-text-dark.svg" alt="404" width="140">
            </router-link>
            <!-- End Header Logo -->
        </header>
        <!-- End Header (Topbar) -->

        <!-- Main -->
        <main class="u-error-content-wrap">
            <div class="u-error-content container text-center my-auto">
                <h1 class="u-error__title">404</h1>
                <h2 class="u-error__sub-title">Page not found</h2>
                <h4 class="font-weight-semi-bold mb-0">
                    <router-link :to="{ name: 'dashboard' }">Return back</router-link>
                </h4>

                <!-- SVG Shapes -->
                <figure class="u-shape u-shape-top-left">
                    <img src="/assets/backend/svg/shapes/shape-1.svg" alt="404">
                </figure>
                <figure class="u-shape u-shape-top-right">
                    <img src="/assets/backend/svg/shapes/shape-2.svg" alt="404">
                </figure>
                <figure class="u-shape u-shape-bottom-left">
                    <img src="/assets/backend/svg/shapes/shape-3.svg" alt="404">
                </figure>
                <figure class="u-shape u-shape-bottom-right">
                    <img src="/assets/backend/svg/shapes/shape-4.svg" alt="404">
                </figure>
                <!-- End SVG Shapes -->

            </div>
        </main>
        <!-- End Main -->

    </div>
</template>

<script>
    export default {
        name: '404',
        mounted() {
            //
        }
    }
</script>