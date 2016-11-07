<template>
<div class="ui container segment">
    <div class="ui dividing header">
        Registrering
    </div>
    <form class="ui form" v-on:submit.prevent="attemptRegister" role="form">
        <input id="_token" type="hidden" name="_token" v-model="register._token">
        <div class="field">
            <label for="name" class="col-md-4 control-label">Name</label>
            <input id="name" type="text" class="form-control" name="name" v-model="register.name" required autofocus>
        </div>

        <div class="field">
            <label for="email" class="col-md-4 control-label">E-Mail Address</label>
            <input id="email" type="email" class="form-control" name="email" v-model="register.email" required>
        </div>

        <div class="field">
            <label for="password" class="col-md-4 control-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" v-model="register.password" required>
        </div>

        <div class="field">
            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" v-model="register.password_confirmation" required>
        </div>

            <button type="submit" class="ui right floated button primary" @keydown.enter.prevent="attemptRegister">
                Register
            </button>

            <br /><br />

    </form>

</div>
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
