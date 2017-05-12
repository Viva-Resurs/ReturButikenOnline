<template lang="pug">
    div.ui.icon.button.hover-default(
        v-tooltip = ""
        ":data-html" = "translate('tool.preview')"
        @click    = "preview(item)"
    )
        i.ui.icon.unhide.icon-style
</template>

<script lang="coffee">
    module.exports =
        name: 'Preview'
        props: [ 'item', 'from' ]
        methods:
            preview: (item) ->
                bus.$emit @from + '_item_preview', item
</script>
