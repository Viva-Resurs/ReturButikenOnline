<script>
    export default {

        name: 'Auth',

        data: function() {
            return {
                user: false
            };
        },

        methods: {

            setUser(user) {

                this.user = user;

            },

            clearUser() {

                this.user = false;

            },

            getUser(mode) {

                console.log('Auth: getUser');

                this.$http.get('user').then(
                    (response) => {

                        this.setUser(response.data);

                        // Set timer to check login-status
                        if (this.loginCheck)
                            clearInterval(this.loginCheck);
                        this.loginCheck = false;
                        this.loginCheck = setInterval( this.getUser, 1000*30 );

                    },
                    (response) => bus.$emit('error',response)
                );

            },

            exitUser() {

                console.log('Auth: exitUser');

                this.$http.post('logout').then(
                    (response) => {

                        this.clearUser();

                        // Clear login-status check
                        if (this.loginCheck)
                            clearInterval(this.loginCheck);
                        this.loginCheck = false;

                        // Go to home
                        this.$router.push({ path: '/' });

                        // Reload page to generate a new Laravel.csrfToken
                        location.reload();

                    },
                    (response) => bus.$emit('error',response)
                );

            }

        },

        mounted: function() {

            this.getUser();

            bus.$on('login_ok', (response) => {
                this.getUser();
                this.$router.push({ path: '/' });
            });

            bus.$on('register_ok', (response) => {
                this.getUser();
                this.$router.push({ path: '/' });
            });

        }

    };
</script>
