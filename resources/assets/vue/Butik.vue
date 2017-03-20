<template lang="pug">
    div.pusher( v-if="settings.lang" )
        div.ui.padded.container.segment
            loading( v-if="$root.loading" )
            div.ui.grid.one.column( v-else="" )
                div.column
                    div.ui.dividing.header.fluid {{ translate('article.header') }}
                article-desktop-list.column.computer.only(
                    ":toolsRow"=`[
                          $options.components.PublishInterval,
                          $options.components.BiddingInterval,
                          $options.components.Preview,
                          $options.components.Edit,
                          $options.components.Remove
                        ]`
                    ":items"="items" )
                article-mobile-list.column.mobile.tablet.only(
                    ":toolsRow"=`[
                          $options.components.PublishInterval,
                          $options.components.BiddingInterval,
                          $options.components.Preview,
                          $options.components.Edit,
                          $options.components.Remove
                        ]`
                    ":items"="items" )
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
    Vue.directive 'focus', require './directives/focus.vue'
    Vue.directive 'item', require './directives/item.vue'
    Vue.directive 'images', require './directives/images.vue'
    Vue.directive 'swipe', require './directives/swipe.vue'

    # Define global mixins
    Vue.mixin require './mixins/GoBack.vue'
    Vue.mixin require './mixins/Translation.vue'

    # Export the root-instance options
    module.exports =
        name: 'Butik'

        components:
            ArticleDesktopList: require './components/article/desktop/list.vue'
            ArticleMobileList: require './components/article/mobile/list.vue'
            FooterComponent: require './components/Footer.vue'

        mixins: [
            require './mixins/ErrorHandler.vue'
            require './mixins/SuccessHandler.vue'
            require './mixins/ArticleNumber.vue'
            require './mixins/LangChoice.vue'
        ]

        data: ->
            loading: true
            items: []
            settings:
                title: 'ReturButikenOnline'
                lang: false

        methods:
            getArticles: ->
                @$root.loading = true
                @$http.get('api/articles').then(
                    (response) =>
                        @items = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

        created: ->
            # Set and log a reference
            if process.env.NODE_ENV == "development"
                console.debug window.Butik = this

            # Get articles
            @getArticles()
</script>
