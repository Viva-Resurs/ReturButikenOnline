<template lang="pug">
    div.ui.container.segment
        div.ui.dividing.header {{header}}

        div.ui.attached( v-if="this.$root.loading" )
            loading

        div.ui.attached( v-else="" )
            div.ui.icon.input
                input.prompt( v-focus="" v-model="search" class="prompt" placeholder="Type to search" )
                i.search.icon

            div.ui.padded.grid
                div.row( v-if="columns && card && countItems==0" )
                    div.ui.warning.message
                        p {{ (items.length != 0) ? 'No results' : 'Empty' }}

                div.row.computer.only( v-if="columns && card" )
                    table.ui.compact.celled.table( v-if="countItems > 0" )
                        thead
                            tr
                                th.center.aligned.collapsing #
                                th(v-for="column in columns"
                                    ":class"="column.class"
                                    @click="(column.sort) ? setSortBy(column.key) : false" ) {{column.label}}

                                    i( v-if="column.sort" ":class"="[headers[column.key], headers[column.key+'_icon']]" )

                                th.collapsing.center.aligned Tools

                        tbody
                            tr( v-for="(item, index) in filterItems" )
                                td.center.aligned.warning.collapsing
                                    strong {{(index+1)+offset}}.

                                td( v-for="column in columns"
                                    ":class"="column.class"
                                    v-tooltip="" ":data-html"="formatTooltip(item[column.tooltip])" )

                                    span( v-if="column.type=='string' || column.type=='number' || column.type==''") {{item[column.key]}}

                                    div.center.aligned( v-if="column.type=='checkbox'" )
                                        i( ":class"="'ui icon ' + ((item[column.key]==1) ? 'checkmark box' : 'square outline')"
                                            v-tooltip="" ":data-html"="((item[column.key]==1) ? column.checkbox_true : column.checkbox_false)" )

                                td.collapsing
                                    div.ui.icon.basic.buttons
                                        component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )


                div.mobile.tablet.only.row(v-if="card")
                    div.ui.fluid.card( v-for="(item, index) in filterItems" )
                        div.content
                            div.header {{item[card.header.label]}}
                            div.meta
                                div( v-for="meta in card.meta" ":class"="meta.class" ) {{meta.title}}: {{item[meta.label]}}

                            div.description {{item[card.description.label]}}

                        div.extra.content
                            div.meta.left.floated
                                div( v-for="extra in card.extra" ":class"="extra.class" )
                                    span( v-if="extra.type=='boolean'" ) {{ (item[extra.label]) ? extra.true : extra.false }}

                            div.ui.icon.basic.buttons.meta.right.floated
                                component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )

                div.row( v-if="!card" )
                    table.ui.compact.unstackable.celled.table
                        thead
                            tr
                                th.center.aligned.collapsing #
                                th( v-for="column in columns"
                                    ":class"="column.class"
                                    @click="(column.sort) ? setSortBy(column.key) : false" ) {{column.label}}

                                    i( v-if="column.sort" ":class"="[headers[column.key], headers[column.key+'_icon']]" )

                                th.collapsing.center.aligned Tools

                        tbody( v-item="")
                            tr( v-for="(item, index) in filterItems" ":id"="item.id" )
                                td.center.aligned.warning.collapsing
                                    strong {{(index+1)+offset}}.
                                td( v-for="column in columns"
                                    ":class"="column.class"
                                    v-tooltip="" ":data-html"="formatTooltip(item[column.tooltip])" )

                                    div.ui.input.fluid( v-if="item.edit && column.type=='string'" )
                                        input( v-model="item[column.key+'_new']" ":placeholder"="'Type ' + column.label"
                                        @keyup.enter="attemptUpdate(item)"
                                        v-focus="" )

                                    div( v-else="") {{item[column.key]}}

                                td.collapsing
                                    div.ui.icon.basic.buttons
                                        component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )

                            tr( v-for="(item, index) in itemsNew" )
                                td.center.aligned.warning.collapsing
                                td( v-for="column in columns"
                                    ":class"="column.class"
                                    v-tooltip="" ":data-html"="formatTooltip(item[column.tooltip])" )

                                    div.ui.input.fluid( v-if="column.type=='string'" )
                                        input( v-model="item[column.key]" ":placeholder"="'Type ' + column.label"
                                        @keyup.enter="attemptUpdate(item)"
                                        v-focus="" )

                                td.collapsing
                                    div.ui.icon.basic.buttons
                                        component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )

                            tr
                                td
                                td( v-for="c in columns" )
                                td
                                    div.ui.icon.basic.buttons
                                        component( v-for="tool in toolsBottom" ":is"="tool" ":from"="from" )

        pagination.ui.bottom.attached(
            ":total"="countItems"
            ":show-pagination"="(search=='' && !limitOffBtn)" )

            div( slot="replacePagination" )
                button.ui.button.searchresults_expander( v-if="limitOffBtn" @click="limitOff = true" ) Visa alla resultat
</template>

<script lang="coffee">
    Filters = require '../mixins/Filters.vue'
    Pagination = require './Pagination.vue';

    module.exports = {
        name: 'ItemGrid'
        props: [ 'header', 'columns', 'card', 'items', 'itemsNew', 'toolsRow', 'toolsBottom' ]
        mixins: [ Filters ]
        components: { Pagination }
        data: ->
            search: ''

            limitOff: false
            limitOffBtn: false

            order: ''
            desc: -1
            headers :
                name : "ui icon"
                name_icon : 'sort'
                updated_at : 'ui icon'
                updated_at_icon : 'sort'

            offset: 0
            maxItems: 10

        computed:
            from: () ->
                @$route.path.substring(1)
            filterItems: ->
                this.items
                    .filter(
                        (item) => (item.removed!=true)
                    )
                    .filter(
                        (item) => @filterBy(item,@search,this.columns)
                    )
                    .sort(
                        (a,b) => @shallowSort(a[@order],b[@order],this)
                    )
                    .filter(
                        (item,index) => @rangeFilter(item,index,this)
                    );
            countItems: ->
                this.items
                    .filter(
                        (item) => (item.removed!=true)
                    )
                    .filter(
                        (item) => @filterBy(item,@search,@columns)
                    )
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
                if (!info)
                    return ''
                formated = info.replace(/\n/g,'<br>')
                return formated;

            setSortBy: (headingTitle) ->
                # Set correct sort icon to the header (ascending, descending)
                selectedHeader = ''

                for key, column of @columns
                    if (headingTitle=='' && column.default_sort)
                        this.setOrder(key,column.desc)
                        selectedHeader = if (this.desc == 1) then "sort numeric ascending icon" else "sort numeric descending icon"
                    if (key == headingTitle)
                        if (column.type == 'string')
                            this.setOrder(key,column.desc)
                            selectedHeader = if (this.desc == 1) then "sort alphabet ascending icon" else "sort alphabet descending icon"
                        if (column.type == 'number')
                            this.setOrder(key,column.desc)
                            selectedHeader = if (this.desc == 1) then "sort numeric ascending icon" else "sort numeric descending icon"

                this.headers[headingTitle+'_icon'] = selectedHeader

                # Change the other (not sorted by) icons to a generic sort icon
                for key, column of @columns
                    if (key != headingTitle)
                        @headers[key+'_icon'] = "sort"

        created: ->
            @setSortBy()
            # Listen for changes in data by components
            bus.$on('offset_changed', (new_offset) => this.offset = new_offset)
            bus.$on('limit_changed', (new_limit) => this.maxItems = new_limit)

        beforeDestroy: ->
            bus.$off('offset_changed')
            bus.$off('limit_changed')
    }
</script>
