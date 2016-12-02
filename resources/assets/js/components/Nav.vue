<template lang="pug">
    div
        div.ui.inverted.pointing.menu.tablet.mobile.only.grid.attached
            div.toc.item
                a.ui.button.launch.black.icon( @click="menuToggle" )
                    i.content.icon
                    | {{ ' ' + $root.settings.title }}

            template( v-if="!user" )
                div.ui.right.secondary.menu
                    router-link.item( to="/auth/login" exact ) Login
                    router-link.item( to="/help" exact ) Hjälp

            template( v-if="user" )
                div.ui.compact.right.item.dropdown( v-dropdown="" ) {{ user.name }}
                    i.dropdown.icon
                    div.menu
                        div.item Profil
                        a.item( @click="$root.exitUser()" ) Logout

        div.ui.grid.fluid.menu.computer.only.attached
            router-link.item( to="/" exact ) {{ $root.settings.title }}

            div.ui.right.secondary.menu

                template( v-if="!user" )
                    router-link.item( to="/auth/login" exact ) Login
                    router-link.item( to="/help" exact ) Hjälp

                template( v-if="user" )
                    router-link.item( to="/sections" exact ) Avdelningar
                    router-link.item( to="/articles/create" exact ) Publicera
                    router-link.item( to="/articles" exact ) Arkiv
                    router-link.item( to="/categories" exact ) Kategorier
                    router-link.item( to="/users" exact ) Användare
                    div.ui.dropdown.item( v-dropdown="" ) {{ user.name }}
                        i.dropdown.icon
                        div.menu
                            div.item Profil
                            a.item( @click="$root.exitUser()" ) Logout

        div.ui.left.vertical.inverted.sidebar.labeled.menu
            div( @click="menuToggle()" )
                router-link.item( to="/" exact )
                    div.ui.container.center.aligned
                        h3.ui.inverted.header {{ $root.settings.title }}
                router-link.item( to="/sections" exact ) Avdelningar
                router-link.item( to="/articles" exact ) Arkiv
                router-link.item( to="/articles/create" exact ) Publicera
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
            navigate: (target) ->
                if (!target || target=='')
                    return this.menuToggle();
                this.$router.push({ path: target })
                this.menuToggle()
        created: () ->
            bus.$on('user_changed', (new_user) => this.user = new_user)
        mounted: () ->
            p = $('.ui.sidebar').detach()
            p.insertBefore('.pusher')
    }
</script>
