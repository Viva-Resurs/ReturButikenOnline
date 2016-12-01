<template lang="pug">
    div
        div.ui.grid.menu.tablet.mobile.only.attached.two.item.unstackable
            div.left.aligned.left.floated.item
                a.ui.button.launch.icon( @click="menuToggle" )
                    i.content.icon
            div.right.aligned.right.floated.item
                div.ui.compact.dropdown( v-dropdown="" ) {{ user.name }}
                    i.dropdown.icon
                    div.menu
                        div.item Profil
                        a.item( @click="$root.exitUser()" ) Logout

        div.ui.grid.fluid.menu.computer.only.attached
            router-link.item( to="/" exact ) {{ $root.settings.title }}

            div.ui.right.secondary.menu

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

        div.ui.left.vertical.inverted.sidebar.labeled.icon.menu
            router-link.item( to="/articles/create" exact ) Publicera
            router-link.item( to="/articles" exact ) Arkiv
            router-link.item( to="/categories" exact ) Kategorier
            router-link.item( to="/users" exact ) Användare
</template>

<script lang="coffee">
    module.exports = {
        name: 'Nav'
        data: () ->
            user: false
        methods:
            menuToggle: () ->
                $('.ui.sidebar').sidebar('toggle')
        created: () ->
            bus.$on('user_changed', (new_user) => this.user = new_user)
        mounted: () ->
            p = $('.ui.sidebar').detach()
            p.insertBefore('.pusher')
    }
</script>
