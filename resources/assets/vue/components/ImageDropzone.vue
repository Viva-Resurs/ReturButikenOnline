<template lang="pug">
    div
        div.ui.attached.right.aligned.menu( v-if="images.length>0" )
            a.item( @click="openFilePicker()" )
                i.upload.icon
                | {{ translate('dropzone.upload') }}
            a.item( @click="toggleDeletion()" ":class"="deletion?'active':''")
                i.remove.icon
                | {{ translate('dropzone.remove') }}
        div.ui.segment.bottom.attached#dropZone
            input#files( type='file' name='files[]' hidden multiple)
            div#images.ui.seven.doubling.cards(
                v-show="buffer.length>0 || images.length>0"
                v-images="sortImages"
                )
                div.ui.card(
                    v-for="(image, index) in sortImages"
                    ":id"="image.id" )
                    img.ui.fluid.rounded.image(
                        ":src"="image.thumb_path"
                        ":class"="(mode=='usefirst' && index==0)?'active':''" )
                    a.ui.red.bottom.attached.label.center.aligned( v-if="deletion" @click="deleteImage(image)")
                        i.remove.icon

                div.ui.fluid.card( v-for="waiting in buffer" )
                    div.ui.loader.centered.inline.active
            div.ui.padded.eight.wide.grid(
                v-show="buffer.length==0 && images.length==0" )
                div.center.aligned.column.link#dropzone_openfilepicker(
                    @click="openFilePicker()" )               
                    i.circular.icon.info
                    p {{ translate('dropzone.info') }}
            div.ui.dimmer
                div.content
                    div.center
                        h2.ui.inverted.icon.header
                            i.upload.icon {{ translate('dropzone.drop_hover') }}
</template>

<script lang="coffee">
    module.exports =
        name: 'Dropzone'
        props: ['images', 'mode']
        computed:
            ###*
            #   Sorts images using order.
            ###
            sortImages: ->
                return @images.sort (a, b) => a.order-b.order
        data: ->
            buffer: []
            deletion: false
        methods:
            ###*
            #   Activate delete mode. 
            ###
            toggleDeletion: ->
                @deletion = !@deletion
            
            ###*
            #   Deletes a image. 
            #   @param {image} image to delete
            ###
            deleteImage: (image) ->  
                @images.splice(image.order, 1)
                $('#images').trigger 'refresh'
     
            ###*
            #   Opens the file picker.
            ###
            openFilePicker: ->
                $('#files').trigger 'click'

            ###*
            #   Adds a file.
            #   @param {file} file to add
            ###
            handleFile: (file) ->
                @buffer.push file
                data = new FormData()
                data.append 'files[]', file
                @$http.post('api/images', data).then(
                    (response) =>
                        @buffer.pop file
                        bus.$emit 'image_added', response.data
                        $('#images').trigger 'refresh'
                    (response) =>
                        bus.$emit 'error', response.data
                        @buffer.pop file
                )

            ###*
            #   Handles file selection event.
            #   @param {e} file select event
            ###
            handleFileSelect: (e) ->
                # Exit if drop doesn´t involve new files
                if e.dataTransfer && e.dataTransfer.types[0] != 'Files'
                    return
                e.stopPropagation()
                e.preventDefault()
                $('#dropZone').dimmer 'hide'
                # Get FileList from drop or input
                files = if e.dataTransfer then e.dataTransfer.files else e.target.files
                for file in files
                    @handleFile file

            ###*
            #  Handles dragover event.
            #  @param {e} dragover event
            ###
            handleDragOver: (e) ->
                # Exit if dragover doesn´t involve new files
                if e.dataTransfer && e.dataTransfer.types[0] != 'Files'
                    return
                e.stopPropagation()
                e.preventDefault()
                e.dataTransfer.dropEffect = 'copy'
                $('#dropZone').dimmer 'show'

            ###*
            #   Handles dragleave (Leaving dropzone area).
            ###
            handleDragLeave: ->
                $('#dropZone').dimmer 'hide'

        mounted: ->
            # Check for the various File API support.
            if !window.File || !window.FileReader || !window.FileList || !window.Blob
                return console.error 'No File API'
            # Listen for drag and drop
            dropZone = document.getElementById 'dropZone'
            dropZone.addEventListener 'dragover', @handleDragOver
            dropZone.addEventListener 'dragleave', @handleDragLeave
            dropZone.addEventListener 'drop', @handleFileSelect
            # Listen for changes in file-select
            fileInput = document.getElementById 'files'
            fileInput.addEventListener 'change', @handleFileSelect
</script>

<style>
    #dropzone_openfilepicker {
        border: 1px dashed #999; 
        border-radius: 10px; 
        cursor:pointer;
    }
</style>