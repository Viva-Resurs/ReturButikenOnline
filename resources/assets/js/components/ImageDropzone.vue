<template lang="pug">
    div.ui.segment.bottom.attached#dropZone
        input#files( type='file' name='files[]' hidden multiple)
        div.ui.eight.doubling.stackable.cards
            div.ui.dimmable.blurring.fluid.card( v-for="image in images" v-image )
                div.ui.dimmer
                    div.content
                        div.center
                            div.ui.inverted.standard.button.icon( @click="show(image)" )
                                i.eye.icon

                            div.ui.modal( ":id"="'imageModal'+image.id" )
                                i.close.icon

                                div.header {{image.name}}

                                div.image.content
                                    div.image
                                        img.ui.fluid.rounded.image( ":src"="image.path" ":id"="image.id" )

                            div.ui.inverted.red.button.icon( @click="remove(image)" )
                                i.delete.icon

                img.ui.fluid.rounded.image( ":src"="image.thumb_path" ":id"="image.id" )

            div.ui.fluid.card( v-for="waiting in buffer" )
                div.ui.loader.centered.inline.active

            div.ui.fluid.card
                div.ui.icon.massive.fluid.button( @click="openDialog()" )
                    i.upload.icon

        div.ui.dimmer
            div.content
                div.center
                    h2.ui.inverted.icon.header
                        i.upload.icon Drop Files
</template>

<script lang="coffee">
    module.exports = {
        name: 'Dropzone'
        props: ['images']
        data: () ->
            buffer: []

        methods:
            show: (image) ->
                # Show image
                $('#imageModal'+image.id)
                        .modal('show');


            remove: (image) ->
                bus.$emit('image_removed',image)

            openDialog: () ->
                $('#files').trigger('click')

            handleFile: (file) ->
                @buffer.push( file )

                data = new FormData()
                data.append('files[]', file)

                this.$http.post('images',data).then(
                    (response) =>
                        @buffer.pop( file )
                        bus.$emit('image_added',response.data)

                    (response) =>
                        bus.$emit('error', response);
                        @buffer.pop( file )

                );

            handleFileSelect: (e) ->
                e.stopPropagation()
                e.preventDefault()
                $('#dropZone').dimmer('hide')

                # Get FileList from drop or input
                files = if e.dataTransfer then e.dataTransfer.files else e.target.files

                for file in files
                    @handleFile file

            handleDragOver: (e) ->
                e.stopPropagation()
                e.preventDefault()
                e.dataTransfer.dropEffect = 'copy'
                $('#dropZone').dimmer('show')

            handleDragLeave: () ->
                $('#dropZone').dimmer('hide')

        mounted: () ->
            # Check for the various File API support.
            if (!window.File || !window.FileReader || !window.FileList || !window.Blob)
                return console.error 'No File API'

            # Listen for drag and drop
            dropZone = document.getElementById('dropZone')
            dropZone.addEventListener('dragover', @handleDragOver)
            dropZone.addEventListener('dragleave', @handleDragLeave)
            dropZone.addEventListener('drop', @handleFileSelect)

            # Listen for changes in file-select
            fileInput = document.getElementById('files')
            fileInput.addEventListener('change', @handleFileSelect)

    }
</script>
