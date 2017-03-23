<script lang="coffee">
    module.exports =
        name: 'ScreenSize'
        data: ->
            screensize: 'none'
            screensizes: 
                mobile: "(max-width: 767px)"
                tablet: "(min-width: 768px) and (max-width: 991px)"
                desktop: "(min-width: 992px)"
                    
        methods:
            WidthChange: (mq, type) ->
                if (mq.matches)                    
                    @screensize = type
                
        mounted: ->            
            self = this;
            mobileMq = window.matchMedia(@screensizes.mobile)            
            mobileMq.addListener( -> 
                self.WidthChange(this, "mobile")
                )

            tabletMq = window.matchMedia(@screensizes.tablet)            
            tabletMq.addListener( -> 
                self.WidthChange(this, "tablet")
                )
                
            desktopMq = window.matchMedia(@screensizes.desktop)            
            desktopMq.addListener( -> 
                self.WidthChange(this, "desktop")
                )           
</script>
