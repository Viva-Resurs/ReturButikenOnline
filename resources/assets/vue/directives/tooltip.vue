<script lang="coffee">
    module.exports =
        inserted: (el) ->
            mq = window.matchMedia("(min-width: 768px)")
            if mq.matches
                $(el).popup(
                    variation: "inverted"
                )

</script>
