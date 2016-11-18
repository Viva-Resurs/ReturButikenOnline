<template>
    <div class="ui icon basic buttons">
        <slot name="extraTools">

        </slot>
        <button class="ui icon button hover-primary"
            v-tooltip data-html="Edit"
            @click="edit(item)">
            <i class="ui icon pencil"></i>
        </button>
        <button class="ui icon button hover-danger"
            v-tooltip data-html="Remove"
            @click="remove(item)">
            <i class="ui icon trash"></i>
        </button>
    </div>
</template>

<script lang="coffee">
    module.exports = {
        name: 'ItemTools'
        props: [ 'item' ]
        methods:
            edit: (item) ->
                bus.$emit('item_edit',item)
            remove: (item) ->
                # Validation?
                bus.$emit('item_remove',item)
    }
</script>
