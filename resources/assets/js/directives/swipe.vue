<script lang="coffee">
    module.exports =
        inserted: (el,binding) ->

            ###
                TODO: Transitions
            ###

            # Setup
            height = 0
            width = 0
            active_image = 0
            images = binding.value

            $(el).css
                'overflow-x': 'scroll'
                'overflow-y': 'hidden'

            # Setup slides-wrapper
            slides = document.createElement 'div'
            slides.style.padding = '0px';
            slides.refs = []

            # Create slides from images
            for image in images
                slide = document.createElement 'div'
                slide.style.cssFloat = 'left';
                slide.style.backgroundRepeat = 'no-repeat'
                slide.image = document.createElement 'img'
                slide.image.onload = ->
                    # Save original size for later calculations
                    this.originalHeight = slide.image.height
                    this.originalWidth = slide.image.width
                slide.image.src = image.path
                slide.style.backgroundImage = "url('" + image.path + "')"
                slide.style.backgroundSize = 'cover'
                slides.refs.push(slide) # Save reference
                slides.appendChild slide # Add slide into wrapper

            # Insert slides
            $(el).append(slides)







            checkActive = ->
                #total = slides.width - width
                pos = $(el).scrollLeft()+width/2
                active_image = Math.floor pos/width
                for image, index in images
                    Vue.set image, 'selected', false
                    if index == active_image
                        Vue.set image, 'selected', true

            # Swipe/scroll in wrapper
            swipeTo = (offset) ->
                $(el).scrollLeft( $(el).scrollLeft()+offset )
                checkActive()

            snapTo = (index) ->
                if typeof index == 'number'
                    active_image = index
                $(el).scrollLeft( active_image*width )
                checkActive()



            # Pointer position
            active = false
            position =
                x: 0
                y: 0
            delta =
                x: 0
                y: 0
            setPosition = (x, y) ->
                position.x = x
                position.y = y

            # Handle pointer-events
            handleDown = (e) ->
                e.preventDefault()
                setPosition e.clientX, e.clientY
                active = true
            handleUp   = (e) ->
                e.preventDefault()
                snapTo()
                active = false
            handleMove = (e) ->
                e.preventDefault()
                if active == false
                    return
                delta =
                    x: position.x - e.clientX
                    y: position.y - e.clientY

                swipeTo delta.x*2
                setPosition e.clientX, e.clientY

            # Register event listeners
            el.addEventListener "mousedown", handleDown
            el.addEventListener "mouseup", handleUp
            el.addEventListener "mouseleave", handleUp
            el.addEventListener "mousemove", handleMove

            bus.$on 'snapTo', (index) => snapTo index







            timer = false
            setDimensions = ->
                # Give browser some time in the event storm
                if timer
                    clearTimeout timer
                    timer = false
                if !timer
                    timer = setTimeout ->
                        # Get current height & set
                        height = el.height = (window.innerHeight-60)/2

                        # Get current width & set
                        width = el.width = el.offsetWidth

                        # Resize slides
                        slides.style.width = (width * images.length) + 'px';
                        for slide in slides.refs
                            # Resize slide
                            slide.style.height = height + 'px';
                            slide.style.width = width + 'px';

                            # Set initial values for image
                            w = slide.image.originalWidth
                            h = slide.image.originalHeight
                            x = 0
                            y = 0

                            # Make images fit while keeping aspect reatio
                            if w > width
                                h = width/w * h
                                w = width
                            if h > height
                                w = height/h * w
                                h = height

                            # Add offset to center image
                            if h < height
                                y = (height-h)/2
                            if w < width
                                x = (width-w)/2

                            # Update css
                            slide.style.backgroundSize = w + 'px ' + h + 'px'
                            slide.style.backgroundPosition = x + 'px ' + y + 'px'

                        snapTo()
                        timer = false
                    , 200

            window.addEventListener "resize", setDimensions
            setDimensions()















</script>
