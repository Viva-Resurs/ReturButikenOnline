<template lang="pug">
    div.ui.icon.button.hover-primary(
        v-tooltip="" data-html="Save"
        v-show="item.edit || !item.id"
        @click="save(item)" )

        i.ui.icon.save    
</template>

<script lang="coffee">
    module.exports = {
        name: 'Save'
        props: [ 'item' ]
        methods:
            save: (item) ->
                if (item.id)
                    bus.$emit('item_changed',item)
                else
                    bus.$emit('item_created',item)
    }
</script>
