<template lang="pug">
    div.ui.fluid.raised.card( v-if="item" )
        div.content

            div.header.left.aligned
                div.ui.right.ribbon.label
                    i.checked.calendar.icon
                    | {{ item.updated_at+' ' }}
                div(
                    style="margin-top: -25px"
                    ) {{item.name}}
                    div.ui.black.horizontal.label(
                        style="position: relative; top: -2px; left: 15px;"
                        v-for="category in item.categories"
                        )
                        | {{ category.name }}

            div.ui.divider( style="margin-bottom: 5px;" )
            div.description
                div.ui.vertical.segment.basic
                    h4.ui.sub.header
                        | {{ translate('article_card.description_header') }}
                    p {{ item.desc }}

                div.ui.vertical.segment.basic.right.floated.center.aligned
                    h4.ui.sub.header
                        | {{ translate('article_card.price_header') }}
                    p {{ item.price }} {{ translate('article_card.price_currency') }}

                div( v-if="item.images.length>0" ).ui.vertical.segment.basic
                    h4.ui.sub.header
                        | {{ translate('article_card.images_header') }}
                    p
                        div.ui.tiny.images
                            img.ui.rounded.image(
                                v-for="image in item.images"
                                ":src"="image.thumb_path" )

        div.extra.content
            div.ui.bottom.aligned.two.column.grid.stackable
                div.column.meta.left.aligned
                    i.ui.icon(
                        ":class"="(item.public==1)?'green world':'red industry'"
                        )
                    span( v-if="item.public==1" )
                        | {{ translate('article_card.published_all') }}
                    span( v-if="item.public!=1" )
                        | {{ translate('article_card.published_intra') }}
                div.column.right.aligned
                    div.ui.icon.basic.buttons
                        component( v-for="tool in tools" ":is"="tool" ":item"="item" ":from"="from" )
</template>

<script lang="coffee">
    module.exports =
        name: 'ArticleCard'
        props: [ 'item', 'tools', 'from' ]
</script>
