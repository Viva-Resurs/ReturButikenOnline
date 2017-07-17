<script lang="coffee">
    module.exports =
        inserted: (el) ->
            # Makes popup inverted (black) in tablet layout and above.
            mq = window.matchMedia("(min-width: 768px)")
            if mq.matches
                $(el).popup(
                    variation: "inverted"
                )

</script>
