require('./bootstrap');
   
window.Vue = require('vue');
import VueRouter from 'vue-router'
Vue.use(VueRouter)

import {createRouter} from './routes';

import store from './store';
import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);

import { i18n } from './lang.js';

/* Vue Notificaitons  */
import VueNotifications from 'vue-notifications';
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
Vue.use(VueNotifications, iziToast);
/* End Vue Notificaitons  */

/* Vue Download Json Excel  */
import JsonExcel from 'vue-json-excel'; 
Vue.component('downloadExcel', JsonExcel);
/* End Vue Download Json Excel */

/* Copy to Clipboard */
import Vue from 'vue';
import VueClipboard from 'vue-clipboard2';
VueClipboard.config.autoSetContainer = true; // add this line
Vue.use(VueClipboard);
/* End Copy to Clipboard */

/* Vue Print-nb */
import Print from 'vue-print-nb';
Vue.use(Print);
/* End Vue Print-nb */

/* VueApexCharts */
import VueApexCharts from 'vue-apexcharts';
Vue.use(VueApexCharts);
Vue.component('apexchart', VueApexCharts);
/* End VueApexCharts */

Vue.config.productionTip = false;

const router = createRouter();

// API KEY
window.api_key = '$2y10oru771fc5qgV6M0EI8e46uKN5qdjRgh6qz91jd';

const app = new Vue({
  i18n,
  router,
}).$mount('#app')
