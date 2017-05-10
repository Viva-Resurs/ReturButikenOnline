<script lang="coffee">
    module.exports =
        inserted: (el, binding) ->
            dragged = false # Currently dragged element
            clearStates = ->
                for element in el.childNodes
                    element.style.opacity = '1.0'            
            
            el.addEventListener 'mouseup', clearStates          

            # Set longpress timer to 200ms
            longPress = ->
                @longpress_timer = setTimeout =>
                    @longpress = true
                    @card.style.opacity = '0.5'
                , 200

           # If not longpress, treat as normal click  
            shortPress = (evt) ->
                if !dragged
                    if !@longpress
                        bus.$emit 'show_message',
                            title: evt.target.card.vmData.original_name
                            type: 'image'
                            image: evt.target.card.vmData
                # Reset
                clearTimeout @longpress_timer
                @longpress = false
    
            setupCards = ->
                
                # Setup each card
                for card, index in el.childNodes
                    # Set references and data-binding
                    card.vmData = binding.value[index] # Reference to the card Vue-data

                    for element in card.childNodes
                        if element.tagName == 'IMG'
                            card.image = element # The image of card
                            element.card = card  # The card of image
                            # Clear longpress state
                            clearTimeout element.longpress_timer
                            element.longpress = false
                            
                            element.addEventListener 'mousedown', longPress                            
                            #element.addEventListener 'touchstart', longPress                            
                            
                            element.addEventListener 'mouseup', shortPress, @card
                            #element.addEventListener 'touchend', shortPress, @card                            
        
                        if element.tagName == 'A'
                            remover = element # Remove button
                            remover.card = card
                            remover.onclick = ->
                                # Remove this card
                                bus.$emit 'image_removed', @card.vmData
                                # When DOM is ready, setup cards again
                                Vue.nextTick -> setupCards()
                    
                    # Set current position
                    card.image.position = index

                    $(card.image).draggable
                        helper: "clone"
                        start: (event, ui) ->                        
                            @card.style.opacity = '0.5'
                            dragged = this
                        stop: (event, ui) ->                            
                            clearStates()

                    # When element is dropped upon
                    $(card.image).droppable
                        drop: (event, ui) ->  
                            event.preventDefault()                          
                            
                            target = event.target
                            temp = dragged.position
                                                        
                            dragged.position = target.position
                            
                            target.position = temp
                            target.card.style.border = ""
                            
                            Vue.set target.card.vmData, 'order', target.position
                            Vue.set dragged.card.vmData, 'order', dragged.position
                            Vue.nextTick ->
                                # Clear currently dragged element
                                dragged = false
                                # Setup cards again
                                setupCards()
                            
                            return
                        out: (event, ui) ->                            
                            @card.style.border = ""
                        over: (event, ui) ->           
                            @card.style.border = "3px dotted red"

            # When DOM is ready, setup cards
            Vue.nextTick -> setupCards()
            # Listen for 'refresh' (when adding new images, rebuild cards)
            $(el).on 'refresh', ->
                # When DOM is ready, setup cards again
                Vue.nextTick -> setupCards()
</script>
