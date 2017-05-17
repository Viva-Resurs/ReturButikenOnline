<template lang="pug">
    div.ui.modal.event-modal(":class"="(type == 'image' ) ? 'basic fullscreen' : ''" ":style"="(type == 'image') ? 'background-color: black; height: windowHeight !important' : ''")
        div.header {{ title }}
        div.top.bottom.attached(v-show="message" ":class"="[selected_action.class]" style="margin-left: 10px; margin-right: 10px") 
            i(":class"="[selected_action.icon]") 
            div.header {{ message }}
        
        div.content(v-show="type =='calendar'")            
            div.ui.form
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
    
                
        div(v-show="type == 'image'" style="background-color: black")#image_container                        
            div.image.content.ui.container(v-if="images")
                img.ui.fluid.rounded.image#selected_image( ":src"="active_image.path" ":id"="active_image.id")
            i.huge.chevron.circle.left.icon(style="position: absolute; left: 5%; top: 45%" "@click"="showPreviousImage")#left_button
            i.huge.chevron.circle.right.icon(style="position: absolute; right: 5%; top: 45%;" "@click"="showNextImage")#right_button

        div.ui.grid.bottom.attached.inverted.equal.width(":class"="(type == 'image') ? 'container' : 'segment'"  ":style"="(type == 'image') ? 'background-color: black; border-color: black;' : ''")        
            div.column.center.aligned.mobile.only("style"="padding-bottom: 0px") 
                div.center.aligned.column.actions
                    div(v-for="button in selected_action.buttons"
                    ":class"="[button.class]") {{button.label}}

            div.column.center.aligned.computer.tablet.only
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
            active_image: 'Empty'
            active_index: 0
            images: []
        computed:
            windowHeight: ->
                return window.innerHeight;
        
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
            
            showNextImage: (evt) ->                
                if !(Number(@active_index+1) > Number(@images.length))
                    @active_index = @active_index + 1
                    @active_image = @images[@active_index]
                    @showOverlayButtons()
        
            showPreviousImage: (evt) ->
                if !(Number(@active_index-1) < Number(0))
                    @active_index = @active_index - 1
                    @active_image = @images[@active_index]                    
                    @showOverlayButtons()
            
            showOverlayButtons: () ->
                leftButton = $('#left_button')
                rightButton = $('#right_button')
                
                if images.length == 1
                   rightButton.hide()
                   leftButton.hide()
                   return
                
                switch @active_index
                    when 0
                        leftButton.hide()
                        rightButton.show()
                
                    when  @images.length-1                    
                        rightButton.hide()
                        leftButton.show()
                        
                    else
                        rightButton.show()
                        leftButton.show()

                
            handleMessage: (message) ->
                @title = message.title
                @message = message.message
                @type = message.type

                switch message.type
                    when "error"
                        @selected_action = @actions['error']
                    when "confirm"
                        
                        @selected_action = @actions['confirm']
                        $('.modal').modal({                            
                            observeChanges: true
                            closable: false
                            onShow: =>
                                setTimeout (->
                                    $('.modal').modal('refresh')
                                ), 100  
                            onApprove: ->     
                                console.log "onApprove"      
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
                            observeChanges: true,
                            closable: true,
                            onShow: =>
                                setTimeout (->
                                    $('.modal').modal('refresh')
                                ), 200 
                            onApprove: ->                       
                                return message.cb( new moment( $('#interval_start').calendar('get date') ), new moment( $('#interval_end').calendar('get date') ) )
                        });

                    when "image"
                        @selected_action = @actions['image']
                        @images = message.images                          
                        @active_index = message.index
                        @active_image = @images[@active_index]                        
                       
                        $('.fullscreen.modal').modal({   
                            onShow: =>
                                setTimeout (->
                                    $('.fullscreen.modal').modal('refresh')
                                ), 200                                  
                            observeChanges: true,
                            closable: true,                                            
                        })                              
                        @showOverlayButtons()

                    else
                        @selected_action = @actions['default']
                
                Vue.nextTick ->                 
                    if @type == 'image'
                        $('fullscreen.modal').modal('refresh').
                        modal('show')                  
                    else
                        $('.modal').modal('refresh')
                        .modal('show')
                    
        mounted: ->
            bus.$on('show_message', (message) => @handleMessage(message) );       
                   
    }
</script>

