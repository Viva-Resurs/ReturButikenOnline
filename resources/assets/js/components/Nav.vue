<template lang="pug">
    div.ui.stackable.secondary.pointing.menu

        router-link.item( to="/" exact ) {{ $root.settings.title }}

        div.ui.right.secondary.stackable.menu

            template( v-if="!user" )
                router-link.item( to="/auth/login" exact ) Login
                router-link.item( to="/auth/register" exact ) Register
                router-link.item( to="/help" exact ) Hjälp

            template( v-if="user" )
                router-link.item( to="/articles/create" exact ) Publicera
                router-link.item( to="/articles" exact ) Arkiv
                router-link.item( to="/categories" exact ) Kategorier
                router-link.item( to="/users" exact ) Användare
                div.ui.dropdown.item( v-dropdown="" ) {{ user.name }}
                    i.dropdown.icon
                    div.menu
                        div.item Profil
                        a.item( @click="$root.exitUser()" ) Logout
</template>

<script lang="coffee">
    module.exports = {
        name: 'Nav'
        data: () ->
            user: false
        created: () ->
            bus.$on('user_changed', (new_user) => this.user = new_user)
    }
</script>
