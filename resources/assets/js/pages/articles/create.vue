<template lang="pug">
    div( v-if="article!=null" ).ui.padded.container.segment
        article-form(
            v-show = "!preview"
            ":original" = "article"
            ":categories" = "categories"
            ":contacts" = "contacts"
        )

        article-preview(
            v-if = "preview"
            ":article" = "preview_article"
            ":categories" = "categories"
            ":contacts" = "contacts"
        )
</template>

<script lang="coffee">
    module.exports =
        name: 'Create'

        components:
            ArticleForm    : require '../../components/ArticleForm.vue'
            ArticlePreview : require '../../components/ArticlePreview.vue'

        data: ->
            preview_article: false
            preview: false

            article:
                name: ''
                desc: ''
                price: ''
                public: false
                publish_interval: ''
                bidding_interval: ''
                selected_categories: []
                selected_images: []
                selected_contacts: []

            categories:
                null
            contacts:
                null

        methods:
            previewArticle: (article) ->
                @preview_article = article
                @preview = true

            modifyArticle: ->
                @preview_article = false
                @preview = false

            createArticle: (article) ->
                @$http.post( 'api/articles', article ).then(
                    (response) =>
                        @$router.push path: '/articles'
                    (response) =>
                        bus.$emit 'error', response.data
                )

            getCategoryList: ->
                @$http.get( 'api/categories' ).then(
                    (response) =>
                        this.categories = response.data
                    (response) =>
                        bus.$emit 'error', response.data
                )

            getContactList: ->
                @$http.get( 'api/contacts' ).then(
                    (response) =>
                        @contacts = response.data ? null
                        if @contacts.length > 0 and
                            @article.selected_contacts.length == 0
                                @article.selected_contacts.push @contacts[0].id
                    (response) =>
                        bus.$emit 'error', response.data
                )

        created: ->
            bus.$on 'article_form_preview', (payload) => @previewArticle payload
            bus.$on 'article_form_modify', => @modifyArticle()
            bus.$on 'article_form_update', (payload) => @createArticle payload

            # Get section-admins
            @getContactList()

            # Get categories
            @getCategoryList()

        beforeDestroy: ->
            bus.$off 'article_form_preview'
            bus.$off 'article_form_modify'
            bus.$off 'article_form_update'
</script>
