
import Vue from 'vue';
import axios from 'axios';
import moment from 'moment';

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import ElementUI_lang from 'element-ui/lib/locale/lang/en'
import ElementUI_locale from 'element-ui/lib/locale'

Vue.use(require('vue-moment'));
Vue.prototype.moment = moment;

ElementUI_locale.use(ElementUI_lang)

//require('./bootstrap');
window.Vue = require('vue');

Vue.prototype.$axios = axios;

Vue.use(ElementUI);

Vue.component('search', require('./components/search.vue').default);

Vue.filter('dateTimeFormat',function(dateStr, pattern='DD.MM.YYYY HH:mm:ss'){
    return moment(dateStr).format(pattern);
});

new Vue({
    el: '#app'
});
