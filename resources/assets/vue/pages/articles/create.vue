<template lang="pug">
    div
        article-form(
            v-show = "!preview"
            ":draft" = "article"
            ":categories" = "categories"
            ":contacts" = "contacts" )
        article-preview(
            v-if = "preview"
            ":article" = "preview" )
</template>

<script lang="coffee">
    module.exports =
        name: 'Create'
        components:
            ArticleForm    : require '../../components/article/form.vue'
            ArticlePreview : require '../../components/article/preview.vue'
        data: ->
            preview: false
            article:
                name: ''
                desc: ''
                price: ''
                public: false
                publish_interval: ''
                bidding_interval: ''
                categories: []
                images: []
                contacts: []
            categories: null
            contacts: null
        methods:
            getCategoryList: ->
                @$http.get('api/categories').then(
                    (response) =>
                        @categories = response.data ? null
                    (response) =>
                        bus.$emit 'error', response.data
                )
            getContactList: ->
                @$http.get('api/contacts').then(
                    (response) =>
                        @contacts = response.data ? null
                        if @contacts.length > 0
                            @article.contacts.push @contacts[0]
                    (response) =>
                        bus.$emit 'error', response.data
                )
            createArticle: (article) ->
                @$http.post('api/articles', article).then(
                    (response) =>
                        @$router.push path: '/articles'
                    (response) =>
                        bus.$emit 'error', response.data
                )
        created: ->
            # Get contacts
            @getContactList()
            # Get categories
            @getCategoryList()
            # When previewing Article
            bus.$on 'article_form_preview', (article) =>
                @preview = article
            # When modifying Article (Exit/Unset preview)
            bus.$on 'article_form_modify', =>
                @preview = false
            # When saving the new Article
            bus.$on 'article_form_update', (article) => @createArticle article
        beforeDestroy: ->
            bus.$off 'article_form_preview'
            bus.$off 'article_form_modify'
            bus.$off 'article_form_update'
</script>
