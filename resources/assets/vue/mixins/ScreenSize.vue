<script lang="coffee">
    module.exports =
        name: 'ScreenSize'
        data: ->
            screentype: 'none'
        methods:
            widthChange: (mq, type) ->
                if mq.matches                    
                    @screentype = type                    
                
            initAndAddListener: (query, type) ->
                self = this;
                mq = window.matchMedia query
                if mq.matches
                    @screentype = type
                mq.addListener -> 
                    self.WidthChange this, type
                    
        mounted: ->            
            @initAndAddListener "(max-width: 767px)", "mobile"
            @initAndAddListener "(min-width: 768px) and (max-width: 991px)", "tablet"
            @initAndAddListener "(min-width: 992px)", "desktop"
</script>
