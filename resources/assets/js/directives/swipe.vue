<script lang="coffee">
    module.exports =
        inserted: (el,binding) ->

            # Setup
            height = 0
            width = 0
            active_image = 0
            images = binding.value

            el.style.overflowX = 'hidden'
            el.style.overflorY = 'hidden'

            # Setup slides-wrapper
            slides = document.createElement 'div'
            slides.style.padding = '0px';
            slides.refs = []

            # Create slides from images
            for image, index in images
                slide = document.createElement 'div'
                slide.style.cssFloat = 'left';
                slide.style.backgroundRepeat = 'no-repeat'
                slide.ready = false
                img = document.createElement 'img'
                img.slideID = index
                img.onload = ->
                    # Save original size for later calculations
                    slides.refs[this.slideID].imageHeight = this.height
                    slides.refs[this.slideID].imageWidth = this.width
                    slides.refs[this.slideID].ready = true
                    console.debug "IMG: #{this.slideID}: #{this.width} #{this.height}"
                img.src = '/' + image.path
                slide.style.backgroundImage = "url('/" + image.path + "')"
                slide.style.backgroundSize = 'cover'
                slides.refs.push(slide) # Save reference in wrapper
                slides.appendChild slide # Add slide element into wrapper

            # Insert slides
            el.appendChild(slides)

            # Set active image by determine position in wrapper
            checkActive = ->
                pos = el.scrollLeft + width/2
                active_image = Math.floor pos/width
                for image, index in images
                    Vue.set image, 'selected', false
                    if index == active_image
                        Vue.set image, 'selected', true

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

                swipeTo delta.x * 2
                setPosition e.clientX, e.clientY

            # Interact with pointer
            el.addEventListener "mousedown", handleDown
            el.addEventListener "mouseup", handleUp
            el.addEventListener "mouseleave", handleUp
            el.addEventListener "mousemove", handleMove

            # Interact programmatically
            bus.$on 'snapTo', (index) => snapTo index

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
                        height = el.height = (window.innerHeight-60)/2

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

                            # Debug
                            # console.debug "Slide[#{index}] width: #{width} height: #{height} w: #{w} h: #{h}"

                        snapTo()
                        timer = false
                    , 200
            # When browser is resized
            window.addEventListener "resize", setDimensions

            # Wait while loading slides
            loader = setInterval ->
                for slide in slides.refs
                    if !slide.ready
                        return false
                # Ready
                clearInterval(loader)
                setDimensions()
            , 200
</script>
