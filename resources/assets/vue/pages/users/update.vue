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
            UserForm: require '../../components/user/form.vue'
        data: ->
            user: null
            roles: false
            sections: false
        methods:
            ###*
            #   Get a user from backend using its user id. 
            #   @param {id} id of user
            ###
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
            
            ###*
            #   Returns a list of roles from backend.
            ###
            getRoleList: ->
                @$http.get('api/roles').then(
                    (response) =>
                        @roles = response.data ? []
                    (response) =>
                        bus.$emit 'error', response
                )
            
            ###*
            #   Returns a list of sections from backend.
            ###
            getSectionList: ->
                @$http.get('api/sections').then(
                    (response) =>
                        @sections = response.data ? []
                    (response) =>
                        bus.$emit 'error', response
                )
            
          
            ###*
            #   Updates a user in the backend.
            #   @param {user} user to update
            ###
            updateUser: (user) ->
                @$http.put('api/users/'+user.id,user).then(
                    (response) =>
                        @$router.push path: '/ui/users#'+user.id
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
