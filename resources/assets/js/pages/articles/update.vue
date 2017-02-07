<template lang="pug">
    div( v-if="article!=null" )
        article-form(
            v-show = "!preview_article"
            ":original" = "article"
            ":categories" = "categories"
            ":contacts" = "contacts"
        )
        article-preview(
            v-if = "preview_article"
            ":article" = "preview_article"
        )
</template>

<script lang="coffee">
    module.exports =
        name: 'Update'
        components:
            ArticleForm    : require '../../components/ArticleForm.vue'
            ArticlePreview : require '../../components/ArticlePreview.vue'
        data: ->
            preview_article: false
            article: null
            categories: null
            contacts: null
        methods:
            getArticle: (id) ->
                @$root.loading = true
                @$http.get('api/articles/'+id).then(
                    (response) =>
                        @article = response.data
                        @getCategoryList()
                        @getContactList()
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

            getCategoryList: ->
                @$http.get('api/categories').then(
                    (response) =>
                        @categories = response.data
                    (response) =>
                        bus.$emit 'error', response.data
                )

            getContactList: ->
                @$http.get('api/contacts').then(
                    (response) =>
                        @contacts = response.data ? null
                        if @contacts.length > 0 and
                            @article.contacts.length == 0
                                @article.contacts.push @contacts[0]
                    (response) =>
                        bus.$emit 'error', response.data
                )

            previewArticle: (article) ->
                @preview_article = article

            modifyArticle: ->
                @preview_article = false

            updateArticle: (article) ->
                @$http.put('api/articles/'+article.id, article).then(
                    (response) =>
                        @$router.push path: '/articles'
                    (response) =>
                        bus.$emit 'error', response.data
                )

        created: ->
            @getArticle @$route.params.id
            bus.$on 'article_form_preview', (payload) => @previewArticle payload
            bus.$on 'article_form_modify', => @modifyArticle()
            bus.$on 'article_form_update', (payload) => @updateArticle payload
        beforeDestroy: ->
            bus.$off 'article_form_preview'
            bus.$off 'article_form_modify'
            bus.$off 'article_form_update'
</script>
