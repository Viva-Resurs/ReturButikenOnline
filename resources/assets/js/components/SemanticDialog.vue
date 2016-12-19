<template lang="pug">
    div.ui.modal.event-modal
        div.content
            div.ui.grid.center.aligned
                div.row
                    i(":class"="[icon]")
                div.row
                    h2 {{ title }}
                div.row
                    p {{ message }}

        div.actions
            div.ui.deny.black.button Close
</template>

<script lang="coffee">
    module.exports = {
        name: 'SemanticDialog'
        data: ->
            icon: 'i'
            title: 'Empty'
            message: 'Empty'

        methods:
            # TODO: Confirm method needed
            confirm: (message,cb) ->
                # do something with message
                # yes = run cb(), no = just close
            handleMessage: (message) ->
                switch message.type
                    when "error" then @icon = 'massive circle warning icon'
                    else
                        @icon = 'massive info cirle icon'

                @title = message.title
                @message = message.message
                $('.modal').modal('show');

        mounted: ->
            bus.$on('show_message', (message) => @handleMessage(message) );
    }
</script>
