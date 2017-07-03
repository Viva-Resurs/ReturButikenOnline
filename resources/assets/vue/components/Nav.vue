<template lang="pug">
    div
        div.ui.inverted.menu.mobile.only.grid.attached.borderless
            div.ui.container(style="width: 100% !important; padding: 0px !important; margin: 0px !important")
                a.item.left.floated
                    router-link( to="/ui/" exact ) {{ $root.settings.title }}
                a.item.right.floated( v-if="user" @click="menuToggle" )
                    | {{ translate('nav.menu') }} &nbsp;
                    i.icon.content(style="margin-right: -5px")
                    | &nbsp;&nbsp;
                router-link.item.right.floated( v-if="!user" to="/ui/auth/login" exact )
                    | {{ translate('user.login') }} &nbsp;
                    i.icon.sign.in(style="margin-right: -5px")
                    | &nbsp;&nbsp;

        div.ui.inverted.menu.tablet.only.grid.attached.borderless
            div.ui.container(style="width: 100% !important; padding: 0px !important; margin: 0px !important")
                a.item.left.floated
                    router-link( to="/ui/" exact ) {{ $root.settings.title }}
                a.item.right.floated( v-if="user" @click="menuToggle" )
                    | {{ translate('nav.menu') }} &nbsp;
                    i.icon.content(style="margin-right: -5px")
                    | &nbsp;&nbsp;
                router-link.item.right.floated( v-if="!user" to="/ui/auth/login" exact )
                    | {{ translate('user.login') }} &nbsp;
                    i.icon.sign.in(style="margin-right: -5px")
                    | &nbsp;&nbsp;

        div.ui.right.vertical.inverted.sidebar.labeled.menu
            div( v-if="user" @click="menuToggle()" )
                div.item
                    div.ui.container.center.aligned
                        h3.ui.inverted.header {{ $root.settings.title }}
                router-link.item( to="/ui/sections" exact v-if="$root.isAdmin()" )
                    i.icon.building
                    |  {{ translate('sections') }}
                router-link.item( to="/ui/users" exact v-if="$root.isAdmin(2)" )
                    i.icon.users
                    |  {{ translate('users') }}
                router-link.item( to="/ui/articles" exact )
                    i.icon.cubes
                    |  {{ translate('article.header') }}
                router-link.item( to="/ui/categories" exact v-if="$root.isAdmin()" )
                    i.icon.tags
                    |  {{ translate('categories') }}

                a.item( @click="goToProfile()" exact)
                                i.icon.user
                                |  {{ translate('user.profile') }}
                a.item( href="docs" )
                    i.icon.help.circle
                    |  {{ translate('nav.help') }}

                a.item( @click="attemptLogout()" ) {{ translate('user.logout') }}
                    i.icon.sign.out

                router-link.item( to="/" exact)
                    i.icon.checkmark
                    |  {{ translate('ui.published') }}

        div.ui.grid.fluid.inverted.menu.computer.only.attached
            div.ui.container.no-padding(style="width: 880px")
                router-link.item( to="/ui/" exact ) {{ $root.settings.title }}
                div.ui.right.inverted.menu(style="border-right: 1px solid rgba(34,36,38,.1);")
                    a.item( href="/" exact)
                        i.icon.checkmark
                        |  {{ translate('ui.published') }}
                    template( v-if="!user" )
                        router-link.item( to="/ui/auth/login" exact ) {{ translate('user.login') }}
                    template( v-if="user" )                        
                        router-link.item( to="/ui/sections" exact v-if="$root.isAdmin()" )
                            i.icon.building
                            |  {{ translate('sections') }}
                        router-link.item( to="/ui/users" exact v-if="$root.isAdmin(2)" )
                            i.icon.users
                            |  {{ translate('users') }}
                        router-link.item( to="/ui/categories" v-if="$root.isAdmin()" exact )
                            i.icon.tags
                            |  {{ translate('categories') }}
                        router-link.item( to="/ui/articles" exact )
                            i.icon.cubes
                            |  {{ translate('article.header') }}
                        div.ui.dropdown.item( v-dropdown="" ) {{ user.name }}
                            i.dropdown.icon
                            div.menu
                                a.item( @click="goToProfile()" exact)
                                    i.icon.user
                                    |  {{ translate('user.profile') }}
                                a.item( href="docs" )
                                    i.icon.help.circle
                                    |  {{ translate('nav.help') }}
                                a.item( @click="attemptLogout()" )
                                    i.icon.sign.out
                                    |  {{ translate('user.logout') }}
</template>

<script lang="coffee">
    module.exports = {
        name: 'Nav'
        data: () ->
            user: false
        methods:
            goToProfile: () ->
                @$router.push path: '/ui/users/'+@$root.user.id

            attemptLogout: () ->
                bus.$emit 'show_message',
                    title: @$root.translate('nav.logout_header')
                    message: @$root.translate('nav.logout_msg')
                    type:'confirm'
                    cb: => this.$root.exitUser()


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
