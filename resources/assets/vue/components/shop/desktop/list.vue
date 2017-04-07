<template lang="pug">
    div
        div.ui.equal.width.grid
            div.row.middle.aligned
                div.column.four.wide
                    search( ":search"="search" ":results"="countItems" )
                div.column.twelve.wide
                    paginate(
                        ":offset"="offset"
                        ":total"="countItems"
                        ":show-pagination"="search==''" )

        div.ui.padded.grid
            div.row( v-if="countItems == 0" )
                div.ui.column.warning.message
                    p {{ (search!='') ? translate('no_results') : translate('empty') }}
            div.row( v-if="countItems > 0" )
                table.ui.very.compact.celled.table.unstackable
                    thead
                        tr
                            th.slim.center.aligned
                                i.icon.image(style="margin: 10px")
                            th.slim.link( @click="setOrder('name')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('name') }}
                                        i.icon( ":class" = "(order=='name') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )

                            th.slim.link( @click="setOrder('categories')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('categories') }}
                                        i.icon( ":class" = "(order=='categories') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )

                            th.slim.link( @click="setOrder('amount')")
                                div.ui.small.secondary.menu
                                    div.item {{ translate('article.amount') }}
                                        i.icon( ":class" = "(order=='amount') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )


                            th.slim.link( @click="setOrder('bidding_interval',1)" style="white-space: nowrap;")
                                div.ui.small.secondary.menu
                                    div.item.nowrap {{ translate('article_list.bidding_interval') }}
                                        i.icon( ":class" = "(order=='bidding_interval') ? (desc==1) ? 'sort ascending' : 'sort descending' : ''" )

                    tbody( v-item="location && location.hash ? location.hash.substr(1) : ''" )
                        tr(
                            v-for="(item, index) in filterItems"
                            ":id"="item.id" )
                            td.center.aligned.collapsing
                                img.ui.mini.fluid.rounded.image(
                                    v-if="item.images.length"
                                    ":src"="item.images[0].thumb_path"
                                )
                                div(v-else="")
                                    i.stacked.icons
                                        i.icon.file.outline
                                        i.small.icon.remove.centered.attached.black

                            td.link( v-tooltip="" ":data-html"="formatTooltip(item.desc)" @click="previewItem(item)")
                                span {{ item.name }}

                            td.collapsing
                                div(v-if="item.categories.size > 0")
                                    span( v-for="(post, column_index) in item.categories")
                                        | {{post.name}}
                                        span( v-if="(column_index != item.categories.length -1)") ,{{ ' ' }}
                                span.center.aligned(v-else="")
                                    p {{ translate('not_set')}}

                            td.collapsing.center.aligned
                                span {{ item.amount }}

                            td.collapsing.center.aligned
                                span {{ biddingFormatted(item) }}

        div.row( v-if="countItems > 0 && search!=''" )
            button.ui.button.searchresults_expander(
                v-if="limitOffBtn"
                @click="limitOff = true" )
                | {{ translate('show_all_results') }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Shop'
        props: [
            'items'
        ]
        mixins: [
            require '../../../mixins/Filters.vue'
        ]
        components:
            Search: require '../../tools/Search.vue'
            Paginate: require '../../tools/Paginate.vue'
            Sort: require '../../tools/Sort.vue'
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

            previewItem: (item) ->
                window.location.href = @$root.encodeArtNR(item)+location.hash

            biddingFormatted: (item) ->
                if (item.bidding_interval)
                    item.bidding_interval.split("|")[0] + " - " + item.bidding_interval.split("|")[1]
                else
                    @.$root.translate('not_set')

</script>
<style>
    .stacked.icons{
        position: relative;
    }

    .stacked.icons > .attached.black.icon{
        text-shadow:
            -0.5px -0.5px 0 #fff,
            0.5px -0.5px 0 #fff,
            -0.5px 0.5px 0 #fff,
            0.5px 0.5px 0 #fff;
    }

    .stacked.icons > .centered {
        position: absolute;
        left: 0.6em;
        top: 0.75em;
    }
</style>
