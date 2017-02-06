<template lang="pug">
    div.ui.section(v-if="user")
        div.ui.attached( v-if="$root.loading" )
            loading

        div.ui.attached( v-else="" )
            div.ui.segment
                div.ui.dividing.header Mina aktiva artiklar
                div(v-if="(articles != undefined) && countArticles > 0")
                    div.ui.middle.aligned.very.relaxed.selection.list
                        div.item(v-for="article in articles")
                            img.ui.mini.spaced.rounded.image(":src"="article.images[0].thumb_path")
                            div.content
                                a.header {{ article.name }}
                                div.description {{ article.desc }}
                            div.right.floated.content(v-if="$root.isAdmin(2)") {{ article.selected_contacts[0].name }}

                div( v-else="")
                    div.ui.warning.message
                        p No articles

            div.ui.divider.hidden


            div.ui.grid.two.columns.stackable.segment
                div.column(v-if="$root.isAdmin()")
                    div.ui.dividing.header Sektioner

                    div( v-if="sections != undefined")
                        div.ui.middle.aligned.very.relaxed.selection.list.stackable
                            div.item(v-for="section in sections")
                                div.content
                                    a.header {{ section.name }}

                    div.ui.warning.message(v-else="")
                        p No sections
                div.column
</template>

<script lang="coffee">
    module.exports =
        name: 'StartDetails'

        props: [
            'sections'
            'articles'
            'user'
        ]

        computed:
            countArticles: ->
                @articles.length

</script>
