<template>
    <item-grid
        header="Articles"
        :columns="columns"
        :card="card"
        :tools="$options.components.ItemTools"
        :extraTools="$options.components.IntervalTools"
        :items="items">
    </item-grid>
</template>

<script lang="coffee">
    ItemGrid = require '../../components/ItemGrid.vue';
    ItemTools = require '../../components/ItemTools.vue';
    IntervalTools = require '../../components/IntervalTools.vue';

    module.exports = {

        name: 'List'

        components: { ItemGrid, ItemTools, IntervalTools }

        data: ->
            items: []
            card:
                header:
                    label: 'name'
                meta:
                    updated_at:
                        title: 'Updated'
                        label: 'updated_at'
                        class: 'right floated time'
                    categories:
                        title: 'Categories'
                        label: 'categories'
                        class: 'category'
                description:
                    label: 'desc'
                extra:
                    public:
                        label: 'public'
                        class: ''
                        type: 'boolean'
                        true: 'Publicerad för allmänheten'
                        false: 'Publicerad på kommunens intranät'
            columns:
                name:
                    label: 'name'
                    type: 'string'
                    search: true
                    sort: true
                    tooltip: 'desc'
                    class: 'link'
                updated_at:
                    label: 'updated_at'
                    type: 'number'
                    desc: true
                    default_sort: true
                    search: true
                    sort: true
                    class: 'link'
                public:
                    label: 'public'
                    search: false
                    sort: false
                    type: 'checkbox'
                    checkbox_true: 'Publicerad för allmänheten'
                    checkbox_false: 'Publicerad på kommunens intranät'
                    class: 'center aligned collapsing'

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
            bus.$on('item_remove', (item) => @attemptRemove(item) )
            bus.$on('item_edit', (item) => @$router.push({ path: '/articles/'+item.id }) )
            bus.$on('article_changed', (payload) => @updateArticle(payload) )
            bus.$on('publish_interval_changed', (id,new_value) =>
                for item in this.items
                    if (Number item.id == Number id)
                        item.publish_interval = new_value
                        bus.$emit('article_changed',item)

            );

            bus.$on('bidding_interval_changed', (id,new_value) =>
                for item in this.items
                    if (Number item.id == Number id)
                        item.bidding_interval = new_value
                        bus.$emit('article_changed',item)

            );

        beforeDestroy: ->
            bus.$off('item_edit');
            bus.$off('item_remove');
            bus.$off('article_changed');
            bus.$off('publish_interval_changed')
            bus.$off('bidding_interval_changed')
    }
</script>
