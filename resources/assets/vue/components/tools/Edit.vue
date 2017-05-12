<template lang="pug">
    div.ui.icon.button.hover-primary(
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
        methods:
            edit: (item) ->
                bus.$emit @from + '_item_edit', item
</script>
