<template>
    <div>
        <navigation></navigation>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    /**
     * Create a event-central
     */
    window.bus = new Vue();

    /**
     * Define global components
     */
    Vue.component('loading', require('./components/Loading.vue'));

    /**
     * Define global directives
     */
    Vue.directive('tooltip', require('./directives/tooltip.vue'));
    Vue.directive('daterangepicker', require('./directives/daterangepicker.vue'));

    /**
     * Export the root-instance options
     */
    import Navigation from './components/Nav.vue'
    import Auth from './mixins/Auth.vue'
    import ErrorHandler from './mixins/ErrorHandler.vue'
    import SuccessHandler from './mixins/SuccessHandler.vue'
    import SweetAlert from './mixins/SweetAlert.vue'
    export default {

        name: 'App',

        components: { Navigation },

        mixins: [ Auth, SweetAlert, ErrorHandler, SuccessHandler ],

        data: function(){
            return {
                loading: true,

                settings: {
                    title: 'ReturButikenOnline'
                }
            }
        },

        created: function(){
            // Log a reference to this App
            console.log(this)
        }

    }
</script>
