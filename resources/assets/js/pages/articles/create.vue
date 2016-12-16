<template lang="pug">
    div
        article-form( v-show="!preview"
            ":article"="article"
            ":categories"="categories"
            ":contacts"="contacts")
            
        article-preview(
            v-if="preview"
            ":article"="preview_article"
            ":categories" = "categories"
            ":contacts" = "contacts"
        )
</template>

<script lang="coffee">
    ArticleForm = require '../../components/ArticleForm.vue';
    ArticlePreview = require '../../components/ArticlePreview.vue';

    module.exports = {

        name: 'Create'

        components: { ArticleForm, ArticlePreview }

        data: ->
            preview_article: false
            preview: false

            article:
                name: ''
                desc: ''
                public: false
                publish_interval: ''
                bidding_interval: ''
                selected_categories: []
                selected_images: []
                selected_contacts: []

            categories:
                null
            contacts:
                null

        methods:
            previewArticle: (article) ->
                @preview_article = article
                @preview = true

            modifyArticle: ->
                @preview_article = false
                @preview = false

            createArticle: (article) ->
                this.$http.post('articles',article).then(
                    (response) =>
                        console.log('ok');
                        this.$router.push({ path: '/articles' });

                    (response) => bus.$emit('error',response.data)
                );

            getCategoryList: ->
                this.$http.get('categories').then(
                    (response) =>
                        this.categories = response.data;

                    (response) =>
                        bus.$emit('error', response.data);
                        this.categories = []

                    )

            getContactList: ->
                this.$http.get('contacts').then(
                    (response) =>
                        list = response.data;
                        if (list.length > 0)
                            if (@article.selected_contacts.length == 0)
                                @article.selected_contacts.push( list[0].id );
                        @contacts = response.data;
                    (response) =>
                        bus.$emit('error', response.data);
                        @contacts = []
                    )

        created: ->
            bus.$on('article_form_preview', (payload) => @previewArticle(payload) )
            bus.$on('article_form_modify', => @modifyArticle() )
            bus.$on('article_form_update', (payload) => @createArticle(payload) )

            # Get section-admins
            @getContactList()

            # Get categories
            @getCategoryList()


        beforeDestroy: ->
            bus.$off('article_form_preview');
            bus.$off('article_form_modify');
            bus.$off('article_form_update');


    }
</script>
