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
    window.bus = new Vue()

    # Define global components
    Vue.component 'loading', require './components/Loading.vue'

    # Define global directives
    Vue.directive 'tooltip', require './directives/tooltip.vue'
    Vue.directive 'accordion', require './directives/accordion.vue'
    Vue.directive 'dropdown', require './directives/dropdown.vue'
    Vue.directive 'checkbox', require './directives/checkbox.vue'
    Vue.directive 'focus', require './directives/focus.vue'
    Vue.directive 'item', require './directives/item.vue'
    Vue.directive 'image', require './directives/image.vue'
    Vue.directive 'calendar', require './directives/calendar.vue'
    Vue.directive 'swipe', require './directives/swipe.vue'

    # Define global mixins
    Vue.mixin require './mixins/GoBack.vue'

    # Export the root-instance options
    module.exports =
        name: 'App'

        components:
            Navigation: require './components/Nav.vue'
            SemanticDialog: require './components/SemanticDialog.vue'

        mixins: [
            require './mixins/Auth.vue'
            require './mixins/ErrorHandler.vue'
            require './mixins/SuccessHandler.vue'
        ]

        data: ->
            loading: true
            settings:
                title: 'ReturButikenOnline'

        created: ->
            # Log a reference to this App
            console.log this

</script>

<style lang="scss">
    @media screen and (max-width: 768px) {
        .ui.container {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        .ui.attached .ui.dividing.header {
            margin-left: -6px !important;
            margin-right: -6px !important;
        }
    }
</style>
