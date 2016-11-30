<template lang="pug">
    div.ui.container.segment
        div.ui.dividing.header Registrering

        form.ui.form( v-on:submit.prevent="attemptRegister" role="form" )
            input#_token( type="hidden" name="_token" v-model="register._token" )
            div.field
                label Name
                input#name( type="text" name="name" v-model="register.name" required autofocus )

            div.field
                label E-Mail Address
                input#email( type="email" name="email" v-model="register.email" required )

            div.field
                label Password
                input#password( type="password" name="password" v-model="register.password" required )

            div.field
                label Confirm Password
                input#password-confirm( type="password" name="password_confirmation" v-model="register.password_confirmation" required )

            button.ui.right.floated.button.primary( type="submit" @keydown.enter.prevent="attemptRegister" ) Register

            br
            br
</template>
<script>
    export default {
    data: function() {
        return {
            register: {
                name: 'testare2345',
                email: 'testare2345@viva.se',
                password: 'test1234',
                password_confirmation: 'test1234',
            },
        }
    },
    methods: {
        attemptRegister() {

            this.$http.post('register', this.register).then(
                (response) => bus.$emit('register_ok', response),
                (response) => bus.$emit('error', response)
            );

        }
    }
}
</script>
