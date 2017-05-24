<script lang="coffee">
    module.exports =            
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
                # Hide buttons depending on position

                leftButton = document.getElementById "leftButton" 
                rightButton = document.getElementById "rightButton" 
                okButton = document.getElementById "okButton" 
                
                if !leftButton || !rightButton  || !okButton             
                    leftButton = document.createElement 'i'
                    leftButton.className = "huge chevron circle left icon"
                    leftButton.style.zIndex = '1'
                    leftButton.id = "leftButton"
                    leftButton.style.position = 'absolute'                
                    leftButton.style.left = '20px';               

                    rightButton = document.createElement 'i'                
                    rightButton.id = "rightButton"           
                    rightButton.className = "huge chevron circle right icon"                
                    rightButton.style.position = 'absolute'                
                    rightButton.style.zIndex = '1'                
                    Vue.nextTick ->  
                        leftButton.addEventListener "mouseup", leftButtonClicked
                        rightButton.addEventListener "mouseup", rightButtonClicked      

                    okButton = document.createElement 'div'   
                    okButton.className += "ui primary button"   
                    okButton.style.zIndex = '1'        
                    buttonText = document.createTextNode('Ok')         
                    okButton.style.textAlign = 'center' 

                    okButton.appendChild(buttonText)                    
                    okButton.style.position = "absolute"
                    okButton.style.width = '60px'   
                    okButton.id = "okButton"                       
                    okButton.onclick = () -> $('.modal').modal('hide')

                    el.appendChild okButton
                    el.appendChild leftButton
                    el.appendChild rightButton
                                
                okButton.style.left = (el.parentElement.offsetWidth/2-30)+'px'
                okButton.style.bottom = 20+'px'
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