<template>
    <div class="ui icon button hover-primary"
        v-tooltip data-html="Add"
        @click="add()">
        <i class="ui icon plus"></i>
    </div>
</template>

<script lang="coffee">
    module.exports = {
        name: 'Add'
        methods:
            add: () ->
                bus.$emit('item_add')
    }
</script>
