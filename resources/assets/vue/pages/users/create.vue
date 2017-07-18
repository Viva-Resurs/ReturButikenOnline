<template lang="pug">
    user-form(
        ":draft"="user"
        ":roles"="roles"
        ":sections"="sections" )
</template>

<script lang="coffee">
    module.exports =
        name: 'Create'
        components:
            UserForm: require '../../components/user/form.vue'
        data: ->
            user:
                name: ''
                password: false
                email: ''
                fullname: ''
                phone: ''
                roles: []
                sections: []
                images: []
            roles: false
            sections: false
        methods:            
            ###*
            #   Gets a list of roles from backend.
            ###
            getRoleList: ->
                @$http.get('api/roles').then(
                    (response) =>
                        @roles = response.data
                    (response) =>
                        bus.$emit 'error', response
                        @roles = []
                )
            
            ###*
            #   Gets a list of sections from backend.
            ###
            getSectionList: ->
                @$http.get('api/sections').then(
                    (response) =>
                        @sections = response.data
                    (response) =>
                        bus.$emit 'error', response
                        @sections = []
                )
            
            ###*
            #   Sends a user create request to backend. 
            #   @param {user} user to create
            ###
            createUser: (user) ->
                @$http.post('api/users', user).then(
                    (response) =>
                        bus.$emit 'success',
                            title: @$root.translate('user_list.success_message')
                            details: @$root.translate('user_list.user_created')
                        @$router.push path: '/ui/users'
                    (response) => bus.$emit 'error', response.data
                )
        created: ->
            # Get roles
            @getRoleList()
            # Get sections
            @getSectionList()
            # When saving the new User
            bus.$on 'user_form_update', (payload) => @createUser payload
        beforeDestroy: ->
            bus.$off 'user_form_update'
</script>
