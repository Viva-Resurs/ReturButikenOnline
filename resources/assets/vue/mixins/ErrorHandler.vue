<script lang="coffee">
    module.exports =
        name: 'ErrorHandler'
        mounted: ->
            # Listen for errors
            bus.$on 'error', (message) =>
                if !message
                    return
                
                translated_message = message.details

                switch message.details
                    when "wrong_publish_interval"
                        translated_message = @$root.translate('errors.wrong_publish_interval')
                    when "wrong_bidding_interval"
                        translated_message = @$root.translate('errors.wrong_bidding_interval')

                bus.$emit 'show_message',
                    type: 'error'
                    title: message.error
                    message: translated_message

                # Refresh token if needed
                if message.error == 'TokenMismatch'
                    @getToken()

                # Unauthorized, logout / exit
                else if message.error == 'Unauthorized'
                    @exitUser 'unauthorized'
        beforeDestroy: ->
            bus.$off 'error'
</script>
