<template lang="pug">
    div.ui.icon.button.hover-default#tool_preview_button(
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
            ###*
            #   Emits item preview.
            ###
            preview: (item) ->
                bus.$emit @from + '_item_preview', item
</script>

<style>
    #tool_preview_button {
        background-color: #FFF; 
        border-top: 1px solid rgb(212,212,213); 
        border-bottom: 1px solid rgb(212,212,213);
    }
</style>
