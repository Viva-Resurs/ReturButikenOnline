<template lang="pug">
    article-form
</template>

<script lang="coffee">
    ArticleForm = require '../../components/ArticleForm.vue';

    module.exports = {

        name: 'Create'

        components: { ArticleForm }

        methods:
            createArticle: (article) ->
                this.$http.post('articles',article).then(
                    (response) =>
                        console.log('ok');
                        this.$router.push({ path: '/articles' });

                    (response) => bus.$emit('error',response.data)
                );

        created: ->
            bus.$on('article_form_update', (payload) => @createArticle(payload) )


        beforeDestroy: ->
            bus.$off('article_form_update');


    }
</script>
