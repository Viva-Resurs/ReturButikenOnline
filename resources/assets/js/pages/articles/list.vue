<template>
    <article-list :ithems="ithems"></article-list>
</template>

<script>
    import ArticleList from '../../components/ArticleList.vue'

    export default {

        name: 'List',

        components: { ArticleList },

        data: function(){
            return {
                ithems: []
            }
        },

        methods: {

            attemptRemove(article){
                // Are you sure?
                // ... yes
                this.removeArticle(article);
            },

            removeArticle(article){
                this.$http.delete('articles/'+article.id).then(
                    (response) => {
                        bus.$emit('success','removed_article');
                        article.removed = true;
                        this.ithems.reverse();
                    },
                    (response) => bus.$emit('error',response)
                );
            },

            updateArticle(article) {

                this.$http.put('articles/'+article.id,article).then(
                    (response) => {
                        console.log('ok');
                        //this.$router.push({ path: '/articles' });
                    },
                    (response) => bus.$emit('error',response)
                );

            },

            getArticles(){

                this.$root.loading = true;

                this.$http.get('articles').then(
                    (response) => {
                        this.ithems = response.data;
                        this.$root.loading = false;
                    },
                    (response) => {
                        bus.$emit('error',response);
                        this.$root.loading = false;
                    }
                );
            }

        },

        created: function(){
            this.getArticles();

            // Listen for changes in data by components
            bus.$on('article_remove', payload => this.attemptRemove(payload) );
            bus.$on('article_changed', payload => this.updateArticle(payload) );
        }
    }
</script>
