<template lang="pug">
    div.ui.padded.container.segment.attached#preview

        div.ui.dividing.header( v-if="mode!='show'" ) Förhandsgranskning

        div.ui.fluid.raised.card( v-if="article!=-1" )
            div.content
                div.ui.basic.segment
                    div.center.aligned.tertiary.inverted.ui.segment.attached.preview_header( v-show="selected_image"  )
                        //img.ui.fluid.centered.image( ":src"="'/'+selected_image.path" )
                    

                    div.ui.basic.segment.bottom.attached
                        div.ui.tiny.center.aligned.images
                            img.ui.image(
                                ":src"="'/'+image.thumb_path"
                                v-for="image in article.selected_images"
                                @click="setSelectedImage(image)"
                                ":class" = "(image.path == selected_image.path) ? 'active' : 'disabled' ")


                div.ui.basic.segment
                    h2.ui.header {{ article.name }}
                        div.sub.header Artikelnr: {{article.id}}
                    div.description {{ article.desc }}

                    div.ui.basic.segment.right.aligned.right.floated
                        h3 Pris : {{article.price}} kr
                    br
                    br
                    br

                div.ui.top.attached.segment
                    div.top.attached.ui.secondary.label Övrig information

                    div.ui.basic.segment
                        h4.ui.sub.header Kategorier
                        p
                            template(v-for="(orig_category, index) in categories")
                                template( v-for="selected_category in article.selected_categories")
                                    span(v-if="(orig_category.id == selected_category)")
                                        i.ui.icon.tag( v-if="index==0" )
                                        span( v-if="index!=0 && article.selected_categories.length>1" )
                                            | {{ ', ' + orig_category.name }}
                                        span( v-else="" )
                                            | {{ orig_category.name }}

                    div.ui.basic.segment( v-if="article.publish_interval" )
                        h4.ui.sub.header Publicerings intervall
                        i.ui.icon.time
                        span  {{ article.publish_interval }}

                    div.ui.basic.segment( v-if="article.bidding_interval" )
                        h4.ui.sub.header Budgivningsintervall
                        i.ui.icon.time
                        span  {{ article.bidding_interval }}

                    div.ui.basic.segment(v-if="article.public")
                        h4.ui.sub.header Område
                        i.ui.icon.green.world
                        | Publicerad externt
                    div.ui.basic.segment(v-if="!article.public")
                        h4.ui.sub.header Område
                        i.ui.icon.red.industry
                        | Publicerad på kommunens intranät

                    div.ui.basic.segment(v-if="article.selected_contacts")
                        h4.ui.sub.header Kontakt
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


            div.extra.content( v-if="mode!='show'" )
                div.ui.right.floated.button.primary(
                    @click="attemptPublish"
                ) Publish

                div.ui.right.floated.button.red(
                    @click="modifyArticle"
                ) Modify
        div( v-if="article==-1" )
            | Artikeln hittades inte
</template>

<script lang="coffee">

    module.exports =

        name: 'Preview'

        props: [ 'mode', 'article', 'categories', 'contacts' ],

        data: ->
            selected_image: false;

        methods:
            attemptPublish: ->
                bus.$emit( 'article_form_update', this.article );

            modifyArticle: ->
                bus.$emit( 'article_form_modify' );

            setSelectedImage: ( image ) ->
                @selected_image = image
                $('.preview_header').css('background-image',"url('/"+image.path+"')")
        mounted: ->
            if @article.selected_images and @article.selected_images.length > 0
                @setSelectedImage @article.selected_images[0]
            $('body').scrollTop(0)
</script>

<style lang="scss">
    #preview {
        .preview_header {
            height: 400px;
            width: 100%;
            background-image:url('');
            background-size: contain;
            background-repeat:no-repeat;
            background-position:center;
        }

        img.disabled {
            opacity: 0.8 !important;
        }

        img.active {
            outline: 2px solid #25f;
            box-shadow: 0px 0px 9px 5px rgba(50,70,255,0.6) !important;
        }
    }
</style>
