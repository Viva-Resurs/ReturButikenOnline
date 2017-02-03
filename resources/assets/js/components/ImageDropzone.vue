<template lang="pug">
    div
        div.ui.attached.right.aligned.menu( v-if="images.length>0" )
            a.item( @click="openDialog()" )
                i.upload.icon
                | Upload
        div.ui.segment.bottom.attached#dropZone
            input#files( type='file' name='files[]' hidden multiple)
            div.ui.five.doubling.cards(
                v-show="buffer.length>0 || images.length>0"
                v-images="images"
                )
                div.ui.card(
                    v-for="(image, index) in sortImages"
                    ":id"="image.id" )
                    img.ui.fluid.rounded.image(
                        ":src"="image.thumb_path"
                        ":class"="(mode=='usefirst' && index==0)?'active':''" )
                    div.ui.bottom.attached.label.center.aligned
                        | {{ '#'+image.id }} order{{ image.order }}
                    a.ui.white.tiny.left.corner.label( @click="show(image)" )
                        i.eye.icon
                    a.ui.red.tiny.right.corner.label( @click="remove(image)" )
                        i.delete.icon
                div.ui.fluid.card( v-for="waiting in buffer" )
                    div.ui.loader.centered.inline.active
            div.ui.padded.eight.wide.grid(
                v-show="buffer.length==0 && images.length==0" )
                div.center.aligned.column.link(
                    @click="openDialog()"
                    style="border: 1px dashed #999; border-radius: 10px; cursor:pointer;")
                    i.circular.icon.info
                    p Dra bilder hit eller tryck för att bläddra
            div.ui.dimmer
                div.content
                    div.center
                        h2.ui.inverted.icon.header
                            i.upload.icon Drop Files
</template>

<script lang="coffee">
    module.exports =
        name: 'Dropzone'
        props: ['images', 'mode']
        computed:
            sortImages: ->
                return @images.sort (a, b) => a.order-b.order
        data: ->
            buffer: []
            order: []
        methods:
            show: (image) ->
                bus.$emit 'show_message',
                    title: image.original_name
                    type: 'image'
                    image: image

            remove: (image) ->
                bus.$emit 'image_removed', image

            openDialog: ->
                $('#files').trigger 'click'

            handleFile: (file) ->
                @buffer.push file
                data = new FormData()
                data.append 'files[]', file
                @$http.post('api/images', data).then(
                    (response) =>
                        @buffer.pop file
                        bus.$emit 'image_added', response.data
                    (response) =>
                        bus.$emit 'error', response.data
                        @buffer.pop file
                )

            handleFileSelect: (e) ->
                # Exit if drop doesn´t involve files
                if e.dataTransfer && e.dataTransfer.types[0] != 'Files'
                    return
                e.stopPropagation()
                e.preventDefault()
                $('#dropZone').dimmer 'hide'
                # Get FileList from drop or input
                files = if e.dataTransfer then e.dataTransfer.files else e.target.files
                for file in files
                    @handleFile file

            handleDragOver: (e) ->
                # Exit if dragover doesn´t involve files
                if e.dataTransfer && e.dataTransfer.types[0] != 'Files'
                    return
                e.stopPropagation()
                e.preventDefault()
                e.dataTransfer.dropEffect = 'copy'
                $('#dropZone').dimmer 'show'

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

<style lang="scss">
    // CSS-hack to keep pointer-cursor on corner-buttons.
    a.ui.corner.label {
        i.icon {
            cursor: pointer;
        }
    }
    img.active {
        outline: 2px solid #25f;
        box-shadow: 0px 0px 9px 5px rgba(50,70,255,0.6) !important;
    }
</style>
