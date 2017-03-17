<script lang="coffee">
    module.exports =
        inserted: (el, binding) ->
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
</script>
