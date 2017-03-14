<template lang="pug">
    div
        div.ui.equal.width.grid
            div.row
                div.column
                    search( ":search"="search" )
        div.ui.padded.grid
            div.row( v-if="countItems==0 && !toolsBottom" )
                div.ui.warning.message
                    p {{ (items.length != 0) ? 'No results' : 'Empty' }}
            div.row( v-if="countItems > 0 || toolsBottom" )
                table.ui.very.compact.celled.table.unstackable
                    thead
                        tr
                            th.collapsing #
                            th.slim
                            th.slim.link( @click="setOrder('name')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('name') }}
                                        i.icon( ":class" = "(order=='name') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.slim.link( @click="setOrder('updated_at',1)")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('updated') }}
                                        i.icon( ":class" = "(order=='updated_at') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.slim.link( @click="setOrder('categories')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('categories') }}
                                        i.icon( ":class" = "(order=='categories') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.slim.link( @click="setOrder('public',0)")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('public') }}
                                        i.icon( ":class" = "(order=='public') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.collapsing {{ translate('tools') }}
                    tbody( v-item="$route.hash.substr(1)" )
                        tr(
                            v-for="(item, index) in filterItems"
                            ":id"="item.id" )
                            td.center.aligned
                                strong {{(index+1)+offset}}
                            td.center.aligned.collapsing
                                img.ui.mini.fluid.rounded.image(
                                    v-if="item.images.length"
                                    ":src"="item.images[0].thumb_path"
                                )
                            td( v-tooltip="" ":data-html"="formatTooltip(item.desc)" )
                                span {{ item.name }}
                            td
                                span {{ item.updated_at }}
                            td
                                span( v-for="(post, column_index) in item.categories")
                                    | {{post.name}}
                                    span( v-if="(column_index != item.categories.length -1)") ,{{ ' ' }}

                            td.center.aligned.collapsing
                                div.center.aligned
                                    i( ":class"="'ui icon ' + ((item.public==1) ? 'green checkmark' : 'red remove')"
                                        v-tooltip="" ":data-html"="(item.public==1) ? $root.translate('article_list.published_all') : $root.translate('article_list.published_intra')" )
                            td.right.aligned
                                div.ui.icon.basic.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )
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
        ]
        mixins: [
            require '../../../mixins/Filters.vue'
        ]
        components:
            Search: require '../../tools/Search.vue'
        data: ->
            order: 'updated_at'
        computed:
            from: ->
                @$route.path.substring 1
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','desc','categories']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this
            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','desc','categories']
                    .length
        methods:
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
</script>
