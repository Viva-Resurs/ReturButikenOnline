<template lang="pug">
    div
        div.ui.grid.equal.width.celled.columns.stackable
            div.ui.column( v-if="$root.isAdmin()" )
                h4 Sections
                section-overview( ":sections"="article_tree" )
            div.ui.column(  v-if="$root.isAdmin(2)" )
                h4 Contacts
                contact-overview( ":contacts"="getContacts" )
            div.ui.column
                h4 Articles
                    div.ui.label.circular 45
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
            getContacts: ->
                return if @section and @section.contacts then @section.contacts else []
            getArticles: ->
                # If not any form of admin, article_tree is just articles
                if !@$root.isAdmin(2)
                    return @article_tree
                # Return selected contact articles or empty array
                return if @contact and @contact.articles then @contact.articles else []

            sectionExist:() ->
                return @article_tree != false && @article_tree.length > 0

        data: ->
            section: false
            contact: false
            article: []

        created: ->
            bus.$on 'section_changed', (section) =>
                @section = section
                @contact = false
                @article = false
            bus.$on 'contact_changed', (contact) =>
                @contact = contact
                @article = false
            bus.$on 'article_changed', (article) =>
                @article = article

        mounted: ->
            if @$root.isAdmin(2)
                @section = @article_tree[0]

</script>
