import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store';
Vue.use(VueRouter)

export function createRouter() {
return new VueRouter({
	mode: 'history',
    base: '/',
    fallback: true,
	routes: [

	/** FRONTEND  **/
    
        // Coming Soon
	
	/** BACKEND **/

    // Auth
    { path: '/dashboard/login', name: 'login', component: require('./components/backend/auth/Login.vue').default, beforeEnter: requireUnAuth },
    { path: '/dashboard/forgot', name:'forgot', component: require('./components/backend/auth/Forgot.vue').default, beforeEnter: requireUnAuth },
    { path: '/dashboard/reset', name:'reset', component: require('./components/backend/auth/Reset.vue').default, beforeEnter: requireUnAuth },

    // Dashboard
    { path: '/dashboard/explore', name: 'dashboard', component: require('./components/backend/explore/App.vue').default, beforeEnter: requireAuth },

    // Posts
    { path: '/dashboard/posts', name: 'posts', component: require('./components/backend/posts/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/posts/status/:status', name: 'status-post', component: require('./components/backend/posts/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/posts/filter/:filter_by/:filter', name: 'filter-post', component: require('./components/backend/posts/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/posts/create', name: 'create-post', component: require('./components/backend/posts/Create.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/posts/:id/edit', name: 'edit-post', component: require('./components/backend/posts/Edit.vue').default, beforeEnter: requireAuth },

    // Categories
    { path: '/dashboard/categories', name: 'categories', component: require('./components/backend/categories/Root.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/categories/status/:status', name: 'status-category', component: require('./components/backend/categories/Root.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/categories/:subcategory', name: 'sub-categories', component: require('./components/backend/categories/Sub.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/categories/:subcategory/status/:status', name: 'status-sub-category', component: require('./components/backend/categories/Sub.vue').default, beforeEnter: requireAuth },

    // Tags
    { path: '/dashboard/tags', name: 'tags', component: require('./components/backend/tags/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/tags/status/:status', name: 'status-tag', component: require('./components/backend/tags/List.vue').default, beforeEnter: requireAuth },

    // Media
    { path: '/dashboard/media', name: 'media', component: require('./components/backend/media/List.vue').default, beforeEnter: requireAuth },

    // Pages
    { path: '/dashboard/pages', name: 'pages', component: require('./components/backend/pages/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/pages/status/:status', name: 'status-page', component: require('./components/backend/pages/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/pages/create', name: 'create-page', component: require('./components/backend/pages/Create.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/pages/:id/edit', name: 'edit-page', component: require('./components/backend/pages/Edit.vue').default, beforeEnter: requireAuth },

    // Users
    { path: '/dashboard/users', name: 'users', component: require('./components/backend/users/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/users/status/:status', name: 'status-user', component: require('./components/backend/users/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/users/create', name: 'create-user', component: require('./components/backend/users/Create.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/users/:id/edit', name: 'edit-user', component: require('./components/backend/users/Edit.vue').default, beforeEnter: requireAuth },

    // Sliders
    { path: '/dashboard/sliders', name: 'sliders', component: require('./components/backend/sliders/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/sliders/status/:status', name: 'status-slider', component: require('./components/backend/sliders/List.vue').default, beforeEnter: requireAuth },

    // Roles
    { path: '/dashboard/roles', name: 'roles', component: require('./components/backend/roles/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/roles/status/:status', name: 'status-role', component: require('./components/backend/roles/List.vue').default, beforeEnter: requireAuth },
    
    // Themes
    { path: '/dashboard/themes', name: 'themes', component: require('./components/backend/themes/List.vue').default, beforeEnter: requireAuth },

    // Reports
    { path: '/dashboard/reports', name: 'reports', component: require('./components/backend/reports/List.vue').default, beforeEnter: requireAuth },

    // Activity Logs
    { path: '/dashboard/activity-logs', name: 'logs', component: require('./components/backend/logs/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/activity-logs/status/:status', name: 'status-log', component: require('./components/backend/logs/List.vue').default, beforeEnter: requireAuth },

    // Messages
    { path: '/dashboard/messages', name: 'messages', component: require('./components/backend/messages/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/messages/status/:status', name: 'status-message', component: require('./components/backend/messages/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/messages/:id/show', name: 'show-message', component: require('./components/backend/messages/Show.vue').default, beforeEnter: requireAuth },

    // Cache Management
    { path: '/dashboard/cache-management', name: 'cache-management', component: require('./components/backend/caches/List.vue').default, beforeEnter: requireAuth },
    
    // Settings
    { path: '/dashboard/settings', name: 'settings', component: require('./components/backend/settings/List.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/settings/create', name: 'create-setting', component: require('./components/backend/settings/Create.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/settings/create/status/:status', name: 'status-setting', component: require('./components/backend/settings/Create.vue').default, beforeEnter: requireAuth },
    
    // Setting Manufacture
    { path: '/dashboard/settings/app/:appid', name: 'app-settings', component: require('./components/backend/settings/App.vue').default, beforeEnter: requireAuth },
    { path: '/dashboard/settings/app/:appid/status/:status', name: 'app-status-setting', component: require('./components/backend/settings/App.vue').default, beforeEnter: requireAuth },

    // Errors
    { path: '/dashboard/access-denied', name: 'access-denied', component: require('./components/backend/errors/AccessDenied.vue').default },
    { path: '/dashboard/internet-server-error', name: 'internet-server-error', component: require('./components/backend/errors/500.vue').default },
    { path: '/*', name: 'not-found', component: require('./components/backend/errors/404.vue').default },
	
    ]
  })
}

function requireAuth(to, from, next) {
  store.dispatch('fetchAccessToken');
  if (!store.state.accessToken) { next('/dashboard/login'); } 
  else { next(); }
}

function requireUnAuth(to, from, next) {
  store.dispatch('fetchAccessToken');
  if (store.state.accessToken) { next('/dashboard/explore'); } 
  else { next(); }
}