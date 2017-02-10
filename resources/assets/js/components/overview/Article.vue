<template lang="pug">
    div.ui.vertical.right.attached.menu
        a.item(v-for="article in contact.articles" @click="selectArticle(article)") {{ article.name }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Article'

        methods:
            selectArticle: (article) ->
                bus.$emit 'article_changed',article

        props: [
            'contact'
        ]

</script>
