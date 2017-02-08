<template lang="pug">
    div
        div.ui.attached( v-if="$root.loading" )
            loading
        div( v-if="!$root.loading" )
            article-preview(
                v-if="article!=null"
                ":article" = "article"
                "mode" = "show"
            )
</template>

<script lang="coffee">
    module.exports =
        name: 'Show'
        components:
            ArticlePreview : require '../../components/ArticlePreview.vue'
        data: ->
            article: null
        methods:
            getArticleByArtNR: (articleNR) ->
                @getArticle @$root.decodeArtNR articleNR
            getArticle: (id) ->
                @$root.loading = true
                @$http.get('api/articles/'+id).then(
                    (response) =>
                        @article = response.data
                        @$root.loading = false
                    (response) =>
                        @article = -1
                        @$root.loading = false
                )
        created: ->
            @getArticleByArtNR @$route.params.artnr
</script>
