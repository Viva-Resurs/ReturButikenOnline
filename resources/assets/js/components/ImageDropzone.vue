<template>
    <div class="ui segment bottom attached" id='dropZone'>
        <input type='file' id='files' name='files[]' hidden multiple/>
        <div class="ui eight doubling stackable cards">
            <div class="ui dimmable blurring fluid card" v-for="image in images" v-image>
                <div class="ui dimmer">
                    <div class="content">
                        <div class="center">
                            <div class="ui inverted standard button icon" @click="show(image)">
                                <i class="eye icon"></i>
                            </div>
                            <div class="ui modal" :id="'imageModal'+image.id">
                                <i class="close icon"></i>
                                <div class="header">
                                    {{image.name}}
                                </div>
                                <div class="image content">
                                    <div class="image">
                                        <img :src="image.path" :id="image.id" class="ui fluid rounded image">
                                    </div>
                              </div>
                          </div>
                          <div class="ui inverted red button icon" @click="remove(image)">
                              <i class="delete icon"></i>
                          </div>
                        </div>
                    </div>
                </div>
                <img :src="image.thumb_path" :id="image.id" class="ui fluid rounded image">
            </div>
            <div class="ui fluid card" v-for="waiting in buffer">
                <div class="ui loader centered inline active">
                </div>
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
