<template lang="pug">
    div.no-top-padding(style="margin-top: 10px")        
        div.ui.equal.width.grid
            div.row.middle.aligned(style="padding-top: 10px; padding-bottom: 10px")
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
                table.ui.very.celled.table.unstackable
                    tbody( v-item="location && location.hash ? location.hash.substr(1) : ''" )
                        tr(                            
                            v-for="(item, index) in filterItems"
                            ":id"="item.id" )
                            td.selectable(style="padding: 10px")
                                div.ui.grid(style="max-height: 200px")
                                    div.row
                                        div.column.three.wide                                        
                                            img.ui.rounded.image(
                                                ":src"="item.images.length > 0 ? item.images[0].thumb_path : 'images/no_image.png'" 
                                                style="max-height: 130px; background-color: black;"
                                                @click="previewImages(item.images)") 
                                            div.ui.top.right.attached.circular.white.label(v-if="item.images.length > 0" 
                                                style="margin-left: 0px; margin-top: -5px") {{ item.images.length }}                                 
                                            
                                        div.column.nine.wide.link(@click="previewItem(item)")
                                            div.row.item
                                                div.content                                            
                                                    div.ui.header {{ item.name }} {{ (item.amount > 1) ? '('+item.amount+')' : '' }}
                                                span( v-for="(post, column_index) in item.categories")
                                                    | {{post.name}}
                                                    span( v-if="(column_index != item.categories.length -1)") ,{{ ' ' }}
                                            div.row(style="white-space: no-wrap; overflow: hidden; max-height: 60px;")
                                                p(style="max-height: 80px; text-overflow: ellipse; white-space: pre-wrap") {{ item.desc }}
                                            div.row(v-if="item.desc.length > 200") ...
                                            

                                        div.column.four.wide
                                            
                                            div.ui.segment.attached.fluid.center.aligned(style="height: 130px; border-radius: 5px")
                                                h2 {{ item.price }} {{ translate('article_card.price_currency') }}                                                                         
                                                div.ui.label.primary.button {{ translate('shop.buy') }}                                                               
                                                div.ui.bottom.attached.small.black.label(style="border-radius: 5px") {{ getDates(item.bidding_interval,0) }} &#8594 {{ getDates(item.bidding_interval,1) }}
                                                

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
            
            getDates: (interval, type) ->
                console.log interval
                divided = interval.split("| ")
                return divided[type].split(" ")[0]   


            previewImages: (images) ->  
                if (images.length > 0)
                    selected_index = 0
                    bus.$emit 'show_message',                        
                        type: 'image'
                        index: selected_index
                        images: images
</script>
