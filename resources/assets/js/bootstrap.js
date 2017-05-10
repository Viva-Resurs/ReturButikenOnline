
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery/dist/jquery.js');


/**
 * JQuery UI
 */
require('jquery-ui-dist/jquery-ui.min.js');

/**
 * JQuery UI Touch Punch
 * Lib used for setting elements draggable on mobile devices.
 */
require('jquery-ui-touch-punch/jquery.ui.touch-punch.min.js');

/**
 * Semantic UI
 * Require used components
 */
//require('semantic-ui-css/semantic.min.js');
require('semantic-ui-css/components/checkbox.js');
require('semantic-ui-css/components/dropdown.js');
require('semantic-ui-css/components/popup.js');
require('semantic-ui-css/components/sidebar.js');
require('semantic-ui-css/components/transition.js');
require('semantic-ui-css/components/dimmer.js');
require('semantic-ui-css/components/modal.js');
require('semantic-ui-css/components/form.js');

/**
 * moment
 * http://momentjs.com/docs/
 */
window.moment = require('moment');

/**
 * Semantic-UI-Calendar
 * https://github.com/mdehoog/Semantic-UI-Calendar
 */
require('semantic-ui-calendar/dist/calendar.js');



/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = require('vue');
require('vue-resource');

if (process.env.BUILD == "ui"){
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
    Vue.http.interceptors.push(function(request, next){
        request.headers['X-CSRF-TOKEN'] = sessionStorage.token;
        next();
    });
}

/**
 * This will try to parse every response.data into JSON if not done allready.
 */
Vue.http.interceptors.push(function(request, next){
    next(function(response){
        if (typeof response.data != 'object'){
            try{
                response.data = JSON.parse(response.data);
            } catch (e){
            }
        }
    });
});

/**
 * This will add Internal token to every request
 */
Vue.http.interceptors.push(function(request, next){
    hash = location.hash;
    if (hash.indexOf('#')>-1)
        hash = hash.substr(1);
    request.headers['INTERNAL-TOKEN'] = hash;
    next();
});
