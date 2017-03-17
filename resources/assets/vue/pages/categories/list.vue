<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div.ui.grid.one.column( v-else="" )
            div.column
                div.ui.dividing.header.fluid {{ translate('category.header') }}
            category-desktop-list.column.computer.only(
                ":toolsRow"=`[
                    $options.components.Edit,
                    $options.components.Save,
                    $options.components.Remove,
                    $options.components.Undo
                ]`
                ":items"="items"
                ":itemsNew"="itemsNew" )
            category-mobile-list.column.mobile.tablet.only(
                ":toolsRow"=`[
                    $options.components.Edit,
                    $options.components.Save,
                    $options.components.Remove,
                    $options.components.Undo
                ]`
                ":items"="items"
                ":itemsNew"="itemsNew" )
</template>

<script lang="coffee">
    module.exports =
        name: 'List'
        components:
            CategoryDesktopList: require '../../components/category/desktop/list.vue'
            CategoryMobileList: require '../../components/category/mobile/list.vue'
            Remove: require '../../components/tools/Remove.vue'
            Save: require '../../components/tools/Save.vue'
            Undo: require '../../components/tools/Undo.vue'
            Edit: require '../../components/tools/Edit.vue'
        data: ->
            items: []
            itemsNew: []
            columns: [ 'name' ]

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
                for key in @columns
                    Vue.set item, key+'_new', item[key]

            revertItem: (item) ->
                Vue.set item, 'edit', false

            attemptUpdate: (item) ->
                Vue.set item, 'edit', false
                for key in @columns
                    Vue.set item, key, item[key+'_new']

                @$http.put('api/categories/'+item.id, item).then(
                    (response) =>
                        Vue.set item, 'updated_at', response.data.updated_at
                        @$nextTick ->
                            $('tbody').trigger 'updated', item.id
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
