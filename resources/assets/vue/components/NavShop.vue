<template lang="pug">
    div.ui.grid.fluid.inverted.menu.attached
        div.ui.container.no-padding(style="width: 880px")
            a.section.item( href="/ui" exact ) Returbutiken
            i.right.angle.icon.divider(style="margin-top: 10px")
            a.section.active.item( href="/" exact ) {{ translate('shop.bidding') }}
                
          
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
