import 'core-js';
import Vue from 'vue';
import vueScrollTo from 'vue-scrollto';
import orckid from 'vue-helpers';
import components from './components/_index';
import pageEvents from './modules/page-events';

require('./bootstrap');

window.Vue = Vue;
Vue.use(vueScrollTo);
Vue.use(orckid);

/* eslint-disable no-unused-vars */
const app = new Vue({
    el: '#app',
    data: {},
    components,
    mounted () {
        pageEvents();
    },
    methods: {
    }
});
