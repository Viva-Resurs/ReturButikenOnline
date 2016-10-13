<script>
    export default {

        name: 'ErrorHandler',

        methods: {

            handleError(error) {

                console.error(error);
                
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