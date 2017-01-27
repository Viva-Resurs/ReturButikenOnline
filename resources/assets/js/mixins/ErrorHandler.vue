<script lang="coffee">
    module.exports =
        name: 'ErrorHandler'
        methods:
            handleError: (message) ->
                bus.$emit 'show_message',
                    type: 'error'
                    title: message.error
                    message: message.details

                # Refresh token if needed
                if message.error == 'TokenMismatch'
                    @getToken()

                # Unauthorized, logout / exit
                else if message.error == 'Unauthorized'
                    @exitUser 'unauthorized'

        mounted: ->
            # Listen for errors
            bus.$on 'error', @handleError
</script>
