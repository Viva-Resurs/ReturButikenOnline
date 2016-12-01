<script>
    export default {
        inserted: function(el){
            el.addEventListener('mouseover', (e) => {
                $(el).dimmer('show')
            })
            el.addEventListener('mouseout', (e) => {
                // if pointer is leaving this element
                //   (and not onto a child of this element)
                if ($(el).find(e.toElement).length==0)
                    $(el).dimmer('hide')
            })
        }
    };
</script>
