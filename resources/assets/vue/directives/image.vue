<script lang="coffee">
    module.exports =
        componentUpdated: (el, binding) ->                       
            imageHeight = ""
            imageWidth = ""
            windowHeight = window.innerHeight-30
            windowWidth = window.innerWidth-50
            position = 0
            navigated = 0

            leftButtonClicked = (e) ->                                      
                $('#leftButton, #rightButton, #closeButton').css('visibility', 'hidden')                  
                $('#leftButton, #rightButton, #closeButton').css('opacity', '0')  
                bus.$emit 'left_button_clicked'   

            rightButtonClicked = (e) ->                                
                $('#leftButton, #rightButton, #closeButton').css('visibility', 'hidden')                                                  
                $('#leftButton, #rightButton, #closeButton').css('opacity', '0')  
                bus.$emit 'right_button_clicked' 

            addOverlayButtons = () ->                
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
                    rightButton.style.right = 20+'px'
                  
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
                    closeButton.style.top = '5px'
                    closeButton.style.right = '5px'
          
                    el.appendChild leftButton
                    el.appendChild rightButton
                    el.appendChild closeButton

                leftButton.style.top = ((imageHeight-30)/2)+'px'
                rightButton.style.top = ((imageHeight-30)/2)+'px'
                closeButton.style.visibility = "visible"                             
                $('#leftButton, #rightButton, #closeButton').animate                            
                    opacity: 1
                , 1000, null
            
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
                if path   
                    img.src = path               
                    img.style.display = 'none'   
                    img.id = "img_active"  

                    img.onload = ->
                        imageHeight = this.height
                        imageWidth = this.width                    
                        newHeightWidth = getAdjustedBounds(imageHeight, imageWidth)
                        
                        imageHeight = newHeightWidth[0]
                        imageWidth = newHeightWidth[1]
                
                        img.style.width = imageWidth+'px'
                        img.style.height = imageHeight+'px'  
                        el.style.overflow = 'hidden'                    
                        el.style.left = windowWidth/2+'px'                             
                        el.style.padding = 0+'px'  
                        img.style.display = 'initial'
                        
                        navigated = binding.value.navigated
                        
                        switch navigated
                            when Number(1)
                                img.style.left = '-100%'
                                $('#img_active').animate
                                    left: '0%'
                                , 350, null

                            when Number(2)
                                img.style.left = '100%'
                                $('#img_active').animate
                                    left: '0%'
                                , 350, null

                        addOverlayButtons() 

                        $('.modal').modal('refresh') 
                  
                        canMoveLeft = true
                        canMoveRight = true
                   
                        if (binding.value.position == 0)
                            canMoveLeft = false
                        if (binding.value.position == 2)
                            canMoveRight = false
                        
                        img.moveLeft = canMoveLeft
                        img.moveRight = canMoveRight
                        
                        imageBox = el.getBoundingClientRect()

                        inArea = (position, img) ->
                            imageRect = img.getBoundingClientRect()                                
                            imageOffset = imageRect.left+imageRect.width/2
                            inside = false

                            switch position
                                when "left"
                                    imageBoxLeftArea = imageBox.left+(Number(imageRect.width/3))                                
                                    inside = imageOffset < imageBoxLeftArea
                                when "right"
                                    imageBoxRightArea = imageBox.right-(Number(imageRect.width/3)) 
                                    inside = imageOffset > imageBoxRightArea
                                    
                            return inside

                        $(img).draggable({ 
                            axis: "x",                         
                            appendTo: 'parent',                        
                            scroll: true,
                            
                            drag: (e) =>
                                if !this.moveLeft                                                                    
                                    if inArea("right", img)
                                        $( "#img_active" ).draggable( "option", "revert", true );
                      
                                if !this.moveRight
                                    if inArea("left", img)
                                        $( "#img_active" ).draggable( "option", "revert", true );
                            
                            stop: (e) =>                                                                      
                                if inArea("left", img)
                                    imageRect = img.getBoundingClientRect()  
                                    $('#img_active').animate
                                        left: "-="+Number(imageBox.left+imageRect.right)
                                    , 350, rightButtonClicked                                                                                        
                                   
                                else if inArea("right", img)                                                                                                               
                                    imageRect = img.getBoundingClientRect()  
                                    $('#img_active').animate
                                        left: "+="+Number(imageBox.right+imageRect.left)
                                    , 350, leftButtonClicked

                                else
                                   $('#img_active').animate                                    
                                        left: 0+'px'
                                    , 350, null
                                
                                $( "#img_active" ).draggable( "option", "revert", false );
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
</script>                