<template lang="pug">
    div.ui.icon.button.hover-primary#tool_edit_button(
        v-tooltip = ""
        ":data-html" = "translate('tool.edit')"
        v-show    = "!item.edit && item.id"
        @click    = "edit(item)"
    )
        i.ui.icon.pencil.icon-style
</template>

<script lang="coffee">
    module.exports =
        name: 'Edit'
        props: [ 'item', 'from' ]
        ###*
        #   Emits item edit.
        ###
        methods:
            edit: (item) ->
                bus.$emit @from + '_item_edit', item
</script>

<style>
    #tool_edit_button {
        background-color: #FFF; 
        border: 1px solid rgb(212,212,213);
    }
</style>
