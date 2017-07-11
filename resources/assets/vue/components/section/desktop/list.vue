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
                    add.item.basic.icon#add_desktop( from="sections")
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
                            th.slim.link( @click="setOrder('users')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('users') }}
                                        i.icon( ":class" = "(order=='users') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.collapsing {{ translate('tools') }}
                    tbody( v-item="$route.hash.substr(1)" )
                        tr( v-for="(item, index) in itemsNew" )
                            td.center.aligned
                            td
                                div.field
                                    label {{ translate('section.name_placeholder') }}:
                                    div.ui.input.fluid
                                        input( v-model="item.name" ":placeholder"="translate('section.name_placeholder')"
                                        v-focus="" )

                                    label {{ translate('section.name_placeholder_sv') }}:
                                    div.ui.input.fluid
                                        input( v-model="item.name_sv" ":placeholder"="translate('section.name_placeholder_sv')"
                                        v-focus="" )
                            td
                            td.right.aligned
                                div.ui.icon.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="getFrom" )

                        tr(
                            v-for="(item, index) in filterItems"
                            ":id"="item.id"
                            ":class"="item.edit ? 'active' : ''" )
                            td.center.aligned
                                strong {{(index+1)+offset}}
                            td
                                div.field(v-if="item.edit")
                                    label {{ translate('section.name_placeholder') }}:
                                    div.ui.input.fluid
                                        input( v-model="item.name_new"
                                        ":placeholder"="translate('section.name_placeholder')"
                                        v-focus="" ).collapsing

                                    label {{ translate('section.name_placeholder_sv') }}:
                                    div.ui.input.fluid
                                        input( v-model="item.name_sv_new"
                                        ":placeholder"="translate('section.name_placeholder_sv')"
                                        v-focus="" ).collapsing

                                span( v-if="!item.edit && getLanguage()=='en'" ) {{ item.name }}
                                span( v-if="!item.edit && getLanguage()=='sv'" ) {{ item.name_sv }}
                            td
                                span( v-for="(user, column_index) in item.users")
                                    span {{ user.name }}
                                    span( v-if="(column_index != item.users.length -1)") ,{{ ' ' }}
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
        name: 'SectionDesktopList'
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
            ###*
            #   Search/Filters items by name and users.
            #   @return {item} matched items
            ###
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','users']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this
            
            ###*
            #   Returns number of matched results.
            #   @return {number} number of filtered items
            ###
            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','users']
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
