<template lang="pug">
    div.pusher( v-if="settings.lang" )
        navigation
        semantic-dialog
        router-view.ui.padded.container.segment
        footer-component( ":lang"="settings.lang" ":language_lib"="language_lib" )
</template>

<script lang="coffee">

    # Create a event-central
    window.bus = new Vue()

    # Define global components
    Vue.component 'loading', require './components/Loading.vue'

    # Define global directives
    Vue.directive 'tooltip', require './directives/tooltip.vue'
    Vue.directive 'dropdown', require './directives/dropdown.vue'
    Vue.directive 'checkbox', require './directives/checkbox.vue'
    Vue.directive 'focus', require './directives/focus.vue'
    Vue.directive 'item', require './directives/item.vue'
    Vue.directive 'images', require './directives/images.vue'
    Vue.directive 'calendar', require './directives/calendar.vue'
    Vue.directive 'swipe', require './directives/swipe.vue'

    # Define global mixins
    Vue.mixin require './mixins/GoBack.vue'
    Vue.mixin require './mixins/Translation.vue'

    # Export the root-instance options
    module.exports =
        name: 'App'

        components:
            Navigation: require './components/Nav.vue'
            SemanticDialog: require './components/SemanticDialog.vue'
            FooterComponent: require './components/Footer.vue'

        mixins: [
            require './mixins/Auth.vue'
            require './mixins/ErrorHandler.vue'
            require './mixins/SuccessHandler.vue'
            require './mixins/ArticleNumber.vue'
            require './mixins/LangChoice.vue'
        ]

        data: ->
            loading: true
            settings:
                title: 'ReturButikenOnline'
                lang: false

        created: ->
            # Log a reference to this App
            if process.env.NODE_ENV == "development"
                console.debug window.App = this
</script>
