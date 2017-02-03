<script lang="coffee">
    module.exports =
        update: (el, binding) ->
            console.log binding.value.id+' updated order:'
            image = el.childNodes[0]
            #console.log image.dataset.order
            binding.order = image.dataset.order
            #document.getElementById(binding.value.id).dataset.order = binding.value.order
        inserted: (el, binding) ->
            # Get image
            for element in el.childNodes
                if element.tagName == 'IMG'
                    @image = element
            # Set default options
            el.dragged = 'no'
            el.order = binding.value.order
            # Get sibling element thats being dragged
            getDragged = ->
                result = false
                for sib in el.parentNode.childNodes
                    if sib.dragged == 'yes'
                        result = sib
                return result



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
                #e.target.style.border = "";
                # Get elements current data-order
                #source = document.getElementById e.dataTransfer.getData "text"
                #targetIMG = e.target
                #order_old = source.dataset.order
                #order_new = e.target.dataset.order
                #console.debug 'drop e:'+e.target.id+' b:'+binding.value.id+' order:'+order_new+'->'+order_old
                #source.dataset.order = order_new
                #e.target.dataset.order = order_old
                # Update this value
                #binding.value.order = e.target.dataset.order
                #bus.$emit 'image_reorder'


            # When hovering, just return (say it´s ok to drop here)
            el.addEventListener 'dragover', (e) ->
                e.preventDefault()

            # When drag enters
            el.addEventListener 'dragenter', (e) ->
                e.preventDefault()
                if el.dragged == 'yes'
                    return # Dont switch with itself
                if !e.target.id
                    return # Dont switch with containers/labels
                dragged = getDragged()
                if !dragged
                    return
                console.log dragged.id + '('+dragged.order+') -> ' + e.target.id+'('+e.target.order+')'

                #dragged.parentNode.removeChild dragged
                #el.parentNode.removeChild el
                #dragged.parentNode.appendChild el
                #el.target.parentNode.appendChild dragged



                #cache = dragged.dataset.order
                #dragged.dataset.order = e.target.dataset.order
                #e.target.dataset.order = cache

                #binding.value.order = cache

                #bus.$emit 'image_order_changed', binding.value.id, e.target.dataset.order
                #bus.$emit 'image_order_changed', e.target.id, cache

                #e.target.dataset.order =
                #e.target.style.border = "3px dotted red"

            # When drag leaves
            el.addEventListener 'dragleave', (e) ->
                el.style.border = ""

            # When starting to drag (store current id)
            el.addEventListener 'dragstart', (e) ->
                #e.dataTransfer.setData "Text", e.target.id
                el.dragged = 'yes'
                el.style.opacity = '0.5'

            # When element is finished draging
            el.addEventListener 'dragend', (e) ->
                el.style.opacity = '1.0'
                el.dragged = 'no'
                #console.debug 'dragend e:'+e.target.id+' b:'+binding.value.id+' order:'+binding.value.order+'->'+e.target.dataset.order
                # Update this value
                #binding.value.order = e.target.dataset.order
                #bus.$emit 'image_reorder'

</script>
