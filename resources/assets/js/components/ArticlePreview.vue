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
                                ":src"="'/'+image.thumb_path"
                                v-for="(image, index) in article.selected_images"
                                @click="setActiveImage(index)"
                                ":class" = "(image.selected) ? 'active' : 'disabled'")

            div.ui.basic.segment
                h2.ui.header {{ article.name }}
                    div.sub.header Artikelnr: {{ $root.displayArtNR(article) }}
                div.description {{ article.desc }}

                br
                br
                div.ui.grid.bottom.aligned.stackable
                    div.two.column.row
                        div.left.aligned.left.floated.column(v-if="article.selected_contacts && article.selected_contacts.length>0")
                            h4.ui.sub.header Kontakt
                            div(v-for="contact in contacts")
                                div( v-for="selected_contact in article.selected_contacts")
                                    div.ui.card(v-if="(contact.id == selected_contact)")
                                        div.content
                                            div.header {{ contact.fullname }}
                                            div.description
                                                p
                                                    i.icon.phone
                                                    b &nbsp;{{ contact.phone }}
                                                p
                                                    i.icon.mail
                                                    b {{ contact.email }}
                        div.right.aligned.right.floated.column( v-if="article.price" )
                            h3 Pris : {{article.price}} kr
                div.ui.hidden.divider
                div.ui.segment( v-if="mode!='show'" )
                    div.ui.top.attached.label( @click="toggleDetails()" )
                        h4.ui.sub.header Mer information
                    div.ui.grid.equal.width.stackable#details
                        div.row( v-if="article.selected_categories.length>0" )
                            div.column
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
                        div.row
                            div.column( v-if="article.publish_interval" )
                                h4.ui.sub.header Publicerings intervall
                                i.ui.icon.time
                                span  {{ formatInterval(article.publish_interval) }}

                            div.column( v-if="article.bidding_interval" )
                                h4.ui.sub.header Budgivningsintervall
                                i.ui.icon.time
                                span  {{ formatInterval(article.bidding_interval) }}
                        div.row
                            div.column(v-if="article.public")
                                h4.ui.sub.header Område
                                i.ui.icon.green.world
                                | Publicerad externt
                            div.column(v-if="!article.public")
                                h4.ui.sub.header Område
                                i.ui.icon.red.industry
                                | Publicerad på kommunens intranät

        div.ui.container
            div.ui.hidden.divider
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
                return dates[0] + ' - ' + dates[1]

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
