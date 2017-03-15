<template lang="pug">
    div
        div.ui.top.attached.menu
            div.left.menu
                search( ":search"="search" )            
                paginate(":total"="countItems"
                    ":show-pagination"="(search=='' && !limitOffBtn)")
            div.right.menu
                sort(
                    ":order"="order"
                    ":desc"="desc"
                    ":columns"="['name','updated_at','categories']" )
        div.ui.padded.grid
            div.row.top.attached
                article-card(
                    v-for="(item, index) in filterItems"
                    ":item"="item"
                    ":tools"="toolsRow"
                    ":from"="from"
                    )

            pagination.row.attached(
                ":total"="countItems"
                ":show-pagination"="(search=='' && !limitOffBtn)"
                )
                div( slot="replacePagination" )
                    button.ui.button.searchresults_expander(
                        v-if="limitOffBtn"
                        @click="limitOff = true"
                        )
                        | {{ translate('show_all_results') }}
</template>

<script lang="coffee">
    module.exports =
        name: 'ItemGrid'
        props: [
            'items'
            'toolsRow'
        ]
        mixins: [
            require '../../../mixins/Filters.vue'
        ]
        components:
            ArticleCard: require '../card.vue'
            Search: require '../../tools/Search.vue'
            Sort: require '../../tools/Sort.vue'
            Paginate: require '../../tools/Paginate.vue'
        data: ->
            order: 'updated_at'
            desc: 1
        computed:
            from: ->
                @$route.path.substring 1
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','description','categories']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this
            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','description','categories']
                    .length
        methods:
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
</script>
