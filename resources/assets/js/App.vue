<template lang="pug">
    div.pusher
        navigation
        semantic-dialog
        div.ui.container
            router-view
        div.offset_container
        div.ui.page.dimmer
</template>

<script lang="coffee">

    # Create a event-central
    window.bus = new Vue();

    # Define global components
    Vue.component('loading', require('./components/Loading.vue'));

    # Define global directives
    Vue.directive('tooltip', require('./directives/tooltip.vue'));
    Vue.directive('accordion', require('./directives/accordion.vue'));
    Vue.directive('dropdown', require('./directives/dropdown.vue'));
    Vue.directive('checkbox', require('./directives/checkbox.vue'));
    Vue.directive('focus', require('./directives/focus.vue'));
    Vue.directive('item', require('./directives/item.vue'));
    Vue.directive('image', require('./directives/image.vue'));

    # Export the root-instance options
    Navigation = require './components/Nav.vue'
    SemanticDialog = require './components/SemanticDialog.vue'

    Auth = require './mixins/Auth.vue'
    ErrorHandler = require './mixins/ErrorHandler.vue'
    SuccessHandler = require './mixins/SuccessHandler.vue'
    SweetAlert = require './mixins/SweetAlert.vue'

    module.exports = {

        name: 'App',

        components: { Navigation, SemanticDialog },

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
