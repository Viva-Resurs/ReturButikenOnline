<template lang="pug">
    item-grid(
        ":header"="translate('category.header')"
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
    module.exports =
        name: 'List'
        components:
            ItemGrid: require '../../components/ItemGrid.vue'
            Remove: require '../../components/tools/Remove.vue'
            Save: require '../../components/tools/Save.vue'
            Undo: require '../../components/tools/Undo.vue'
            Edit: require '../../components/tools/Edit.vue'
            Add: require '../../components/tools/Add.vue'
        data: ->
            items: []
            itemsNew: []
            columns:
                name:
                    label: 'name'
                    key: 'name'
                    type: 'string'
                    search: true
                    sort: true
                    default_sort: true
                    class: 'link'

        methods:
            addItem: ->
                @itemsNew.push id_new: @itemsNew.length

            attemptCreate: (category) ->
                # Validation
                @createCategory category

            createCategory: (new_category) ->
                @$http.post('api/categories', new_category).then(
                    (response) =>
                        @attemptRemove new_category
                        category = response.data
                        @items.push category
                        @$nextTick ->
                            $('tbody').trigger 'updated', category.id
                    (response) => bus.$emit 'error', response.data
                )

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

                @$http.put('api/categories/'+category.id, category).then(
                    (response) =>
                        Vue.set category, 'updated_at', response.data.updated_at
                        @$nextTick ->
                            $('tbody').trigger 'updated', category.id
                    (response) => bus.$emit 'error', response.data
                )

            attemptRemove: (category) ->
                # Remove new items that are not yet created
                if !category.id
                    for index, ob of @itemsNew
                        if Number(ob.id_new) == Number(category.id_new)
                            return @itemsNew.splice index, 1
                    return false
                # Are you sure?
                @removeCategory category

            removeCategory: (category) ->
                @$http.delete('api/categories/'+category.id).then(
                    (response) =>
                        $('tbody').trigger 'removed', category.id, ->
                            Vue.set category, 'removed', true
                    (response) => bus.$emit 'error', response.data
                )

            getCategories: ->
                @$root.loading = true;
                @$http.get('api/categories').then(
                    (response) =>
                        @items = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

        created: ->
            @getCategories()
            bus.$on 'categories_item_add', => @addItem()
            bus.$on 'categories_item_edit', (item) => @editItem item
            bus.$on 'categories_item_revert', (item) => @revertItem item
            bus.$on 'categories_item_remove', (item) => @attemptRemove item
            bus.$on 'categories_item_changed', (item) => @attemptUpdate item
            bus.$on 'categories_item_created', (item) => @attemptCreate item
        beforeDestroy: ->
            bus.$off 'categories_item_add'
            bus.$off 'categories_item_edit'
            bus.$off 'categories_item_revert'
            bus.$off 'categories_item_remove'
            bus.$off 'categories_item_changed'
            bus.$off 'categories_item_created'
</script>