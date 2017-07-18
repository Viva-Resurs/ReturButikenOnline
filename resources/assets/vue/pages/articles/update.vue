<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div( v-if="!$root.loading" )
            article-form(
                v-show = "!preview"
                ":draft" = "article"
                ":categories" = "categories"
                ":contacts" = "contacts" )
            article-preview(
                v-if = "preview"
                ":article" = "preview" )
</template>

<script lang="coffee">
    module.exports =
        name: 'Update'
        components:
            ArticleForm    : require '../../components/article/form.vue'
            ArticlePreview : require '../../components/article/preview.vue'
        data: ->
            preview: false
            article: null
            categories: null
            contacts: null
        methods:
            ###*
            #   Get a article from backend using its article id. 
            #   @param {id} id of article
            ###
            getArticle: (id) ->
                @$root.loading = true
                @$http.get('api/articles/'+id).then(
                    (response) =>
                        @article = response.data
                        @getCategoryList()
                        @getContactList()
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )
            
            ###*
            #   Returns a list of categores from backend.
            ###
            getCategoryList: ->
                @$http.get('api/categories').then(
                    (response) =>
                        @categories = response.data ? null
                    (response) =>
                        bus.$emit 'error', response.data
                )
            
            ###*
            #   Returns a list of contacts from backend.
            ###
            getContactList: ->
                @$http.get('api/contacts').then(
                    (response) =>
                        @contacts = response.data ? null
                        if @contacts.length > 0 and
                            @article.contacts.length == 0
                                @article.contacts.push @contacts[0]
                    (response) =>
                        bus.$emit 'error', response.data
                )
            
  
            ###*
            #   Updates a article in the backend.
            #   @param {article} article to update
            ###
            updateArticle: (article) ->
                @$http.put('api/articles/'+article.id, article).then(
                    (response) =>
                        @$router.push path: '/ui/articles'
                    (response) =>
                        bus.$emit 'error', response.data
                )
        created: ->
            # Get Article to edit
            @getArticle @$route.params.id
            # When previewing Article
            bus.$on 'article_form_preview', (article) =>
                @preview = article
            # When modifying Article (Exit/Unset preview)
            bus.$on 'article_form_modify', =>
                @preview = false
            # When saving changes to Article
            bus.$on 'article_form_update', (article) => @updateArticle article
        beforeDestroy: ->
            bus.$off 'article_form_preview'
            bus.$off 'article_form_modify'
            bus.$off 'article_form_update'
</script>
