<script>
	export default {

		name: 'Auth',

        data: function() {
            return {
                user: {
                    id: 0,
                    name: '',
                    email: '',
                    roles: []
                },

                authenticated: false
            }
        },

        methods: {

            setUser(user) {

                this.user = user;
                this.authenticated = true;

            },

            clearUser() {

                this.user = false;
        		this.authenticated = false;

            },

            getUser() {

            	console.log('Auth: getUser')

                this.$http.get('user').then(
                    (response) => {
                    	console.log('welcome');
                        this.setUser(response.data);
                    },
                    (response) => {
                        console.error(response.error);
                    }
                );

            },

            exitUser() {

            	console.log('Auth: exitUser')

                this.$http.post('logout').then(
                    (response) => {
                    	console.log('bye');
                        this.clearUser();
                    },
                    (response) => {
                        console.error(response.error);
                    }
                );

            }

        },

        mounted: function() {

            this.getUser();

        }

	}
</script>