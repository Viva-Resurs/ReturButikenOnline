<template lang="pug">
    div.ui.fluid.raised.card(v-if="item")
        div.content

            div.header.left.aligned
                div.ui.dividing.header {{item.name}}

            div.description(style="position: relative; top: -10px")
                div.column( v-if="item.categories.length>0" ).ui.vertical.segment.basic
                    h4.ui.sub.header {{ translate('article_card.categories_header') }}
                    div.ui.black.horizontal.label.stackable(
                        v-for="category in item.categories"
                        )
                        | {{ category.name }}

                div.ui.vertical.segment.basic
                    h4.ui.sub.header
                        | {{ translate('article_card.description_header') }}
                    p {{ item.desc }}

                div( v-if="item.images.length>0" ).ui.vertical.segment.basic
                    h4.ui.sub.header
                        | {{ translate('article_card.images_header') }}
                    p
                        div.ui.green.segment
                            div.ui.tiny.images
                                img.ui.rounded.image( style="background-color: lightgrey"
                                    v-for="image in item.images" @click="previewImages(image)"
                                    ":src"="image.thumb_path" )


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

          
        div.extra.content
            div.ui.grid.equal.width
                div.column.left.middle.aligned
                    div.column.center.aligned.left.floated
                        div.ui.basic.label
                            i.checked.calendar.icon.icon-style
                            | {{ item.updated_at+' ' }}

                div.column.right.aligned
                    div.column.center.aligned.right.floated
                        div.ui.green.button( @click="previewItem(item)") {{ translate('article_card.make_bidding') }}

        </template>

<script lang="coffee">
    module.exports =
        name: 'ArticleCard'
        props: [ 'item', 'tools', 'from' ]
        methods: 
            previewImages: (image) ->      
                selected_index = 0
                for img, index in @item.images
                    if Number(img.id) == Number(image.id)
                        selected_index = index

                bus.$emit 'show_message',                        
                    type: 'image'
                    index: selected_index
                    images: @item.images
            
            previewItem: (item) ->
                window.location.href = @$root.encodeArtNR(item)+location.hash

</script>
