<template lang="pug">
    div.pusher( v-if="settings.lang" )
        navigation
        semantic-dialog
        router-view.ui.padded.container.segment
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

        mixins: [
            require './mixins/Debug.vue'
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
            if @DEBUG
                console.log window.App = this

</script>
