<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div.ui.grid.one.column( v-else="" )
            div.column
                div.ui.dividing.header.fluid {{ translate('article.header') }}
            article-desktop-list.column(
                style="padding-top: 0px"
                v-if="screenType == 'desktop'"
                ":toolsRow"=`[
                      $options.components.ActiveInactive,
                      $options.components.PublishInterval,
                      $options.components.BiddingInterval,
                      $options.components.Preview,
                      $options.components.Edit,
                      $options.components.Remove
                    ]`
                ":items"="items" )
            article-mobile-list.column(
                v-if="(screenType == 'mobile') || (screenType == 'tablet')"
                ":toolsRow"=`[
                      $options.components.ActiveInactive,
                      $options.components.PublishInterval,
                      $options.components.BiddingInterval,
                      $options.components.Preview,
                      $options.components.Edit,
                      $options.components.Remove
                    ]`
                ":items"="items" )
</template>

<script lang="coffee">
    module.exports =
        name: 'List'
        components:
            ArticleDesktopList: require '../../components/article/desktop/list.vue'
            ArticleMobileList: require '../../components/article/mobile/list.vue'
            ActiveInactive: require '../../components/tools/Active.vue'
            PublishInterval: require '../../components/tools/PublishInterval.vue'
            BiddingInterval: require '../../components/tools/BiddingInterval.vue'
            Preview: require '../../components/tools/Preview.vue'
            Remove: require '../../components/tools/Remove.vue'
            Edit: require '../../components/tools/Edit.vue'
        data: ->
            items: []

        methods:
            ###*
            #   Asks the user if it want to remove a article.
            #   @param {article} article to remove
            ###
            attemptRemove: (article) ->
                bus.$emit 'show_message',
                    title: @$root.translate('article_list.remove_article_title') + " \""+article.name+"\""
                    message: @$root.translate('article_list.remove_article_message')
                    type:'confirm'
                    cb: => @removeArticle article

            ###*
            #   Removes a article from the backend. 
            #   @param {article} article to remove  
            ###
            removeArticle: (article) ->
                @$http.delete('api/articles/'+article.id).then(
                    (response) =>
                        bus.$emit 'success',
                            title: @$root.translate('article_list.success_message')
                            details: @$root.translate('article_list.article_removed')
                        Vue.set article, 'removed', true
                    (response) => bus.$emit 'error', response.data
                )

            ###*
            #   Attempt to update a article in the backend.
            #   @param {article} article to update
            ###
            attemptUpdate: (article) ->
                # Validation
                @updateArticle article

            ###*
            #   Updates a article in the backend.
            #   @param {article} article to update
            ###
            updateArticle: (article) ->
                @$http.put('api/articles/'+article.id, article).then(
                    (response) =>
                        bus.$emit 'success',
                            title: @$root.translate('article_list.success_message')
                            details: @$root.translate('article_list.article_updated')
                    (response) => 
                        article.active = "0"
                        bus.$emit 'error', response.data
                )

            ###*
            #   Redirect user to article preview.
            #   @param {article} article to preview
            ###
            previewArticle: (article) ->
                @$router.push @$root.encodeArtNR article

            ###*
            #   Gets a list of articles from backend.
            ###
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
            @getArticles()
            bus.$on 'articles_item_remove', (item) => @attemptRemove item
            bus.$on 'articles_item_preview', (item) => @previewArticle item
            bus.$on 'articles_item_changed', (item) => @attemptUpdate item
            bus.$on 'articles_item_edit', (item) =>
                @$router.push path: '/ui/articles/'+item.id
            bus.$on 'articles_item_add', =>
                @$router.push path: '/ui/articles/create'
            bus.$on 'publish_interval_changed', (id, new_value) =>
                for item in @items
                    if Number(item.id) == Number(id)
                        Vue.set item, 'publish_interval', new_value
                        bus.$emit 'articles_item_changed', item
            bus.$on 'bidding_interval_changed', (id, new_value) =>
                for item in @items
                    if Number(item.id) == Number(id)
                        Vue.set item, 'bidding_interval', new_value
                        bus.$emit 'articles_item_changed', item
        beforeDestroy: ->
            bus.$off 'articles_item_remove'
            bus.$off 'articles_item_preview'
            bus.$off 'articles_item_changed'
            bus.$off 'articles_item_edit'
            bus.$off 'articles_item_add'
            bus.$off 'publish_interval_changed'
            bus.$off 'bidding_interval_changed'
</script>
