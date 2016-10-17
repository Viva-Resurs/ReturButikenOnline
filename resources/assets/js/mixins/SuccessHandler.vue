<script>
    export default {

        name: 'ErrorHandler',

        methods: {

            handleSuccess(message) {

                console.log(message);

            }

        },

        mounted: function() {

            // Listen for success
            bus.$on('success', this.handleSuccess );

        }

    }
</script>