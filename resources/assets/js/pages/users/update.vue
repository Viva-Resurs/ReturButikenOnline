<template lang="pug">
    user-form( v-if="user!=null" ":original"="user" )
</template>

<script lang="coffee">
    UserForm = require '../../components/UserForm.vue';

    module.exports = {

        name: 'Update'

        components: { UserForm }

        data: ->
            user: null

        methods: {

            getUser: (id) ->
                @$root.loading = true;

                @$http.get('api/users/'+id).then(
                    (response) =>
                        @user = response.data;
                        @$root.loading = false;

                    (response) =>
                        bus.$emit('error',response.data);
                        @$root.loading = false;
                );

            updateUser: (user) ->
                @$http.put('api/users/'+user.id,user).then(
                    (response) =>
                        @$router.push({ path: '/users' });

                    (response) => bus.$emit('error',response.data)
                );

        },

        created: ->
            this.getUser(this.$route.params.id);

            bus.$on('user_form_update', (payload) => this.updateUser(payload) )

        beforeDestroy: ->
            bus.$off('user_form_update');

    }
</script>
