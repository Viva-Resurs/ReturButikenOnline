<template lang="pug">
    div
        div.ui.attached( v-if="$root.loading" )
            loading
        user-form(
            v-if="!$root.loading && user!=null"
            ":draft"="user"
            ":roles"="roles"
            ":sections"="sections" )
</template>

<script lang="coffee">
    module.exports =
        name: 'Profile'
        components:
            UserForm: require '../../components/UserForm.vue'
        data: ->
            user: null
            roles: false
            sections: false
        methods:
            getMyUser: (user) ->
                @$root.loading = true
                @$http.get('api/users/'+user.id).then(
                    (response) =>
                        @user = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )

            getRoleList: ->
                @$http.get('api/roles').then(
                    (response) =>
                        @roles = response.data
                    (response) =>
                        bus.$emit 'error', response
                        @roles = []
                )

            getSectionList: ->
                @$http.get('api/sections').then(
                    (response) =>
                        @sections = response.data
                    (response) =>
                        bus.$emit 'error', response
                        @sections = []
                )

            updateUser: (user) ->
                @$http.put('api/users/'+user.id,user).then(
                    (response) =>
                        @$router.push path: '/users'
                    (response) => bus.$emit 'error', response.data
                )
        mounted: ->
            if @$root.user
                @getMyUser @$root.user
            else
                bus.$on 'user_changed', @getMyUser
            # Listen for changes
            bus.$on 'user_form_update', (payload) => @updateUser payload
            # Get roles
            @getRoleList()
            # Get sections
            @getSectionList()
        beforeDestroy: ->
            bus.$off 'user_form_update'
            bus.$off 'user_changed', @getMyUser
</script>
