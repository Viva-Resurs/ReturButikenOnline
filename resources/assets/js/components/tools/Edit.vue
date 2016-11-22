<template>
    <div class="ui icon button hover-primary"
        v-tooltip data-html="Edit"
        v-show="!item.edit && item.id"
        @click="edit(item)">
        <i class="ui icon pencil"></i>
    </div>
</template>

<script lang="coffee">
    module.exports = {
        name: 'Edit'
        props: [ 'item' ]
        methods:
            edit: (item) ->
                bus.$emit('item_edit',item)
    }
</script>
