<template lang="pug">
    div.no-top-padding
        div.ui.segments
            div.ui.segment.equal.width.grid.stackable.steps#overview_start_steps
                div.column.step(
                    ":id"="screenType == 'mobile' ? 'overview_section_step_mobile' : 'overview_section_step'"
                    v-if="$root.isAdmin()"
                    ":class"="!section ? 'active' : ''" )
                    section-overview( ":sections"="getSections" )
                div.column.step.right.floated(
                    ":id"="screenType == 'mobile' ? 'overview_contact_step_mobile' : 'overview_contact_step'"
                    v-if="$root.isAdmin(2)"
                    ":class"="section && !contact ? 'active' : ''" )
                    contact-overview( ":contacts"="getContacts" )
                div.column.step(
                    ":id"="screenType == 'mobile' ? 'overview_article_step_mobile' : 'overview_article_step'"
                    ":class"="contact && !article ? 'active' : ''" )                    
                    article-overview( ":articles"="getArticles" )
        article-card(
            ":item"="article"
            ":tools"=`[
                $options.components.Active,
                $options.components.PublishInterval,
                $options.components.BiddingInterval,
                $options.components.Preview,
                $options.components.Edit,
                $options.components.Remove ]`
            "from"="start"
            )
</template>

<script lang="coffee">
    module.exports =
        name: 'Start'

        components:
            SectionOverview: require './Section.vue'
            ContactOverview: require './Contact.vue'
            ArticleOverview: require './Article.vue'
            ArticleCard: require '../article/card.vue'
            Active: require '../tools/Active.vue'
            PublishInterval: require '../tools/PublishInterval.vue'
            BiddingInterval: require '../tools/BiddingInterval.vue'
            Preview: require '../tools/Preview.vue'
            Remove: require '../tools/Remove.vue'
            Edit: require '../tools/Edit.vue'

        props: [ 'article_tree' ]

        computed:
            ###*
            #   Returns a tree with sections as base (sections->contacts->articles)
            #   @return {article_tree} article tree
            ###
            getSections: ->               
                return @article_tree
            
            ###*
            #   Returns the contact list from the selected section
            #   @return {contacts} sections contact list
            ###
            getContacts: ->                
                return if @section and @section.contacts then @section.contacts else []
            
            ###*
            #   Returns either a article tree if user is a admin 
            #   or the articles belonging to a logged in user 
            #   @return {article_tree} articles from a contact or from article_tree
            ###
            getArticles: ->
                # If not any form of admin, article_tree is just articles
                if !@$root.isAdmin(2)
                    return @article_tree
                # Return selected contact articles or empty array
                return if @contact and @contact.articles then @contact.articles else []

        data: ->
            section: false
            contact: false
            article: false

        created: ->
            bus.$on 'section_changed', (section) =>
                # Update selection
                @section = section
                bus.$emit 'contact_changed', false
                # Update selected state
                for s in @getSections
                    Vue.set s, 'selected', false
                if section
                    Vue.set section, 'selected', true
                    $('#sections').dropdown 'set selected', section.id
                else
                    $('#contacts').dropdown 'restore placeholder text'
            bus.$on 'contact_changed', (contact) =>
                # Update selection
                @contact = contact
                bus.$emit 'article_changed', false
                # Update selected state
                for c in @getContacts
                    Vue.set c, 'selected', false
                if contact
                    Vue.set contact, 'selected', true
                    $('#contacts').dropdown 'set selected', contact.id
                else
                    $('#contacts').dropdown 'restore placeholder text'

            bus.$on 'article_changed', (article) =>
                # Update selection
                @article = article
                # Update selected state
                for a in @getArticles
                    Vue.set a, 'selected', false
                if article
                    Vue.set article, 'selected', true
                    $('#articles').dropdown 'set selected', article.id
                else
                    $('#articles').dropdown 'restore placeholder text'

        mounted: ->
            # Select the first section avaiable
            if @$root.isAdmin(2)
                bus.$emit 'section_changed', @article_tree[0]
            # or Article
            else
                bus.$emit 'article_changed', @article_tree[0]

</script>

<style>    
    #overview_start_steps {
        padding: 0px;
    }

    #overview_section_step, #overview_contact_step, #overview_article_step {
        height: 160px;
        overflow: hidden;
        padding: 2px !important; 
        
    }

    #overview_section_step_mobile, #overview_contact_step_mobile, #overview_article_step_mobile {        
        padding: 5px !important;         
    }

    #overview_section_step.column.step::after, #overview_contact_step.column.step::after{
        display: none;        
    }
   
    #overview_contact_step.column.step {
        border-right: 2px solid !important; 
        border-right-color: rgba(34,36,38,.15) !important;
        
    }

    #overview_section_step.column.step {
        border-right: 2px solid !important; 
        border-right-color: rgba(34,36,38,.15) !important;
        
    }
    
</style>
