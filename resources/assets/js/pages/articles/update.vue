<template>
    <article-form v-if="article!=null" :original="article"></article-form>
</template>

<script lang="coffee">
    ArticleForm = require '../../components/ArticleForm.vue';

    module.exports = {

        name: 'Update'

        components: { ArticleForm }

        data: ->
            article: null

        methods: {

            getArticle: (id) ->
                @$root.loading = true;

                @$http.get('articles/'+id).then(
                    (response) =>
                        @article = response.data;
                        @$root.loading = false;

                    (response) =>
                        bus.$emit('error',response);
                        @$root.loading = false;
                );

            updateArticle: (article) ->
                @$http.put('articles/'+article.id,article).then(
                    (response) =>
                        console.log('ok');
                        @$router.push({ path: '/articles' });

                    (response) => bus.$emit('error',response)
                );

        },

        created: ->
            this.getArticle(this.$route.params.id);

            bus.$on('article_form_update', (payload) => this.updateArticle(payload) )

        beforeDestroy: ->
            bus.$off('article_form_update');

    }
</script>
