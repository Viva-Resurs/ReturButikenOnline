<template>
    <div class="ui icon button hover-danger"
        v-tooltip data-html="Remove"
        v-show="!item.edit || !item.id"
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
                bus.$emit('item_remove',item)
    }
</script>
