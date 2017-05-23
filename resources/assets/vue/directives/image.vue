<script lang="coffee">
    module.exports =            
       
        # When component is updated, do the following
        componentUpdated: (el, binding) ->           
            #Global properties
            imageHeight = ""
            imageWidth = ""
          
            windowHeight = window.innerHeight-50
            windowWidth = window.innerWidth-50

            getAdjustedBounds = (imageHeight, imageWidth) ->
                newImageWidth = ""
                newImageHeight = ""
                imageAspectRatio = 0

                #Image is in portrait mode
                if imageHeight > imageWidth                    
                    imageAspectRatio = imageHeight/imageWidth 
                    expectedWidth = windowWidth                        
                    newHeight = expectedWidth * imageAspectRatio  
                
                    if newHeight < windowHeight
                        newImageHeight = newHeight
                        newImageWidth = expectedWidth
                    else
                        expectedHeight = windowHeight
                        imageAspectRatio = imageWidth/imageHeight                            
                        newWidth = expectedHeight * imageAspectRatio
                        newImageWidth = newWidth
                        newImageHeight = expectedHeight                            

                #Image is in landscape mode or square            
                else if imageWidth >= imageHeight                    
                    expectedHeight = windowHeight                     
                    imageAspectRatio = imageWidth/imageHeight 
                    newWidth = expectedHeight * imageAspectRatio                        

                    if newWidth < windowWidth
                        newImageWidth = newWidth
                        newImageHeight = expectedHeight
                    else
                        imageAspectRatio = imageHeight/imageWidth
                        expectedWidth = windowWidth
                        newHeight = expectedWidth * imageAspectRatio
                        newImageHeight = newHeight
                        newImageWidth = expectedWidth
                
                return [newImageHeight, newImageWidth]
      
            setImageProperties = (img, path) ->            
                img.src = path
                img.onload = ->
                    imageHeight = this.height
                    imageWidth = this.width                    
                    console.log img.height+","+img.width
                    newHeightWidth = getAdjustedBounds(imageHeight, imageWidth)
                    
                    imageHeight = newHeightWidth[0]
                    imageWidth = newHeightWidth[1]
            
                    img.style.width = imageWidth-5+'px'
                    img.style.height = imageHeight-5+'px'                      
                    el.style.left = windowWidth/2+'px'
                    windowImageDiff = window.innerWidth-(imageWidth+20)
                    console.log windowImageDiff
                    el.style.padding = 0+'px'  
                    $('.modal').modal('refresh')                              
                    
                    

                return img
            
            active_image = binding.value            
            
            image_element = el.getElementsByTagName("img")[0]            
            if image_element
                el.removeChild(image_element)
            
            image_element = document.createElement 'img'                                
            image_element = setImageProperties(image_element,active_image.path)            
            el.appendChild(image_element)
            
            image_element.style.position = 'relative'
            image_element.style.margin = 'auto'            
            image_element.style.left = '0'
            image_element.style.top = '0'
            image_element.style.right = '0'
            image_element.style.bottom = '0'

            #window.addEventListener "resize", setImageProperties(image_element,active_image.path)             
 
</script>                