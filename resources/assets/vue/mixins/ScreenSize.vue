<script lang="coffee">
    module.exports =
        name: 'ScreenSize'
        data: ->
            screenType: 'none'
        methods:
            ###*
            #   Checks if media query matches a specific screen type and updates the
            #   screenType value.
            #   @param {mq} media query to use
            #   @param {type} tablet, mobile or desktop
            ###
            widthChange: (mq, type) ->
                if mq.matches                    
                    @screenType = type                    
                
            ###*
            #   Initializes a listener for matching a specific media query. 
            #   @param {query} query to match
            #   @param {type} tablet, mobile or desktop
            ###
            initAndAddListener: (query, type) ->
                self = this;
                mq = window.matchMedia query
                if mq.matches
                    @screenType = type
                mq.addListener -> 
                    self.widthChange this, type
                    
        mounted: ->            
            @initAndAddListener "(max-width: 767px)", "mobile"
            @initAndAddListener "(min-width: 768px) and (max-width: 991px)", "tablet"
            @initAndAddListener "(min-width: 992px)", "desktop"
</script>
