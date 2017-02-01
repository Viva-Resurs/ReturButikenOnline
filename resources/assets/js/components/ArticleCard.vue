<template lang="pug">
    div.ui.fluid.raised.card
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
                        v-for="category in item.selected_categories"
                        )
                        | {{ category.name }}

            div.ui.divider( style="margin-bottom: 5px;" )
            div.description
                div.ui.vertical.segment.basic
                    h4.ui.sub.header
                        | Beskrivning
                    p {{ item.desc }}

                div.ui.vertical.segment.basic.right.floated.center.aligned
                    h4.ui.sub.header
                        | Pris
                    p {{ item.price }} kr

                div( v-if="item.images.length>0" ).ui.vertical.segment.basic
                    h4.ui.sub.header
                        | Bilder
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
                        | Publicerad för allmänheten
                    span( v-if="item.public!=1" )
                        | Publicerad på kommunens intranät
                div.column.right.aligned
                    div.ui.icon.basic.buttons
                        component( v-for="tool in tools" ":is"="tool" ":item"="item" ":from"="from" )
</template>

<script lang="coffee">
    module.exports =
        name: 'ArticleCard'
        props: [ 'item', 'tools', 'from' ]
</script>
