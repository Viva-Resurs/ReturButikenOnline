<template lang="pug">
    div.ui.modal.middle.aligned.event-modal(":class"="(type == 'image' ) ? 'basic fullscreen' : 'small'" ":id"="(type == 'image') ? 'image_modal' : ''")
        div.header(v-show="type != 'image'") {{ title }}
        div.top.attached#semantic_dialog_topattached(v-show="message" ":class"="[selected_action.class]")             
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
    
                
        div.image.content.attached#semantic_dialog_image_content(v-show="type == 'image'" v-image="{ active_image: active_image, position: position, navigated: navigated }")                                                           
            
        div.ui.grid.inverted.equal.width.bottom.attached(v-show="type != 'image'" ":class"="'segment'")        
            div.column.center.aligned.mobile.only#semantic_dialog_mobile_actions 
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
            position: ""
            navigated: 0
            images: []
        computed:
            ###*
            #   Get window height.
            ###
            windowHeight: ->
                return window.innerHeight;
        
        methods:
            ###*
            #   Get language setting.
            ###
            getLanguage: () ->
                return @$root.settings.lang

            ###*
            #   Validates the calendar and displays buttons accordingly.
            ###
            validateCalendar: ->
                console.log "validating calendar"
                setTimeout =>
                    range = [
                        $('#interval_start').calendar('get date'),
                        $('#interval_end').calendar('get date')
                    ]
                    console.log range[0]
                    console.log range[1]
                    # Just check if a date is selected
                    if range[0] && range[1]
                        @actions.calendar.buttons[1].class = 'ui approve primary button'
                    else
                        @actions.calendar.buttons[1].class = 'ui approve disabled button'                                        
                , 100             
                
            ###*
            #   Shows next image (if exist).
            #   @param {evt} event (not used)
            ###
            showNextImage: (evt) ->                                
                if !(Number(@active_index+1) > Number(@images.length-1))                                   
                    @active_index = Number(@active_index) + 1
                    @active_image = @images[@active_index]
                    @setImagePosition()
                    @navigated = 2
                
            ###*
            #   Shows previous image (if exist).
            #   @param {evt} event (not used)
            ###
            showPreviousImage: (evt) ->              
                if !(Number(@active_index-1) < Number(0))          
                    @active_index = @active_index - 1
                    @active_image = @images[@active_index]                                                                                                    
                    @setImagePosition()
                    @navigated = 1
            
            ###*
            #   Updates position in order to check if the user have navigated 
            #   all to the left, right, in between or if there is a single image available.
            ###
            setImagePosition: () ->
                left = 0
                middle = 1
                right = 2
                single_image = 3
               
                if @images.length == 1
                    @position = single_image

                else if @active_index == @images.length-1                    
                    @position = right                

                else if @active_index == 0
                    @position = left
                
                else 
                    @position = middle
         
                
            ###*
            #   Shows a dialog according to a message type (image, calendar, error, ..).
            #   @param {message} message to handle
            ###
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
                            duration: 0,
                            onApprove: ->                       
                                return message.cb( new moment( $('#interval_start').calendar('get date') ), new moment( $('#interval_end').calendar('get date') ) )
                        });

                    when "image"
                        @selected_action = @actions['image']
                        @images = message.images                                
                        @active_index = message.index
                        @setImagePosition()  
                        @active_image = @images[@active_index]
                        @navigated = 0
                        $('.modal').modal({   
                            closable: false,                                           
                            observeChanges: true,                         
                            transition: 'fade',
                            duration: 450                                                           
                        })
                        $('.modals.dimmer .ui.scrolling.modal').css('margin', '0px auto !important')
                        $('.scrolling.dimmable .ui.scrolling.modal').css('margin', '1rem auto !important')
                       

                    else
                        @selected_action = @actions['default']
                
                Vue.nextTick ->                 
                    $('.modal')
                        .modal('show')
                    
        mounted: ->
            bus.$on('show_message', (message) => @handleMessage(message) );   
            bus.$on('left_button_clicked', () => @showPreviousImage() );    
            bus.$on('right_button_clicked', () => @showNextImage() );    

        beforeDestroy: ->
            bus.$off 'show_message'  
            bus.$off 'left_button_clicked'
            bus.$off 'right_button_clicked'            
    }
</script>
<style>
    #image_modal {
        margin: 1rem auto !important;
        top: 5% !important;
        background-color: black;
    }
    
    #semantic_dialog_topattached {
        margin: 5px;
    }
    
    #semantic_dialog_image_content {
        background-color: black;
    }

    #semantic_dialog_mobile_actions {
        padding-bottom: 0px;
    }
</style>

