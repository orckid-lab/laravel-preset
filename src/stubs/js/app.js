import 'core-js';
import vueScrollTo from 'vue-scrollto';
import components from './components/_index';
import pageEvents from './modules/page-events';

require('./bootstrap');

window.Vue = require('vue');
Vue.use(require('keen-ui'));
Vue.use(vueScrollTo);

/* eslint-disable no-unused-vars */
const app = new Vue({
  el: '#app',
  data() {
    return {
      mobileMenu: false
    };
  },
  components,
  mounted() {
    pageEvents();
  },
  methods: {
    openModal(ref) {
      this.$refs[ref].open();
      this.modals.push(ref);
    },
    closeModal(ref) {
      this.modals.splice(this.modals.indexOf(ref));
      this.$refs[ref].close();
    },
    onModalClose(ref) {
      this.modals.splice(this.modals.indexOf(ref));
    },
    openMobileMenu() {
      this.mobileMenu = !this.mobileMenu;
    }
  }
});
