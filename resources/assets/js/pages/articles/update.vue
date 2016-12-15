<template lang="pug">
    div( v-if="article!=null" )
        article-form( v-show="!preview" ":original"="article" )
        article-preview(
            v-if="preview"
            ":article"="preview_article"
        )
</template>

<script lang="coffee">
    ArticleForm = require '../../components/ArticleForm.vue';
    ArticlePreview = require '../../components/ArticlePreview.vue';

    module.exports = {

        name: 'Update'

        components: { ArticleForm, ArticlePreview }

        data: ->
            article: null
            preview_article: null
            preview: false

        methods: {

            getArticle: (id) ->
                @$root.loading = true;

                @$http.get('articles/'+id).then(
                    (response) =>
                        @article = response.data;
                        @$root.loading = false;

                    (response) =>
                        bus.$emit('error',response.data);
                        @$root.loading = false;
                );

            previewArticle: (article) ->
                @preview_article = article
                @preview = true

            modifyArticle: ->
                console.log 'test'
                @preview_article = null
                @preview = false

            updateArticle: (article) ->
                @$http.put('articles/'+article.id,article).then(
                    (response) =>
                        @$router.push({ path: '/articles' });

                    (response) => bus.$emit('error',response.data)
                );

        },

        created: ->
            this.getArticle(this.$route.params.id);
            bus.$on('article_form_preview', (payload) => @previewArticle(payload) )
            bus.$on('article_form_modify', => @modifyArticle() )
            bus.$on('article_form_update', (payload) => @updateArticle(payload) )

        beforeDestroy: ->
            bus.$off('article_form_preview');
            bus.$off('article_form_modify');
            bus.$off('article_form_update');

    }
</script>
