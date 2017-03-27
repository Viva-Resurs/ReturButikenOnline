
/**
 * Load JavaScript dependencies
 */
require('./bootstrap');

/**
 * Extend the Vue-constructor using our Shop
 */
const Shop = Vue.extend( require('../vue/Shop.vue') );

/**
 * Create a new root instance and mount it on target
 */
new Shop().$mount('#app');
