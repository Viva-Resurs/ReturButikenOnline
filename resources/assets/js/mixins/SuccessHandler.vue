<script lang="coffee">
    module.exports = {
        name: 'SuccessHandler'
        methods:
            handleSuccess: (message) ->
                bus.$emit('show_message',{
                    type: 'success',
                    title: message.title,
                    message: message.details
                });
        mounted: () ->
            # Listen for success
            bus.$on('success', this.handleSuccess );
    }
</script>
