<template lang="pug">
    div.ui.grid.padded
        div.ui.vertical.fluid.compact.menu.computer.tablet.only.row
            router-link.item.header( to="/ui/articles" )
                | {{ translate('article.header') }}
                i.icon.external.square(
                    v-tooltip = ""
                    ":data-html" = "translate('goto.articles')" )
            a.item(
                v-for="article in articles"
                ":class"="article.selected ? 'active':''"
                @click="selectArticle(article)" ) {{ article.name }}
            i.item( v-if="articles.length==0" ) {{ translate('article.empty') }}
        div.ui.row.selection.dropdown.mobile.only#articles(
            v-dropdown=""
            ":class"="articles.length > 0 ? '' : 'disabled'" )
            input(type="hidden" name="article_selection" )
            i.dropdown.icon
            div.default.text {{ articles.length > 0 ? translate('article.header') : translate('article.empty') }}
            div.class.menu
                div.item(
                    v-for="article in articles"
                    @click="selectArticle(article)"
                    ":data-value"="article.id" ) {{article.name}}
</template>

<script lang="coffee">
    module.exports =
        name: 'Article'
        props: [ 'articles' ]
        methods:
            selectArticle: (article) ->
                bus.$emit 'article_changed', article
</script>
