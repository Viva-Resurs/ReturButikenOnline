<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div.ui.grid.one.column( v-else="" )
            div.row
                div.column
                    div.ui.dividing.header.fluid {{ translate('section.header') }}
            div.row
                section-desktop-list.column.computer.only(
                    ":toolsRow"=`[
                        $options.components.Edit,
                        $options.components.Save,
                        $options.components.Remove,
                        $options.components.Undo
                    ]`
                    ":toolsBottom"=`[
                        $options.components.Add
                    ]`
                    ":items"="items" )
            div.row
                section-mobile-list.column.mobile.tablet.only(
                    ":toolsRow"=`[
                        $options.components.Edit,
                        $options.components.Save,
                        $options.components.Remove,
                        $options.components.Undo
                    ]`
                    ":toolsBottom"=`[
                        $options.components.Add
                    ]`
                    ":items"="items" )
</template>

<script lang="coffee">
    module.exports =
        name: 'List'
        components:
            SectionDesktopList: require '../../components/section/desktop/list.vue'
            SectionMobileList: require '../../components/section/mobile/list.vue'
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
                users:
                    label: 'users'
                    key: 'users'
                    type: 'array'
                    search: true
                    sort: true
                    class: 'link'
        methods:
            addItem: ->
                @itemsNew.push id_new: @itemsNew.length

            attemptCreate: (section) ->
                # Validation
                @createSection(section)

            createSection: (new_section) ->
                @$http.post('api/sections', new_section).then(
                    (response) =>
                        # Remove new items that are not yet created
                        #if !section.id

                        #    return false
                        #@removeSection new_section
                        for index, ob of @itemsNew
                            if Number(ob.id_new) == Number(new_section.id_new)
                                @itemsNew.splice index, 1
                        section = response.data
                        @items.push section
                        @$nextTick ->
                            $('tbody').trigger 'updated', section.id
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
                            $('tbody').trigger 'updated', section.id
                    (response) => bus.$emit 'error', response.data
                )

            attemptRemove: (section) ->
                bus.$emit 'show_message',
                    title: @$root.translate('section_list.remove_section_title') + "''"+section.name+"''."
                    message: @$root.translate('section_list.remove_section_message')
                    type:'confirm'
                    cb: => @removeSection section

            removeSection: (section) ->
                @$http.delete('api/sections/'+section.id).then(
                    (response) =>
                        bus.$emit 'success',
                            title: @$root.translate('section_list.success_message')
                            details: @$root.translate('section_list.section_removed')
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
