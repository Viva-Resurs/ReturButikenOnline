<template>
    <div class="panel panel-default" id="articleForm">

        <div class="panel-heading">
            Inloggning
        </div>

        <form class="form-vertical" v-on:submit.prevent="attemptLogin" role="form" name="myform">

            <div class="panel-body">

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="control-label" for="name">E-post:</label>
                            <input class="form-control"
                                id="name"
                                type="email"
                                v-model="login.email"
                                placeholder="name@domain.com"
                            >
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="control-label" for="password">Lösenord:</label>
                            <input class="form-control"
                                id="password"
                                type="password"
                                v-model="login.password"
                            >
                        </div>
                    </div>
                </div>

            </div>

            <div class="panel-footer text-right">
                <button type="submit" class="btn btn-primary" @keydown.enter.prevent="attemptLogin">
                    Login
                </button>
            </div>

        </form>

    </div>
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
