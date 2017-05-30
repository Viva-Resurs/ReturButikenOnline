<script lang="coffee">
    module.exports =            
        componentUpdated: (el, binding) ->           
            #Global properties
            
            imageHeight = ""
            imageWidth = ""
          
            windowHeight = window.innerHeight-150
            windowWidth = window.innerWidth-50
            position = 0

            leftButtonClicked = (e) ->               
                bus.$emit 'left_button_clicked'                     
                               
            rightButtonClicked = (e) ->
                bus.$emit 'right_button_clicked'                     

            addOverlayButtons = () ->                
                # Hide buttons depending on position

                leftButton = document.getElementById "leftButton" 
                rightButton = document.getElementById "rightButton" 
                closeButton = document.getElementById "closeButton" 
                
                if !leftButton || !rightButton  || !closeButton             
                    leftButton = document.createElement 'i'
                    leftButton.className = "huge chevron circle left icon"
                    leftButton.style.zIndex = '1'
                    leftButton.id = "leftButton"
                    leftButton.style.position = 'absolute'   
                    leftButton.style.filter = 'drop-shadow(2px 2px 2px #222)'
                    leftButton.style.left = '20px'

                    rightButton = document.createElement 'i'                
                    rightButton.id = "rightButton"           
                    rightButton.className = "huge chevron circle right icon"                
                    rightButton.style.position = 'absolute'   
                    rightButton.style.filter = 'drop-shadow(2px 2px 2px #222)'             
                    rightButton.style.zIndex = '1'                
                    Vue.nextTick ->  
                        leftButton.addEventListener "mouseup", leftButtonClicked
                        rightButton.addEventListener "mouseup", rightButtonClicked      

                    closeButton = document.createElement 'div'   
                    closeButton.className += "ui red button"   
                    closeButton.style.zIndex = '1'        
                    closeButton.style.width = '10px'                     
                    closeButton.style.leftMargin = '0px'
                    closeButton.style.rightMargin = '0px'
                    closeButton.style.textAlign = 'center'                     
                    closeButton.style.position = "absolute"                    
                    closeButton.id = "closeButton"         

                    closeButtonIcon = document.createElement 'i'
                    closeButtonIcon.className += "remove icon" 
                    closeButtonIcon.style.position = 'relative'
                    closeButtonIcon.style.left = '-5px'
                   
                    closeButton.appendChild(closeButtonIcon)                                                      
                    closeButton.onclick = () -> $('.modal').modal('hide')

                    el.appendChild leftButton
                    el.appendChild rightButton
                    el.appendChild closeButton

                closeButton.style.top = '5px'
                closeButton.style.right = '5px'
                leftButton.style.top = ((imageHeight-30)/2)+'px'
                rightButton.style.top = ((imageHeight-30)/2)+'px'
                rightButton.style.right = 20+'px'
                rightButton.style.margin = '0px'

                hideButtons(leftButton, rightButton)

            hideButtons = (leftButton, rightButton) ->                
                switch binding.value.position
                    when 0
                        leftButton.style.visibility = 'hidden'
                        rightButton.style.visibility = 'visible' 
                    when 1
                        leftButton.style.visibility = 'visible' 
                        rightButton.style.visibility = 'visible'
                    when 2
                        leftButton.style.visibility = 'visible' 
                        rightButton.style.visibility = 'hidden' 
                    else 
                        leftButton.style.visibility = 'hidden' 
                        rightButton.style.visibility = 'hidden'

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
                img.style.display = 'none'   
                img.id = "img_"+active_image                              
                img.onload = ->
                    console.log "on load"
                    imageHeight = this.height
                    imageWidth = this.width                    
                    newHeightWidth = getAdjustedBounds(imageHeight, imageWidth)
                    
                    imageHeight = newHeightWidth[0]
                    imageWidth = newHeightWidth[1]
            
                    img.style.width = imageWidth-5+'px'
                    img.style.height = imageHeight-5+'px'  
                    el.style.overflow = 'hidden'                    
                    el.style.left = windowWidth/2+'px'                             
                    el.style.padding = 0+'px'  
                    
                    addOverlayButtons()
                    img.style.display = 'initial'
                                                 
                    imageBox = el.getBoundingClientRect()
                         
                    $('.modal').modal('refresh') 
                
                    $('img').draggable({ 
                        axis: "x",                         
                        appendTo: 'parent',                        
                        scroll: true,
                    
                        stop: (e) =>
                            e.preventDefault()                                             
                            imageRect = img.getBoundingClientRect()
                            
                            if (imageRect.right-e.offsetX < imageBox.left+(imageWidth/4))
                                rightButtonClicked()                                
                                
                            else if (imageRect.left+e.offsetX > imageBox.right-(imageWidth/4))                                                                                                               
                                leftButtonClicked()                                
                        
                            img.style.left = 0+'px'                     
                    })        

                return img
            
            active_image = binding.value.active_image            
            
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