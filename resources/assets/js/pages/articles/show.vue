<template lang="pug">
    div( v-if="article!=null" )
        article-preview(
            ":article" = "article"
            "mode" = "show"
            ":categories" = "categories"
            ":contacts" = "article.public_contacts"
        )
</template>

<script lang="coffee">
    module.exports =
        name: 'Show'

        components:
            ArticlePreview : require '../../components/ArticlePreview.vue'

        data: ->
            article: null
            categories: null
            contacts: null

        methods:

            getArticle: (id) ->
                @$root.loading = true
                @$http.get( '/api/articles/'+id ).then(
                    (response) =>
                        @article = response.data
                        @getCategoryList()
                        @$root.loading = false
                    (response) =>
                        @article=-1
                        #bus.$emit 'error', response.data
                        @$root.loading = false
                )

            getCategoryList: ->
                @$http.get( '/api/categories' ).then(
                    (response) =>
                        this.categories = response.data
                    (response) =>
                        #bus.$emit 'error', response.data
                )

            previewArticle: (article) ->
                @preview_article = article
                @preview = true

            modifyArticle: ->
                @preview_article = null
                @preview = false

            updateArticle: (article) ->
                @$http.put( '/articles/'+article.id, article ).then(
                    (response) =>
                        @$router.push path: '/articles'

                    (response) => bus.$emit 'error', response.data
                )

        created: ->
            @getArticle @$route.params.id

</script>
