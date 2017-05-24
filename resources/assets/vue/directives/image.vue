<script lang="coffee">
    module.exports =            
       
        # When component is updated, do the following
        componentUpdated: (el, binding) ->           
            #Global properties
            
            imageHeight = ""
            imageWidth = ""
          
            windowHeight = window.innerHeight-150
            windowWidth = window.innerWidth-50
            
            leftButtonClicked = (e) ->               
               bus.$emit 'left_button_clicked'                     
                               
            rightButtonClicked = (e) ->
               bus.$emit 'right_button_clicked'                     

            addOverlayButtons = () ->                

                leftButton = document.getElementById "leftButton" 
                rightButton = document.getElementById "rightButton" 
                
                if !leftButton || !rightButton               
                    leftButton = document.createElement 'i'
                    leftButton.className = "huge chevron circle left icon"
                    leftButton.style.zIndex = '1'
                    leftButton.id = "leftButton"
                    leftButton.style.position = 'absolute'                
                    leftButton.style.left = '0px';               

                    rightButton = document.createElement 'i'                
                    rightButton.id = "rightButton"           
                    rightButton.className = "huge chevron circle right icon"                
                    rightButton.style.position = 'absolute'                
                    rightButton.style.zIndex = '1'                
                    Vue.nextTick ->  
                        leftButton.addEventListener "mouseup", leftButtonClicked
                        rightButton.addEventListener "mouseup", rightButtonClicked      

                    el.parentElement.appendChild leftButton
                    el.parentElement.appendChild rightButton
                
                leftButton.style.top = (windowHeight/2-16.5)+'px'
                rightButton.style.top = (windowHeight/2-16.5)+'px'
                rightButton.style.left = (windowWidth-63-6)+'px'
                rightButton.style.margin = '0px'
            
                #hideLeftButton()
                #hideRightButton()


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
                    newHeightWidth = getAdjustedBounds(imageHeight, imageWidth)
                    
                    imageHeight = newHeightWidth[0]
                    imageWidth = newHeightWidth[1]
            
                    img.style.width = imageWidth-5+'px'
                    img.style.height = imageHeight-5+'px'                      
                    el.style.left = windowWidth/2+'px'                             
                    el.style.padding = 0+'px'  
                    
                    button = document.createElement 'div'   
                    button.className += "ui primary button"          
                    buttonText = document.createTextNode('Ok')         
                    button.style.textAlign = 'center' 

                    button.appendChild(buttonText)                    
                    button.style.position = "absolute"
                    button.style.width = '60px'                        
                    button.style.left = (el.parentElement.offsetWidth/2-30)+'px'
                    button.style.bottom = 20+'px'
                    button.onclick = () -> $('.modal').modal('hide')

                    el.appendChild(button)
                    addOverlayButtons()
                    $('.modal').modal('refresh')                              

                    

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

            console.log "position: "+binding.value.pos
            #window.addEventListener "resize", setImageProperties(image_element,active_image.path)             
 
</script>                