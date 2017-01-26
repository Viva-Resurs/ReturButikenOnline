<template lang="pug">
    user-form( v-if="user!=null" ":original"="user" )
</template>

<script lang="coffee">
    UserForm = require '../../components/UserForm.vue'
    module.exports =
        name: 'Profile'
        components: { UserForm }
        data: ->
            user: null
        methods:
            getUser: (user) ->
                @$root.loading = true
                @$http.get('api/users/'+user.id).then(
                    (response) =>
                        @user = response.data
                        @$root.loading = false
                    (response) =>
                        bus.$emit 'error', response.data
                        @$root.loading = false
                )
            updateUser: (user) ->
                @$http.put('api/users/'+user.id,user).then(
                    (response) =>
                        @$router.push({ path: 'users' })
                    (response) =>
                        bus.$emit 'error', response.data
                )
        mounted: ->
            if @$root.user
                @getUser @$root.user
            else
                bus.$on 'user_changed', @getUser
            bus.$on 'user_form_update', (payload) => @updateUser payload

        beforeDestroy: ->
            bus.$off 'user_form_update'
            bus.$off 'user_changed', @getUser
</script>
