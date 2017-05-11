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
                                h4.ui.sub.header {{ translate('semantic_dialog.start_date_header') }}
                                div.ui.calendar#interval_start( v-calendar="validateCalendar" )
                                    div.ui.input.left.icon.bottom.attached
                                        i.calendar.icon
                                        input( type="text" placeholder="????-??-??" )
                            div.field
                                h4.ui.sub.header {{ translate('semantic_dialog.end_date_header') }}
                                div.ui.calendar#interval_end( v-calendar="validateCalendar" )
                                    div.ui.input.left.icon
                                        i.calendar.icon
                                        input( type="text" placeholder="????-??-??" )
                    div.ui.fluid.basic.segment( v-show="type=='image'" )
                        div.image.content(v-if="image && image.path")
                            img.ui.fluid.rounded.image( ":src"="image.path" ":id"="image.id" )

            div.ui.grid.segment.bottom.inverted.attached
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
                            label: @$root.translate('semantic_dialog.close_label')
                        },
                    ]

                error:
                    class: 'ui negative icon message'
                    icon: 'huge center aligned circular red warning icon'
                    buttons: [
                        {
                            class: 'ui deny red button',
                            label: @$root.translate('semantic_dialog.close_label')
                        },
                    ]

                confirm:
                    class: 'ui warning icon message raised'
                    icon: 'huge center aligned circular warning question icon'
                    buttons: [
                        {
                            class: 'ui deny button',
                            label: @$root.translate('semantic_dialog.abort_label')
                        },
                        {
                            class: 'ui approve primary button',
                            label: @$root.translate('semantic_dialog.confirm_label')
                        },
                    ]

                calendar:
                    class: 'ui message'
                    icon: ''
                    buttons: [
                        {
                            class: 'ui deny button',
                            label: @$root.translate('semantic_dialog.abort_label')
                        },
                        {
                            class: 'ui approve primary button',
                            label: @$root.translate('semantic_dialog.confirm_label')
                        },
                    ]

                image:
                    class: 'ui floated inverted basic segment'
                    icon: ''
                    buttons: [
                        {
                            class: 'ui approve primary button',
                            label: @$root.translate('semantic_dialog.confirm_label')
                        },
                    ]

            title: 'Empty'
            message: 'Empty'
            type: 'Empty'
            image: 'Empty'
        methods:
            validateCalendar: ->
                setTimeout =>
                    range = [
                        $('#interval_start').calendar('get date'),
                        $('#interval_end').calendar('get date')
                    ]
                    # Just check if a date is selected
                    if range[0] && range[1]
                        @actions.calendar.buttons[1].class = 'ui approve primary button'
                    else
                        @actions.calendar.buttons[1].class = 'ui approve disabled button'
                , 100
            handleMessage: (message) ->
                @title = message.title
                @message = message.message
                @type = message.type

                if process.env.NODE_ENV == "development"
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
                        @validateCalendar()

                        $('.modal').modal({
                            observeChanges: true
                            closable: true
                            onApprove: ->
                                return message.cb( new moment( $('#interval_start').calendar('get date') ), new moment( $('#interval_end').calendar('get date') ) )
                        });

                    when "image"
                        @selected_action = @actions['image']
                        @image = message.image
                        $('.modal').modal({                            
                            observeChanges: true,
                            closable: true
                        })


                    else
                        @selected_action = @actions['default']
                Vue.nextTick -> 
                           
                    $('.modal').modal({
                        onShow: =>
                            setTimeout (->
                                $('.modal').modal('refresh')
                                return
                            ), 100
                            return
                        observeChanges: true
                    }).modal('show')

        mounted: ->
            bus.$on('show_message', (message) => @handleMessage(message) );
    }
</script>
