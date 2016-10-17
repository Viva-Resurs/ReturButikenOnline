<script>
    export default {

        name: 'ErrorHandler',

        methods: {

            handleError(message) {

                console.error(message);
                
                if (!this.$root.user){
                    console.error('Not logged in!');
                    this.$root.exitUser();
                }

            }

        },

        mounted: function() {

            // Listen for errors
            bus.$on('error', this.handleError );

        }

    }
</script>