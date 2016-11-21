<template>
    <item-grid
        header="Categories"
        :columns="columns"
        :tools=
            "[
              $options.components.Edit,
              $options.components.Remove,
              $options.components.Save,
              $options.components.Undo
            ]"
        :items="items">
    </item-grid>
</template>

<script lang="coffee">
    ItemGrid = require '../../components/ItemGrid.vue';
    Remove = require '../../components/tools/Remove.vue';
    Save = require '../../components/tools/Save.vue';
    Undo = require '../../components/tools/Undo.vue';
    Edit = require '../../components/tools/Edit.vue';

    module.exports = {

        name: 'List'

        components: { ItemGrid, Edit, Remove, Save, Undo }

        data: ->
            items: []

            # Not done
            adding: false
            new_category:
                name: ''

            columns:
                name:
                    label: 'name'
                    type: 'string'
                    search: true
                    sort: true
                    class: 'link'
                updated_at:
                    label: 'updated_at'
                    type: 'number'
                    desc: true
                    default_sort: true
                    search: true
                    sort: true
                    class: 'collapsing'


        methods:
            updateList: () ->
                @items.reverse()
                @$nextTick ->
                    @items.reverse()

            attemptCreate: (category) ->
                # Validation
                @createCategory(category)

            createCategory: (category) ->
                # Not done...
                return false
                @$http.post('categories', @new_category).then(
                    (response) =>
                        console.log('ok')
                        category = response.data
                        @items.push(category)
                        @new_category.name = ''
                        @adding = false
                        @$nextTick ->
                            $('#category_content').trigger('updated',category.id)

                    (response) => bus.$emit('error', response)
                );

            editItem: (item) ->
                item.edit = true
                @updateList()

            revertItem: (item) ->
                item.edit = false
                @updateList()

            attemptUpdate: (category) ->
                category.edit = false;
                # Not done...
                return false
                category.name = category.new_name.trim()
                @$http.put('categories/' + category.id, category).then(
                    (response) =>
                        category.updated_at = response.data.updated_at
                        @updateList()
                        @$nextTick ->
                            $('#category_content').trigger('updated',category.id)
                    (response) => bus.$emit('error', response)
                );

            attemptRemove: (category) ->
                # Are you sure?
                @removeCategory(category)

            removeCategory: (category) ->
                @$http.delete('categories/' + category.id).then(
                    (response) =>
                        bus.$emit('success', 'removed_category')
                        $('table').trigger('removed',category.id, ->
                            category.removed = true
                            @updateList()
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

            bus.$on('item_edit', (item) => @editItem(item) )
            bus.$on('item_revert', (item) => @revertItem(item) )
            bus.$on('item_remove', (item) => @attemptRemove(item) )
            bus.$on('item_changed', (item) => @attemptUpdate(item) )

        beforeDestroy: ->
            bus.$off('item_edit')
            bus.$off('item_revert')
            bus.$off('item_remove')
            bus.$off('item_changed')
    }
</script>
