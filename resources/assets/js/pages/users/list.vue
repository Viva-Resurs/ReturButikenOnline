<template lang="pug">
    item-grid(
        header="Användare"
        ":columns"="columns"
        ":toolsRow"=`[
            $options.components.Edit,
            $options.components.Remove
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
            ItemGrid: require '../../components/ItemGrid.vue'
            Remove: require '../../components/tools/Remove.vue'
            Edit: require '../../components/tools/Edit.vue'
            Add: require '../../components/tools/Add.vue'
        data: ->
            items: []
            columns:
                name:
                    label: 'Name'
                    key: 'name'
                    type: 'string'
                    search: true
                    sort: true
                    default_sort: true
                    tooltip: 'desc'
                    class: 'link'
                section:
                    label: 'Section'
                    key: 'sections'
                    type: 'array'
                    search: true
                    sort: true
                    class: 'link'
                roles:
                    label: 'Roles'
                    key: 'roles'
                    type: 'array'
                    search: true
                    sort: true
                    class: 'link'
        methods:
            attemptRemove: (user) ->
                bus.$emit 'show_message',
                    title: 'Confirm'
                    message: 'Do you want to remove the user?'
                    type: 'confirm'
                    cb: => @removeUser user
            removeUser: (user) ->
                @$http.delete('api/users/'+user.id).then(
                    (response) =>
                        bus.$emit 'success',
                            title: 'Success'
                            details: 'User removed'
                        Vue.set user, 'removed', true
                    (response) => bus.$emit 'error', response.data
                )
            getUsers: ->
                @$root.loading = true
                @$http.get('api/users').then(
                    (response) =>
                        @items = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )
        created: ->
            @getUsers()
            bus.$on 'users_item_add', => @$router.push path: '/users/create'
            bus.$on 'users_item_remove', (item) => @attemptRemove item
            bus.$on 'users_item_edit', (item) => @$router.push path: '/users/'+item.id
        beforeDestroy: ->
            bus.$off 'users_item_add'
            bus.$off 'users_item_edit'
            bus.$off 'users_item_remove'
</script>
