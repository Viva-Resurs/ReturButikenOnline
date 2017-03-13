<script lang="coffee">
    module.exports =
        inserted: (el, binding) ->
            dragged = false # Currently dragged element
            clearStates = ->
                for element in el.childNodes
                    element.style.opacity = '1.0'
            el.onmouseup = clearStates
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
                            # Set longpress timer to 200ms
                            element.onmousedown = ->
                                @longpress_timer = setTimeout =>
                                    @longpress = true
                                    @card.style.opacity = '0.5'
                                , 200
                            element.onmouseup = ->
                                # If not longpress, treat as normal click
                                if !@longpress
                                    bus.$emit 'show_message',
                                        title: @card.vmData.original_name
                                        type: 'image'
                                        image: @card.vmData
                                # Reset
                                clearTimeout @longpress_timer
                                @longpress = false
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
                    # When element is dropped upon
                    card.image.ondrop = (e) ->
                        e.preventDefault()
                        target = this
                        if process.env.NODE_ENV == "development"
                            console.debug 'Move (d t): ' + dragged.position + ' -> ' + target.position
                        # Switch positions
                        temp = dragged.position
                        dragged.position = target.position
                        target.position = temp
                        if process.env.NODE_ENV == "development"
                            console.debug 'Position (d t): ' + dragged.position + ' ' + target.position
                        # Clear styles
                        target.card.style.border = ""
                        # Update Vue-Data
                        Vue.set target.card.vmData, 'order', target.position
                        Vue.set dragged.card.vmData, 'order', dragged.position
                        # When DOM is ready
                        Vue.nextTick ->
                            if process.env.NODE_ENV == "development"
                                console.debug 'VM (d t): '+ dragged.card.vmData.order + ' ' + target.card.vmData.order
                            # Clear currently dragged element
                            dragged = false
                            # Setup cards again
                            setupCards()
                    # When hovering, just return (say it´s ok to drop here)
                    card.image.ondragover = (e) ->
                        e.preventDefault()
                    # When drag enters
                    card.image.ondragenter = (e) ->
                        @card.style.border = "3px dotted red"
                    # When drag leaves
                    card.image.ondragleave = (e) ->
                        @card.style.border = ""
                    # When starting to drag (set currently dragged element)
                    card.image.ondragstart = (e) ->
                        @card.style.opacity = '0.5'
                        dragged = this
                    # When element is finished draging
                    card.image.ondragend = (e) ->
                        clearStates()
            # When DOM is ready, setup cards
            Vue.nextTick -> setupCards()
            # Listen for 'refresh' (when adding new images, rebuild cards)
            $(el).on 'refresh', ->
                # When DOM is ready, setup cards again
                Vue.nextTick -> setupCards()
</script>
