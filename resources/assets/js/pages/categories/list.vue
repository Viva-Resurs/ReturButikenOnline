<template lang="pug">
    item-grid(
        header="Categories"
        ":columns"="columns"
        ":toolsRow"=
            `[
                $options.components.Edit,
                $options.components.Save,
                $options.components.Remove,
                $options.components.Undo
            ]`
        ":toolsBottom"=
            `[
                $options.components.Add
            ]`
        ":itemsNew"="itemsNew"
        ":items"="items" )
</template>

<script lang="coffee">
    module.exports = {
        name: 'List'
        components: {
            ItemGrid: require '../../components/ItemGrid.vue'
            Remove: require '../../components/tools/Remove.vue'
            Save: require '../../components/tools/Save.vue'
            Undo: require '../../components/tools/Undo.vue'
            Edit: require '../../components/tools/Edit.vue'
            Add: require '../../components/tools/Add.vue'
        }
        data: ->
            items: []
            itemsNew: []
            columns:
                name:
                    label: 'Name'
                    key: 'name'
                    type: 'string'
                    search: true
                    sort: true
                    class: 'link'
                updated_at:
                    label: 'Updated'
                    key: 'updated_at'
                    type: 'number'
                    desc: true
                    default_sort: true
                    search: true
                    sort: true
                    class: 'collapsing'

        methods:
            addItem: () ->
                @itemsNew.push({id_new:@itemsNew.length})

            attemptCreate: (category) ->
                # Validation
                @createCategory(category)

            createCategory: (new_category) ->
                @$http.post('categories', new_category).then(
                    (response) =>
                        @attemptRemove(new_category)
                        category = response.data
                        @items.push(category)
                        @$nextTick ->
                            $('#category_content').trigger('updated',category.id)

                    (response) => bus.$emit('error', response)
                );

            editItem: (item) ->
                Vue.set item, 'edit', true
                for key, column of @columns
                    Vue.set item, key+'_new', item[key]

            revertItem: (item) ->
                Vue.set item, 'edit', false

            attemptUpdate: (category) ->
                Vue.set category, 'edit', false
                for key, column of @columns
                    Vue.set category, key, category[key+'_new']

                @$http.put('categories/' + category.id, category).then(
                    (response) =>
                        Vue.set category, 'updated_at', response.data.updated_at
                        @$nextTick ->
                            $('#category_content').trigger('updated',category.id)
                    (response) => bus.$emit('error', response)
                );

            attemptRemove: (category) ->
                # Remove new items that are not yet created
                if (!category.id)
                    for index, ob of @itemsNew
                        if (Number ob.id_new == Number category.id_new)
                            return @itemsNew.splice(index,1)
                    return false
                # Are you sure?
                @removeCategory(category)

            removeCategory: (category) ->
                @$http.delete('categories/' + category.id).then(
                    (response) =>
                        bus.$emit('success', 'removed_category')
                        $('tbody').trigger('removed',category.id, ->
                            Vue.set category, 'removed', true
                        )
                    (response) => bus.$emit('error', response)
                );

            getCategories: () ->
                @$root.loading = true;
                @$http.get('categories').then(
                    (response) =>
                        @items = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit('error', response)
                        @$root.loading = false
                );

        created: ->
            @getCategories()
            bus.$on('item_add', () => @addItem() )
            bus.$on('item_edit', (item) => @editItem(item) )
            bus.$on('item_revert', (item) => @revertItem(item) )
            bus.$on('item_remove', (item) => @attemptRemove(item) )
            bus.$on('item_changed', (item) => @attemptUpdate(item) )
            bus.$on('item_created', (item) => @attemptCreate(item) )

        beforeDestroy: ->
            bus.$off('item_add')
            bus.$off('item_edit')
            bus.$off('item_revert')
            bus.$off('item_remove')
            bus.$off('item_changed')
            bus.$off('item_created')
    }
</script>
