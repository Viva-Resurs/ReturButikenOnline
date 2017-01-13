<template lang="pug">
    div.ui.icon.button.hover-primary(
        v-tooltip = ""
        data-html = "Preview"
        @click    = "preview(item)"
    )
        i.ui.icon.unhide
</template>

<script lang="coffee">
    module.exports =
        name: 'Preview'
        props: [ 'item', 'from' ]
        methods:
            preview: (item) ->
                bus.$emit @from + '_item_preview', item
</script>
