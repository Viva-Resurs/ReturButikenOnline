<script lang="coffee">
    module.exports =
        inserted: (el) ->
            if el.id == "interval_start"
                $(el).calendar
                    debug: true
                    ampm: false
                    inline: true
                    endCalendar: $('#interval_end')
            if el.id == "interval_end"
                $(el).calendar
                    ampm: false
                    inline: true
                    startCalendar: $('#interval_start')
</script>
