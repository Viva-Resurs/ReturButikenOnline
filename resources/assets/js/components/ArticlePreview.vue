<template lang="pug">
    div.ui.padded.container.segment.attached#preview

        div.ui.dividing.header( v-if="mode!='show'" ) Förhandsgranskning

        div( v-if="article!=-1" ":class"="(mode!='show')?'ui card fluid raised':''" )
            div.content
                div.ui.basic.segment.center.aligned.preview_header( v-swipe="article.selected_images" )

                div.ui.basic.segment.center.aligned.preview_thumbs
                    div.ui.buttons
                        //button.ui.button.icon.black
                            i.icon.angle.left
                        div.ui.tiny.left.aligned.images
                            img.ui.image(
                                ":src"="'/'+image.thumb_path"
                                v-for="(image, index) in article.selected_images"
                                @click="setActiveImage(index)"
                                ":class" = "(image.selected) ? 'active' : 'disabled' ")
                        //button.ui.button.icon.black.right.floated
                            i.icon.angle.right


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

                    div.ui.basic.segment( v-if="article.publish_interval && mode!='show'" )
                        h4.ui.sub.header Publicerings intervall
                        i.ui.icon.time
                        span  {{ article.publish_interval }}

                    div.ui.basic.segment( v-if="article.bidding_interval && mode!='show'" )
                        h4.ui.sub.header Budgivningsintervall
                        i.ui.icon.time
                        span  {{ article.bidding_interval }}

                    div.ui.basic.segment(v-if="article.public && mode!='show'")
                        h4.ui.sub.header Område
                        i.ui.icon.green.world
                        | Publicerad externt
                    div.ui.basic.segment(v-if="!article.public && mode!='show'")
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

        div.ui.container( v-if="mode=='show'" )
            div.ui.hidden.divider
            div.ui.container.right.aligned
                div.ui.button.secondary(
                    @click="goBack()"
                ) Back

        div( v-if="article==-1" )
            | Artikeln hittades inte
</template>

<script lang="coffee">
    module.exports =
        name: 'Preview'
        props: [ 'mode', 'article', 'categories', 'contacts' ]

        methods:
            attemptPublish: ->
                bus.$emit( 'article_form_update', this.article )

            modifyArticle: ->
                bus.$emit( 'article_form_modify' )

            setActiveImage: (index) ->
                bus.$emit('snapTo', index );

        mounted: ->
            $('body').scrollTop(0)
</script>

<style lang="scss">
    #preview {
        .preview_header {
            background-color: rgba(0,0,0,0.4);

            width: 100%;
            padding: 0px;
            background-image:url('');
            background-size: contain;
            background-repeat:no-repeat;
            background-position:center;
            margin-bottom: 0px;
        }
        .preview_thumbs {
            margin-top: 0px;
            background-color: rgba(0,0,0,0.1);
        }
        .ui.images {
            img.image {
                //margin: 0px;
            }
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
