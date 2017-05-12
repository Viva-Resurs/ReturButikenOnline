<template lang="pug">
    div.ui.icon.button.hover-danger(
        v-tooltip = ""
        ":data-html" = "translate('tool.remove')"
        v-show    = "!item.edit || !item.id"
        @click    = "remove(item)"
    )
        i.ui.icon.trash.icon-style
</template>

<script lang="coffee">
    module.exports =
        name: 'Remove'
        props: [ 'item', 'from' ]
        methods:
            remove: (item) ->
                bus.$emit @from + '_item_remove', item
</script>
