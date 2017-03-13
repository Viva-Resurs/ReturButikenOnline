<script lang="coffee">
    module.exports =
        inserted: (el) ->
            Vue.nextTick -> el.focus()
</script>
