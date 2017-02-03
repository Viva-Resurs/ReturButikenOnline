<script lang="coffee">
    module.exports =
        update: (el, binding) ->
            console.log 'updated'
        inserted: (el, binding) ->
            # Setup. Hook vue-data & DOM-elements
            @images = binding.value
            @cards = el.childNodes
            dragged = 0
            # Get IMG-elements
            for card in @cards
                for element in card.childNodes
                    if element.tagName == 'IMG'
                        card.image = element
            # Go through each card
            for card, index in @cards
                # Set position
                card.image.position = index

                # When element is dropped upon
                card.image.addEventListener 'drop', (e) ->
                    e.preventDefault()
                    console.debug dragged.position + ' -> ' + this.position

                # When hovering, just return (say it´s ok to drop here)
                card.image.addEventListener 'dragover', (e) ->
                    e.preventDefault()

                # When drag enters
                card.image.addEventListener 'dragenter', (e) ->
                    this.style.border = "3px dotted red"

                # When drag leaves
                card.image.addEventListener 'dragleave', (e) ->
                    this.style.border = ""

                # When starting to drag (store current position)
                card.image.addEventListener 'dragstart', (e) ->
                    this.style.opacity = '0.5'
                    dragged = this

                # When element is finished draging
                card.image.addEventListener 'dragend', (e) ->
                    this.style.opacity = '1.0'
</script>
