<script lang="coffee">
    module.exports = {
        name: 'ErrorHandler'
        methods: {
            handleError: (message) ->
                bus.$emit('show_message',{
                    type: 'error',
                    title: message.error,
                    message: message.details
                });

                # Refresh token if needed
                if (message.error=='TokenMismatch')
                    this.getToken()

                # Unauthorized, logout / exit
                else if (message.error=='Unauthorized')
                    this.exitUser('unauthorized');
        }
        mounted: () ->
            # Listen for errors
            bus.$on('error', this.handleError )
    }
</script>
