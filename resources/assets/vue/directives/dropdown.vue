<script lang="coffee">
    module.exports =
        inserted: (el) ->
            $(el).dropdown(
                # Emit updates if working in a select-menu
                onChange: (value, text, $selectedItem) ->
                    new_value = []

                    if value != ''
                        new_value = value.split ','

                    bus.$emit(
                        el.getAttribute('name')+'_changed',
                        el.id,
                        new_value
                    )
            )

            # Set pre-selected choices when avaiable
            if $(el).data 'selected'
                select = String( $(el).data 'selected' ).split ','
                for s in select
                    $(el).dropdown 'set selected', s
</script>
