<template lang="pug">
    item-grid(
        header="Avdelningar"
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
                    label: 'Name'
                    key: 'name'
                    type: 'string'
                    search: true
                    sort: true
                    class: 'link'
                users:
                    label: 'Users'
                    key: 'users'
                    type: 'array'
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
                    class: 'link collapsing'
        methods:
            addItem: ->
                @itemsNew.push id_new: @itemsNew.length

            attemptCreate: (section) ->
                # Validation
                @createSection(section)

            createSection: (new_section) ->
                @$http.post('api/sections', new_section).then(
                    (response) =>
                        @attemptRemove new_section
                        section = response.data
                        @items.push section
                        @$nextTick ->
                            $('#section_content').trigger 'updated', section.id
                    (response) => bus.$emit 'error', response.data
                )

            editItem: (item) ->
                Vue.set item, 'edit', true
                for key, column of @columns
                    Vue.set item, key+'_new', item[key]

            revertItem: (item) ->
                Vue.set item, 'edit', false

            attemptUpdate: (section) ->
                Vue.set section, 'edit', false
                for key, column of @columns
                    Vue.set section, key, section[key+'_new']
                @$http.put('api/sections/'+section.id, section).then(
                    (response) =>
                        Vue.set section, 'updated_at', response.data.updated_at
                        @$nextTick ->
                            $('#section_content').trigger 'updated', section.id
                    (response) => bus.$emit 'error', response.data
                )

            attemptRemove: (section) ->
                # Remove new items that are not yet created
                if !section.id
                    for index, ob of @itemsNew
                        if Number(ob.id_new) == Number(section.id_new)
                            return @itemsNew.splice index, 1
                    return false
                # Are you sure?
                @removeSection section

            removeSection: (section) ->
                @$http.delete('api/sections/'+section.id).then(
                    (response) =>
                        bus.$emit 'success', 'removed_category'
                        $('tbody').trigger 'removed', section.id, ->
                            Vue.set section, 'removed', true
                    (response) => bus.$emit 'error', response.data
                )

            getSections: ->
                @$root.loading = true
                @$http.get('api/sections').then(
                    (response) =>
                        @items = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

        created: ->
            @getSections()
            bus.$on 'sections_item_add', => @addItem()
            bus.$on 'sections_item_edit', (item) => @editItem item
            bus.$on 'sections_item_revert', (item) => @revertItem item
            bus.$on 'sections_item_remove', (item) => @attemptRemove item
            bus.$on 'sections_item_changed', (item) => @attemptUpdate item
            bus.$on 'sections_item_created', (item) => @attemptCreate item
        beforeDestroy: ->
            bus.$off 'sections_item_add'
            bus.$off 'sections_item_edit'
            bus.$off 'sections_item_revert'
            bus.$off 'sections_item_remove'
            bus.$off 'sections_item_changed'
            bus.$off 'sections_item_created'
</script>
