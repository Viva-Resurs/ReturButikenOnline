<template>
    <div class="ui icon button hover-danger"
        v-tooltip data-html="Remove"
        @click="remove(item)">
        <i class="ui icon trash"></i>
    </div>
</template>

<script lang="coffee">
    module.exports = {
        name: 'Remove'
        props: [ 'item' ]
        methods:
            remove: (item) ->
                # Validation?
                bus.$emit('item_remove',item)
    }
</script>
