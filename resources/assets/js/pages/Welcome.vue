<template lang="pug">
    div.ui.segments
        div.ui.segment
            div.ui.grid.two.columns.stackable
                div.column.ten.wide
                    h2.header Welcome
                div.column.right.floated.six.wide
                    user-card(v-if="user" ":user"="user" "detailed"="true" picture="true")
        div.ui.secondary.segment(v-if="user")
            start-details(":articles"="articles" ":sections"="sections" ":user"="user")
</template>

<script lang="coffee">
    module.exports =
        name: 'Welcome'
        components:
            UserCard: require './../components/UserCard.vue'
            StartDetails: require './../components/StartDetails.vue'
        data: ->
            user: false
            articles: []
            sections: []

        methods:
            getArticles: ->
                @$root.loading = true
                @$http.get('api/articles').then(
                    (response) =>
                        @articles = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

            getSections: ->
                @$root.loading = true
                @$http.get('api/sections').then(
                    (response) =>
                        @sections = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

        created: () ->
            bus.$on('user_changed', (new_user) =>
                @user = new_user
                @getArticles();
                if @user.roles[0].name == 'admin'
                    @getSections()
            )


</script>
