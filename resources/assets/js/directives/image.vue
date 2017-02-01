<script lang="coffee">
    module.exports =
        inserted: (el, binding) ->
            # Not done here...
            # Set elements image data-order to current order
            # Then access it and refresh it...
            el.getElementById(binding.value.id).dataset.order = binding.value.order

            #el.addEventListener 'mouseover', (e) ->
                #$(el).dimmer 'show'
            #el.addEventListener 'mouseout', (e) ->
                # if pointer is leaving this element
                #   (and not onto a child of this element)
                #if $(el).find(e.toElement).length==0
                #    $(el).dimmer 'hide'

            # When element is dropped
            el.addEventListener 'drop', (e) ->
                sourceIMG = document.getElementById e.dataTransfer.getData "text"
                targetIMG = e.target
                order_old = sourceIMG.dataset.order
                order_new = targetIMG.dataset.order
                console.log order_old + ' -> ' + order_new
                sourceIMG.dataset.order = order_new
                targetIMG.dataset.order = order_old
                #binding.value.order = el.dataset.order
                e.preventDefault()
                e.target.style.border = "";
                #bus.$emit 'image_reorder'


            # When element is hovered
            el.addEventListener 'dragover', (e) ->
                e.preventDefault()

            el.addEventListener 'dragenter', (e) ->
                e.preventDefault()
                e.target.style.border = "3px dotted red"

            el.addEventListener 'dragleave', (e) ->
                e.target.style.border = ""

            # When starting to drag
            el.addEventListener 'dragstart', (e) ->
                e.dataTransfer.setData "Text", e.target.id
                el.style.opacity = '0.5'

            # When element is finished draging
            el.addEventListener 'dragend', (e) ->
                el.style.opacity = '1.0'
                #binding.value.order = el.dataset.order
                #bus.$emit 'image_reorder'

</script>
