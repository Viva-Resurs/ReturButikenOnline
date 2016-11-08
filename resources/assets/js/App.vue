<template>
    <div>
        <navigation></navigation>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script lang="coffee">

    # Create a event-central
    window.bus = new Vue();

    # Define global components
    Vue.component('loading', require('./components/Loading.vue'));

    # Define global directives
    Vue.directive('tooltip', require('./directives/tooltip.vue'));
    Vue.directive('dropdown', require('./directives/dropdown.vue'));
    Vue.directive('daterangepicker', require('./directives/daterangepicker.vue'));
    Vue.directive('focus', require('./directives/focus.vue'));

    # Export the root-instance options
    Navigation = require './components/Nav.vue'

    Auth = require './mixins/Auth.vue'
    ErrorHandler = require './mixins/ErrorHandler.vue'
    SuccessHandler = require './mixins/SuccessHandler.vue'
    SweetAlert = require './mixins/SweetAlert.vue'

    module.exports = {

        name: 'App',

        components: { Navigation },

        mixins: [ Auth, SweetAlert, ErrorHandler, SuccessHandler ]

        data: ->
            loading: true,

            settings:
                title: 'ReturButikenOnline'

        created: ->
            # Log a reference to this App
            console.log(this)


    }
</script>
