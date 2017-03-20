
/**
 * Load JavaScript dependencies
 */
require('./bootstrap');

/**
 * Extend the Vue-constructor using our App
 */
const Butik = Vue.extend( require('../vue/Butik.vue') );

/**
 * Create a new root instance, enable vue-router and mount it on target
 */
new Butik().$mount('#app');
