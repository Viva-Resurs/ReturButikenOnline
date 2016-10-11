<template>
    <div class="panel panel-default" id="articleForm">

        <div class="panel-heading">
            Inloggning
        </div>

        <form class="form-vertical" v-on:submit.prevent="login" role="form" name="myform">

            <div class="panel-body">

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="control-label" for="name">E-post:</label>
                            <input class="form-control"
                                id="name"
                                type="email"
                                v-model="user.email"
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
                                v-model="user.password"
                            >
                        </div>
                    </div>
                </div>

            </div>

            <div class="panel-footer text-right">
                <button type="submit" class="btn btn-primary" @keydown.enter.prevent="login">
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
            user: {
                name: '',
                email: '',
                password: ''
            },
            myform: []
        }
    },

    methods: {

        login() {

            this.$http.post('login',this.user).then(
                (response) => {
                    console.log('ok');
                    this.$root.setUser(this.user);
                    this.$router.push({ path: '/' });
                },
                (response) => {
                    console.error(response.error);
                }
            );

        }

    }

}
</script>
