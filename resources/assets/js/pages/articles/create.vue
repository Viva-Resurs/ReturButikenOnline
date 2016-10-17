<template>
    <article-form></article-form>
</template>

<script>
    import ArticleForm from '../../components/ArticleForm.vue'

    export default {

        name: 'Create',

        components: { ArticleForm },

        methods: {

            createArticle(article) {

                this.$http.post('articles',article).then(
                    (response) => {
                        console.log('ok');
                        this.$router.push({ path: '/articles' });
                    },
                    (response) => bus.$emit('error',response)
                );

            }

        },

        created: function() {
            bus.$on('article_changed', payload => { this.createArticle(payload); } );
        },

        beforeDestroy: function() {
            bus.$off('article_changed');
        }

    }
</script>
