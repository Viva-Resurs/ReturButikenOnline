<template lang="pug">
    div.pusher( v-if="settings.lang")
        navigation#navbarcontainer
        semantic-dialog
        router-view.ui.container.segment#rootcontainer
        footer-component#footercontainer( ":lang"="settings.lang" ":language_lib"="language_lib" )
</template>

<script lang="coffee">

    # Create a event-central
    window.bus = new Vue()

    # Define global components
    Vue.component 'loading', require './components/Loading.vue'

    # Define global directives
    Vue.directive 'tooltip', require './directives/tooltip.vue'
    Vue.directive 'dropdown', require './directives/dropdown.vue'
    Vue.directive 'focus', require './directives/focus.vue'
    Vue.directive 'item', require './directives/item.vue'
    Vue.directive 'images', require './directives/images.vue'
    Vue.directive 'calendar', require './directives/calendar.vue'
    Vue.directive 'swipe', require './directives/swipe.vue'
    Vue.directive 'image', require './directives/image.vue'

    # Define global mixins
    Vue.mixin require './mixins/GoBack.vue'
    Vue.mixin require './mixins/Translation.vue'
    Vue.mixin require './mixins/ScreenSize.vue'
    Vue.mixin require './mixins/From.vue'

    # Export the root-instance options
    module.exports =
        name: 'Ui'

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
