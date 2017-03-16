<template lang="pug">
    div.ui.grid.padded
        div.ui.vertical.fluid.compact.menu.row
            router-link.item.header( to="/articles" )
                | {{ translate('article.header') }}
                i.icon.external.square(
                    v-tooltip = ""
                    ":data-html" = "translate('goto.articles')" )
            a.item(
                v-for="article in articles"
                ":class"="article.selected ? 'active':''"
                @click="selectArticle(article)" ) {{ article.name }}
            i.item( v-if="articles.length==0" ) {{ translate('article.empty') }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Article'
        props: [ 'articles' ]
        methods:
            selectArticle: (article) ->
                bus.$emit 'article_changed', article
</script>
