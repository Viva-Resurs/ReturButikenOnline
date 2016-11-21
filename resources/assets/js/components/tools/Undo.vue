<template>
    <div class="ui icon button"
        v-tooltip data-html="Undo"
        v-show="item.edit"
        @click="undo(item)">
        <i class="ui icon undo"></i>
    </div>
</template>

<script lang="coffee">
    module.exports = {
        name: 'Undo'
        props: [ 'item' ]
        methods:
            undo: (item) ->
                bus.$emit('item_revert',item)
    }
</script>
