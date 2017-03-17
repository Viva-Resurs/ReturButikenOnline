<template lang="pug">
    div
        div.ui.top.attached.menu
            div.left.menu
                search( ":search"="search" ":results"="countItems" )
            div.right.menu.fitted.item
                paginate(
                    ":offset"="offset"
                    ":total"="countItems"
                    ":show-pagination"="(search=='' && !limitOffBtn)" )
                div.right.menu
                    sort(
                        ":order"="order"
                        ":desc"="desc"
                        ":columns"="['name','users']" )
                    div.right.menu
                        div.ui.buttons
                            add.right.item.large.icon( from="sections" )
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
                            ":id"="item.id" )
                            td.slim
                                div.ui.item.fluid(style="margin-left: 5px;")
                                    div.ui.input.fluid( v-if="item.edit" )
                                        input( v-model="item.name_new"
                                        ":placeholder"="translate('placeholder.type')+' '+translate('name')"
                                        v-focus="" ).collapsing
                                    div.ui.vertical.segment.basic( "v-if"="!item.edit && itemHaveData('name', item.name)" )
                                        h4.ui.sub.header {{ translate('name') }}
                                        p {{ item.name }}
                                    div.ui.vertical.segment.basic( "v-if"="itemHaveData('users', item.users)" )
                                        h4.ui.sub.header {{ translate('users') }}
                                        p
                                            span( v-for="(role, column_index) in item.users")
                                                span {{ role.name }}
                                                span( v-if="(column_index != item.users.length -1)") ,{{ ' ' }}
                            td.collapsing.bottom.aligned
                                div.ui.icon.basic.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )
        div.row( v-if="countItems > 0 && search!=''" )
            button.ui.button.searchresults_expander(
                v-if="limitOffBtn"
                @click="limitOff = true" )
                | {{ translate('show_all_results') }}
</template>

<script lang="coffee">
    module.exports =
        name: 'ItemGrid'
        props: [
            'items'
            'itemsNew'
            'toolsRow'
        ]
        mixins: [
            require '../../../mixins/Filters.vue'
        ]
        components:
            Search: require '../../tools/Search.vue'
            Paginate: require '../../tools/Paginate.vue'
            Sort: require '../../tools/Sort.vue'
            Add: require '../../tools/Add.vue'
        data: ->
            order: 'name'
            desc: 1
        computed:
            from: ->
                @$route.path.substring 1
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','users']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this

            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','users']
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
