<template lang="pug">
    div
        loading( v-if="$root.loading" )
        user-form(
            v-if="!$root.loading"
            ":draft"="user"
            ":roles"="roles"
            ":sections"="sections" )
</template>

<script lang="coffee">
    module.exports =
        name: 'Update'
        components:
            UserForm: require '../../components/UserForm.vue'
        data: ->
            user: null
            roles: false
            sections: false
        methods:
            getUser: (id) ->
                @$root.loading = true
                @$http.get('api/users/'+id).then(
                    (response) =>
                        @user = response.data
                        @getRoleList()
                        @getSectionList()
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )
            getRoleList: ->
                @$http.get('api/roles').then(
                    (response) =>
                        @roles = response.data ? []
                    (response) =>
                        bus.$emit 'error', response
                )
            getSectionList: ->
                @$http.get('api/sections').then(
                    (response) =>
                        @sections = response.data ? []
                    (response) =>
                        bus.$emit 'error', response
                )
            updateUser: (user) ->
                @$http.put('api/users/'+user.id,user).then(
                    (response) =>
                        @$router.push path: '/users#'+user.id
                    (response) =>
                        bus.$emit 'error', response.data
                )
        created: ->
            # Get User to edit
            @getUser @$route.params.id
            # When saving changes to User
            bus.$on 'user_form_update', (payload) => @updateUser payload
        beforeDestroy: ->
            bus.$off 'user_form_update'
</script>
