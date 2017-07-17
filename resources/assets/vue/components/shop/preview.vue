<template lang="pug">
    div#preview
        div( v-if="article==-1" )
            | {{ translate('article_preview.article_not_found') }}
        div( v-if="article!=-1")

            div.ui.grid.equal.width
                div.ui.left.aligned.column
                    h2.ui.header {{ article.name }}
                        div.ui.black.horizontal.label#shop_preview_category_label(                    
                            v-for="category in article.categories"
                            )
                                | {{ category.name }}

            div#shop_preview_images( v-if="article.images && article.images.length>0")
                div.ui.basic.segment.center.aligned.preview_header(
                    v-swipe="{images: article.images, screenType: screenType }")

            div.description#shop_preview_description {{ article.desc }}
            div.ui.hidden.divider

            div.ui.grid.equal.width
                div.column.eight.wide.left.aligned.left.floated
                    div.row
                        h4.ui.sub.header {{ translate('article.amount') }}:
                    div.row
                        h3 {{ article.amount }} {{ translate('article.pieces')}}

                div.column.eight.wide.right.aligned.right.floated
                    div.row
                        h4.ui.sub.header {{ translate('article_preview.price_label') }}
                    div.row
                        h3.right.aligned {{ article.price }} {{ translate('article_preview.price_currency_label') }}
 
            div.ui.bottom.aligned.stackable.grid.mobile.reversed#shop_preview_contacts
                div.left.aligned.column.eight.wide#contactcards
                    h4.ui.sub.header {{ translate('article_preview.contact_header') }}
                    template( v-for="contact in article.contacts" )
                        user-card( ":user"="contact" "picture"="true" "type" = "horizontal" )


            div.ui.divider
            div.ui.grid.padded
                span
                    i( v-if="article.updated_at" )
                        b {{ translate('article_preview.updated_at_label') }}
                        | &nbsp;&nbsp;{{ formatDate(article.updated_at.date) }}
                    i( v-if="!article.updated_at && article.created_at" )
                        | {{ translate('article_preview.created_at_label') }} {{ formatDate(article.created_at.date) }}
                    i( v-if="!article.updated_at && !article.created_at" )
                        | {{ translate('article_preview.created_at_label') }} YYYY-MM-DD HH:MM
                span
                    b {{ translate('article_preview.article_number_label') }}
                    | &nbsp;&nbsp;{{ $root.displayArtNR(article) }}
            div.ui.divider
            div.ui.grid
                div.column.right.aligned
                    div.ui.button.secondary(
                        @click="goBack()"
                    ) {{ translate('nav.back') }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Preview'
        props: [ 'mode', 'article' ]
        data: ->
            expandDetails: @mode
        components:
            UserCard: require '../user/card.vue'

        methods:
            ###*
            #   Returns dates splitted by ':'. 
            #   @param {d} date string 
            #   @return {dates} start date and end date
            ###
            formatDate: (d) ->
                date = String d
                return date.substr 0, date.lastIndexOf ':'


        mounted: ->
            $('body').scrollTop(0)
</script>
<style>
    #shop_preview_category_label {
        position: relative; 
        top: -2px; 
        left: 15px;
    }
    
    #shop_preview_images {
        position: relative; 
        padding-top: 10px;
    }

    #shop_preview_description {
        white-space: pre-wrap; 
        padding-top: 10px;
    }

    #shop_preview_contacts {
        width: auto;
    }
</style>
