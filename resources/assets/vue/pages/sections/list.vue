<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div.ui.grid.one.column( v-else="" )
            div.column
                div.ui.dividing.header.fluid {{ translate('section.header') }}
            section-desktop-list.column(
                v-if="screenType == 'desktop'"
                ":toolsRow"=`[
                    $options.components.Edit,
                    $options.components.Save,
                    $options.components.Remove,
                    $options.components.Undo
                ]`
                ":items"="items"
                ":itemsNew"="itemsNew" )
            section-mobile-list.column(
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
            SectionDesktopList: require '../../components/section/desktop/list.vue'
            SectionMobileList: require '../../components/section/mobile/list.vue'
            Remove: require '../../components/tools/Remove.vue'
            Save: require '../../components/tools/Save.vue'
            Undo: require '../../components/tools/Undo.vue'
            Edit: require '../../components/tools/Edit.vue'
        data: ->
            items: []
            itemsNew: []
            columns: ['name']
        methods:
            ###*
            #   Adds a item to the new items array.
            ###
            addItem: ->
                @itemsNew.push id_new: @itemsNew.length

            ###*
            #   Attempt to create a section.
            #   @param {section} section to use
            ###
            attemptCreate: (section) ->
                # Validation
                @createSection(section)

            ###*
            #   Sends a section create request to backend. 
            #   @param {new_section} section to create
            ###
            createSection: (new_section) ->
                @$http.post('api/sections', new_section).then(
                    (response) =>
                        for index, ob of @itemsNew
                            if Number(ob.id_new) == Number(new_section.id_new)
                                @itemsNew.splice index, 1
                        section = response.data
                        @items.push section
                        @$nextTick ->
                            $('tbody').trigger 'updated', section.id
                    (response) => bus.$emit 'error', response.data
                )

            ###*
            #   Edit a item in the section list.
            #   @param {item} item to edit
            ###
            editItem: (item) ->
                Vue.set item, 'edit', true
                for key in @columns
                    Vue.set item, key+'_new', item[key]
                    Vue.set item, key+'_sv_new', item[key+'_sv']

            ###*
            #   Revert changes of item. 
            #   @param {item} item to revert.
            ###
            revertItem: (item) ->
                Vue.set item, 'edit', false

            ###*
            #   Attempt to updates a section in the backend.
            #   @param {item} section to update
            ###
            attemptUpdate: (item) ->
                Vue.set item, 'edit', false
                for key in @columns
                    Vue.set item, key, item[key+'_new']
                    Vue.set item, key+'_sv', item[key+'_sv_new']
                @$http.put('api/sections/'+item.id, item).then(
                    (response) =>
                        Vue.set item, 'updated_at', response.data.updated_at
                        @$nextTick ->
                            $('tbody').trigger 'updated', item.id
                    (response) => bus.$emit 'error', response.data
                )

            ###*
            #   Attempt to remove a section. 
            #   @param {section} section to remove
            ###
            attemptRemove: (section) ->
                bus.$emit 'show_message',
                    title: @$root.translate('section_list.remove_section_title') + " \""+section.name+"\""
                    message: @$root.translate('section_list.remove_section_message')
                    type:'confirm'
                    cb: => @removeSection section

            ###*
            #   Removes a section from the backend. 
            #   @param {section} section to remove  
            ###
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

            ###*
            #   Gets a list of sections from backend.
            ###
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
