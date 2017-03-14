<template lang="pug">
    div
        div.ui.top.attached.fluid.menu
            search( ":search"="search" )

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
        div.ui.padded.grid
            div.row
                table.ui.very.basic.table.very.compact.unstackable
                    tbody( v-item="$route.hash.substr(1)" )
                        tr( v-for="(item, index) in itemsNew" )
                            td.slim
                                div.ui.input.fluid
                                    input( v-model="item.name" ":placeholder"="translate('placeholder.type')+' '+translate('name')"
                                    v-focus="" )
                            td
                                div.ui.icon.basic.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )
                        tr(
                            v-for="(item, filterIndex) in filterItems"
                            ":id"="item.id"
                            ":class"="item.edit ? 'active' : ''" )
                            td.slim
                                div.ui.item.fluid(style="margin-left: 5px;")
                                    div.ui.input.fluid( v-if="item.edit" )
                                        input( v-model="item.name_new"
                                        ":placeholder"="translate('placeholder.type')+' '+translate('name')"
                                        v-focus="" ).collapsing
                                    div.ui.vertical.segment.basic( "v-if"="!item.edit && itemHaveData('name', item.name)" )
                                        h4.ui.sub.header {{ translate('name') }}
                                        p {{ item.name }}
                            td.collapsing.bottom.aligned
                                div.ui.icon.basic.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )
                        tr( v-if="toolsBottom")
                            td
                            td.right.aligned
                                div.ui.icon.basic.buttons
                                    component( v-for="tool in toolsBottom" ":is"="tool" ":from"="from" )
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
        name: 'CategoryMobileList'
        props: [
            'items'
            'itemsNew'
            'toolsRow'
            'toolsBottom'
        ]
        components:
            Search: require '../../tools/Search.vue'
        mixins: [
            require '../../../mixins/Filters.vue'
        ]
        data: ->
            order: 'name'
            desc: 1
        computed:
            from: ->
                @$route.path.substring 1
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this

            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name']
                    .length
            firstColumn: ->
                @columns[Object.keys(@columns)[0]]
        methods:
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
            itemHaveData: (column, item) ->
                if item
                    if (column.type == 'array')
                        return (item.length > 0)
                    return true
                return false
</script>
