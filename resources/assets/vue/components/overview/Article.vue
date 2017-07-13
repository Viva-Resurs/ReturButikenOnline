<template lang="pug">
    div.ui.grid.padded.start-item
        div.ui.vertical.fluid.compact.secondary.pointing.menu.computer.tablet.only.row#overview_article_tablet_menu
            div.ui.grid.item.header
                div.twelve.wide.column
                    | {{ translate('article.header') }}
                    div.ui.tiny.green.label#overview_article_numarticles(v-if="articles.length > 1") {{ articles.length }}
                div.ui.container.right.aligned.four.wide.column
                    router-link( to="/ui/articles" )
                        i.icon.external.icon-style(
                            v-tooltip = ""
                            ":data-html" = "translate('goto.articles')" )
                    router-link( to="/ui/articles/create" )
                        i.plus.icon.square.outline.right.floated.icon-style(
                            v-tooltip = ""
                            ":data-html" = "translate('goto.createArticle')" )
            div.ui.divider#overview_article_divider
            div(":id"="articles.length > 3 ? 'overview_article_list' : ''")
                a.item(
                    v-for="article in articles"
                    ":class"="article.selected ? 'active':''"
                    @click="selectArticle(article)" ) {{ article.name }}
                i.item( v-if="articles.length==0" ) {{ translate('article.empty') }}
        
        div.ui.fluid.row.mobile.only.no-padding
            div.ui.fluid.label#overview_article_mobile_header {{ translate('article.header') }}
            div.ui.fluid.selection.label.dropdown#articles(                            
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
            ###*
            #   Emits article change
            #   @param {article} article that have changed
            ###
            selectArticle: (article) ->
                bus.$emit 'article_changed', article
</script>

<style>
    #overview_article_list {
        max-height: 120px; 
        overflow-y: auto; 
        padding-right: 2px;
    }

    #overview_article_divider {
        margin-top: 0px; 
        margin-bottom: 0px;
    }

    #overview_article_tablet_menu {
        height: 100%;
    }

    #overview_article_mobile_header {
        border-radius: 0px; 
        margin-right: 0px;
    }
  
    #overview_article_tablet_menu.ui.secondary.vertical.pointing.menu {
        border-right-style: hidden;
    }
    #articles {
        border-radius: 0px;
        background-color: white; 
        margin-left: 0px;
    }

    #overview_article_numarticles {
        position: absolute; 
        top: -3px;
    }

</style>
