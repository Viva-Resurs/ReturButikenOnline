<script>
    export default {
        name: 'Auth',
        methods: {
            data(){
                return {
                    user: false,
                }
            },
            setUser(payload) {
                sessionStorage.token = payload.token;
                this.user = payload.user;
                bus.$emit('user_changed', payload.user );
            },
            isAdmin(level) {
                for (var i=0 ; i<this.user.roles.length ; i++){
                    if (this.user.roles[i].name == 'admin')
                        return true;
                    if (this.user.roles[i].name == 'sectionadmin' && level==2)
                        return true;
                }

                return false;
            },
            clearUser() {
                sessionStorage.token = false;
                this.user = false;
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
                    (response) => bus.$emit('error',response.data)
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
