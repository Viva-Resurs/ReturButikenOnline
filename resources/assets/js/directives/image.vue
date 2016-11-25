<script>
    export default {
        inserted: function(el){
            el.addEventListener('mouseover', (e) => {
                $(el).dimmer('show')
            })
            el.addEventListener('mouseout', (e) => {
                if (e.toElement.classList.contains('cards'))
                    $(el).dimmer('hide')
            })
        }
    };
</script>
