<template>
    <div class="ui segment" id='dropZone'>
        <input type='file' id='files' name='files[]' hidden multiple/>
        <div class="ui four doubling stackable cards">
            <div class="ui fluid card" v-for="file in files">
                <img :src="file.src" class="ui fluid rounded image">
            </div>
            <div class="ui fluid card" v-for="waiting in buffer">
                <div class="ui loader active"></div>
            </div>
            <div class="ui fluid card">
                <div class="ui icon massive fluid button" @click="openDialog()">
                    <i class="upload icon"></i>
                </div>
            </div>
        </div>
        <p></p>
        <div class="ui dimmer">
            <div class="content">
                <div class="center">
                    <h2 class="ui inverted icon header">
                        <i class="upload icon"></i>
                        Drop Files
                    </h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="coffee">
    module.exports = {
        name: 'Dropzone'
        data: () ->
            files: []
            buffer: []

        methods:
            openDialog: () ->
                $('#files').trigger('click')

            handleFile: (file) ->
                reader = new FileReader()

                img = document.createElement('img')
                img.file = file

                # Add to buffer
                @buffer.push(img)

                # When ready
                reader.onload = (e) =>
                    img.src = e.target.result

                    # Move to files
                    @files.push( img )
                    @buffer.pop( img )

                # Start reading file
                reader.readAsDataURL(file)

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
