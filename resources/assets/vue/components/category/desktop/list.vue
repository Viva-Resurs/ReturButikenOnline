<template lang="pug">
    div
        div.ui.equal.width.grid
            div.row.middle.aligned
                div.column.four.wide
                    search( ":search"="search" ":results"="countItems" )
                div.column.ten.wide
                    paginate(
                        ":offset"="offset"
                        ":total"="countItems"
                        ":show-pagination"="search==''" )
                div.column.two.wide.right.floated.right.aligned
                    add.item.basic.icon( from="categories" )
        div.ui.padded.grid
            div.row( v-if="countItems == 0 && itemsNew.length == 0" )
                div.ui.column.warning.message
                    p {{ (search!='') ? translate('no_results') : translate('empty') }}
            div.row( v-if="countItems > 0 || itemsNew.length > 0" )
                table.ui.very.compact.celled.table.unstackable
                    thead
                        tr
                            th.collapsing #
                            th.slim.link( @click="setOrder('name')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('name') }}
                                        i.icon( ":class" = "(order=='name') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.collapsing {{ translate('tools') }}
                    tbody( v-item="$route.hash.substr(1)" )
                        tr( v-for="(item, index) in itemsNew" )
                            td.center.aligned.collapsing
                            td
                                div.ui.input.fluid
                                    input( v-model="item.name" ":placeholder"="translate('placeholder.type')+' '+translate('name')"
                                    v-focus="" )
                            td
                                div.ui.icon.basic.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )

                        tr(
                            v-for="(item, index) in filterItems"
                            ":id"="item.id"
                            ":class"="item.edit ? 'active' : ''" )
                            td.center.aligned
                                strong {{(index+1)+offset}}
                            td
                                div.ui.input.fluid( v-if="item.edit" )
                                    input( v-model="item.name_new"
                                    ":placeholder"="translate('placeholder.type')+' '+translate('name')"
                                    v-focus="" ).collapsing
                                span( v-if="!item.edit" ) {{ item.name }}
                            td.right.aligned
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
        name: 'CategoryDesktopList'
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
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','fullname','sections','roles']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this

            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterBy item, @search, ['name','fullname','sections','roles']
                    .length
        methods:
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
</script>
