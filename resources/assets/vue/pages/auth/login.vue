<template lang="pug">
    div#articleForm
        div.ui.dividing.header {{ translate('login.header') }}
        form.ui.form( v-on:submit.prevent="attemptLogin" role="form" name="myform" )
            div.field
                label {{ translate('login.email_label') }}
                input#name(
                    type="email"
                    v-model="login.email"
                    placeholder="name@domain.com" )
            div.field
                label {{ translate('login.password_label') }}
                input#password(
                    type="password"
                    v-model="login.password" )
                
            div.ui.grid
                div.column.right.aligned
                    button.ui.right.aligned.button.primary( type="submit" @keydown.enter.prevent="attemptLogin" ) {{ translate('login.login') }}
        
</template>

<script lang="coffee">
    module.exports =
        name: 'Login'
        data: ->
            login:
                name: '',
                email: '',
                password: ''
            myform: []
        methods:
            ###*
            #   Attempt to login to the backend.            
            #   @param {tries} number of attempts
            ###
            attemptLogin: (tries) ->
                @$http.post('login',@login).then(
                    (response) =>
                        bus.$emit 'login_ok'
                    (response) =>
                        # If login fails on token, try one more time
                        if tries != 2 && response.data.error == 'TokenMismatch'
                            @$http.get('api/token').then(
                                (response) =>
                                    # New token ready, try to login again
                                    sessionStorage.token = response.data.token;
                                    @attemptLogin 2
                                (response) =>
                                    bus.$emit 'error', response
                            )
                        else
                            bus.$emit 'error', response.data
                )
</script>
