
import Vue from 'vue';
import axios from 'axios';
import ElementUI from 'element-ui';

import 'element-ui/lib/theme-chalk/index.css';

//require('./bootstrap');
window.Vue = require('vue');

Vue.prototype.$axios = axios;

Vue.use(ElementUI);

Vue.component('search', require('./components/search.vue').default);

new Vue({
    el: '#app'
});
