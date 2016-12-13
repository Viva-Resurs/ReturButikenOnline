<template lang="pug">
    div
        div.ui.inverted.menu.tablet.mobile.only.grid.attached.three.item
            div.item
            router-link.item( to="/" exact ) {{ $root.settings.title }}
            a.item( v-if="user" @click="menuToggle" )
                div.column.right.floated
                    div.ui.container
                        | Meny &nbsp;
                        i.icon.content
            router-link.item( v-if="!user" to="/auth/login" exact )
                div.column.right.floated
                    div.ui.container
                        | Logga in &nbsp;
                        i.icon.sign.in

        div.ui.right.vertical.inverted.sidebar.labeled.menu
            div( v-if="user" @click="menuToggle()" )
                div.item
                    div.ui.container.center.aligned
                        h3.ui.inverted.header {{ $root.settings.title }}
                div.item( v-if="$root.isAdmin()" )
                    div.header Avdelningar
                    div.menu
                        router-link.item( to="/sections" exact ) Lista
                            i.icon.browser
                div.item( v-if="$root.isAdmin(2)" )
                    div.header Användare
                    div.menu
                        router-link.item( to="/users" exact ) Lista
                            i.icon.browser
                div.item
                    div.header Artiklar
                    div.menu
                        router-link.item( to="/articles" exact ) Lista
                            i.icon.browser
                        router-link.item( to="/articles/create" exact ) Lägg till vara
                            i.icon.write
                div.item( v-if="$root.isAdmin()" )
                    div.header Kategorier
                    div.menu
                        router-link.item( to="/categories" exact ) Lista
                            i.icon.browser
                div.item
                    div.ui.label.grey {{ user.name }}
                    div.header
                        | Inloggad som

                    div.vertical.menu
                        router-link.item( to="/auth/profile" exact ) Profil
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
                        router-link.item( to="/articles/create" exact )
                            i.icon.write
                            |  Lägg till vara
                        router-link.item( to="/articles" exact )
                            i.icon.browser
                            |  Arkiv
                        router-link.item( to="/categories" v-if="$root.isAdmin()" exact )
                            i.icon.tags
                            |  Kategorier

                        div.ui.dropdown.item( v-dropdown="" ) {{ user.name }}
                            i.dropdown.icon
                            div.menu
                                div.item
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
