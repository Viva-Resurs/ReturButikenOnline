<template lang="pug">
    div.ui.icon.button(
        v-tooltip = ""
        ":data-html" = "translate('tool.undo')"
        v-show    = "item.edit"
        @click    = "undo(item)"
    )
        i.ui.icon.undo
</template>

<script lang="coffee">
    module.exports =
        name: 'Undo'
        props: [ 'item', 'from' ]
        methods:
            ###*
            #   Undo item change.
            #   @param {item} item to revert
            ###
            undo: (item) ->
                bus.$emit @from + '_item_revert', item
</script>
