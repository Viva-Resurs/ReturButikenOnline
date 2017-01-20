<script lang="coffee">
    module.exports =
        inserted: (el) ->
            el.addEventListener 'mouseover', (e) ->
                #$(el).dimmer('show')
            el.addEventListener 'mouseout', (e) ->
                # if pointer is leaving this element
                #   (and not onto a child of this element)
                #if $(el).find(e.toElement).length==0
                #    $(el).dimmer('hide')

            # When element is dropped
            el.addEventListener 'drop', (e) ->
                console.log e
                sourceIMG = document.getElementById( e.dataTransfer.getData("text") )
                sourceDIV = sourceIMG.parentNode;
                targetIMG = e.target;
                targetDIV = e.target.parentNode;
                e.preventDefault();
                targetDIV.removeChild(targetIMG);
                targetDIV.appendChild(sourceIMG)
                sourceDIV.appendChild(targetIMG)

            # When element is hovered
            el.addEventListener 'dragover', (e) ->
                e.preventDefault();

            # When starting to drag
            el.addEventListener 'dragstart', (e) ->
                e.dataTransfer.setData("text", e.target.id);
                #el.style.opacity = '0.5'

            # When element is finished draging
            el.addEventListener 'dragend', (e) ->
                #el.style.opacity = '1.0'
</script>
