
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');

/**
 * Semantic UI
 */
require('../../../semantic/dist/semantic.min.js');

/**
 * moment
 * http://momentjs.com/docs/
 */
window.moment = require('moment');

/**
 * daterangepicker
 * https://www.npmjs.com/package/semantic-ui-daterangepicker
 */
require('./vendor/semantic-ui-daterangepicker.1.3.21.js');

/**
 * Semantic-UI-Calendar
 * https://github.com/mdehoog/Semantic-UI-Calendar
 */
require('./vendor/calendar.min.js');


/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = require('vue');
require('vue-resource');

/**
 * Vue-Router
 */
window.VueRouter = require('vue-router');
window.Vue.use(VueRouter);

/**
 * We'll register a HTTP interceptor to attach the "CSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 */

Vue.http.interceptors.push((request, next) => {
    request.headers['X-CSRF-TOKEN'] = sessionStorage.token;

    next();
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from "laravel-echo"

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
