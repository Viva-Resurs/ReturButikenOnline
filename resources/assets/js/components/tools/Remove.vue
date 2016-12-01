<template lang="pug">
    div.ui.icon.button.hover-danger(
        v-tooltip = ""
        data-html = "Remove"
        v-show    = "!item.edit || !item.id"
        @click    = "remove(item)"
    )
        i.ui.icon.trash
</template>

<script lang="coffee">
    module.exports = {
        name: 'Remove'
        props: [ 'item' ]
        methods:
            remove: (item) ->
                bus.$emit('item_remove',item)
    }
</script>
