<script lang="coffee">
    module.exports =
        inserted: (el) ->
            $(el).popup(
                variation: "inverted"
            )
</script>
