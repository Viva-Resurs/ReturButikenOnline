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

                if (message.error=='TokenMismatch')
                    this.getToken()

                # TODO: only do this when on a auth-route
                else if (message.error=='Unauthorized')
                    bus.$emit('user_changed', false );
                    this.$router.push({ path: '/auth/login' })
        }
        mounted: () ->
            # Listen for errors
            bus.$on('error', this.handleError )
    }
</script>
