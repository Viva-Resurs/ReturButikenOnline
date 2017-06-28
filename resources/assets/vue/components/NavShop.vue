<template lang="pug">
    div
        div.ui.inverted.menu.tablet.mobile.only.grid.attached.borderless
            div.ui.container(style="width: 100% !important; padding: 0px !important; margin: 0px !important")
                a.item.left.floated
                    a.item( href="/" exact ) {{ $root.settings.title }}
         
        div.ui.right.vertical.inverted.sidebar.labeled.menu
            div( @click="menuToggle()" )
                div.item
                    div.ui.container.center.aligned
                        h3.ui.inverted.header {{ $root.settings.title }}

                a.item( href="docs" )
                    i.icon.help.circle
                    |  {{ translate('nav.help') }}
                
        div.ui.grid.fluid.inverted.menu.computer.only.attached
            div.ui.container.no-padding(style="width: 880px")
                a.item( href="/" exact ) {{ $root.settings.title }}
                
          
</template>

<script lang="coffee">
    module.exports = {
        name: 'NavShop'

        methods:
            menuToggle: () ->
                $('.ui.sidebar').sidebar('toggle')
            navigate: (target) ->
                if (!target || target=='')
                    return this.menuToggle();
                this.$router.push({ path: target })
                this.menuToggle()
  
        mounted: () ->
            p = $('.ui.sidebar').detach()
            p.insertBefore('.pusher')
    }
</script>
