<template lang="pug">
    div.ui.fluid.raised.card( v-if="item" )
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
                        div.ui.tiny.images
                            img.ui.rounded.image( style="background-color: lightgrey"
                                v-for="image in item.images"
                                ":src"="image.thumb_path" )
        
               

                div.ui.grid.equal.width            
                    div.column.bottom.aligned
                        i.ui.icon(
                        ":class"="(item.public==1)?'green world':'red industry'"
                        )
                        span( v-if="item.public==1" )
                            | {{ translate('article_card.published_all') }}
                        span( v-if="item.public!=1" )
                            | {{ translate('article_card.published_intra') }}

                    div.column.right.aligned                        
                        div.column.center.aligned.right.floated
                                h4.ui.sub.header
                                    | {{ translate('article_card.price_header') }}
                                p {{ item.price }} {{ translate('article_card.price_currency') }}
                
        div.extra.content
            div.ui.equal.width.grid.stackable
                div.column.middle.aligned.tablet.only
                    div.ui.basic.label
                        i.checked.calendar.icon
                        | {{ item.updated_at+' ' }}
                      
                div.column.right.aligned.tablet.only
                    div.ui.icon.basic.buttons
                        component( v-for="tool in tools" ":is"="tool" ":item"="item" ":from"="from" )
                
              
                div.column.center.aligned.mobile.only
                    div.ui.icon.basic.buttons
                        component( v-for="tool in tools" ":is"="tool" ":item"="item" ":from"="from" )
                
                div.column.center.aligned.mobile.only
                    div.ui.basic.label.bottom.attached
                        i.checked.calendar.icon
                        | {{ item.updated_at+' ' }}
                 

        </template>

<script lang="coffee">
    module.exports =
        name: 'ArticleCard'
        props: [ 'item', 'tools', 'from' ]
</script>
