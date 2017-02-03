<template lang="pug">
    div.ui.padded.container.segment
        div.ui.segment.top.attached
            div.ui.grid.two.columns.stackable

                div.column.ten.wide
                    h2.header Welcome
                div.column.right.floated.six.wide
                    user-card(v-if="user" ":user"="user" "detailed"="true" picture="true")
        div.ui.segment.attached
            | article info here
</template>

<script lang="coffee">
    module.exports =
        name: 'Welcome'
        components:
            UserCard: require './../components/UserCard.vue'
        data: ->
            user: false
        #Get user from emit
        #Get categories from emit
        #Get roles from emit
        created: () ->
           bus.$on('user_changed', (new_user) => this.user = new_user)

</script>
