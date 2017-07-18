<template lang="pug">
    div.ui.segments
        div.ui.segment.no-padding
            div.ui.grid.equal.width.stackable( v-show="!user" )
                div.column.eight.wide
                    h2.ui.header
                        div.content {{ translate('overview.welcome') }}
            div.ui.grid.mobile.reversed.stackable.equal.width#overview_pages_grid( v-if="user")

                div.column.eight.wide.computer.tablet.only#overview_pages_tablet_column
                    h2.ui.header
                        div.content {{ user.fullname }}
                    div.ui.message
                        p {{ translate('overview.welcome_info') }}
                        a( href="docs" ) {{ translate('overview.welcome_help') }}

                div.column.center.aligned.eight.wide.mobile.only#overview_pages_mobile_column
                    h2.ui.header
                        div.content {{ user.fullname }}
                    div.ui.message
                        p {{ translate('overview.welcome_info') }}
                        a( href="docs" ) {{ translate('overview.welcome_help') }}

                div.column.right.floated.tablet.computer.only
                    user-card(
                        v-if="user"
                        ":user"="user"
                        picture = "picture_only"
                        )

        div.ui.no-padding( v-if="user" )
            loading( v-if="$root.loading" )
            start-overview(
                v-if="!$root.loading"
                ":article_tree"="article_tree" )
</template>

<script lang="coffee">
    module.exports =
        name: 'List'
        components:
            UserCard: require '../../components/user/card.vue'
            StartOverview: require '../../components/overview/Start.vue'
        data: ->
            user: false
            article_tree: false
        methods:
            ###*
            #   Get a article by using its id. 
            #   @param {id} articles id
            #   @return {article} if exist, otherwise 0.
            ###
            getArticleById: (id) ->
                # If not any form of admin, article_tree is just articles
                if !@$root.isAdmin(2)
                    for article in @article_tree
                        if Number(article.id) == Number(id)
                            return article
                else
                    for section in @article_tree
                        for contact in section.contacts
                            for article in contact.articles
                                if Number(article.id) == Number(id)
                                    return article
                return 0
            
            ###*
            #   Gets a overview article tree from backend.
            ###
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
            
            ###*
            #   Get users own articles from backend. 
            ###
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
                        
            ###*
            #   Redirect user to article preview.
            #   @param {article} article to preview
            ###
            previewArticle: (article) ->
                @$router.push @$root.encodeArtNR article
            
            ###*
            #   Asks the user if it want to remove a article.
            #   @param {article} article to remove
            ###
            attemptRemove: (article) ->
                bus.$emit 'show_message',
                    title: @$root.translate('article_list.remove_article_title') + "''"+article.name+"''."
                    message: @$root.translate('article_list.remove_article_message')
                    type:'confirm'
                    cb: => @removeArticle article
                        
            ###*
            #   Removes a article from the backend. 
            #   @param {article} article to remove  
            ###
            removeArticle: (article) ->
                @$http.delete('api/articles/'+article.id).then(
                    (response) =>
                        bus.$emit 'success',
                            title: @$root.translate('article_list.success_message')
                            details: @$root.translate('article_list.article_removed')
                        Vue.set article, 'removed', true
                    (response) => bus.$emit 'error', response.data
                )
            
            ###*
            #   Attempt to update a article in the backend.
            #   @param {article} article to update
            ###
            attemptUpdate: (article) ->
                # Validation
                @updateArticle article
            
            ###*
            #   Updates a article in the backend.
            #   @param {article} article to update
            ###
            updateArticle: (article) ->
                @$http.put('api/articles/'+article.id, article).then(
                    (response) =>
                        bus.$emit 'success',
                            title: @$root.translate('article_list.success_message')
                            details: @$root.translate('article_list.article_updated')
                    (response) => bus.$emit 'error', response.data
                )
            
            ###*
            #   Sets users overview.
            ###
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
            bus.$on 'ui_item_remove', (item) => @attemptRemove item
            bus.$on 'ui_item_preview', (item) => @previewArticle item
            bus.$on 'ui_item_changed', (item) => @attemptUpdate item
            bus.$on 'ui_item_edit', (item) =>
                @$router.push path: '/ui/articles/'+item.id
            
            bus.$on 'start_item_edit', (item) =>
                @$router.push path: '/ui/articles/'+item.id
            bus.$on 'start_item_remove', (item) => @attemptRemove item
            bus.$on 'start_item_preview', (item) => @previewArticle item
           
            bus.$on 'publish_interval_changed', (id, new_value) =>
                item = @getArticleById id
                if item
                    Vue.set item, 'publish_interval', new_value
                    bus.$emit 'start_item_changed', item
            bus.$on 'bidding_interval_changed', (id, new_value) =>
                item = @getArticleById id
                if item
                    Vue.set item, 'bidding_interval', new_value
                    bus.$emit 'start_item_changed', item
        beforeDestroy: ->
            bus.$off 'user_changed', @setUser
            bus.$off 'start_item_remove'
            bus.$off 'start_item_preview'
            bus.$off 'start_item_changed'
            bus.$off 'start_item_edit'
            bus.$off 'publish_interval_changed'
            bus.$off 'bidding_interval_changed'
</script>

<style>
    #overview_pages_grid{
        margin-top: 0px;
    }
    #overview_pages_tablet_column, #overview_pages_mobile_column {
        position: relative; 
        top: -10px;
    }
    
</style>