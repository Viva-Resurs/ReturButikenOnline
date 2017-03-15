<template lang="pug">
    div
        div.ui.equal.width.grid
            div.row
                div.column
                    search( ":search"="search" )
                div.column.right.aligned
                    sort(
                        ":order"="order"
                        ":desc"="desc"
                        ":columns"="['username','fullname','sections','roles']" )
        div.ui.padded.grid
            div.row
                table.ui.very.compact.celled.table.unstackable
                    thead
                        tr
                            th.collapsing #
                            th.slim.link( @click="setOrder('username')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('username') }}
                                        i.icon( ":class" = "(order=='username') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.slim.link( @click="setOrder('fullname')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('fullname') }}
                                        i.icon( ":class" = "(order=='fullname') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.slim.link( @click="setOrder('sections')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('sections') }}
                                        i.icon( ":class" = "(order=='sections') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.slim.link( @click="setOrder('roles')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('roles') }}
                                        i.icon( ":class" = "(order=='roles') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.collapsing {{ translate('tools') }}
                    tbody( v-item="$route.hash.substr(1)" )
                        tr(
                            v-for="(item, index) in filterItems"
                            ":id"="item.id"
                            ":class"="item.edit ? 'active' : ''" )
                            td.center.aligned
                                strong {{(index+1)+offset}}
                            td {{ item.username }}
                            td {{ item.fullname }}
                            td
                                span( v-for="(section, column_index) in item.sections")
                                    span {{ section.name }}
                                    span( v-if="(column_index != item.sections.length -1)") ,{{ ' ' }}
                            td
                                span( v-for="(role, column_index) in item.roles")
                                    span {{ role.name }}
                                    span( v-if="(column_index != item.roles.length -1)") ,{{ ' ' }}
                            td.right.aligned
                                div.ui.icon.basic.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )
                        tr( v-if="toolsBottom")
                            td
                            td
                            td
                            td
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
        components:
            Search: require '../../tools/Search.vue'
            Sort: require '../../tools/Sort.vue'
        data: ->
            order: 'username'
            desc: 1
        computed:
            from: ->
                @$route.path.substring 1
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','fullname','sections','roles']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this

            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','fullname','sections','roles']
                    .length
        methods:
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
</script>
