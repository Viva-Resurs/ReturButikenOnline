<template lang="pug">
    div.ui.basic.small.modal.event-modal
        div.content
            div( v-if = "type == 'calendar'" )
                div.ui.form
                    div.two.stackable.fields
                        div.field
                            h4.ui.sub.header Start date
                            div.ui.small.calendar#interval_start
                                div.ui.input.left.icon
                                    i.calendar.icon
                                    input( type="text" placeholder="Start" )

                        div.field
                            h4.ui.sub.header End date
                            div.ui.calendar#interval_end
                                div.ui.input.left.icon
                                    i.calendar.icon
                                    input( type="text" placeholder="End" )

            div( v-else )
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

                calendar:
                    class: ''
                    icon: ''
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
            type: 'Empty'

        methods:
            handleMessage: (message) ->
                @title = message.title
                @message = message.message
                @type = message.type
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
                    when "calendar"
                        if $('#interval_start').data("moduleCalendar") == undefined
                            $('#interval_start').calendar({
                                debug: true
                                ampm: false
                                inline: true
                                endCalendar: $('#interval_end')
                            })

                        if $('#interval_end').data("moduleCalendar") == undefined
                            $('#interval_end').calendar
                                ampm: false
                                inline: true
                                startCalendar: $('#interval_start')

                        $('#interval_start').calendar('set date',message.start)
                        $('#interval_end').calendar('set date',message.end)

                        $('.modal').modal({
                            closable: false
                            onApprove: ->
                                return message.cb( new moment( $('#interval_start').calendar('get date') ), new moment( $('#interval_end').calendar('get date') ) )
                        })

                    else
                        @selected_action = @actions['default']

                $('.modal').modal('show');

        mounted: ->
            bus.$on('show_message', (message) => @handleMessage(message) );
    }
</script>
