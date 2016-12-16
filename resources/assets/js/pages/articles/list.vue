<template lang="pug">
    item-grid(
        header="Arkiv"
        ":columns"="columns"
        ":card"="$options.components.ArticleCard"
        ":toolsRow"=`[
              $options.components.PublishInterval,
              $options.components.BiddingInterval,
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
            Remove: require '../../components/tools/Remove.vue'
            Edit: require '../../components/tools/Edit.vue'

        data: ->
            items: []

            columns:
                image:
                    label: 'Image'
                    key: 'selected_images'
                    type: 'image'
                    class: 'center aligned collapsing'
                name:
                    label: 'Name'
                    key: 'name'
                    type: 'string'
                    search: true
                    sort: true
                    tooltip: 'desc'
                    class: 'link'
                updated_at:
                    label: 'Updated'
                    key: 'updated_at'
                    type: 'number'
                    desc: true
                    default_sort: true
                    search: true
                    sort: true
                    class: 'link'
                category:
                    label: 'Categories'
                    key: 'selected_categories'
                    type: 'array'
                    search: true
                    sort: true
                    class: 'link'
                public:
                    label: 'Public'
                    key: 'public'
                    search: false
                    sort: false
                    type: 'checkbox'
                    checkbox_true: 'Publicerad för allmänheten'
                    checkbox_false: 'Publicerad på kommunens intranät'
                    class: 'center aligned collapsing'

        methods:
            attemptRemove: (article) ->
                # Are you sure?
                @removeArticle article

            removeArticle: (article) ->
                @$http.delete( 'articles/'+article.id ).then(
                    (response) =>
                        bus.$emit 'success', 'removed_article'
                        Vue.set article, 'removed', true
                    (response) => bus.$emit 'error', response.data
                )

            attemptUpdate: (article) ->
                # Validation
                @updateArticle article

            updateArticle: (article) ->
                @$http.put( 'articles/'+article.id, article ).then(
                    (response) =>
                        bus.$emit 'success', 'updated_article'
                    (response) => bus.$emit 'error', response.data
                )

            getArticles: () ->
                @$root.loading = true
                @$http.get( 'articles' ).then(
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
            bus.$on 'articles_item_changed', (payload) => @attemptUpdate payload
            bus.$on 'articles_item_edit', (item) =>
                @$router.push path: '/articles/'+item.id

            bus.$on 'publish_interval_changed', (id,new_value) =>
                for item in this.items
                    if Number item.id == Number id
                        Vue.set item, 'publish_interval', new_value
                        bus.$emit 'item_changed', item

            bus.$on 'bidding_interval_changed', (id,new_value) =>
                for item in this.items
                    if Number item.id == Number id
                        Vue.set item, 'bidding_interval', new_value
                        bus.$emit 'item_changed', item

        beforeDestroy: ->
            bus.$off 'articles_item_edit'
            bus.$off 'articles_item_remove'
            bus.$off 'articles_item_changed'
            bus.$off 'publish_interval_changed'
            bus.$off 'bidding_interval_changed'
</script>
