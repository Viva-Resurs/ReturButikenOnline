<script lang="coffee">
    module.exports =
        inserted: (el,binding) ->

            ###
                TODO: Fix dimensions
                TODO: Transitions
                TODO: Start and End padding (when images are below 100% in width)
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
                slide.style.background = '#ff0'
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

            # Register
            $(el).on( "mousedown", handleDown )
            $(el).on( "mouseup", handleUp )
            $(el).on( "mouseleave", handleUp )
            $(el).on( "mousemove", handleMove )
            bus.$on 'snapTo', (index) =>
                snapTo index







            timer = false
            setDimensions = ->
                if (timer)
                    clearTimeout(timer)
                    timer = false
                if (!timer)
                    timer = setTimeout () ->
                        console.log 'resized'
                        # Get current height - padding and take up half
                        height = (window.innerHeight-60)/2
                        $(el).height(height)

                        width = Number( $(el).width() )

                        slides.width = width * images.length;
                        slides.style.width = (width * images.length) + 'px';
                        slides.height = height;
                        for slide in slides.refs
                            slide.style.height = height + 'px';
                            slide.height = height

                            slide.style.width = width + 'px';
                            slide.width = width;

                        snapTo()
                        timer = false
                    , 200

            window.addEventListener("resize", setDimensions);
            setDimensions()















</script>
