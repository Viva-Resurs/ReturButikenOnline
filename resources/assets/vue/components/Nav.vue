<template lang="pug">
    div
        div.ui.inverted.menu.mobile.only.grid.attached.borderless
            div.ui.container#nav_mobile_container
                a.item.left.floated
                    router-link( to="/ui/" exact ) {{ $root.settings.title }}
                
                a.item.fitted.left( v-if="screenType == 'mobile' && !user" href="/" exact)
                    i.icon.checkmark
                    |  {{ translate('ui.published') }}
                
                a.item.right.floated( v-if="user" @click="menuToggle" )
                    | {{ translate('nav.menu') }} &nbsp;
                    i.icon.content#nav_mobile_menu_icon
                    | &nbsp;&nbsp;
                router-link.item.right.floated( v-if="!user" to="/ui/auth/login" exact )
                    | {{ translate('user.login') }} &nbsp;
                    i.icon.sign.in#nav_mobile_menu_icon_signin
                    | &nbsp;&nbsp;

        div.ui.inverted.menu.tablet.only.grid.attached.borderless
            div.ui.container#nav_tablet_container
                a.item.left.fitted.floated#nav_tablet_menu_left
                    router-link( to="/ui/" exact ) {{ $root.settings.title }}
                
                a.item.left.fitted.floated( href="/" exact)
                    i.icon.checkmark
                    |  {{ translate('ui.published') }}

                a.item.right.floated( v-if="user" @click="menuToggle" )
                    | {{ translate('nav.menu') }} &nbsp;
                    i.icon.content#nav_tablet_menu_icon
                    | &nbsp;&nbsp;
                router-link.item.right.floated( v-if="!user" to="/ui/auth/login" exact )
                    | {{ translate('user.login') }} &nbsp;
                    i.icon.sign.in#nav_tablet_menu_icon_signin
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

                a.item( v-if="screenType == 'mobile'" href="/" exact)
                    i.icon.checkmark
                    |  {{ translate('ui.published') }}

        div.ui.grid.fluid.inverted.menu.computer.only.attached
            div.ui.container.no-padding#nav_computer_container
                router-link.item( to="/ui/" exact ) {{ $root.settings.title }}
                a.item( href="/" exact)
                        i.icon.checkmark
                        |  {{ translate('ui.published') }}
                    
                div.ui.right.inverted.menu#nav_computer_menu
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
            ###*
            #   Navigates to the logged in users profile page.
            ###
            goToProfile: () ->
                @$router.push path: '/ui/users/'+@$root.user.id

            ###*
            #   Asks the currently logged in user to logout. 
            ###
            attemptLogout: () ->
                bus.$emit 'show_message',
                    title: @$root.translate('nav.logout_header')
                    message: @$root.translate('nav.logout_msg')
                    type:'confirm'
                    cb: => this.$root.exitUser()

            ###*
            #   Toggles the sidebar. 
            ###
            menuToggle: () ->
                $('.ui.sidebar').sidebar('toggle')
            
            ###*
            #   Navigate to to a link and close the menu.
            #   @param {target} link to navigate to
            ###
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

<style>
    #nav_mobile_container, #nav_tablet_container {
        width: 100% !important; 
        padding: 0px !important; 
        margin: 0px !important;
    }

    #nav_mobile_menu_icon, #nav_tablet_menu_icon, 
    #nav_mobile_menu_icon_signin, #nav_tablet_menu_icon_signin {
        margin-right: -5px;
    }
    
    #nav_tablet_menu_left {
        margin-right: 0px !important;
    }

    #nav_computer_container {
        width: 880px;
    }

    #nav_computer_menu {
        border-right: 1px solid rgba(34,36,38,.1);
    }
</style>