<script lang="coffee">
    module.exports =
        name: 'ScreenSize'
        data: ->
            screenType: 'none'
        methods:
            widthChange: (mq, type) ->
                if mq.matches                    
                    @screenType = type                    
                
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
