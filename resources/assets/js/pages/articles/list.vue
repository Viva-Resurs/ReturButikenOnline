<template lang="pug">
    item-grid(
        ":header"="translate('article.header')"
        ":columns"="columns"
        ":card"="$options.components.ArticleCard"
        ":toolsRow"=`[
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
            ItemGrid: require '../../components/ItemGrid.vue'
            ArticleCard: require '../../components/ArticleCard.vue'
            PublishInterval: require '../../components/tools/PublishInterval.vue'
            BiddingInterval: require '../../components/tools/BiddingInterval.vue'
            Preview: require '../../components/tools/Preview.vue'
            Remove: require '../../components/tools/Remove.vue'
            Edit: require '../../components/tools/Edit.vue'
        data: ->
            items: []
            columns:
                image:
                    label: 'image'
                    key: 'images'
                    type: 'image'
                    class: 'center aligned collapsing'
                name:
                    label: 'name'
                    key: 'name'
                    type: 'string'
                    search: true
                    sort: true
                    tooltip: 'desc'
                    class: 'link'
                updated_at:
                    label: 'updated'
                    key: 'updated_at'
                    type: 'number'
                    desc: true
                    default_sort: true
                    search: true
                    sort: true
                    class: 'link'
                category:
                    label: 'categories'
                    key: 'categories'
                    type: 'array'
                    search: true
                    sort: true
                    class: 'link'
                public:
                    label: 'public'
                    key: 'public'
                    search: false
                    sort: true
                    type: 'checkbox'
                    checkbox_true: @$root.translate('article_list.published_all')
                    checkbox_false: @$root.translate('article_list.published_intra')
                    class: 'link center aligned collapsing'
        methods:
            attemptRemove: (article) ->
                bus.$emit 'show_message',
                    title: @$root.translate('article_list.remove_article_title') + "''"+article.name+"''."
                    message: @$root.translate('article_list.remove_article_message')
                    type:'confirm'
                    cb: => @removeArticle article

            removeArticle: (article) ->
                @$http.delete('api/articles/'+article.id).then(
                    (response) =>
                        bus.$emit 'success',
                            title: @$root.translate('article_list.success_message')
                            details: @$root.translate('article_list.article_removed')
                        Vue.set article, 'removed', true
                    (response) => bus.$emit 'error', response.data
                )

            attemptUpdate: (article) ->
                # Validation
                @updateArticle article

            updateArticle: (article) ->
                @$http.put('api/articles/'+article.id, article).then(
                    (response) =>
                        bus.$emit 'success',
                            title: @$root.translate('article_list.success_message')
                            details: @$root.translate('article_list.article_updated')
                    (response) => bus.$emit 'error', response.data
                )

            previewArticle: (article) ->
                @$router.push @$root.encodeArtNR article

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
            bus.$on 'articles_item_changed', (payload) => @attemptUpdate payload
            bus.$on 'articles_item_edit', (item) =>
                @$router.push path: '/articles/'+item.id
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
            bus.$off 'publish_interval_changed'
            bus.$off 'bidding_interval_changed'
</script>
