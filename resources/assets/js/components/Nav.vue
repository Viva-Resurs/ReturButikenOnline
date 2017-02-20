<template lang="pug">
    div
        div.ui.inverted.menu.tablet.mobile.only.grid.attached.borderless
            a.item.left.floated
                router-link( to="/" exact ) {{ $root.settings.title }}
            a.item.right.floated( v-if="user" @click="menuToggle" )
                | Meny &nbsp;
                i.icon.content
                | &nbsp;&nbsp;
            router-link.item.right.floated( v-if="!user" to="/auth/login" exact )
                | Logga in &nbsp;
                i.icon.sign.in
                | &nbsp;&nbsp;
        div.ui.right.vertical.inverted.sidebar.labeled.menu
            div( v-if="user" @click="menuToggle()" )
                div.item
                    div.ui.container.center.aligned
                        h3.ui.inverted.header {{ $root.settings.title }}
                div.item( v-if="$root.isAdmin()" )
                    i.icon.building
                    div.header Avdelningar
                    div.menu
                        router-link.item( to="/sections" exact ) Lista
                div.item( v-if="$root.isAdmin(2)" )
                    i.icon.users
                    div.header Användare
                    div.menu
                        router-link.item( to="/users" exact ) Lista
                div.item
                    i.icon.cubes
                    div.header Artiklar
                    div.menu
                        router-link.item( to="/articles" exact ) Lista
                        router-link.item( to="/articles/create" exact ) Lägg till vara
                div.item( v-if="$root.isAdmin()" )
                    i.icon.tags
                    div.header Kategorier
                    div.menu
                        router-link.item( to="/categories" exact ) Lista
                div.item
                    div.ui.label.grey {{ user.name }}
                    div.header
                        | Inloggad som
                    div.vertical.menu
                        router-link.item( to="/users/profile" exact ) Profil
                            i.icon.user
                        a.item( @click="$root.exitUser()" ) Logga ut
                            i.icon.sign.out
        div.ui.grid.fluid.inverted.menu.computer.only.attached
            div.ui.container
                router-link.item( to="/" exact ) {{ $root.settings.title }}
                div.ui.right.inverted.menu
                    template( v-if="!user" )
                        router-link.item( to="/auth/login" exact ) Login
                        router-link.item( to="/help" exact ) Hjälp
                    template( v-if="user" )
                        router-link.item( to="/sections" exact v-if="$root.isAdmin()" )
                            i.icon.building
                            |  Avdelningar
                        router-link.item( to="/users" exact v-if="$root.isAdmin(2)" )
                            i.icon.users
                            |  Användare
                        router-link.item( to="/categories" v-if="$root.isAdmin()" exact )
                            i.icon.tags
                            |  Kategorier
                        router-link.item( to="/articles" exact )
                            i.icon.cubes
                            |  Artiklar
                        router-link.item( to="/articles/create" exact )
                            i.icon.write
                            |  Lägg till vara
                        div.ui.dropdown.item( v-dropdown="" ) {{ user.name }}
                            i.dropdown.icon
                            div.menu
                                router-link.item( to="/users/profile" exact )
                                    i.icon.user
                                    |  Profil
                                a.item( @click="$root.exitUser()" )
                                    i.icon.sign.out
                                    |  Logga ut
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
