<template lang="pug">
    div.no-top-padding
        div.ui.equal.width.grid
            div.row.middle.aligned.no-top-bottom-padding
                div.column.four.wide
                    search( ":search"="search" ":results"="countItems" )
                div.column.ten.wide
                    paginate(
                        ":offset"="offset"
                        ":total"="countItems"
                        ":show-pagination"="search==''" )
                div.column.two.wide.right.floated.right.aligned
                    add.item.basic.icon#add_desktop( from="users")
        div.ui.padded.grid
            div.row( v-if="countItems == 0 && itemsNew.length == 0" )
                div.ui.column.warning.message
                    p {{ (search!='') ? translate('no_results') : translate('empty') }}
            div.row( v-if="countItems > 0 || itemsNew.length > 0" )
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
                            td
                                p.trunc-comp {{ item.username }}
                            td {{ item.fullname }}
                            td
                                span( v-for="(section, column_index) in item.sections")
                                    span {{ getLanguage() == 'sv' ? section.name_sv : section.name }}
                                    span( v-if="(column_index != item.sections.length -1)") ,{{ ' ' }}
                            td
                                span( v-for="(role, column_index) in item.roles")
                                    span {{ role.name }}
                                    span( v-if="(column_index != item.roles.length -1)") ,{{ ' ' }}
                            td.right.aligned
                                div.ui.icon.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="getFrom" )
        div.row( v-if="countItems > 0 && search!=''" )
            button.ui.button.searchresults_expander(
                v-if="limitOffBtn"
                @click="limitOff = true" )
                | {{ translate('show_all_results') }}
</template>

<script lang="coffee">
    module.exports =
        name: 'UserDesktopList'
        props: [
            'items'
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
            order: 'username'
            desc: 1
        computed:
            ###*
            #   Search/Filters items by name, fullname, sections and roles.
            #   @return {item} matched items
            ###
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','fullname','sections','roles']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this

            ###*
            #   Returns number of matched results.
            #   @return {number} number of filtered items
            ###
            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','fullname','sections','roles']
                    .length
        methods:
            ###*
            #   Returns a formatted tooltip replacing newline(\n) with <br>.
            #    @param {string} original text 
            #    @return {string} formatted text
            ###
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
</script>
