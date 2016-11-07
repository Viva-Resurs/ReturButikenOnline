<template>
    <div class="ui container segment" id="articleForm">

        <div class="ui dividing header">
            Inloggning
        </div>

        <form class="ui form" v-on:submit.prevent="attemptLogin" role="form" name="myform">

            <div class="field">
                <label class="control-label" for="name">E-post:</label>
                <input class="form-control"
                    id="name"
                    type="email"
                    v-model="login.email"
                    placeholder="name@domain.com"
                >
            </div>

            <div class="field">
                <label class="control-label" for="password">Lösenord:</label>
                <input class="form-control"
                    id="password"
                    type="password"
                    v-model="login.password"
                >
            </div>

            <button type="submit" class="ui right floated button primary" @keydown.enter.prevent="attemptLogin">
                Login
            </button>
            <br /> <br />

        </form>

</template>

<style>
</style>

<script>
export default {

    name: 'Login',

    data: function() {
        return {
            login: {
                name: '',
                email: '',
                password: ''
            },
            myform: []
        }
    },

    methods: {

        attemptLogin() {

            this.$http.post('login',this.login).then(
                (response) => bus.$emit('login_ok'),
                (response) => bus.$emit('error','Could not login')
            );

        }

    }

}
</script>
