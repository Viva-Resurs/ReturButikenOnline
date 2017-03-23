<script lang="coffee">
    module.exports =
        name: 'ScreenSize'
        data: ->
            screentype: 'none'
        methods:
            WidthChange: (mq, type) ->
                if mq.matches                    
                    @screentype = type                    
                
            InitAndAddListener: (query, type) ->
                self = this;
                mq = window.matchMedia query
                if mq.matches
                    @screentype = type
                mq.addListener -> 
                    self.WidthChange this, type
                    
        mounted: ->            
            @InitAndAddListener "(max-width: 767px)", "mobile"
            @InitAndAddListener "(min-width: 768px) and (max-width: 991px)", "tablet"
            @InitAndAddListener "(min-width: 992px)", "desktop"
</script>
