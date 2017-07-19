<template lang="pug">
    div.ui.grid(v-if="item" 
        ":class"="screenType == 'tablet' ? 'container' : ''" )        
        div.ui.grid.fluid.card.tablet.only
            div.content
                div.header.left.aligned
                    div.ui.dividing.header {{item.name}} {{ (item.amount > 1) ? '('+item.amount+')' : '' }}

                div.description#shop_card_tablet_description
                    div.column#shop_card_tablet_categories( v-if="item.categories.length>0")
                        div.ui.black.horizontal.label.stackable(
                            v-for="category in item.categories"
                            )
                            | {{ category.name }}
                    br
                    div.no-padding.ui.vertical.segment.basic( v-if="item.images.length > 0")
                        div.ui.tiny.images
                            img.ui.rounded.image#shop_card_tablet_image( 
                                v-for="image in item.images" @click="previewImages(image)"
                                ":src"="image.thumb_path" )
                    
                    div.ui.vertical.segment.basic
                        p#shop_card_tablet_p {{ item.desc }}

                    div.ui.grid.equal.width
                        div.column.right.aligned
                            div.column.center.aligned.right.floated                            
                                div.ui.basic.segment
                                    h2 {{ item.price }} {{ translate('article_card.price_currency') }}

            
            div.extra.content#shop_card_tablet_extra_content
                div.ui.grid.equal.width
                    div.column.left.middle.aligned
                        div.ui.grid.center.aligned.left.floated.equal.width
                            div.column
                                div.ui.labeled.button.label()
                                    div.ui.label {{translate('shop.bidding')}}
                                    div.ui.basic.label {{ getDates(item.bidding_interval,0) }} - {{ getDates(item.bidding_interval,1) }}

                    div.column.right.aligned
                        div.column.center.aligned.right.floated
                            div.ui.primary.label.button( @click="previewItem(item)") {{ translate('shop.buy') }}
        
        div.ui.grid.segment.mobile.only#shop_card_mobile_segment
       
            div.column.four.wide.top.aligned#shop_card_mobile_left_segment
                img.ui.rounded.image.centered#shop_card_mobile_image(
                    ":src"="item.images.length > 0 ? item.images[0].thumb_path : 'images/no_image.png'" 
                   
                    @click="previewImages(item.images)") 
                div.ui.circular.white.label#shop_card_mobile_image_label(v-if="item.images.length > 0") {{ item.images.length }}
            

            div.column.twelve.wide                               
                div.no-padding
                    div.ui.sub.header {{item.name}} {{ (item.amount > 1) ? '('+item.amount+')' : '' }}

                div.no-padding
                    p(":id"="'article_desc_'+item.id") {{ item.desc }}
                
                div.ui.basic.circular.label.button.right.floated(
                    v-if="item.desc.length > 150"
                    ":id"="'article_expand_button_'+item.id" 
                    @click="toggleDescription(item.id)" 
                    )
                    i.angle.down.icon#shop_card_mobile_angle_icon

            div.row#shop_card_mobile_bottom_tool_row                
                div.column.four.wide.middle.center.aligned
                    div.column.center.aligned.right.floated                                                   
                        h4 {{ item.price }} {{ translate('article_card.price_currency') }}
                   
                div.column.twelve.wide.right.aligned
                    div.ui.labeled.button.label()                        
                        div.ui.basic.label {{ getDates(item.bidding_interval,0) }} - {{ getDates(item.bidding_interval,1) }}
                        div.ui.primary.label.button( @click="previewItem(item)") {{ translate('shop.buy') }}

</template>

<script lang="coffee">
    module.exports =
        name: 'ArticleCard'
        props: [ 'item', 'tools', 'from' ]
        methods: 
            ###*
            #   Opens a preview of all images with the first image selected.
            #   @param {image} image to preview
            ###
            previewImages: (image) ->      
                selected_index = 0
                for img, index in @item.images
                    if Number(img.id) == Number(image.id)
                        selected_index = index

                if @item.images.length > 0
                    bus.$emit 'show_message',                        
                        type: 'image'
                        index: selected_index
                        images: @item.images
            
            ###*
            #   Opens a link that preview the selected item/article.
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
            #   Expands or minimizes the description of an item/article.
            #   @param {itemId} item to expand
            ###
            toggleDescription: (itemId) ->         
                clicked_desc = $('#article_desc_'+itemId)
                
                if (clicked_desc.attr('class') == 'expanded')
                    clicked_desc.removeClass("expanded")  
                    clicked_desc.animate(
                        "height": "40px",                        
                    ,200 )
                    itemIcon = $('#article_expand_button_'+itemId).find("i")
                    itemIcon.removeClass("up")
                    itemIcon.addClass("down")

                    itemButton = $('#article_expand_button_'+itemId)
                    itemButton.animate(
                        "top": 60+'%',
                    , 200 )
                                       
                else 
                    clicked_desc.addClass("expanded")
                    clicked_desc.animate(
                        "height": clicked_desc[0].scrollHeight+'px',                        
                    , 200 )
                    itemIcon = $('#article_expand_button_'+itemId).find("i")
                    itemIcon.removeClass("down")
                    itemIcon.addClass("up")
                    
                    itemButton = $('#article_expand_button_'+itemId)
                    itemButton.animate(
                        "top": clicked_desc[0].scrollHeight+'px',                        
                    , 200 )
</script>

<style>
    #shop_card_tablet_description {
        position: relative; 
        top: -10px;
    }

    #shop_card_tablet_categories {
        padding-top: 6px;
    }

    #shop_card_tablet_image {
        background-color: lightgrey;
    }

    #shop_card_tablet_p {
        white-space: pre-wrap;
        position: absolute;
        top: -0px;
    }

    #shop_card_tablet_extra_content {
        height: 50px;
    }

    #shop_card_mobile_segment {
        margin-left: 13px; 
        margin-right: 13px; 
        padding: 0px;
    }

    #shop_card_mobile_left_segment {
        padding-left: 15px;
    }

    #shop_card_mobile_image {
        background-color: black;
    }

    #shop_card_mobile_image_label {  
        position: absolute;
        top: 10px;
        right: 5px;              
    }

    *[id^="article_desc_"]{
        height: 60px; 
        overflow: hidden; 
        white-space: pre-wrap;
    }
    
    *[id^="article_expand_button_"]{
        padding: 10px; 
        top: 60%; 
        right: 15px; 
        position:absolute;
    }
    
    #shop_card_mobile_angle_icon {
        margin: 5px;
    }

    #shop_card_mobile_bottom_tool_row {
        border-style: dotted; 
        border-width: 1px 0px 0px 0px; 
        border-color: lightgrey
    }
</style>