<template lang="pug">
    div
        loading( v-if="$root.loading" )
        div.ui.grid.one.column( v-else="" )
            div.column
                div.ui.dividing.header.fluid {{ translate('user.header') }}
            user-desktop-list.column(
                v-if="screenType == 'desktop'"
                ":toolsRow"=`[
                    $options.components.Edit,
                    $options.components.Remove
                ]`
                ":items"="items" )
            user-mobile-list.column(
                v-if="(screenType == 'mobile') || (screenType == 'tablet')"
                ":toolsRow"=`[
                    $options.components.Edit,
                    $options.components.Remove
                ]`
                ":items"="items" )
</template>

<script lang="coffee">
    module.exports =
        name: 'List'
        components:
            UserDesktopList: require '../../components/user/desktop/list.vue'
            UserMobileList: require '../../components/user/mobile/list.vue'
            Remove: require '../../components/tools/Remove.vue'
            Edit: require '../../components/tools/Edit.vue'
        data: ->
            items: []
        methods:
            attemptRemove: (user) ->
                bus.$emit 'show_message',
                    title: @$root.translate('user_list.remove_user_title') + " \""+user.name+"\""
                    message: @$root.translate('user_list.remove_user_message')
                    type: 'confirm'
                    cb: => @removeUser user
            removeUser: (user) ->
                @$http.delete('api/users/'+user.id).then(
                    (response) =>
                        bus.$emit 'success',
                            title: @$root.translate('user_list.success_message')
                            details: @$root.translate('user_list.user_removed')
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
            bus.$on 'users_item_add', => @$router.push path: '/ui/users/create'
            bus.$on 'users_item_remove', (item) => @attemptRemove item
            bus.$on 'users_item_edit', (item) => @$router.push path: '/ui/users/'+item.id
        beforeDestroy: ->
            bus.$off 'users_item_add'
            bus.$off 'users_item_edit'
            bus.$off 'users_item_remove'
</script>
