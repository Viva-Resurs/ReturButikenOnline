<script lang="coffee">
    module.exports =
        inserted: (el, binding) ->
            dragged = false # Currently dragged element
            cardIsDragged = false

            ###*
            #   Clears the states of each childnode. 
            #   (Makes each node visible again).
            ###
            clearStates = ->
                for element in el.childNodes
                    element.style.opacity = '1.0'            
            
            el.addEventListener 'mouseup', clearStates          

            ###*
            #   Handles card click event while its not dragged.
            #   Triggers image preview on click.
            ###
            clickedCard = (evt) ->
                if !cardIsDragged
                    bus.$emit 'show_message',
                        title: evt.target.card.vmData.original_name
                        type: 'image'
                        index: evt.target.card.image.position
                        images: binding.value                                        
            
            ###*
            #   Creates a new array of images from passed 
            #   on binding value. 
            #   @return {images} list of images
            ###
            getImages = () ->
                images = []
                binded_images = binding.value
                for image in binded_images
                    images.push image                
                return images

            ###*
            #   Sets up all image cards. 
            #   Handles moving around and removing cards/images.
            ###
            setupCards = ->
                getImages()
                # Setup each card
                for card, index in el.childNodes                    
                    # Set references and data-binding
                    card.vmData = binding.value[index] # Reference to the card Vue-data

                    for element in card.childNodes
                        if element.tagName == 'IMG'
                            card.image = element # The image of card
                            element.card = card  # The card of image
                            # Clear longpress state
                            element.addEventListener 'mouseup', clickedCard, @card                            
                  
                        if element.tagName == 'A'
                            remover = element # Remove button
                            remover.card = card
                            remover.onclick = ->
                                # Remove this card
                                bus.$emit 'image_removed', @card.vmData
                                # When DOM is ready, setup cards again
                                Vue.nextTick -> setupCards()
                    
                    # Set current position
                    if card.image
                        card.image.position = index                    

                    $(card.image).draggable
                        helper: "clone"
                        start: (event, ui) ->                        
                            @card.style.opacity = '0.5'
                            dragged = this
                            cardIsDragged = true                            
                        stop: (event, ui) ->   
                            cardIsDragged = false                         
                            clearStates()

                    # When element is dropped upon
                    $(card.image).droppable
                        drop: (event, ui) ->  
                            if cardIsDragged
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
