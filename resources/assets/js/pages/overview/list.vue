<template lang="pug">
    div.ui.segments
        div.ui.segment
            div.ui.grid.two.columns.stackable
                div.column.ten.wide
                    h2.header Welcome
                div.column.right.floated.six.wide
                    user-card(
                        v-if="user"
                        ":user"="user"
                        "type" = "vertical"
                        "detailed"="true"
                        picture="true" )
        div.ui.segment( v-if="user" )
            loading( v-if="$root.loading" )
            start-overview(
                v-if="!$root.loading"
                ":article_tree"="article_tree" )
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

            getArticleOverview: ->
                @$root.loading = true
                @$http.get('api/overview/my').then(
                    (response) =>
                        @article_tree = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

            setUser: ->
                @user = @$root.user
                if @$root.isAdmin() || @$root.isAdmin(2)
                    @getOverview()
                else
                    @getArticleOverview()

                bus.$off 'user_changed', @setUser

        created: ->
            if @$root.user
                @setUser()
            else
                bus.$on 'user_changed', @setUser

</script>
