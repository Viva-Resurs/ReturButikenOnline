<template lang="pug">
    div.ui.icon.button.hover-primary(
        v-tooltip = ""
        ":data-html" = "translate('tool.add')"
        @click    = "add()"
    )
        i.ui.icon.plus
</template>

<script lang="coffee">
    module.exports =
        name: 'Add'
        props: [ 'from' ]
        methods:
            add: ->
                bus.$emit @from + '_item_add'
</script>
