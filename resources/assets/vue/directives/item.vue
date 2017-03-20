<script lang="coffee">
    module.exports =
        inserted: (el, binding) ->
            if binding.value
                $('#'+binding.value).transition(
                    animation : 'pulse'
                    onStart : ->
                        @classList.add 'positive'
                    onComplete : ->
                        @classList.remove 'positive'
                )
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
                $('#'+arg).each ->
                    $(this).transition
                        animation : 'fade'
                        onStart : ->
                            @classList.add 'negative'
                        onComplete : cb
</script>
