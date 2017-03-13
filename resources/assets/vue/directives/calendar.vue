<script lang="coffee">
    module.exports =
        inserted: (el) ->
            if el.id == "interval_start"
                $(el).calendar
                    debug: !process.env.NODE_ENV
                    ampm: false
                    inline: true
                    endCalendar: $ '#interval_end'
                    formatter:
                        date: (date) ->
                            return moment(date).format 'YYYY-MM-DD'
                        time: (date, settings, forCalendar) ->
                            return moment(date).format 'HH:mm'
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
</script>
