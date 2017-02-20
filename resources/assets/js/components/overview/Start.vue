<template lang="pug">
    div
        div.ui.equal.width.grid.stackable.steps
            div.column.step(
                v-if="$root.isAdmin()"
                ":class"="!section ? 'active' : ''" )
                section-overview( ":sections"="getSections" )
            div.column.step(
                v-if="$root.isAdmin(2)"
                ":class"="section && !contact ? 'active' : ''" )
                contact-overview( ":contacts"="getContacts" )
            div.column.step(
                ":class"="contact && !article ? 'active' : ''" )
                article-overview( ":articles"="getArticles" )
</template>

<script lang="coffee">
    module.exports =
        name: 'Start'

        components:
            SectionOverview: require './Section.vue'
            ContactOverview: require './Contact.vue'
            ArticleOverview: require './Article.vue'

        props: [ 'article_tree' ]

        computed:
            getSections: ->
                # ArticleTree is a list of sections
                return @article_tree
            getContacts: ->
                # Return section contacts or empty array
                return if @section and @section.contacts then @section.contacts else []
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
                console.log section.name
                # Update selection
                @section = section
                bus.$emit 'contact_changed', false
                bus.$emit 'article_changed', false
                # Update selected state
                for s in @getSections
                    Vue.set s, 'selected', false
                if section
                    Vue.set section, 'selected', true
            bus.$on 'contact_changed', (contact) =>
                # Update selection
                @contact = contact
                bus.$emit 'article_changed', false
                # Update selected state
                for c in @getContacts
                    Vue.set c, 'selected', false
                if contact
                    Vue.set contact, 'selected', true
            bus.$on 'article_changed', (article) =>
                # Update selection
                @article = article
                # Update selected state
                for a in @getArticles
                    Vue.set a, 'selected', false
                if article
                    Vue.set article, 'selected', true

        mounted: ->
            # Select the first section avaiable
            if @$root.isAdmin(2)
                bus.$emit 'section_changed', @article_tree[0]
            # or Article
            else
                bus.$emit 'article_changed', @article_tree[0]

</script>
