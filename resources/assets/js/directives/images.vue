<script lang="coffee">
    module.exports =
        inserted: (el, binding) ->
            dragged = false # Currently dragged element
            setupCards = ->
                # Setup each card
                for card, index in el.childNodes
                    # Set references and data-binding
                    card.vmData = binding.value[index] # Reference to the card Vue-data
                    for element in card.childNodes
                        if element.tagName == 'IMG'
                            card.image = element # The image of card
                            element.card = card  # The card of image
                    # Set current position
                    card.image.position = index
                    # When element is dropped upon
                    card.image.ondrop = (e) ->
                        e.preventDefault()
                        target = this
                        console.debug 'Move (d t): ' + dragged.position + ' -> ' + target.position
                        # Switch positions
                        temp = dragged.position
                        dragged.position = target.position
                        target.position = temp
                        console.debug 'Position (d t): ' + dragged.position + ' ' + target.position
                        # Clear styles
                        target.style.border = ""
                        # Update Vue-Data
                        Vue.set target.card.vmData, 'order', target.position
                        Vue.set dragged.card.vmData, 'order', dragged.position
                        # When DOM is ready
                        Vue.nextTick ->
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
                        this.style.border = "3px dotted red"
                    # When drag leaves
                    card.image.ondragleave = (e) ->
                        this.style.border = ""
                    # When starting to drag (set currently dragged element)
                    card.image.ondragstart = (e) ->
                        this.style.opacity = '0.5'
                        dragged = this
                    # When element is finished draging
                    card.image.ondragend = (e) ->
                        this.style.opacity = '1.0'
            # Run setup now
            setupCards()
</script>
