<script>
    export default {
        name: 'Auth',
        methods: {
            setUser(payload) {
                sessionStorage.user = payload.user;
                sessionStorage.token = payload.token;
                bus.$emit('user_changed', payload.user );
            },
            clearUser() {
                sessionStorage.user = false;
                sessionStorage.token = false;
                bus.$emit('user_changed', false );
            },
            getUser(mode) {
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
                this.$http.post('logout').then(
                    (response) => {

                        this.clearUser();

                        // Clear login-status check
                        if (this.loginCheck)
                            clearInterval(this.loginCheck);
                        this.loginCheck = false;

                        // Go to home
                        this.$router.push({ path: '/' });

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
