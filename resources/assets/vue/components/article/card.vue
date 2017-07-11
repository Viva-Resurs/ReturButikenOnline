<template lang="pug">
    div.ui.fluid.card( v-if="item" ":class"="item.active == '1' ? 'green':''")
        div.content

            div.header.left.aligned
                div.ui.dividing.header {{item.name}}

            div.description#card_description
                div.column( v-if="item.categories.length>0" ).ui.vertical.segment.basic
                    h4.ui.sub.header {{ translate('article_card.categories_header') }}
                    div.ui.black.horizontal.label.stackable(
                        v-for="category in item.categories"
                        )
                        | {{ category.name }}
                
                div.ui.hidden.divider.no-padding#card_header_divider(v-else="")

                div( v-if="item.images.length>0" ).ui.vertical.segment.basic.no-padding
                    h4.ui.sub.header
                        | {{ translate('article_card.images_header') }}
                    p
                        div.ui.basic.segment.no-padding
                            div.ui.tiny.images
                                img.ui.rounded.image#card_image(
                                    v-for="image in item.images" @click="previewImages(image)"
                                    ":src"="image.thumb_path" )

                div.ui.vertical.segment.basic#card_item_description
                    h4.ui.sub.header
                        | {{ translate('article_card.description_header') }}
                    p.wrap-lines {{ item.desc }}


                div.ui.grid.equal.width
                    div.column.left.aligned
                        div.column.center.aligned.left.floated
                            h4.ui.sub.header
                                | {{ translate('article.amount') }}
                            p {{ item.amount }} {{ translate('article.pieces')}}

                    div.column.right.aligned
                        div.column.center.aligned.right.floated
                            h4.ui.sub.header
                                | {{ translate('article_card.price_header') }}
                            p {{ item.price }} {{ translate('article_card.price_currency') }}
                
                
                div.ui.grid.mobile.only#card_mobile_buttons
                        
                    div.ui.segment.grid.attached.no-padding
                        div.column.sixteen.wide.no-padding
                            i.ui.icon(
                            ":class"="(item.public==1)?'green world':'red industry'"
                            )
                            span( v-if="item.public==1" )
                                | {{ translate('article_card.published_all') }}
                            span( v-if="item.public!=1" )
                                | {{ translate('article_card.published_intra') }}

           
                    div.ui.segment.attached
                        div.column.center.aligned
                            div.column.eight.wide
                                div.ui.icon.buttons
                                    component( v-for="tool in tools" ":is"="tool" ":item"="item" ":from"="from" )
                        
                    div.ui.small.segment.bottom.attached
                        div.ui.small.label.basic.bottom.attached.center.aligned
                            i.checked.calendar.icon.icon-style
                            | {{ item.updated_at+' ' }}

                    
        div.extra.content.tablet.computer.only(v-if="screenType != 'mobile'")
            div.ui.equal.width.grid.stackable
                div.column.middle.aligned.tablet.computer.only
                    div.ui.basic.label
                        i.checked.calendar.icon.icon-style
                        | {{ item.updated_at+' ' }}

                div.column.right.aligned.tablet.computer.only
                    div.ui.icon.buttons
                        component( v-for="tool in tools" ":is"="tool" ":item"="item" ":from"="from" )

                                           
        </template>

<script lang="coffee">
    module.exports =
        name: 'ArticleCard'
        props: [ 'item', 'tools', 'from' ]
        methods:
        
            ###*
            #   Triggers preview of a clicked image.
            #   @param {image} image to preview
            ###
            previewImages: (image) ->
                selected_index = 0
                for img, index in @item.images
                    if Number(img.id) == Number(image.id)
                        selected_index = index

                bus.$emit 'show_message',
                    type: 'image'
                    index: selected_index
                    images: @item.images

</script>
<style>

    #card_header_divider {
        margin-top: 0px;
    }

    #card_description {
        position: relative; 
        top: -10px;
    }

    #card_item_description {
        padding-top: 5px !important;
    }

    #card_image{
        background-color: lightgrey;
    }

    #card_mobile_buttons {
        padding-left: 14px; 
        padding-right: 14px;
    }
</style>
