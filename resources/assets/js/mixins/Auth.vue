<script>
    export default {
        name: 'Auth',
        methods: {
            data(){
                return {
                    user: false,
                    loginCheck: false
                }
            },
            isAdmin(level) {
                if (this.user && this.user.roles)
                    for (var i=0 ; i<this.user.roles.length ; i++){
                        if (this.user.roles[i].name == 'admin')
                            return true;
                        if (this.user.roles[i].name == 'sectionadmin' && level==2)
                            return true;
                    }
                return false;
            },
            setUser(payload) {
                sessionStorage.token = payload.token;
                this.user = payload.user;
                bus.$emit('user_changed', payload.user );
            },
            clearUser() {
                sessionStorage.token = false;
                this.user = false;
                bus.$emit('user_changed', false );
            },
            getUser() {
                this.$http.get('/api/user').then(
                    (response) => {
                        // Update username & token
                        this.setUser(response.data);

                        // Set timer to keep track of login-status
                        if (!this.loginCheck)
                            this.loginCheck = setInterval( this.getUser, 1000*30 );
                    },
                    (response) => {
                        // Go through matched routes and check requiresAuth
                        for (var i=0 ; i<this.$route.matched.length ; i++)
                            if (this.$route.matched[i].meta.requiresAuth){
                                // Current route needs authorization, kick user
                                this.exitUser('unauthorized');
                                // Notify
                                bus.$emit('show_message',{
                                    type: 'info',
                                    title: 'Utloggad',
                                    message: 'Du har blivigt utloggad'
                                });
                            }
                    }
                );
            },
            exitUser(mode) {
                // Post a logout to server
                this.$http.post('logout').then(
                    () => { return true; },
                    () => { return false; }
                );

                // Remove associated data about user
                this.clearUser();

                // Clear timer for login-status
                if (this.loginCheck)
                    clearInterval(this.loginCheck);
                this.loginCheck = false;

                // Go to login-page if unauthorized, else home
                if (mode=='unauthorized')
                    this.$router.push({ path: '/auth/login' })
                else
                    this.$router.push({ path: '/' });
            }
        },
        mounted: function() {
            this.getUser();
            bus.$on('login_ok', (response) => {
                this.getUser();
                this.$router.push({ path: '/' });
            });
        }
    };
</script>
