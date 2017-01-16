<script lang="coffee">
    module.exports =
        inserted: (el,binding) ->

            ###
                TODO: Update width/height on resize
            ###

            # Get current width
            width = Number( $(el).width() )
            height = Number( $(el).height() )
            $(el).css
                'overflow-x': 'scroll'
                'overflow-y': 'hidden'

            # Grab images
            images = binding.value

            # Setup slides-wrapper
            slides = document.createElement 'div'
            slides.width = width * images.length;
            slides.style.width = (width * images.length) + 'px';
            slides.style.padding = '0px';
            slides.height = height;

            # Create slides from images
            for image in images
                slide = document.createElement 'div'

                slide.style.height = height + 'px';
                slide.height = height

                slide.style.width = width + 'px';
                slide.width = width;
                slide.style.cssFloat = 'left';
                slide.style.background = '#ff0'
                slide.style.backgroundImage = "url('" + image.path + "')"
                slide.style.backgroundSize = 'cover'

                slides.appendChild slide # Add slide into wrapper

            active_image = 0

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

</script>
