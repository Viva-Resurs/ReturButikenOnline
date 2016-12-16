<template lang="pug">
    div.ui.container.segment.attached#preview

        div.ui.dividing.header Förhandsgranskning

        div.ui.fluid.raised.card
            div.content
                div.ui.basic.segment
                    div.center.aligned
                        img.ui.fluid.image(v-if="selected_image" ":src"="selected_image.path")

                    br

                    div.ui.tiny.images
                        img.ui.image(
                            ":src"="image.thumb_path"
                            v-for="image in article.selected_images"
                            @click="setSelectedImage(image)"
                            ":class" = "(image.path == selected_image.path) ? 'bordered disabled' : '' ")


                div.ui.basic.segment
                    h2.ui.header {{ article.name }}
                        div.sub.header Artikelnummer: XXXXXXXXXXXXXX
                    div.description {{ article.desc }}


                div.ui.top.attached.segment
                    div.top.attached.ui.secondary.label Övrig information
                    h4.ui.sub.header Kategorier
                    div(v-for="orig_category in categories")
                        div( v-for="selected_category in article.selected_categories")
                            p(v-if="(orig_category.id == selected_category)") {{ orig_category.name }}
                    br
                    div.ui.grid.two.columns
                        div.column
                            h4.ui.sub.header Publicerings intervall
                            p {{ article.publish_interval }}

                        div.column
                            h4.ui.sub.header Budgivningsintervall
                            p {{ article.bidding_interval }}

                    br

                    h4.ui.sub.header Område
                    div(v-if="article.public")
                        | Publicerad externt
                    div(v-else)
                        | Publicerad på kommunens intranät

                    br
                    div.ui.divider
                    div( v-if="article.selected_contacts")
                        h4.ui.sub.header Kontakter
                        div(v-for="orig_contact in contacts")
                            div( v-for="selected_contact in article.selected_contacts")
                                div.ui.card(v-if="(orig_contact.id == selected_contact)")
                                    div.content
                                        div.header {{ orig_contact.fullname }}

                                        div.description
                                            p Phone
                                            b {{ orig_contact.phone }}

                                            p Email
                                            b {{ orig_contact.email }}

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

        props: [ 'article', 'categories', 'contacts' ],

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
