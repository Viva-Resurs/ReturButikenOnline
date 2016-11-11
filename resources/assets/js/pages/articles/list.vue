<template>
    <article-list :items="items"></article-list>
</template>

<script lang="coffee">
    ArticleList = require '../../components/ArticleList.vue';

    module.exports = {

        name: 'List'

        components: { ArticleList }

        data: ->
            items: []

        methods:
            attemptRemove: (article) ->
                # Are you sure?
                # ... yes
                @removeArticle(article);

            removeArticle: (article) ->
                @$http.delete('articles/'+article.id).then(
                    (response) =>
                        bus.$emit('success','removed_article')
                        article.removed = true;
                        @items.reverse();

                    (response) => bus.$emit('error',response)
                );

            updateArticle: (article) ->
                @$http.put('articles/'+article.id,article).then(
                    (response) =>
                        console.log('ok');
                        #@$router.push({ path: '/articles' })

                    (response) => bus.$emit('error',response)
                );

            getArticles: () ->
                @$root.loading = true;

                @$http.get('articles').then(
                    (response) =>
                        @items = response.data
                        @$root.loading = false;

                    (response) =>
                        bus.$emit('error',response)
                        @$root.loading = false;

                );

        created: ->
            this.getArticles();
            # Listen for changes in data by components
            bus.$on('article_remove', (payload) => @attemptRemove(payload) )
            bus.$on('article_changed', (payload) => @updateArticle(payload) )

        beforeDestroy: ->
            bus.$off('article_remove');
            bus.$off('article_changed');
    }
</script>
