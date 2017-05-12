<template lang="pug">

        div.ui.icon.button.hover-default(
            style="margin-right: 0px"
            v-tooltip = ""
            ":data-html" = "translate('tool.add')"
            @click    = "add()"
            )
            i.ui.large.icons(
                style="margin-left: 12px; margin-right: 9px"
                )
                i.user.icon.icon-style(v-if="from=='users'" style="margin-right: 3px")
                i.cube.icon.icon-style(v-if="from=='articles'" style="margin-right: 3px")
                i.building.icon.icon-style(v-if="from=='sections'" style="margin-right: 3px")
                i.tag.icon.icon-style(v-if="from=='categories'" style="margin-right: 3px")
                i.corner.plus.icon.icon-style
</template>

<script lang="coffee">
    module.exports =
        name: 'Add'
        props: [ 'from' ]
        methods:
            add: ->
                bus.$emit @from + '_item_add'
</script>
