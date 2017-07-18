<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div.ui.grid.one.column( v-else="" )
            div.column
                div.ui.dividing.header.fluid {{ translate('category.header') }}
            category-desktop-list.column( 
                v-if="screenType == 'desktop'"
                ":toolsRow"=`[
                    $options.components.Edit,
                    $options.components.Save,
                    $options.components.Remove,
                    $options.components.Undo
                ]`
                ":items"="items"
                ":itemsNew"="itemsNew" )
            category-mobile-list.column(
                v-if="(screenType == 'mobile') || (screenType == 'tablet')"
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
            ###*
            #   Adds a item to the new items array.
            ###
            addItem: ->
                @itemsNew.push id_new: @itemsNew.length

            ###*
            #   Attempt to create a category.
            #   @param {category} category to use
            ###
            attemptCreate: (category) ->
                # Validation
                @createCategory category

            ###*
            #   Sends a category create request to backend. 
            #   @param {new_category} category to create
            ###
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

            ###*
            #   Edit a item in the category list.
            #   @param {item} item to edit
            ###
            editItem: (item) ->
                Vue.set item, 'edit', true
                for key in @columns
                    Vue.set item, key+'_new', item[key]

            ###*
            #   Revert changes of item. 
            #   @param {item} item to revert.
            ###
            revertItem: (item) ->
                Vue.set item, 'edit', false

            ###*
            #   Attempt to updates a category in the backend.
            #   @param {item} category to update
            ###
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

            ###*
            #   Attempt to remove a category. 
            #   @param {category} category to remove
            ###
            attemptRemove: (category) ->
                # Remove new items that are not yet created
                if !category.id
                    for index, ob of @itemsNew
                        if Number(ob.id_new) == Number(category.id_new)
                            return @itemsNew.splice index, 1
                    return false
                # Are you sure?
                @removeCategory category

            ###*
            #   Removes a category from the backend. 
            #   @param {category} category to remove  
            ###
            removeCategory: (category) ->
                @$http.delete('api/categories/'+category.id).then(
                    (response) =>
                        $('tbody').trigger 'removed', category.id, ->
                            Vue.set category, 'removed', true
                    (response) => bus.$emit 'error', response.data
                )

            ###*
            #   Gets a list of categories from backend.
            ###
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
