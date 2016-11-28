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

            }

        },

        mounted: function() {

            // Listen for errors
            bus.$on('error', this.handleError );

        }

    };
</script>
