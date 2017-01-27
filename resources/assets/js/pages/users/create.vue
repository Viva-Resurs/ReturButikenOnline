<template lang="pug">
    user-form
</template>

<script lang="coffee">
    UserForm = require '../../components/UserForm.vue';

    module.exports = {

        name: 'Create'

        components: { UserForm }

        methods:
            createUser: (user) ->
                @$http.post('api/users',user).then(
                    (response) =>
                        bus.$emit('success',response)
                        @$router.push({ path: '/users' });

                    (response) => bus.$emit('error',response.data)
                );

        created: ->
            bus.$on('user_form_update', (payload) => @createUser(payload) )


        beforeDestroy: ->
            bus.$off('user_form_update');


    }
</script>
