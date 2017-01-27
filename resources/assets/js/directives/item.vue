<script lang="coffee">
    module.exports =
        inserted: (el) ->
            $(el).on 'updated', (e, arg) ->
                if !arg
                    return
                $('#'+arg).transition(
                    animation : 'pulse'
                    onStart : ->
                        @classList.add 'positive'
                    onComplete : ->
                        @classList.remove 'positive'
                )
            $(el).on 'removed', (e, arg, cb) ->
                if !arg
                    return
                $('#'+arg).transition(
                    animation : 'fade'
                    onStart : ->
                        @classList.add 'negative'
                    onComplete : cb
                )
</script>
