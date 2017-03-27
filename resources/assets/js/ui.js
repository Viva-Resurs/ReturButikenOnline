
/**
 * Load JavaScript dependencies
 */
require('./bootstrap');

/**
 * Create a vue-router with configuration
 */
const router = new VueRouter( require('./VueRouterConfig') );

/**
 * Extend the Vue-constructor using our User Interface
 */
const Ui = Vue.extend( require('../vue/Ui.vue') );

/**
 * Create a new root instance, enable vue-router and mount it on target
 */
new Ui({
    router: router
}).$mount('#app');
