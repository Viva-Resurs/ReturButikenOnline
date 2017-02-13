<template lang="pug">
    div.ui.vertical.fluid.menu
        div.item
            div.header Articles
        a.item(
            v-for="article in articles"
            ":class"="article.selected ? 'active':''"
            @click="selectArticle(article)" ) {{ article.name }}
        i.item(
            v-if="articles.length==0"
            ) empty...
</template>

<script lang="coffee">
    module.exports =
        name: 'Article'
        props: [ 'articles' ]
        methods:
            selectArticle: (article) ->
                bus.$emit 'article_changed', article
</script>
