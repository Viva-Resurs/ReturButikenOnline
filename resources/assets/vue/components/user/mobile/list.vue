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
                        ":columns"="['name','users']" )
                    div.right.menu
                        div.ui.buttons
                            add.right.item.large.icon.no-padding( from="users")
        div.ui.padded.grid
            div.row( v-if="countItems == 0 && itemsNew.length == 0" )
                div.ui.column.warning.message
                    p {{ (search!='') ? translate('no_results') : translate('empty') }}
            div.row( v-if="countItems > 0 || itemsNew.length > 0" )
                table.ui.very.basic.table.very.compact.unstackable
                    tbody( v-item="$route.hash.substr(1)" )
                        tr( v-for="(item, index) in itemsNew" )
                            td.slim
                                div.ui.input.fluid
                                    input( v-model="item.name" ":placeholder"="translate('placeholder.type')+' '+translate('name')"
                                    v-focus="" )
                            td
                            td
                                div.ui.icon.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="getFrom" )
                        tr(
                            v-for="(item, filterIndex) in filterItems"
                            ":id"="item.id" )
                            td.slim
                                div.ui.item.fluid#user_mobile_form
                                    div.ui.input.fluid( v-if="item.edit" )
                                        input( v-model="item.name_new"
                                        ":placeholder"="translate('placeholder.type')+' '+translate('name')"
                                        v-focus="" ).collapsing
                                    div.ui.vertical.segment.basic#user_tablet_name( "v-if"="!item.edit && itemHaveData('name', item.name) && screenType == 'tablet'")
                                        h4.ui.sub.header {{ translate('name') }}
                                        p.trunc-tab {{ item.name }}
                                    div.ui.vertical.segment.basic#user_mobile_name( "v-if"="!item.edit && itemHaveData('name', item.name) && screenType == 'mobile'")
                                        h4.ui.sub.header {{ translate('name') }}
                                        p.trunc-mob {{ item.name }}
                                    div.ui.vertical.segment.basic( "v-if"="itemHaveData('users', item.users)" )
                                        h4.ui.sub.header {{ translate('users') }}
                                        p
                                            span( v-for="(role, column_index) in item.users")
                                                span {{ role.name }}
                                                span( v-if="(column_index != item.users.length -1)") ,{{ ' ' }}
                            td.collapsing
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
        name: 'UserMobileList'
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
                                
            ###*
            #   Returns the first column.
            #   @return {column} first column 
            ###
            firstColumn: ->
                @columns[Object.keys(@columns)[0]]
        methods:
            ###*
            #   Returns a formatted tooltip replacing newline(\n) with <br>.
            #    @param {string} original text 
            #    @return {string} formatted text
            ###
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
            
            ###*
            #   Checks if item at a specific column have data.
            #    @param {column} column to check
            #    @param {item} item to check
            #    @return {boolean} true if it have data, otherwise false
            ###
            itemHaveData: (column, item) ->
                if item
                    if (column.type == 'array')
                        return (item.length > 0)
                    return true
                return false
</script>
<style>
    #user_mobile_form {
        margin-left: 5px;
    }

    #user_tablet_name {
        padding-top: 6px; 
        padding-bottom: 6px;
    }

    #user_mobile_name {
        padding-top: 6px; 
        padding-bottom: 6px;
    }
</style>
