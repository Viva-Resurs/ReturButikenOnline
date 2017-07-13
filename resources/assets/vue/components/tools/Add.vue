<template lang="pug">
        div.ui.icon.button.hover-default#tool_add_button(
            v-tooltip = ""
            ":data-html" = "translate('tool.add')"
            @click    = "add()"
            )
            i.ui.large.icons#tool_add_icons
                i.user.icon.icon-style#tool_add_icons_users(v-if="from=='users'")
                i.cube.icon.icon-style#tool_add_icons_articles(v-if="from=='articles'")
                i.building.icon.icon-style#tool_add_icons_sections(v-if="from=='sections'")
                i.tag.icon.icon-style#tool_add_icons_categories(v-if="from=='categories'")
                i.corner.plus.icon.icon-style
</template>

<script lang="coffee">
    module.exports =
        name: 'Add'
        props: [ 'from' ]
        methods:
            ###*
            #   Emits item add.
            ###
            add: ->
                bus.$emit @from + '_item_add'
</script>

<style>
    #tool_add_button {
        margin-right: 0px;
    }

    #tool_add_icons {
        margin-left: 12px; 
        margin-right: 9px;
    }

    #tool_add_icons_users, #tool_add_icons_articles, 
    #tool_add_icons_sections, #tool_add_icons_categories {
        margin-right: 3px;        
    }

</style>
