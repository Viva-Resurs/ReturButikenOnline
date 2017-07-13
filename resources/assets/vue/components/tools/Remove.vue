<template lang="pug">
    div.ui.icon.button.hover-danger#tool_remove_button(      
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
            ###*
            #   Emits item remove.
            ###
            remove: (item) ->
                bus.$emit @from + '_item_remove', item
</script>

<style>
    #tool_remove_button {
        background-color: #FFF; 
        border-top: 1px solid rgb(212,212,213);
        border-bottom: 1px solid rgb(212,212,213); 
        border-right: 1px solid rgb(212,212,213);
    }
</style>
