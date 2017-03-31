<template lang="pug">
    div.pusher( v-if="settings.lang" )
        div.ui.padded.container.segment
            loading( v-if="$root.loading" )
            div.ui.grid.one.column( v-if="!$root.loading" )
                div.column
                    div.ui.dividing.header.fluid {{ translate('shop.header') }}
                article-preview.column(
                    v-if="article"
                    ":article" = "article"
                    "mode" = "show" )
                shop-desktop-list.column(
                    v-if="!article && screenType == 'desktop'"
                    ":items"="items" )
                shop-mobile-list.column(
                    v-if="!article && (screenType == 'mobile' || screenType == 'tablet')"
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
    Vue.mixin require './mixins/ScreenSize.vue'

    # Export the root-instance options
    module.exports =
        name: 'Shop'

        components:
            ShopDesktopList: require './components/shop/desktop/list.vue'
            ShopMobileList: require './components/shop/mobile/list.vue'
            ArticlePreview : require './components/shop/preview.vue'
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
            article: null
            settings:
                title: 'ReturButikenOnline'
                lang: false

        methods:
            getArticleByArtNR: (articleNR) ->
                @getArticle @decodeArtNR articleNR
            getArticle: (id) ->
                @$root.loading = true
                @$http.get('api/shop/'+id).then(
                    (response) =>
                        @article = response.data
                        @$root.loading = false
                    (response) =>
                        @article = -1
                        @$root.loading = false
                )
            getArticles: ->
                @$root.loading = true
                @$http.get('api/shop').then(
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

            # Check ArticleNR
            artnr = location.href.substr location.href.lastIndexOf('/')+1
            if artnr.indexOf('#')>-1
                artnr = artnr.substr 0, artnr.indexOf('#')

            # Check Token
            token = location.hash
            if token.length > 1
                token = token.substr 1

            # Get article or articles
            if artnr
                @getArticleByArtNR artnr
            else
                @getArticles()
</script>
