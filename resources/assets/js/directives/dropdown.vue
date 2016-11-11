<script>
    export default {
        inserted: function(el){
            $(el).dropdown({
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
            });
        }
    };
</script>
