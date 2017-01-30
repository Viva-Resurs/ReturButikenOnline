<script lang="coffee">
    module.exports =
        name: 'SuccessHandler'
        mounted: ->
            # Listen for success
            bus.$on 'success', (message) ->
                bus.$emit 'show_message',
                    type: 'success'
                    title: message.title
                    message: message.details
        beforeDestroy: ->
            bus.$off 'success'
</script>
