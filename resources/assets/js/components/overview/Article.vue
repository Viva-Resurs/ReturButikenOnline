<template lang="pug">
    div.ui.vertical.fluid.menu
        a.item(
            v-for="article in articles"
            @click="selectArticle(article)" ) {{ article.name }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Article'
        props: [ 'articles' ]
        methods:
            selectArticle: (article) ->
                bus.$emit 'article_changed',article
</script>
