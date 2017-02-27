
//window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');

/**
 * Semantic UI
 * Require used components
 */
//require('semantic-ui-css/semantic.min.js');
require('semantic-ui-css/components/checkbox.min.js');
require('semantic-ui-css/components/dropdown.min.js');
require('semantic-ui-css/components/popup.min.js');
require('semantic-ui-css/components/sidebar.min.js');
require('semantic-ui-css/components/transition.min.js');
require('semantic-ui-css/components/dimmer.min.js');
require('semantic-ui-css/components/modal.min.js');
require('semantic-ui-css/components/form.min.js');

/**
 * moment
 * http://momentjs.com/docs/
 */
window.moment = require('moment/min/moment.min.js');

/**
 * Semantic-UI-Calendar
 * https://github.com/mdehoog/Semantic-UI-Calendar
 */
require('semantic-ui-calendar/dist/calendar.min.js');


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
 * This will try to parse every response.data into JSON if not done allready.
 */
Vue.http.interceptors.push((request, next) => {
    next((response) => {
        if (typeof response.data != 'object'){
            try{
                response.data = JSON.parse(response.data);
            } catch (e){
            }
        }
    });
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
