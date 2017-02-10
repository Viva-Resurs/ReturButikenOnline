<template lang="pug">
    div.ui.segments
        div.ui.segment
            div.ui.grid.two.columns.stackable
                div.column.ten.wide
                    h2.header Welcome
                div.column.right.floated.six.wide
                    user-card(v-if="user" ":user"="user" "detailed"="true" picture="true")

        div.ui.secondary.segment(v-if="user")
            start-overview(
                ":article_tree"="article_tree"
                ":user"="user"
                )
</template>

<script lang="coffee">
    module.exports =
        name: 'List'
        components:
            UserCard: require '../../components/UserCard.vue'
            StartOverview: require '../../components/overview/Start.vue'

        data: ->
            user: false
            article_tree: false

        methods:
            getOverview: ->
                @$root.loading = true
                @$http.get('api/overview').then(
                    (response) =>
                        @article_tree = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

            setUser: ->
                @user = @$root.user
                @getOverview()
                bus.$off 'user_changed', @setUser

        created: ->
            if @$root.user
                @setUser()
            else
                bus.$on 'user_changed', @setUser

</script>
