<template lang="pug">
    div.ui.section(v-if="user")
        div.ui.attached( v-if="$root.loading" )
            loading

        div.ui.attached( v-else="" )
            div.ui.horizontal.segments
                div.ui.segment
                    h4 Sections
                    section-overview(":sections"="article_tree")
                div.ui.segment
                    h4 Contacts
                    contact-overview(":section"="section")
                div.ui.segment
                    h4 Articles
                    article-overview(":contact"="contact")
</template>

<script lang="coffee">
    module.exports =
        name: 'Start'

        components:
            SectionOverview: require './Section.vue'
            ContactOverview: require './Contact.vue'
            ArticleOverview: require './Article.vue'

        props: [
            'article_tree'
            'user'
        ]

        data: ->
            section: false
            contact: false

        created: ->
            bus.$on 'section_changed', (section) => @section = section
            bus.$on 'contact_changed', (contact) => @contact = contact
            bus.$on 'article_changed', (article) => @article = article
</script>
