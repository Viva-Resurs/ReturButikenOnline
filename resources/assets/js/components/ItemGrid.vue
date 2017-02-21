<template lang="pug">
    div
        div.ui.dividing.header {{ header }}

        loading( v-if="$root.loading" )

        div.ui.attached( v-else="" )

            // Top tools
            div.ui.equal.width.grid
                div.row
                    div.column.grid.mobile.tablet.only
                        div.ui.icon.input.fluid
                            input.prompt(
                                v-focus=""
                                v-model="search"
                                placeholder="Type to search"
                                )
                            i.search.icon
                    div.column.grid.computer.only
                        div.ui.icon.input
                            input.prompt(
                                v-focus=""
                                v-model="search"
                                ":placeholder"="translate('placeholder.search')"
                                )
                            i.search.icon
                    div.column.right.aligned( v-if="card"
                        ":class"="(card)?'mobile tablet only':''"
                        )
                        div.ui.floated.basic.button.dropdown#order( v-dropdown="" )
                            div.default.value(
                                v-for="column in columns"
                                v-if="order==column.key"
                                )
                                | {{ column.label }} &nbsp;
                                i.icon.label.sort.right.floated(
                                    ":class" = "(desc==1)?'ascending':'descending'"
                                )
                            div.menu
                                div.item(
                                    v-for="column in columns"
                                    v-if="column.sort"
                                    ":class"="(order==column.key)?'active':''"
                                    @click="setOrder(column.key,column.desc)"
                                    )
                                    | {{column.label}} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order==column.key"
                                        ":class" = "(desc)?'ascending':'descending'"
                                    )

            // Data
            div.ui.padded.grid
                div.row( v-if="countItems==0 && !toolsBottom" )
                    div.ui.warning.message
                        p {{ (items.length != 0) ? 'No results' : 'Empty' }}

                div.row( v-if="countItems > 0 || toolsBottom"
                    ":class"="(card)?'computer only':''"
                    )
                    table.ui.very.compact.celled.table.unstackable
                        thead
                            tr
                                th.collapsing #
                                th.slim( v-for="column in columns"
                                    ":class"="column.class"
                                    @click=`
                                        (column.sort) ? setOrder(column.key,column.desc) : false
                                    ` )
                                    div.ui.small.secondary.menu
                                        div.item
                                            | {{ translate(column.label) }}
                                            i.icon(
                                                ":class" = `
                                                    (order==column.key) ?
                                                        (desc==1) ?
                                                            'sort ascending' :
                                                            'sort descending'
                                                        :
                                                    ''
                                                `
                                            )

                                th.collapsing {{ translate('tools') }}

                        tbody( v-item="$route.hash.substr(1)" )

                            tr( v-for="(item, index) in itemsNew" )
                                td.center.aligned.collapsing
                                td( v-for="column in columns"
                                    ":class"="column.class"
                                    v-tooltip="" ":data-html"="formatTooltip(item[column.tooltip])" )

                                    div.ui.input.fluid( v-if="column.type=='string'" )
                                        input( v-model="item[column.key]" ":placeholder"="'Type ' + column.label"
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

                                td( v-for="column in columns"
                                    ":class"="column.class"
                                    v-tooltip="" ":data-html"="formatTooltip(item[column.tooltip])" )

                                    div.ui.input.fluid( v-if="item.edit && column.type=='string'" )
                                        input( v-model="item[column.key+'_new']" ":placeholder"="'Type ' + column.label"
                                        v-focus="" ).collapsing

                                    div( v-else="" )
                                        span( v-if="column.type=='string' || column.type=='number' || column.type==''")
                                            | {{item[column.key]}}

                                    img.ui.mini.fluid.rounded.image(
                                        v-if="column.type=='image' && item[column.key].length"
                                        ":src"="item[column.key][0].thumb_path"
                                    )

                                    div(v-if="column.type=='array'")
                                        span( v-for="(post, column_index) in item[column.key]")
                                            router-link.item( v-if="column.key=='users'"
                                            ":to"="'/users/'+post.id" exact ) {{post.name}}
                                            span( v-else ) {{post.name}}
                                            span( v-if="(column_index != item[column.key].length -1)") ,{{ ' ' }}

                                    div.center.aligned( v-if="column.type=='checkbox'" )
                                        i( ":class"="'ui icon ' + ((item[column.key]==1) ? 'green checkmark' : 'red remove')"
                                            v-tooltip="" ":data-html"="((item[column.key]==1) ? column.checkbox_true : column.checkbox_false)" )

                                td.right.aligned
                                    div.ui.icon.basic.buttons
                                        component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )

                            tr( v-if="toolsBottom")
                                td
                                td( v-for="c in columns" )
                                td.right.aligned
                                    div.ui.icon.basic.buttons
                                        component( v-for="tool in toolsBottom" ":is"="tool" ":from"="from" )

                div.mobile.tablet.only.row( v-if="card" )
                    component(
                        v-for="(item, index) in filterItems"
                        ":is"="card"
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
                    | Visa alla resultat
</template>

<script lang="coffee">
    module.exports =
        name: 'ItemGrid'
        props: [
            'header'
            'columns'
            'card'
            'items'
            'itemsNew'
            'toolsRow'
            'toolsBottom'
        ]
        mixins: [
            require '../mixins/Filters.vue'
        ]
        components:
            Pagination: require './Pagination.vue'

        data: ->
            search: ''

            limitOff: false
            limitOffBtn: false

            order: 'updated_at'
            desc: -1

            offset: 0
            maxItems: 10

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

        watch:
            # Reset show all results when editing search
            search: (val, oldVal) ->
                this.offset = 0
                this.limitOff = false
            # Reset offset when changing maxItems
            maxItems: (val, oldVal) ->
                this.offset = 0

        methods:
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''

        created: ->
            bus.$on 'offset_changed', (new_offset) => this.offset = new_offset
            bus.$on 'limit_changed', (new_limit) => this.maxItems = new_limit
            # Set default order
            for index, column of @columns
                if column.default_sort == true
                    @setOrder column.key, column.desc
        beforeDestroy: ->
            bus.$off 'offset_changed'
            bus.$off 'limit_changed'
</script>
