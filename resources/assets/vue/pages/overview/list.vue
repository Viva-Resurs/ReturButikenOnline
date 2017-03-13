<template lang="pug">
    div.ui.segments
        div.ui.segment
            div.ui.grid.two.columns.stackable( v-if="!user" )
                div.column.eight.wide
                    h2.ui.header
                        div.content {{ translate('overview.welcome') }}
            div.ui.grid.two.columns.stackable( v-if="user" )
                div.column.eight.wide
                    h2.ui.header
                        div.content {{ translate('overview.welcome') + ' ' + user.fullname }}
                    p {{ translate('overview.welcome_info') }}
                    a( href="docs" ) {{ translate('overview.welcome_help') }}
                div.column.right.floated.eight.wide
                    user-card(
                        v-if="user"
                        ":user"="user"
                        "type" = "horizontal"
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

        created: ->
            # Check if User is ready
            if @$root.user
                @setUser()
            # When User is changed
            bus.$on 'user_changed', @setUser

        beforeDestroy: ->
            bus.$off 'user_changed', @setUser
</script>