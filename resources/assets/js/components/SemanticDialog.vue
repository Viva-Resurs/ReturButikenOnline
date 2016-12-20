<template lang="pug">
    div.ui.basic.small.modal.event-modal
        div.content
            div(":class"="[selected_action.class]")
                i(":class"="[selected_action.icon]")
                div.class.content
                    div.header {{ title }}
                    p {{ message }}
            div.ui.grid.component.padded( style="position: relative; top: -11px;" )
                div.center.aligned.column.actions( style="background: rgba(0,0,0,0.1);")
                    div(v-for="button in selected_action.buttons"
                    ":class"="[button.class]") {{button.label}}
</template>

<script lang="coffee">
    module.exports = {
        name: 'SemanticDialog'
        data: ->
            selected_action: false

            actions:
                default:
                    class: 'ui info icon message'
                    icon: 'huge center aligned circular info icon'
                    buttons: [
                        {
                            class: 'ui deny inverted blue button',
                            label: 'Close'
                        },
                    ]

                error:
                    class: 'ui warning icon message'
                    icon: 'huge center aligned circular red warning icon'
                    buttons: [
                        {
                            class: 'ui deny inverted red button',
                            label: 'Close'
                        },
                    ]

                confirm:
                    class: 'ui positive icon message'
                    icon: 'huge center aligned circular blue question icon'
                    buttons: [
                        {
                            class: 'ui deny inverted red button',
                            label: 'Abort'
                        },
                        {
                            class: 'ui approve inverted green button',
                            label: 'Confirm'
                        },
                    ]
            title: 'Empty'
            message: 'Empty'

        methods:
            handleMessage: (message) ->
                @title = message.title
                @message = message.message
                console.log message

                switch message.type
                    when "error"
                        @selected_action = @actions['error']
                    when "confirm"
                        @selected_action = @actions['confirm']
                        $('.modal').modal({
                            closable: false
                            onApprove: ->
                                return message.cb();

                        })
                    else
                        @selected_action = @actions['default']

                $('.modal').modal('show');

        mounted: ->
            bus.$on('show_message', (message) => @handleMessage(message) );
    }
</script>
