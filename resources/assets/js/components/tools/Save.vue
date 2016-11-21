<template>
    <div class="ui icon button hover-primary"
        v-tooltip data-html="Save"
        v-show="item.edit"
        @click="save(item)">
        <i class="ui icon save"></i>
    </div>
</template>

<script lang="coffee">
    module.exports = {
        name: 'Save'
        props: [ 'item' ]
        methods:
            save: (item) ->
                bus.$emit('item_updated',item)
    }
</script>
