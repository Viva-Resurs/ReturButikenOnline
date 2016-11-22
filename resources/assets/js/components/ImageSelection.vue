<template>
    <div class="ui container segment">
        <input type="file" id="fileinput" multiple="multiple" accept="image/*" />
        <div class="ui tiny images" id="selectedImages">
            <img class="ui image" src="" />
        </div>
    </div>
</template>

<script lang="coffee">

    module.exports = {

        name: 'ImageSelection',

        props: [ ]

        computed:

        data: ->
            images : []

        methods:

            previewSelectedImages () ->
                #Integrate and adapt to Vue 2.0
                var uploadFilesDiv = document.querySelector('#fileInput')
                uploadFilesDiv.addEventListener('change', function() {
                    var files = this.files;
                    for (var i = 0; i < files.length; i++){
                        previewImage(this.files[i]);
                    }
                }, false);

            previewImage(file) ->

                #Integrate and adapt to Vue 2.0
                var selectedImagesId = "selectedImages";
                var selectedImages = document.getElementById(selectedImagesId);

                var imageType=/image.*/;
                if (!file.type.match(imageType))
                    throw "File Type must be a image";
                var thumb = document.createElement("div");
                thumb.classList.add('thumbnail');

                var img = document.createElement("img");
                img.file = file;
                thumb.appendChild(img);
                selectedImages.appendChild(thumb);

                var reader = new FileReader();
                reader.onload = (function(aImage) {
                    return function(e) {
                        aImage.src = e.target.result;
                    };
                })(img);

                reader.readAsDataURL(file);

            uploadFile(file) ->
                #Upload file using
                #Example from internet
                var url = '/server/index.php';
                var xhr = new XmlHttpRequest();
                var fd = new FormData();
                xhr.open("POST", url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200){
                        console.log(xhr.responseText);
                    }

                };
                fd.append("upload_file", file);
                xhr.send(fd);

        watch:

        created: ->

        beforeDestroy: ->

    }
</script>
