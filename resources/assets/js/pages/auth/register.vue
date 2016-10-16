<template>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" v-on:submit.prevent="attemptRegister" role="form">
                        <input id="_token" type="hidden" name="_token" v-model="register._token">
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" v-model="register.name" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" v-model="register.email" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" v-model="register.password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" v-model="register.password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" @keydown.enter.prevent="attemptRegister">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                (response) => {
                    console.log(response);
                    bus.$emit('register_ok');
                },
                (response) => bus.$emit('error', response)
            );
            }
        }
    }
</script>
