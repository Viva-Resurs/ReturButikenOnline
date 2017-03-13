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
                                    ":class"="(order=='username')?'active':''"
                                    @click="setOrder('username')"
                                    )
                                    | {{ translate('username') }} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order=='username'"
                                        ":class" = "(desc)?'ascending':'descending'"
                                    )
                                div.item(
                                    ":class"="(order=='fullname')?'active':''"
                                    @click="setOrder('fullname')"
                                    )
                                    | {{ translate('fullname') }} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order=='fullname'"
                                        ":class" = "(desc)?'ascending':'descending'"
                                    )
                                div.item(
                                    ":class"="(order=='sections')?'active':''"
                                    @click="setOrder('sections')"
                                    )
                                    | {{ translate('sections') }} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order=='sections'"
                                        ":class" = "(desc)?'ascending':'descending'"
                                    )
                                div.item(
                                    ":class"="(order=='roles')?'active':''"
                                    @click="setOrder('roles')"
                                    )
                                    | {{ translate('roles') }} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order=='roles'"
                                        ":class" = "(desc)?'ascending':'descending'"
                                    )
            div.ui.padded.grid
                div.row( v-if="countItems==0 && !toolsBottom" )
                    div.ui.warning.message
                        p {{ (items.length != 0) ? 'No results' : 'Empty' }}
                div.row( v-if="countItems > 0 || toolsBottom" )
                    table.ui.very.basic.table.very.compact.unstackable
                        tbody( v-item="$route.hash.substr(1)" )
                            tr(
                                v-for="(item, filterIndex) in filterItems"
                                ":id"="item.id" )
                                td.slim
                                    div.ui.item.fluid(style="margin-left: 5px;")
                                        div.ui.vertical.segment.basic( "v-if"="itemHaveData('username', item.username)" )
                                            h4.ui.sub.header {{ translate('username') }}
                                            p {{ item.username }}
                                        div.ui.vertical.segment.basic( "v-if"="itemHaveData('name', item.fullname)" )
                                            h4.ui.sub.header {{ translate('fullname') }}
                                            p {{ item.fullname }}
                                        div.ui.vertical.segment.basic( "v-if"="itemHaveData('sections', item.sections)" )
                                            h4.ui.sub.header {{ translate('sections') }}
                                            p
                                                span( v-for="(section, column_index) in item.sections")
                                                    span {{ section.name }}
                                                    span( v-if="(column_index != item.sections.length -1)") ,{{ ' ' }}
                                        div.ui.vertical.segment.basic( "v-if"="itemHaveData('roles', item.roles)" )
                                            h4.ui.sub.header {{ translate('roles') }}
                                            p
                                                span( v-for="(role, column_index) in item.roles")
                                                    span {{ role.name }}
                                                    span( v-if="(column_index != item.roles.length -1)") ,{{ ' ' }}
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
        name: 'ItemGrid'
        props: [
            'items'
            'toolsRow'
            'toolsBottom'
        ]
        mixins: [
            require '../../../mixins/Filters.vue'
        ]
        data: ->
            order: 'username'
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
