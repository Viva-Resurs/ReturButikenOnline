<template lang="pug">
    div.ui.icon.button.hover-primary(
        v-tooltip = ""
        data-html = "Save"
        v-show    = "item.edit || !item.id"
        @click    = "save(item)"
    )
        i.ui.icon.save
</template>

<script lang="coffee">
    module.exports = {
        name: 'Save'
        props: [ 'item', 'from' ]
        methods:
            save: (item) ->
                if (item.id)
                    bus.$emit(@from+'_item_changed',item)
                else
                    bus.$emit(@from+'_item_created',item)
    }
</script>
