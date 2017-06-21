<script lang="coffee">
    module.exports =
        
        # On insert, do the following
        inserted: (el,binding) ->

            # Setup
            height = 0
            width = 0
            active_image = 0
            images = binding.value.images

            el.style.overflowX = 'hidden'
            el.style.overflorY = 'hidden'

            # Setup slides-wrapper
            slides = document.createElement 'div'
            slides.style.padding = '0px'
            slides.refs = []

            # Create slides from images
            for image, index in images
                slide = document.createElement 'div'
                slide.style.cssFloat = 'left'
                slide.style.backgroundRepeat = 'no-repeat'
                slide.ready = false
                img = document.createElement 'img'
                img.slideID = index
                img.onload = ->
                    # Save original size for later calculations
                    slides.refs[this.slideID].imageHeight = this.height
                    slides.refs[this.slideID].imageWidth = this.width
                    slides.refs[this.slideID].ready = true
                    if process.env.NODE_ENV == "development"
                        console.debug "IMG: #{this.slideID}: #{this.width} #{this.height}"                
                img.src = image.path         
                slide.style.backgroundImage = "url('#{image.path}')"
                slide.style.backgroundSize = 'cover'
                slides.refs.push slide # Save reference in wrapper
                slides.appendChild slide # Add slide element into wrapper
                

            # Insert slides
            el.appendChild slides
            

            # Set active image by determine position in wrapper
            checkActive = ->
                #Utgår från vänstra kanten + elementets bredd / 2
                pos = el.scrollLeft + width/2
                
                active_image = Math.floor pos/width
                
                #Sätt alla unselected 
                for image, index in images
                    Vue.set image, 'selected', false
                    if index == active_image
                        Vue.set image, 'selected', true
            
            #Configure overlay button according to screen type
            configureButton = (button, screenType, dir) ->                
                button.className = dir+" "+"chevron circle icon"
                button.style.position = "absolute"
                button.style.zIndex = '1'                
         
                switch screenType
                    when "mobile"
                        button.className += " big"
                        button.style.top = (height/2-16)+'px'                            
                        if dir == "left"                             
                            button.style.left = '10px'; 
                        else                                                        
                            button.style.right = 10+'px'
                            button.style.margin = 0+'px' 

                    when "tablet"
                        button.className += " huge"
                        button.style.top = (height/2-30)+'px'                            
                        if dir == "left"                             
                            button.style.left = '10px'; 
                        else                                                        
                            button.style.right = 10+'px'
                            button.style.margin = 0+'px' 

                    when "desktop"
                        button.className += " huge"
                        button.style.top = (height/2-30)+'px'                            
                        if dir == "left"                             
                            button.style.left = '10px'; 
                        else                                                        
                            button.style.right = 10+'px' 
                            button.style.margin = 0+'px'                       

                return button    
            
            #Overlay buttons
            addOverlayButtons = (type) ->                             
                leftButton = document.getElementById "previewLeftButton" 
                rightButton = document.getElementById "previewRightButton" 
                numImagesLabel = document.getElementById "numImagesLabel"
                
                if !leftButton || !rightButton               
                    leftButton = document.createElement 'i'
                    leftButton.id = "previewLeftButton"
       
                    rightButton = document.createElement 'i'                
                    rightButton.id = "previewRightButton"           
         
                    numImagesLabel = document.createElement 'div'
                    numImagesLabel.id = "numImagesLabel"
                    numImagesLabel.className = "ui black label"
                    numImagesLabel.style.position = 'absolute'
                    numImagesLabel.style.zIndex = '1'                            
                    numImagesLabel.style.textAlign = 'center'                       
                    numImagesLabel.style.right = '20px';  
                    numImagesLabel.style.top = '20px';     
          
                    el.parentElement.appendChild numImagesLabel
                    el.parentElement.appendChild leftButton
                    el.parentElement.appendChild rightButton
           
                leftButton = configureButton(leftButton, type, "left")
                rightButton = configureButton(rightButton, type, "right")
    
                leftButton.addEventListener "mouseup", leftButtonClicked
                rightButton.addEventListener "mouseup", rightButtonClicked

                if slides.refs.length > 1
                    hideLeftButton()
                    hideRightButton()
            
            #Hide buttons if images are missing
            hideLeftButton = () ->
                leftButton = document.getElementById "previewLeftButton" 
                if leftButton
                    if active_image == 0                    
                        leftButton.style.visibility = 'hidden';               
                    else 
                        leftButton.style.visibility = 'visible';                 

            hideRightButton = () ->    
                rightButton = document.getElementById "previewRightButton"
                if rightButton 
                    if active_image == slides.refs.length-1
                        rightButton.style.visibility = 'hidden';               
                    else         
                        rightButton.style.visibility = 'visible';                            
                        
            leftButtonClicked = (e) ->               
                checkActive()
                if slides.refs[active_image-1]
                    active_image = active_image - 1
                    snapTo()
                               
            rightButtonClicked = (e) ->
                checkActive()
                if slides.refs[active_image+1]
                    active_image = active_image + 1
                    snapTo()
                              
            # Swipe/scroll in wrapper
            swipeTo = (offset) ->
                el.scrollLeft = el.scrollLeft + offset
                checkActive()

            snapTo = (index) ->
                if typeof index == 'number'
                    active_image = index
                $(el).animate
                    scrollLeft: active_image * width
                , 200, checkActive
                
                if binding.value.images.length > 1
                    hideLeftButton()
                    hideRightButton()
                    numImagesLabel = document.getElementById "numImagesLabel"       
                    if numImagesLabel.childNodes.length > 0
                        numImagesLabel.childNodes[0].nodeValue = active_image+1+"/"+images.length
                    else 
                        labelText = document.createTextNode(active_image+1+"/"+images.length)
                        numImagesLabel.appendChild(labelText)

            # Pointer position
            active = false
            position =
                x: 0
                y: 0
            delta =
                x: 0
                y: 0

            moved = false       

            setPosition = (x, y) ->
                position.x = x
                position.y = y
            
            startedClicking = 0
            stoppedClicking = 0
            
            # Handle pointer-events
            handleDown = (e) ->               
                e.preventDefault() 
                startedClicking = e.timeStamp
                el.addEventListener "mousemove", handleMove
                moved = false               
                setPosition e.clientX, e.clientY
                active = true
            
            handleUp   = (e) ->
                e.preventDefault()     
                stoppedClicking = e.timeStamp
                difference = stoppedClicking - startedClicking                
                if (Number(difference) < Number(150))                
                    openImagePreview()

                snapTo()
                active = false
                el.removeEventListener("mousemove", handleMove, false); 
            
            handleMove = (e) ->

                e.preventDefault()                  
                moved = true              
                if active == false
                    return
                delta =
                    x: position.x - e.clientX
                    y: position.y - e.clientY
                                
                swipeTo delta.x * 2
                setPosition e.clientX, e.clientY
            
            #Handle touch-events
            handleTouchStart = (e) ->     
                moved = false           
                e.preventDefault()                
                t = e.touches[0]
                setPosition t.screenX, t.screenY
                active = true
                
            handleTouchMove = (e) ->
                e.preventDefault()
                moved = true
                t = e.touches[0];
                if active == false
                    return
                
                delta =
                    x: position.x - t.screenX
                    y: position.y - t.screenY
                                
                swipeTo delta.x * 2
                setPosition t.screenX, t.screenY
            
            openImagePreview = () ->
                bus.$emit 'show_message',                        
                        type: 'image'
                        index: active_image
                        images: images

            handleTouchEnd = (e) ->
                if !moved
                    openImagePreview()
     
                e.preventDefault()
                snapTo()
                active = false

            # Interact with pointer
            el.addEventListener "mousedown", handleDown
            el.addEventListener "mouseup", handleUp
            el.addEventListener "mouseleave", handleUp
            

            # Interact with touch events
            el.addEventListener "touchstart", handleTouchStart
            el.addEventListener "touchmove", handleTouchMove
            el.addEventListener "touchend", handleTouchEnd

            # Interact programmatically
            bus.$on 'snapTo', (index) => snapTo index
            bus.$on 'addButtons', (type) => 
                addOverlayButtons type         

            # Resize wrapper and slides
            timer = false
            setDimensions = ->
                # Give browser some time in the event storm
                if timer
                    clearTimeout timer
                    timer = false
                if !timer
                    timer = setTimeout ->
                        # Get current height & set
                        height = el.height = (window.innerHeight-60)/3

                        # Get current width & set
                        width = el.width = el.offsetWidth

                        # Resize wrapper
                        slides.style.width = (width * images.length) + 'px';
                        for slide, index in slides.refs
                            # Resize slide
                            slide.style.height = height + 'px';
                            slide.style.width = width + 'px';                            

                            # Set initial values for image
                            w = slide.imageWidth
                            h = slide.imageHeight
                            x = 0
                            y = 0

                            # Make images fit while keeping aspect reatio
                            if w > width
                                h = width/w * h
                                w = width
                            if h > height
                                w = height/h * w
                                h = height
                            if w > width
                                h = width/w * h
                                w = width

                            # Add offset to center image
                            if h < height
                                y = (height-h)/2
                            if w < width
                                x = (width-w)/2

                            # Update css
                            slide.style.backgroundSize = w + 'px ' + h + 'px'
                            slide.style.backgroundPosition = x + 'px ' + y + 'px'
                        
                        #if slides.refs.length > 1
                            #addOverlayButtons()
                        
                        snapTo()

                        timer = false
                    , 200
                
            # When browser is resized
            window.addEventListener "resize", setDimensions
            #addOverlayButtons()

            # Wait while loading slides
            loader = setInterval ->
                for slide in slides.refs
                    if !slide.ready
                        return false
                # Ready
                clearInterval loader
                setDimensions()
            , 200

        componentUpdated: (el,binding) ->            
            if binding.value.images.length > 1    
                bus.$emit 'addButtons', binding.value.screenType 
</script>
