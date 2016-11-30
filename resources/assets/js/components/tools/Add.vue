<template lang="pug">
    div.ui.icon.button.hover-primary(
        v-tooltip="" data-html="Add"
        @click="add()" )
        
        i.ui.icon.plus
</template>

<script lang="coffee">
    module.exports = {
        name: 'Add'
        methods:
            add: () ->
                bus.$emit('item_add')
    }
</script>
