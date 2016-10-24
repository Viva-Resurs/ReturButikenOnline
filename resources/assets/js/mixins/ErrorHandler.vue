<script>
    export default {

        name: 'ErrorHandler',

        methods: {

            handleError(message) {

                this.showError({text:message});

                if (!this.$root.user)
                    console.error('Not logged in!');

            }

        },

        mounted: function() {

            // Listen for errors
            bus.$on('error', this.handleError );

        }

    };
</script>
