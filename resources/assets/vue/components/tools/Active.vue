<template lang="pug">
        div.ui.icon.button.hover-default#tool_active_button(
            v-tooltip = ""
            ":data-html" = `
                (item.active) ? translate('tool.active') : translate('tool.inactive')
            `
            @click    = "update(item)"
            ":class" = `
                (item.active == 1) ? 'active-interval':''
            `
        )
            i.ui.icon.check.circle.icon-style(v-if="item.active == 1")
            i.ui.icon.minus.circle.icon-style(v-else="")
</template>

<script lang="coffee">
    module.exports =
        name: 'Active'
        props: [ 'item', 'from' ]
        methods:
            ###*
            #   Toggles items active state.
            #   @param {item} item to toggle
            ###
            update: (item) ->
                if item.active == "1"
                    item.active = "0"                    
                else
                    item.active = "1"
              
                bus.$emit @from + '_item_changed', item
</script>

<style>
    #tool_active_button {
        background-color: #FFF; 
        border-top: 1px solid rgb(212,212,213); 
        border-bottom: 1px solid rgb(212,212,213); 
        border-left: 1px solid rgb(212,212,213)
    }
</style>
