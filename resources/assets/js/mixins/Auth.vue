<script lang="coffee">
    module.exports =
        name: 'Auth'
        methods:
            data: ->
                user: false
                loginCheck: false
            isAdmin: (level) ->
                if @user and @user.roles
                    for role in @user.roles
                        if role.name == 'admin'
                            return true
                        if role.name == 'sectionadmin' and level==2
                            return true
                return false

            setUser: (payload) ->
                sessionStorage.token = payload.token
                @user = payload.user
                bus.$emit 'user_changed', payload.user

            clearUser: ->
                sessionStorage.token = false
                @user = false
                bus.$emit 'user_changed', false

            getUser: ->
                @$http.get('api/user').then(
                    (response) =>
                        # Update username & token
                        @setUser response.data

                        # Set timer to keep track of login-status
                        if !@loginCheck
                            @loginCheck = setInterval @getUser, 1000*30
                    (response) =>
                        # Go through matched routes and check requiresAuth
                        for route in @$route.matched
                            if route.meta.requiresAuth
                                # Current route needs authorization, kick user
                                @exitUser 'unauthorized'
                                # Notify
                                bus.$emit 'show_message',
                                    type: 'info'
                                    title: 'Utloggad'
                                    message: 'Du har blivigt utloggad'
                )

            exitUser: (mode) ->
                # Post a logout to server
                @$http.post('logout').then(
                    => return true
                    => return false
                )

                # Remove associated data about user
                @clearUser()

                # Clear timer for login-status
                if @loginCheck
                    clearInterval @loginCheck
                @loginCheck = false

                # Go to login-page if unauthorized, else home
                if mode == 'unauthorized'
                    @$router.push path: '/auth/login'
                else
                    @$router.push path: '/'

        mounted: ->
            @getUser()
            bus.$on 'login_ok', (response) =>
                @getUser()
                @$router.push path: '/'
        beforeDestroy: ->
            bus.$off 'login_ok'
</script>
