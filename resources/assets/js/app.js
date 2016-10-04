
/**
 * Load JavaScript dependencies
 */
require('./bootstrap');

/**
 * Create a vue-router with configuration
 */
const router = new VueRouter( require('./VueRouterConfig') );

/**
 * Extend the Vue-constructor using our App
 */
const App = Vue.extend( require('./App.vue') );

/**
 * Create a new root instance, enable vue-router and mount it on target
 */
new App({
    router
}).$mount('#app');
