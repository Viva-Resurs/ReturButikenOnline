<template lang="pug">
    div.ui.grid(v-if="item" 
        ":class"="screenType == 'tablet' ? 'container' : ''" )        
        div.ui.grid.fluid.card.tablet.only
            div.content
                div.header.left.aligned
                    div.ui.dividing.header {{item.name}} {{ (item.amount > 1) ? '('+item.amount+')' : '' }}

                div.description(style="position: relative; top: -10px")
                    div.column( v-if="item.categories.length>0" ).ui.vertical.segment.basic
                        div.ui.black.horizontal.label.stackable(
                            v-for="category in item.categories"
                            )
                            | {{ category.name }}
                    br
                    div.no-padding.ui.vertical.segment.basic( v-if="item.images.length > 0")
                        div.ui.tiny.images
                            img.ui.rounded.image( style="background-color: lightgrey"
                                v-for="image in item.images" @click="previewImages(image)"
                                ":src"="image.thumb_path" )

                    div.ui.vertical.segment.basic
                        p(style="white-space: pre-wrap") {{ item.desc }}

                    div.ui.grid.equal.width
                        div.column.right.aligned
                            div.column.center.aligned.right.floated                            
                                div.ui.segment
                                    h2 {{ item.price }} {{ translate('article_card.price_currency') }}

            
            div.extra.content(style="height: 50px")
                div.ui.grid.equal.width
                    div.column.left.middle.aligned
                        div.ui.grid.center.aligned.left.floated.equal.width
                            div.column
                                div.ui.labeled.button.label()
                                    div.ui.label {{translate('shop.bidding')}}
                                    div.ui.basic.label {{ getDates(item.bidding_interval,0) }} &#8594 {{ getDates(item.bidding_interval,1) }}

                    div.column.right.aligned
                        div.column.center.aligned.right.floated
                            div.ui.primary.label.button( @click="previewItem(item)") {{ translate('shop.buy') }}
        
        div.ui.grid.segment.mobile.only(style="margin-left: 13px; margin-right: 13px; padding: 0px;")
       
            div.column.four.wide.top.aligned(style="padding-left: 15px;")
                img.ui.rounded.image.centered(
                    ":src"="item.images.length > 0 ? item.images[0].thumb_path : 'images/no_image.png'" 
                    style="background-color: black;"
                    @click="previewImages(item.images)") 
                div.ui.top.right.attached.circular.white.label(v-if="item.images.length > 0" style="margin-left: 0px; margin-top: 0px") {{ item.images.length }}
            

            div.column.twelve.wide                               
                div.no-padding
                    div.ui.sub.header {{item.name}} ( {{ item.amount }} )

                div.no-padding
                    p(style="height: 60px; overflow: hidden; white-space: pre-wrap;" ":id"="'article_desc_'+item.id") {{ item.desc }}
                
                div.ui.basic.circular.label.button.right.floated(
                    v-if="item.desc.length > 150"
                    ":id"="'article_expand_button_'+item.id" 
                    @click="toggleDescription(item.id)" 
                    style="padding: 10px; top: 60%; right: 15px; position:absolute;")
                    i.angle.down.icon(style="margin: 5px")

            div.row(style="border-style: dotted; border-width: 1px 0px 0px 0px; border-color: lightgrey")                
                div.column.four.wide.middle.center.aligned
                    div.column.center.aligned.right.floated
                        div.ui.segment.no-padding                            
                            h3 {{ item.price }} {{ translate('article_card.price_currency') }}
                   
                div.column.twelve.wide.right.aligned
                    div.ui.labeled.button.label()                        
                        div.ui.basic.label {{ getDates(item.bidding_interval,0) }} &#8594 {{ getDates(item.bidding_interval,1) }}
                        div.ui.primary.label.button( @click="previewItem(item)") {{ translate('shop.buy') }}




</template>

<script lang="coffee">
    module.exports =
        name: 'ArticleCard'
        props: [ 'item', 'tools', 'from' ]
        methods: 
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
            
            previewItem: (item) ->
                window.location.href = @$root.encodeArtNR(item)+location.hash
            
            getDates: (interval, type) ->
                divided = interval.split("| ")
                return divided[type].split(" ")[0]   
            
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
