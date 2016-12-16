<template lang="pug">
    div.ui.container.segment.attached#preview

        div.ui.dividing.header Förhandsgranskning

        div.ui.fluid.raised.card
            div.content
                div.ui.top.attached.segment
                    div.top.attached.ui.secondary.label Article

                    div.center.aligned
                        img.ui.fluid.image(v-if="selected_image" ":src"="selected_image.path")

                    br

                    div.ui.tiny.images
                        img.ui.image(
                            ":src"="image.thumb_path"
                            v-for="image in article.selected_images"
                            @click="setSelectedImage(image)"
                            ":class" = "(image.path == selected_image.path) ? 'bordered disabled' : '' ")


                div.ui.bottom.attached.segment
                    div.ui.header  {{ article.name }}

                    //TODO : Needs original category list to identify category from id
                    div(v-for="orig_category in categories")
                        div( v-for="selected_category in article.selected_categories")
                            div.ui.black.horizontal.label(v-if="(orig_category.id == selected_category)") {{ orig_category.name }}

                    div.description {{ article.desc }}

                div.ui.top.attached.segment
                    div.top.attached.ui.secondary.label Additional information
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

                div.ui.bottom.attached.segment
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

            div.extra.content
                div.ui.right.floated.button.primary(
                    @click="attemptPublish"
                ) Publish

                div.ui.right.floated.button.red(
                    @click="modifyArticle"
                ) Modify
</template>

<script lang="coffee">

    module.exports =

        name: 'Preview'

        props: [ 'article' ],

        data: ->
            selected_image: false;

        methods:
            attemptPublish: ->
                bus.$emit( 'article_form_update', this.article );

            modifyArticle: ->
                bus.$emit( 'article_form_modify' );

            setSelectedImage: ( image ) ->
                @selected_image = image
                console.log @selected_image.path

</script>
