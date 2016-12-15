<template lang="pug">
    div.ui.container.segment.attached#preview

        div.ui.dividing.header Förhandsgranskning

        div.ui.fluid.raised.card
            div.content
                div.ui.segment
                    div.top.attached.ui.secondary.label
                    | Selected images

                    div.ui.tiny.images
                        div( v-for="image in article.selected_images" )
                            img.ui.tiny.rounded.image( ":src"="image.thumb_path" )

                div.header
                | {{ article.name }}

                div( v-for="category in article.selected_categories" )
                    div.ui.black.horizontal.label( v-for="category in article.selected_categories")
                    | {{ category.name }}

                br
                br

                div.description
                    | {{ article.desc }}

            div.extra.content
                div.ui.grid.two.columns
                    div.column
                        strong Publish interval
                        p {{ article.publish_interval }}

                    div.column
                        strong Bidding interval
                        p {{ article.bidding_interval }}

                br
                div(v-if="article.public")
                    | Publicerad externt
                div(v-else)
                    | Publicerad på kommunens intranät

            div.extra.content
                div( v-if="article.selected_contacts")
                    p
                        strong Contacts

                    div.ui.card( v-for="contact in article.selected_contacts" )
                        div.content
                            div.header
                            | {{ contact.fullname }}

                            div.description
                                p Phone
                                b {{ contact.phone }}

                                p Email
                                b {{ contact.email }}

                div.ui.right.floated.button.primary(
                    type="submit"
                    @keydown.enter.prevent="attemptPublish"
                ) Publish

                div.ui.right.floated.button.red(
                    @click="modifyArticle"
                ) Modify
</template>

<script lang="coffee">

    module.exports =

        name: 'Preview'

        props: [ 'article' ],

        methods:
            attemptPublish: ->
                bus.$emit( 'article_form_update', this.article );

            modifyArticle: ->
                bus.$emit( 'article_form_modify' );
</script>
