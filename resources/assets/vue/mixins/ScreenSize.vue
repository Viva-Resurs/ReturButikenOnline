<script lang="coffee">
    module.exports =
        name: 'ScreenSize'
        data: ->
            screensize: 'none'
        methods:
            WidthChange: (mq, type) ->
                if (mq.matches)                    
                    @screensize = type                    
                
        mounted: ->            
            self = this;
            mobileMq = window.matchMedia("(max-width: 767px)")            
            mobileMq.addListener( -> 
                self.WidthChange(this, "mobile")
                )

            tabletMq = window.matchMedia("(min-width: 768px) and (max-width: 991px)")            
            tabletMq.addListener( -> 
                self.WidthChange(this, "tablet")
                )
                
            desktopMq = window.matchMedia("(min-width: 992px)")            
            desktopMq.addListener( -> 
                self.WidthChange(this, "desktop")
                )           
</script>
