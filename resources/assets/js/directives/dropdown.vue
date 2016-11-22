<script>
    export default {
        inserted: function(el){
            $(el).dropdown({
                // Emit updates if working in a select-menu
                onChange: function(value, text, $selectedItem) {
                    var new_value = []

                    if (value!='')
                        new_value = value.split(',');

                    bus.$emit(
                        el.getAttribute('name')+'_changed',
                        el.id,
                        new_value
                    );
                }
            })

            // Set pre-selected choices when avaiable
            if ( $(el).data('selected') ) {
                var select = String($(el).data('selected')).split(',')
                for (var i=0 ; i<select.length ; i++)
                    $(el).dropdown('set selected', select[i] );
            }
        }
    };
</script>
