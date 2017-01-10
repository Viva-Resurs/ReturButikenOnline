<template lang="pug">
    div.ui.basic.small.modal.event-modal
        div.content
            div(":class"="[selected_action.class]").attached
                i(":class"="[selected_action.icon]")
                div.class.content
                    div.header
                        h3 {{ title }}
                        p {{ message }}
                    div.ui.form( v-show="type=='calendar'" )
                        div.two.stackable.fields
                            div.field
                                h4.ui.sub.header Start date
                                div.ui.calendar#interval_start( v-calendar="" )
                                    div.ui.input.left.icon.bottom.attached
                                        i.calendar.icon
                                        input( type="text" placeholder="Start" )
                            div.field
                                h4.ui.sub.header End date
                                div.ui.calendar#interval_end( v-calendar="" )
                                    div.ui.input.left.icon
                                        i.calendar.icon
                                        input( type="text" placeholder="End" )
            div.ui.grid.segment.bottom.attached
                div.center.aligned.column.actions
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
                    class: 'ui icon message'
                    icon: 'huge center aligned circular info icon'
                    buttons: [
                        {
                            class: 'ui deny primary button',
                            label: 'Close'
                        },
                    ]

                error:
                    class: 'ui negative icon message'
                    icon: 'huge center aligned circular red warning icon'
                    buttons: [
                        {
                            class: 'ui deny red button',
                            label: 'Close'
                        },
                    ]

                confirm:
                    class: 'ui warning icon message raised'
                    icon: 'huge center aligned circular warning question icon'
                    buttons: [
                        {
                            class: 'ui deny button',
                            label: 'Abort'
                        },
                        {
                            class: 'ui approve primary button',
                            label: 'Confirm'
                        },
                    ]

                calendar:
                    class: 'ui message'
                    icon: ''
                    buttons: [
                        {
                            class: 'ui deny button',
                            label: 'Abort'
                        },
                        {
                            class: 'ui approve primary button',
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
                            observeChanges: true
                            closable: false
                            onApprove: ->
                                return message.cb();

                        })
                    when "calendar"
                        @selected_action = @actions['calendar']

                        $('#interval_start').calendar('set date',message.start)
                        $('#interval_start').calendar('set mode','day')
                        $('#interval_end').calendar('set date',message.end)
                        $('#interval_end').calendar('set mode','day')

                        $('.modal').modal({
                            observeChanges: true
                            closable: true
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
