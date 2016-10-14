<template>
    <article-form v-if="article!=null" :original="article"></article-form>
</template>

<script>
    import ArticleForm from '../../components/ArticleForm.vue'

    export default {

        name: 'Update',

        components: { ArticleForm },

        data: function(){
            return {
                article: null
            }
        },

        methods: {

            getArticle(id){

                this.$root.loading = true;

                this.$http.get('articles/'+id).then(
                    (response) => {
                        this.article = response.data;
                        this.$root.loading = false;
                    },
                    (response) => {
                        bus.$emit('error',response);
                        this.$root.loading = false;
                    }
                );
            },

            updateArticle(article) {

                this.$http.put('articles/'+article.id,article).then(
                    (response) => {
                        console.log('ok');
                        this.$router.push({ path: '/articles' });
                    },
                    (response) => bus.$emit('error',response)
                );

            }

        },

        created: function() {

            this.getArticle(this.$route.params.id);

            bus.$on('article_changed', payload => this.updateArticle(payload) );

        }

    }
</script>
