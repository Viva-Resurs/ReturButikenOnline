<template lang="pug">
    div.no-top-padding#shop_desktop_list        
        div.ui.equal.width.grid
            div.row.middle.aligned#shop_desktop_topmenu
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
                table.ui.very.basic.table.unstackable
                    tbody( v-item="location && location.hash ? location.hash.substr(1) : ''" )
                        tr(                            
                            v-for="(item, index) in filterItems"
                            ":id"="item.id" )
                            td.selectable.no-padding#shop_desktop_row
                                div.ui.celled.grid.no-padding#shop_desktop_row_grid
                                    div.row

                                        div.column.two.wide#shop_desktop_image_column                                      
                                            img.ui.image.centered#shop_desktop_row_image(
                                                ":src"="item.images.length > 0 ? item.images[0].thumb_path : 'images/no_image.png'" 
                                                @click="previewImages(item.images)") 
                                            div.ui.top.right.attached.circular.white.label#shop_desktop_row_image_label(v-if="item.images.length > 1" 
                                                ) {{ item.images.length }}                                 
                                            
                                        div.column.eleven.wide.link#shop_desktop_row_column(@click="previewItem(item)")
                                            div.row.item
                                                div.content                                            
                                                    h3.ui.header {{ item.name }} 
                                                    
                                            div.row#shop_desktop_row_desc
                                                p#shop_desktop_row_desc_p {{ item.desc }}  
                                            
                                            div.column.fluid#shop_desktop_row_desc_dots(v-if="item.desc.length > 200") ...
                                            
                                            div.ui.red.bottom.right.attached.tiny.label#shop_desktop_category_label(v-for="(post, column_index) in item.categories")
                                                | {{post.name}}
                                                span( v-if="(column_index != item.categories.length -1)") ,{{ ' ' }}                                            
                                    
                                        
                                        div.column.three.wide.center.aligned                                            
                                            div.ui.grid.center.aligned#shop_desktop_bidding_grid
                                                div.row#shop_desktop_bidding_interval_row
                                                    div.ui.small.label.top.attached#shop_desktop_bidding_interval_label {{ getDates(item.bidding_interval,0) }} - {{ getDates(item.bidding_interval,1) }}
                                                
                                                div.row.no-padding#shop_desktop_bidding_price_row
                                                    div.ui.labeled.button.label.equal.width.grid#shop_desktop_bidding_price_row_labels
                                                        div.ui.label.eight.wide.column#shop_desktop_bidding_price_row_amount(v-if="item.amount > 1") {{ (item.amount > 1) ? item.amount : '' }} {{ translate('article.pieces') }}
                                                        div.ui.basic.label.column.eight.wide#shop_desktop_bidding_price_row_price {{ item.price }} {{ translate('article_card.price_currency') }}                                                                         
                                                
                                                div.row#shop_desktop_bidding_buy_row
                                                    div.ui.small.label.primary.button.bottom.attached#shop_desktop_bidding_buy_row_button {{ translate('shop.buy') }}                                                               
 
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
            ###*
            #   Search/Filters items by name
            #   @return {item} matched items
            ###
            filterItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','desc','categories']
                    .sort (a, b) => @deepSort a, b, @order, @desc
                    .filter (item, index) => @rangeFilter item, index, this
            
            ###*
            #   Returns number of matched results.
            #   @return {number} number of filtered items
            ###
            countItems: ->
                @items
                    .filter (item) => item.removed != true
                    .filter (item) => @filterArrayBy item, @search, ['name','desc','categories']
                    .length
        methods:
            ###*
            #   Returns a formatted tooltip replacing newline(\n) with <br>.
            #   @param {info} original text 
            #   @return {string} formatted text
            ###
            formatTooltip: (info) ->
                return if info then info.replace /\n/g, '<br>' else ''
            
            ###*
            #   Preview article.
            #   @param {item} item to preview
            ###
            
            previewItem: (item) ->
                window.location.href = @$root.encodeArtNR(item)+location.hash
            
            ###*
            #   Return a the start or the end of a interval.
            #   @param {interval} interval to check
            #   @param {type} 0 or 1 (start or end)
            ###
            getDates: (interval, type) ->
                divided = interval.split("| ")
                return divided[type].split(" ")[0]   

            ###*
            #   Preview images. 
            #   @param {images} images to preview
            ###
            previewImages: (images) ->  
                if (images.length > 0)
                    selected_index = 0
                    bus.$emit 'show_message',                        
                        type: 'image'
                        index: selected_index
                        images: images
</script>

<style>
    #shop_desktop_list {
        margin-top: 10px;
    }

    #shop_desktop_topmenu {
        padding-top: 10px; 
        padding-bottom: 10px;
    }
    #shop_desktop_row {
        margin-top: 0px !important;
    }

    #shop_desktop_image_column{
        padding-left: 5px; padding-top: 5px; padding-bottom: 5px; padding-right: 5px;
    }

    #shop_desktop_row_column {
        padding-left: 10px;
        padding-right: 6px;
        padding-bottom: 6px;
        padding-top: 6px;
    }
    
    #shop_desktop_row_grid {
        max-height: 200px;
        margin-top: 0px;
        margin-bottom: 0px;
    }

    #shop_desktop_row_image {        
        max-height: 30px;         
        background-color: black;
        background-size: cover;        
        zoom: 4;        
    }

    #shop_desktop_row_image_label {
        position: absolute;
        right: 5px;        
        top: 5px;        
    }

    #shop_desktop_row_desc { 
        overflow: hidden; 
        max-height: 60px;
        position: absolute;
        top: 25px;
    }

    #shop_desktop_row_desc_p {
        max-height: 80px;  
        white-space: pre-wrap;
    }

    #shop_desktop_row_desc_dots {
        position: absolute; 
        bottom: 5px;
    }
    
    #shop_desktop_category_label {
        margin-right: 5px; 
        margin-bottom: 5px; 
        border-radius: 2px;
    }

    #shop_desktop_bidding_grid {
        height: 106px;
    }

    #shop_desktop_bidding_interval_row {
        margin-top: 5px; 
        margin-left: 5px; 
        margin-right: 5px;
    }

    #shop_desktop_bidding_interval_label {
        border-radius: 0px;
    }

    #shop_desktop_bidding_price_row {
        margin-left: 6px; margin-right: 3px;
    }

    #shop_desktop_bidding_price_row_labels {
        width: 100%; 
        height: 30px; 
        margin-top: 3px; 
        margin-bottom: 2px;
    }

    #shop_desktop_bidding_price_row_amount {
        border-radius: 4px 0px 0px 4px; 
        padding-top: 8px;
    }

    #shop_desktop_bidding_price_row_price {
        padding-top: 8px;
    }

    #shop_desktop_bidding_buy_row {
        margin-bottom: 5px; 
        margin-left: 5px; 
        margin-right: 5px;
    }

    #shop_desktop_bidding_buy_row_button {
        border-radius: 2px;
    }

</style>
