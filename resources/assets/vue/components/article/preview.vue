<template lang="pug">
    div#preview
        div.ui.dividing.header( v-if="mode!='show'" ) {{ translate('article_preview.header') }}
        div( v-if="article==-1" )
            | {{ translate('article_preview.article_not_found') }}
        div( v-if="article!=-1")
           
            div.ui.grid.equal.width
                div.ui.left.aligned.column
                    h2.ui.header {{ article.name }}
                        div.ui.black.horizontal.label#preview_labels(                          
                            v-for="category in article.categories"
                            )
                                | {{ category.name }}

            div#previewed_images( v-if="article.images && article.images.length>0")
                div.ui.basic.segment.center.aligned.preview_header(
                    v-swipe="{images: article.images, screenType: screenType }")
            
            div.ui.hidden.divider#preview_header_divider(v-else="")
            
            div.description.wrap-lines {{ article.desc }}            
            div.ui.hidden.divider

            div.ui.grid.equal.width
                div.column.eight.wide.left.aligned.left.floated
                    div.row
                        h4.ui.sub.header {{ translate('article.amount') }}:
                    div.row
                        h3 {{ article.amount }} {{ translate('article.pieces')}}

                div.column.eight.wide.right.aligned.right.floated
                    div.row
                        h4.ui.sub.header {{ translate('article_preview.price_label') }}
                    div.row
                        h3.right.aligned {{ article.price }} {{ translate('article_preview.price_currency_label') }}

            div.ui.bottom.aligned.stackable.grid.mobile.reversed#preview_contacts_list
                div.left.aligned.column.eight.wide#contactcards
                    h4.ui.sub.header {{ translate('article_preview.contact_header') }}
                    template( v-for="contact in article.contacts" )
                        user-card( ":user"="contact" "picture"="true" "type" = "horizontal" )

            
            div.ui.divider
            div.ui.grid.padded
                span
                    i( v-if="article.updated_at" )
                        b {{ translate('article_preview.updated_at_label') }}
                        | &nbsp;&nbsp;{{ formatDate(article.updated_at.date) }}
                    i( v-if="!article.updated_at && article.created_at" )
                        | {{ translate('article_preview.created_at_label') }} {{ formatDate(article.created_at.date) }}
                    i( v-if="!article.updated_at && !article.created_at" )
                        | {{ translate('article_preview.created_at_label') }} YYYY-MM-DD HH:MM
                span
                    b {{ translate('article_preview.article_number_label') }}
                    | &nbsp;&nbsp;{{ $root.displayArtNR(article) }}
            div.ui.hidden.divider
            
            div.ui.segment( v-if="mode!='show'" )
                div.ui.top.attached.label( @click="toggleDetails()" )
                    h4.ui.sub.header {{ translate('article_preview.publish_info_header') }}
                div.ui.grid.equal.width.stackable#details
                    div.row
                        div.column( v-if="article.publish_interval" )
                            h4.ui.sub.header {{ translate('article_preview.publish_interval_header') }}
                            i.ui.icon.time.icon-style
                            span {{ formatInterval(article.publish_interval) }}
                        div.column( v-if="article.bidding_interval" )
                            h4.ui.sub.header {{ translate('article_preview.bidding_interval_header') }}
                            i.ui.icon.time.icon-style
                            span {{ formatInterval(article.bidding_interval) }}
                        div.column( v-if="!article.publish_interval && !article.bidding_interval" )
                            h4.ui.sub.header
                            i.ui.icon.warning
                            i {{ translate('article_preview.not_published') }}
                    div.row
                        div.column(v-if="article.public==true")
                            h4.ui.sub.header {{ translate('article_preview.published_for_header') }}
                            i.ui.icon.green.world
                            | {{ translate('article_preview.published_all') }}
                        div.column(v-if="article.public!=true")
                            h4.ui.sub.header {{ translate('article_preview.published_for_header') }}
                            i.ui.icon.red.industry
                            | {{ translate('article_preview.published_intra') }}
                        
            div.ui.divider
            div.ui.grid
                div.column.middle.aligned.six.wide#preview_checkbox_publish(v-if="mode!='show'")            
                    div.ui.green.toggle.checkbox                            
                        input#activeOn(
                            type="checkbox"
                            name="active"
                            value="1"
                            v-model="article.active"
                        )
                        label( for="activeOn" ) {{ translate('article_preview.publish_article') }}
        
                div.column.right.aligned#preview_navigation_buttons(":class"="mode == show ? 'ten wide': ''")
                    div.ui.button.secondary( v-if="mode=='show'"
                        @click="goBack()"
                    ) {{ translate('nav.back') }}
                    
                    div.ui.button.secondary( v-if="mode!='show'"
                        @click="modifyArticle"
                    ) {{ translate('article_preview.article_modify') }}
                    div.ui.button.primary( v-if="mode!='show'"
                        @click="attemptPublish"
                    ) {{ translate('article_preview.article_publish') }}

</template>

<script lang="coffee">
    module.exports =
        name: 'Preview'
        props: [ 'mode', 'article' ]
        data: ->
            expandDetails: @mode
        components:
            UserCard: require '../user/card.vue'

        methods:
            ###*
            #   Toggles the details window. 
            ###
            toggleDetails: ->
                $('#details').transition
                    animation  : 'slide down'
                    onShow : =>
                        @expandDetails = true
                    onHide : =>
                        @expandDetails = false

            ###*
            #   Returns a formatted date string: 
            #   start-date - end-date
            #   @param {interval} original date string separated with "|"
            #   @return {string} formatted date string separated with "-"
            ### 
            formatInterval: (interval) ->
                dates = String(interval).split '|'
                dates[0] = dates[0].substr 0, dates[0].lastIndexOf ':'
                dates[1] = dates[1].substr 0, dates[1].lastIndexOf ':'
                return "#{dates[0]} - #{dates[1]}"

            ###*
            #   Returns dates splitted by ':'. 
            #   @param {d} date string 
            #   @return {dates} start date and end date
            ###
            formatDate: (d) ->
                date = String d
                return date.substr 0, date.lastIndexOf ':'

            ###*
            #   Emits update of article.
            ###
            attemptPublish: ->
                bus.$emit( 'article_form_update', this.article )

            ###*
            #   Emits modification of article.
            ###
            modifyArticle: ->
                bus.$emit( 'article_form_modify' )

            ###*
            #   Emits snap to index (image preview).
            #   @param {index} index of image
            ###
            setActiveImage: (index) ->
                bus.$emit('snapTo', index );

        mounted: ->
            $('body').scrollTop(0)
</script>
<style>
    #preview_contacts_grid {
        width: auto;
    }

    #preview_labels {
        position: relative; 
        top: -2px; 
        left: 15px;
    }

    #previewed_images {
        position: relative; 
        padding-top: 5px;
    }

    #preview_header_divider {
        margin-top: 0px;
    }
    #preview_checkbox_publish {
        padding-left: 20px;
    }

    #preview_navigation_buttons {
        padding-right: 10px;
    }
</style>
