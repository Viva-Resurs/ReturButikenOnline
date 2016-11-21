<template>
    <div class="ui icon button hover-primary"
        v-tooltip data-html="Edit"
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
