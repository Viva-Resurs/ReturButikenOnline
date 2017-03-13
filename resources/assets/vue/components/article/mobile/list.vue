<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div.ui.attached( v-else="" )
            div.ui.equal.width.grid
                div.row
                    div.column.grid
                        div.ui.icon.input.fluid
                            input.prompt(
                                v-model="search"
                                ":placeholder"="translate('placeholder.search')"
                                )
                            i.search.icon
                    div.column.right.aligned
                        div.ui.floated.basic.button.dropdown#order( v-dropdown="" )
                            div.default.value
                                | {{ translate(order) }} &nbsp;
                                i.icon.label.sort.right.floated(
                                    ":class" = "(desc==1)?'ascending':'descending'"
                                )
                            div.menu
                                div.item(
                                    ":class"="(order=='name')?'active':''"
                                    @click="setOrder('name')"
                                    )
                                    | {{ translate('name') }} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order=='name'"
                                        ":class" = "(desc)?'ascending':'descending'"
                                    )
                                div.item(
                                    ":class"="(order=='updated_at')?'active':''"
                                    @click="setOrder('updated_at')"
                                    )
                                    | {{ translate('updated') }} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order=='updated_at'"
                                        ":class" = "(desc)?'ascending':'descending'"
                                    )
                                div.item(
                                    ":class"="(order=='categories')?'active':''"
                                    @click="setOrder('categories')"
                                    )
                                    | {{ translate('categories') }} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order=='categories'"
                                        ":class" = "(desc)?'ascending':'descending'"
                                    )
                                div.item(
                                    ":class"="(order=='public')?'active':''"
                                    @click="setOrder('public')"
                                    )
                                    | {{ translate('public') }} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order=='public'"
                                        ":class" = "(desc)?'ascending':'descending'"
                                    )
            br
            div.ui.grid.padded.row
                article-card(
                    v-for="(item, index) in filterItems"
                    ":item"="item"
                    ":tools"="toolsRow"
                    ":from"="from"
                    )
        pagination.ui.bottom.attached(
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
        data: ->
            order: 'updated_at'
        computed:
            from: ->
                @$route.path.substring 1
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterBy item, @search, @columns
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this
            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterBy item, @search, @columns
                    .length
        methods:
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
</script>
