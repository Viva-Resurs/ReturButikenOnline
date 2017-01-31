<template lang="pug">
    div#preview
        div.ui.dividing.header( v-if="mode!='show'" ) Förhandsgranskning
        div( v-if="article!=-1" )
            div( v-if="article.selected_images && article.selected_images.length>0" )
                div.ui.basic.segment.center.aligned.preview_header(
                    v-swipe="article.selected_images")
                div.ui.basic.segment.center.aligned.preview_thumbs
                    div.ui.buttons
                        div.ui.tiny.left.aligned.images
                            img.ui.image(
                                ":src"="image.thumb_path"
                                v-for="(image, index) in article.selected_images"
                                @click="setActiveImage(index)"
                                ondrag="false"
                                dragable="false"
                                ":class" = "(image.selected) ? 'active' : 'disabled'")
            div.ui.basic.segment
                h2.ui.header {{ article.name }}
                    div.ui.black.horizontal.label(
                        style="position: relative; top: -2px; left: 15px;"
                        v-for="selected_category in article.selected_categories"
                        )
                        template(
                            v-for="category in categories"
                            v-if="category.id == selected_category" )
                            | {{ category.name }}
                div.ui.hidden.divider
                div.ui.grid.stackable
                    div.row
                        div.ten.wide.left.aligned.column
                            div.description {{ article.desc }}
                            h3( v-if="article.price" ) Pris : {{article.price}} kr
                        div.six.wide.center.aligned.column(v-if="article.selected_contacts && article.selected_contacts.length>0")
                            h4.ui.sub.header Kontakt
                            template(v-for="contact in contacts")
                                template( v-for="selected_contact in article.selected_contacts")
                                    div.ui.card.fluid(v-if="(contact.id == selected_contact)")
                                        div.content.left.aligned
                                            img.right.floated.ui.mini.image(
                                                v-if="contact.images && contact.images.length>0"
                                                ":src"="contact.images[0].thumb_path" )
                                            div.header {{ contact.fullname }}
                                            div.description
                                                p
                                                    i.icon.phone
                                                    b &nbsp;{{ contact.phone }}
                                                p
                                                    i.icon.mail
                                                    b {{ contact.email }}
                div.ui.divider
                div.ui.grid.padded
                    span
                        i( v-if="article.updated_at" )
                            b Ändrad:
                            | &nbsp;&nbsp;{{ formatDate(article.updated_at.date) }}
                        i( v-if="!article.updated_at && article.created_at" )
                            | Publicerad {{ formatDate(article.created_at.date) }}
                        i( v-if="!article.updated_at && !article.created_at" )
                            | Publicerad YYYY-MM-DD HH:MM
                    span
                        b Artikelnr:
                        | &nbsp;&nbsp;{{ $root.displayArtNR(article) }}
                div.ui.hidden.divider
                div.ui.segment( v-if="mode!='show'" )
                    div.ui.top.attached.label( @click="toggleDetails()" )
                        h4.ui.sub.header Information om publicering
                    div.ui.grid.equal.width.stackable#details
                        div.row
                            div.column( v-if="article.publish_interval" )
                                h4.ui.sub.header Publiceringsintervall
                                i.ui.icon.time
                                span {{ formatInterval(article.publish_interval) }}
                            div.column( v-if="article.bidding_interval" )
                                h4.ui.sub.header Budgivningsintervall
                                i.ui.icon.time
                                span {{ formatInterval(article.bidding_interval) }}
                            div.column( v-if="!article.publish_interval && !article.bidding_interval" )
                                h4.ui.sub.header
                                i.ui.icon.warning
                                i Artikeln har varken publiceringsintervall eller budgivningsintervall
                        div.row
                            div.column(v-if="article.public==true")
                                h4.ui.sub.header Publiceras för
                                i.ui.icon.green.world
                                | Allmänheten
                            div.column(v-if="article.public!=true")
                                h4.ui.sub.header Publiceras för
                                i.ui.icon.red.industry
                                | Kommunens intranät
        div.ui.container
            div.ui.container.right.aligned
                div.ui.button.secondary( v-if="mode=='show'"
                    @click="goBack()"
                ) Back
                div.ui.button.secondary( v-if="mode!='show'"
                    @click="modifyArticle"
                ) Modify
                div.ui.button.primary( v-if="mode!='show'"
                    @click="attemptPublish"
                ) Publish
        div( v-if="article==-1" )
            | Artikeln hittades inte
</template>

<script lang="coffee">
    module.exports =
        name: 'Preview'
        props: [ 'mode', 'article', 'categories', 'contacts' ]
        data: ->
            expandDetails: @mode
        methods:
            toggleDetails: ->
                $('#details').transition
                    animation  : 'slide down'
                    onShow : =>
                        @expandDetails = true
                    onHide : =>
                        @expandDetails = false

            formatInterval: (interval) ->
                dates = String(interval).split '|'
                dates[0] = dates[0].substr 0, dates[0].lastIndexOf ':'
                dates[1] = dates[1].substr 0, dates[1].lastIndexOf ':'
                return "#{dates[0]} - #{dates[1]}"

            formatDate: (d) ->
                date = String d
                return date.substr 0, date.lastIndexOf ':'

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
