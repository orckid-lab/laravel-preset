import 'core-js';
import vueScrollTo from 'vue-scrollto';

require('./bootstrap');

require('./components');

window.Vue = require('vue');
Vue.use(require('keen-ui'));
Vue.use(vueScrollTo);

/* eslint-disable no-unused-vars */
const app = new Vue({
  el: '#app'
});
