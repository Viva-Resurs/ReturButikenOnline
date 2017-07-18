<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div( v-if="!$root.loading" )
            article-preview(
                ":article" = "article"
                "mode" = "show" )
</template>

<script lang="coffee">
    module.exports =
        name: 'Show'
        components:
            ArticlePreview : require '../../components/article/preview.vue'
        data: ->
            article: null
        methods:
            ###*
            #   Get a article by using its article nr. 
            #   @param {articleNr} article number to use
            ###
            getArticleByArtNR: (articleNR) ->
                @getArticle @$root.decodeArtNR articleNR
            
            ###*
            #   Get a article from backend using its article id. 
            #   @param {id} id of article
            ###
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
