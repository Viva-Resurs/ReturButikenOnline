<template lang="pug">
    div
        div.ui.top.attached.menu
            div.left.menu
                search( ":search"="search" ":results"="countItems" )
            div.right.menu.fitted.item
                paginate(
                    ":offset"="offset"
                    ":total"="countItems"
                    ":show-pagination"="search==''" )
                div.right.menu
                    sort(
                        ":order"="order"
                        ":desc"="desc"
                        ":columns"="['name','updated_at','categories']" )

        div.ui.padded.grid
            div.row( v-if="countItems == 0" )
                div.ui.column.warning.message
                    p {{ (search!='') ? translate('no_results') : translate('empty') }}
            div.row( v-if="countItems > 0" )
                article-card(
                    v-for="(item, index) in filterItems"
                    ":item"="item"
                    ":from"="from"
                    )

        div.row( v-if="countItems > 0 && search!=''" )
            button.ui.button.searchresults_expander(
                v-if="limitOffBtn"
                @click="limitOff = true" )
                | {{ translate('show_all_results') }}
        div.row( v-if="countItems > 0 && search==''" )
            button.ui.button.left.floated(
                v-if="offset-maxItems >= 0"
                @click="prevPage()" ) Previous page
            button.ui.button.right.floated(
                v-if="countItems > offset+maxItems"
                @click="nextPage()" ) Next page
</template>

<script lang="coffee">
    module.exports =
        name: 'Shop'
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
            Paginate: require '../../tools/Paginate.vue'
            Sort: require '../../tools/Sort.vue'
        data: ->
            order: 'updated_at'
            desc: 1
        computed:
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
            prevPage: ->
                if @offset-@maxItems < 0
                    return @firstPage()
                bus.$emit 'offset_changed', @offset - @maxItems
            nextPage: ->
                window.scrollTo 0, 0
                if @offset >= @total-@maxItems
                    return @lastPage()
                bus.$emit 'offset_changed', @offset + @maxItems

            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
</script>
