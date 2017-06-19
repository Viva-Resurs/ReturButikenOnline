<template lang="pug">
    div.ui.grid.padded.start-item
        div.ui.vertical.fluid.compact.menu.computer.tablet.only.row
            div.ui.grid.item.header
                div.twelve.wide.column
                    | {{ translate('article.header') }}
                div.ui.container.right.aligned.four.wide.column
                    router-link( to="/ui/articles" )
                        i.icon.external.icon-style(
                            v-tooltip = ""
                            ":data-html" = "translate('goto.articles')" )
                    router-link( to="/ui/articles/create" )
                        i.plus.icon.square.outline.right.floated.icon-style(
                            v-tooltip = ""
                            ":data-html" = "translate('goto.createArticle')" )
            a.item(
                v-for="article in articles"
                ":class"="article.selected ? 'active':''"
                @click="selectArticle(article)" ) {{ article.name }}
            i.item( v-if="articles.length==0" ) {{ translate('article.empty') }}
        
        div.ui.fluid.row.mobile.only.no-padding
            div.ui.fluid.label(style="border-radius: 0px; margin-right: 0px") {{ translate('article.header') }}
            div.ui.fluid.selection.label.dropdown#articles(    
                style="border-radius: 0px;background-color: white; margin-left: 0px"            
                v-dropdown=""
                ":class"="articles.length > 0 ? '' : 'disabled'" )
                input(type="hidden" name="article_selection")
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
