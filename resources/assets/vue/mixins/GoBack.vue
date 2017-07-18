<script lang="coffee">
    module.exports =
        name: 'GoBack'
        methods:
            ###*
            #   Navigates the user back to the previous page.
            ###
            goBack: ->
                window.history.back()
</script>
