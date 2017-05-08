<template lang="pug">
    div.ui.grid.padded
        div.ui.vertical.fluid.compact.menu.computer.tablet.only.row(style="padding-top: 0px !important")
            div.ui.grid.item.header(
                style="padding-top: 10px !important; padding-bottom: 4px !important; padding-left: 10px !important; padding-right: 6px !important")
                div.thirteen.wide.column(style="padding: 0px !important")
                    | {{ translate('article.header') }}
                div.ui.container.right.aligned.three.wide.column(style="padding: 0px !important; margin: 0px !important")
                    router-link( to="/ui/articles" )
                        i.black.icon.external.square(
                            style="margin: 0px !important"
                            v-tooltip = ""
                            ":data-html" = "translate('goto.articles')" )
                    router-link( to="/ui/articles/create" )
                        i.black.plus.icon.square(
                            style="margin: 0px !important"
                            v-tooltip = ""
                            ":data-html" = "translate('goto.createArticle')" )
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
