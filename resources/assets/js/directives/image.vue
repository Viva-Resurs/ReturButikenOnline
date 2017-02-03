<script lang="coffee">
    module.exports =
        update: (el, binding) ->
            console.log binding.value.id+' updated'
            #document.getElementById(binding.value.id).dataset.order = binding.value.order
        inserted: (el, binding) ->
            # Set elements image data-order to current order
            document.getElementById(binding.value.id).dataset.order = binding.value.order

            #el.addEventListener 'mouseover', (e) ->
                #$(el).dimmer 'show'
            #el.addEventListener 'mouseout', (e) ->
                # if pointer is leaving this element
                #   (and not onto a child of this element)
                #if $(el).find(e.toElement).length==0
                #    $(el).dimmer 'hide'

            # When element is dropped upon
            el.addEventListener 'drop', (e) ->
                e.preventDefault()
                e.target.style.border = "";
                # Get elements current data-order
                source = document.getElementById e.dataTransfer.getData "text"
                targetIMG = e.target
                order_old = source.dataset.order
                order_new = e.target.dataset.order
                console.debug 'drop e:'+e.target.id+' b:'+binding.value.id+' order:'+order_new+'->'+order_old
                source.dataset.order = order_new
                e.target.dataset.order = order_old
                # Update this value
                binding.value.order = e.target.dataset.order
                #bus.$emit 'image_reorder'


            # When hovering, just return (say it´s ok to drop here)
            el.addEventListener 'dragover', (e) ->
                e.preventDefault()

            # When drag enters
            el.addEventListener 'dragenter', (e) ->
                e.preventDefault()
                e.target.style.border = "3px dotted red"

            # When drag leaves
            el.addEventListener 'dragleave', (e) ->
                e.target.style.border = ""

            # When starting to drag (store current id)
            el.addEventListener 'dragstart', (e) ->
                e.dataTransfer.setData "Text", e.target.id
                e.target.style.opacity = '0.5'

            # When element is finished draging
            el.addEventListener 'dragend', (e) ->
                e.target.style.opacity = '1.0'
                console.debug 'dragend e:'+e.target.id+' b:'+binding.value.id+' order:'+binding.value.order+'->'+e.target.dataset.order
                # Update this value
                binding.value.order = e.target.dataset.order
                bus.$emit 'image_reorder'

</script>
