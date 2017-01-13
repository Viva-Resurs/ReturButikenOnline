<script lang="coffee">
    module.exports =
        inserted: (el,binding) ->

            ###
                TODO: Snap to nearest image
                TODO: Update width/height on resize
                TODO: ScrollTo a specifyed image
                TODO: Prevent 'drag-and-drop' actions (or use it instead of mousemove?)
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

            # Insert slides
            $(el).append(slides)

            # Swipe/scroll in wrapper
            swipeTo = (offset) ->
                $(el).scrollLeft( $(el).scrollLeft()+offset )

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
                setPosition e.clientX, e.clientY
                active = true
            handleUp   = (e) ->
                active = false
            handleMove = (e) ->
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

</script>
