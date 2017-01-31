<script lang="coffee">
    module.exports =
        inserted: (el,binding) ->            
            $(el).form(binding.value);
</script>
