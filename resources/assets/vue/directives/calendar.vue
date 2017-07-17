<script lang="coffee">
    module.exports =
        inserted: (el, binding) ->
            # Initializes start calendar.
            if el.id == "interval_start"
                $(el).calendar
                    ampm: false
                    inline: true
                    endCalendar: $ '#interval_end'
                    formatter:
                        date: (date) ->
                            return moment(date).format 'YYYY-MM-DD'
                        time: (date, settings, forCalendar) ->
                            return moment(date).format 'HH:mm'
                    onChange: binding.value
                    onShow:  $('.modal').modal('refresh')
            
            # Initializes end calendar.
            if el.id == "interval_end"
                $(el).calendar
                    ampm: false
                    inline: true
                    startCalendar: $ '#interval_start'
                    formatter:
                        date: (date) ->
                            return moment(date).format 'YYYY-MM-DD'
                        time: (date, settings, forCalendar) ->
                            return moment(date).format 'HH:mm'
                    onChange: binding.value
                    onShow:  $('.modal').modal('refresh')
        
        componentUpdated: (el, binding) ->
            $('.modal').modal('refresh')
   
</script>
