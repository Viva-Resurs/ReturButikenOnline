<template lang="pug">
    div.no-top-padding
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
                    div.right.menu
                        div.ui.buttons
                            add.right.item.large.icon.no-padding( from="articles" )
        div.ui.padded.grid
            div.row( v-if="countItems == 0" )
                div.ui.column.warning.message
                    p {{ (search!='') ? translate('no_results') : translate('empty') }}
            div.row( v-if="countItems > 0" )
                article-card(
                    v-for="(item, index) in filterItems"
                    ":item"="item"
                    ":tools"="toolsRow"
                    ":from"="getFrom"
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
        name: 'ArticleMobileList'
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
            Add: require '../../tools/Add.vue'
        data: ->
            order: 'updated_at'
            desc: 1
        computed:
            ###*
            #   Search/Filters items by name, desc, categories and article_nr.
            #   @return {item} matched items
            ###
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','description','categories','article_nr']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this
            
            ###*
            #   Returns number of matched results.
            #   @return {number} number of filtered items
            ###
            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','description','categories','article_nr']
                    .length
        methods:
            ###*
            # Triggers position change to previous page.
            ###
            prevPage: ->
                if @offset-@maxItems < 0
                    return @firstPage()
                bus.$emit 'offset_changed', @offset - @maxItems
            
            ###*
            # Triggers position change to next page.
            ###
            nextPage: ->
                window.scrollTo 0, 0
                if @offset >= @total-@maxItems
                    return @lastPage()
                bus.$emit 'offset_changed', @offset + @maxItems

            ###*
            #   Returns a formatted tooltip replacing newline(\n) with <br>.
            #    @return {string} formatted text
            ###
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
</script>
