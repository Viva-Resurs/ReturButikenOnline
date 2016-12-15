<template lang="pug">
    div
        article-form( v-show="!preview" )
        article-preview(
            v-if="preview"
            ":article"="preview_article"
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

        created: ->
            bus.$on('article_form_preview', (payload) => @previewArticle(payload) )
            bus.$on('article_form_modify', => @modifyArticle() )
            bus.$on('article_form_update', (payload) => @createArticle(payload) )


        beforeDestroy: ->
            bus.$off('article_form_preview');
            bus.$off('article_form_modify');
            bus.$off('article_form_update');


    }
</script>
