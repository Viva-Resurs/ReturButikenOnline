<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div( v-else="" )
            div.ui.top.attached.menu
                div.left.menu
                    div.ui.search.item
                        div.ui.transparent.icon.input(style="width: 150px")
                            input.prompt(
                                v-model="search"
                                ":placeholder"="translate('placeholder.search')"
                                )
                            i.search.icon

                div.ui.right.dropdown.item#order( v-dropdown="" )
                    div.default.value

                        i.icon.label.sort( ":class" = "(desc == 1)?'ascending':'descending'" )

                        i.icon.label.font(
                            v-if="order=='name'"
                        )
                        i.icon.label.wait(
                            v-if="order=='updated_at'"
                        )
                        i.icon.label.pie.chart(
                            v-if="order=='categories'"
                        )
                        i.icon.label.universal.access(
                            v-if="order=='public'"
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
        data: ->
            order: 'updated_at'
            desc: 1
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
