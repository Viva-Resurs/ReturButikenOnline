<template lang="pug">
    div
        div.ui.equal.width.grid
            div.row.middle.aligned(style="padding-top: 10px; padding-bottom: 10px")
                div.column.four.wide
                    search( ":search"="search" ":results"="countItems" )
                div.column.ten.wide
                    paginate(
                        ":offset"="offset"
                        ":total"="countItems"
                        ":show-pagination"="search==''" )
                div.column.two.wide.right.floated.right.aligned
                    add.item.basic.icon( from="articles" style="box-shadow: 0 1px 2px 0 rgba(34,36,38,.15); border: 1px solid rgba(34,36,38,.15); padding-top: 9.5px; padding-bottom: 9.5px" )
        div.ui.padded.grid
            div.row( v-if="countItems == 0" )
                div.ui.column.warning.message
                    p {{ (search!='') ? translate('no_results') : translate('empty') }}
            div.row( v-if="countItems > 0" )
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

                            th.slim.link( @click="setOrder('amount')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('article.amount') }}
                                        i.icon( ":class" = "(order=='amount') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )

                            th.slim.link( @click="setOrder('public',0)")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('public') }}
                                        i.icon( ":class" = "(order=='public') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )
                            th.collapsing {{ translate('tools') }}
                    tbody( v-item="location && location.hash ? location.hash.substr(1) : ''" )
                        tr(
                            v-for="(item, index) in filterItems"
                            ":id"="item.id" )
                            td.center.aligned
                                strong {{(index+1)+offset}}
                            td.center.aligned.no-padding
                                div.ui.stacked.segment.no-padding(v-if="item.images.length > 1")
                                    img.ui.image.table_image.multiple_preview(
                                        v-if="item.images.length"
                                        ":src"="item.images[0].thumb_path"
                                    )

                                div.ui.segment.no-padding(v-else-if="item.images.length == 1")
                                    img.ui.image.table_image.preview(
                                    ":src"="item.images[0].thumb_path")

                                div.no_image_icon(v-else="")
                                    i.icon.remove

                            td( v-tooltip="" ":data-html"="formatTooltip(item.desc)" )
                                span {{ item.name }}
                            td
                                span {{ item.updated_at }}
                            td
                                span( v-for="(post, column_index) in item.categories")
                                    | {{post.name}}
                                    span( v-if="(column_index != item.categories.length -1)") ,{{ ' ' }}

                            td.collapsing.center.aligned
                                span {{ item.amount }}

                            td.center.aligned.collapsing
                                div.center.aligned
                                    i( ":class"="'ui icon ' + ((item.public==1) ? 'green checkmark' : 'red remove')"
                                        v-tooltip="" ":data-html"="(item.public==1) ? $root.translate('article_list.published_all') : $root.translate('article_list.published_intra')" )
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
        name: 'ArticleDesktopList'
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
        computed:
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
