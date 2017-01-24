<template lang="pug">
    div.ui.container.segment#articleForm

        div.ui.dividing.header Inloggning

        form.ui.form( v-on:submit.prevent="attemptLogin" role="form" name="myform" )

            div.field
                label E-post:
                input#name(
                    type="email"
                    v-model="login.email"
                    placeholder="name@domain.com" )

            div.field
                label Lösenord:
                input#password(
                    type="password"
                    v-model="login.password" )

            button.ui.right.floated.button.primary( type="submit" @keydown.enter.prevent="attemptLogin" ) Login
            br
            br
</template>

<script lang="coffee">
    module.exports = {
        name: 'Login'
        data: () ->
            login:
                name: '',
                email: '',
                password: ''
            myform: []
        methods:
            attemptLogin: (tries) ->
                validate = (r) ->
                    console.log 'checking r.data'
                    console.log r
                    if typeof r.data != 'object'
                        try
                            r.data = JSON.parse r.data
                        catch e
                            console.error e
                @$http.post('/login',@login).then(
                    (response) =>
                        bus.$emit('login_ok')
                    (response) =>
                        validate response
                        # If login fails on token, try one more time
                        if (tries!=2 && response.data.error == 'TokenMismatch')
                            @$http.get('/api/token').then(
                                (response) =>
                                    validate response
                                    # New token ready, try to login again
                                    sessionStorage.token = response.data.token;
                                    @attemptLogin(2);
                                (response) =>
                                    validate response
                                    bus.$emit('error',response)
                            );
                        else
                            bus.$emit('error',response.data)
                );
    }
</script>
