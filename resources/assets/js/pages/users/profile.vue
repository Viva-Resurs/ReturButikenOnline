<template lang="pug">
    div.ui.attached
        loading
</template>

<script lang="coffee">
    module.exports =
        name: 'ProfileRedirect'
        methods:
            goToUpdate: ->
                @$router.push path: '/users/'+@$root.user.id
        created: ->
            # Check if User is ready or wait
            if @$root.user
                @goToUpdate()
            else
                bus.$on 'user_changed', @goToUpdate
        beforeDestroy: ->
            bus.$off 'user_changed', @goToUpdate
</script>
