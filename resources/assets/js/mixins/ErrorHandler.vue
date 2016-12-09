<script>
    export default {

        name: 'ErrorHandler',

        methods: {

            handleError(message) {

                this.showError({
                    title: message.error,
                    message: message.details
                });

                if (message.error=='TokenMismatch')
                    this.getToken();
                else if (message.error=='Unauthorized')
                    bus.$emit('user_changed', false );
                    this.$router.push({ path: '/auth/login' });

            }

        },

        mounted: function() {

            // Listen for errors
            bus.$on('error', this.handleError );

        }

    };
</script>
