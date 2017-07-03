<template lang="pug">
    div.ui.modal.middle.aligned.event-modal#image_modal("class"="basic fullscreen" "style"="background-color: black")                
        div.image.content.attached(style="background-color: black" v-image="{ active_image: active_image, position: position, navigated: navigated }")                                                              
</template>

<script lang="coffee">
    module.exports = {
        name: 'ImagePreview'
        data: ->
            selected_action: false

            actions:
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
            windowHeight: ->
                return window.innerHeight;
        
        methods:           
            showNextImage: (evt) ->                                
                if !(Number(@active_index+1) > Number(@images.length-1))                                   
                    @active_index = Number(@active_index) + 1
                    @active_image = @images[@active_index]
                    @setImagePosition()
                    @navigated = 2
                
            showPreviousImage: (evt) ->              
                if !(Number(@active_index-1) < Number(0))          
                    @active_index = @active_index - 1
                    @active_image = @images[@active_index]                                                                                                    
                    @setImagePosition()
                    @navigated = 1

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

            showImage: (message) ->
                @title = message.title
                @message = message.message
                @type = message.type

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
        
                Vue.nextTick ->                 
                        $('.modal')
                            .modal('show')
                    
        mounted: ->
            bus.$on('show_message', (message) => @showImage(message) );   
            bus.$on('left_button_clicked', () => @showPreviousImage() );    
            bus.$on('right_button_clicked', () => @showNextImage() );    

        beforeDestroy: ->
            bus.$off 'left_button_clicked'
            bus.$off 'right_button_clicked'            
    }
</script>
<style>
    #image_modal {
        margin: 1rem auto !important;
        top: 5% !important;
    }
</style>

