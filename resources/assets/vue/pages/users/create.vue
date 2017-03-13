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
            UserForm: require '../../components/UserForm.vue'
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
            createUser: (user) ->
                @$http.post('api/users', user).then(
                    (response) =>
                        bus.$emit 'success',
                            title: 'Success'
                            details: 'User created'
                        @$router.push path: '/users'
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
