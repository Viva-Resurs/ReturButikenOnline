<template lang="pug">
    div.ui.padded.container.segment
        div.ui.dividing.header {{header}}

        div.ui.attached( v-if="this.$root.loading" )
            loading

        div.ui.attached( v-else="" )
            div.ui.grid.two.columns
                div.column.floated.left
                    div.ui.icon.input
                        input.prompt( v-focus="" v-model="search" class="prompt" placeholder="Type to search" )
                        i.search.icon

                div.column.right.floated.right.aligned.mobile.tablet.only
                        div.ui.floated.basic.button.dropdown#order( v-dropdown="" )
                            div.default.value( v-for="column in columns"
                                v-if="order==column.key"
                            ) {{ column.label }} &nbsp;
                                i.icon.label.sort.right.floated(
                                    ":class" = "(desc==1)?'ascending':'descending'"
                                )
                            div.menu
                                div.item(
                                    v-for="column in columns"
                                    v-if="column.sort"
                                    ":class"="(order==column.key)?'active':''"
                                    @click="setSortBy(column.key)" )
                                    | {{column.label}} &nbsp;
                                    i.icon.label.sort(
                                        v-if="order==column.key"
                                        ":class" = "(desc==1)?'ascending':'descending'"
                                    )

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
                                    @click="(column.sort) ? setOrder(column.key) : false" ) {{column.label}}
                                    i.icon.label.sort(
                                        v-if="order==column.key"
                                        ":class" = "(desc==1)?'ascending':'descending'"
                                    )

                                th.collapsing.center.aligned Tools

                        tbody
                            tr( v-for="(item, index) in filterItems" )
                                td.center.aligned.warning.collapsing
                                    strong {{(index+1)+offset}}.

                                td( v-for="column in columns"
                                    ":class"="column.class"
                                    v-tooltip="" ":data-html"="formatTooltip(item[column.tooltip])" )

                                    span( v-if="column.type=='string' || column.type=='number' || column.type==''") {{item[column.key]}}

                                    img.ui.mini.fluid.rounded.image(
                                        v-if="column.type=='image' && item[column.key].length"
                                        ":src"="item[column.key][0].thumb_path"
                                    )

                                    div(v-if="column.type=='array'")
                                        div( v-for="(post, column_index) in item[column.key]") {{ post.name }}
                                            span(v-if="column_index != item[column.key].length -1") ,

                                    div.center.aligned( v-if="column.type=='checkbox'" )
                                        i( ":class"="'ui icon ' + ((item[column.key]==1) ? 'green checkmark' : 'red remove')"
                                            v-tooltip="" ":data-html"="((item[column.key]==1) ? column.checkbox_true : column.checkbox_false)" )

                                td.collapsing
                                    div.ui.icon.basic.buttons
                                        component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )


                div.mobile.tablet.only.row(v-if="card")

                    div.ui.fluid.raised.card( v-for="(item, index) in filterItems" )
                        div.content

                            div.header.left.aligned
                                div.ui.right.ribbon.label
                                    i.checked.calendar.icon
                                    | {{ item[card.meta.updated_at.key]+' ' }}
                                div(
                                    style="margin-top: -25px"
                                    ) {{item[card.header.label]}}
                                    div.ui.black.horizontal.label(
                                        style="position: relative; top: -2px; left: 15px;"
                                        v-if="card.meta.categories"
                                        v-for="category in item[card.meta.categories.key]"
                                        )
                                        | {{ category.name }}

                            div.ui.divider( style="margin-bottom: 5px;" )
                            div.description
                                div.ui.vertical.segment.basic
                                    h4.ui.sub.header
                                        | Beskrivning
                                    p {{item[card.description.key]}}


                                div( v-if="item[card.meta.image.key].length>0" ).ui.vertical.segment.basic
                                    h4.ui.sub.header
                                        | Bilder
                                    p
                                        div( ":class" = "card.meta.image.class" )
                                            div.ui.image( v-for="image in item[card.meta.image.key]" )
                                                img.ui.tiny.rounded.image( ":src"="image.thumb_path" )


                        div.extra.content
                            div.meta.left.floated
                                br
                                div( v-for="extra in card.extra" ":class"="extra.class" )
                                    i( ":class"="'ui icon ' + ((item[extra.key]==1) ? 'green world' : 'red industry')")
                                    span( v-if="extra.type=='boolean'" ) {{ (item[extra.key] == 1) ? extra.true : extra.false }}



                            div.ui.icon.basic.buttons.right.floated
                                component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )

                div.row( v-if="!card" )
                    table.ui.compact.unstackable.celled.table
                        thead
                            tr
                                th.center.aligned.collapsing #
                                th(v-for="column in columns"
                                    ":class"="column.class"
                                    @click="(column.sort) ? setOrder(column.key) : false" ) {{column.label}}
                                    i.icon.label.sort(
                                        v-if="order==column.key"
                                        ":class" = "(desc==1)?'ascending':'descending'"
                                    )
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
                                        v-focus="" )

                                    div( v-else="" )
                                        span( v-if="column.type=='string' || column.type=='number' || column.type==''") {{item[column.key]}}

                                    div(v-if="column.type=='array'")
                                        div( v-for="(post, column_index) in item[column.key]") {{ post.name }}
                                            span(v-if="column_index != item[column.key].length -1") ,


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

            order: 'updated_at'
            desc: -1

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
