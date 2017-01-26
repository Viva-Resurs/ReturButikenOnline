<script lang="coffee">
    module.exports =
        name: 'Debug'
        computed:
            DEBUG: -> Vue.config.devtools
</script>
