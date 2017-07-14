<template lang="pug">
    div.ui.grid.fluid.inverted.menu.attached
        div.ui.container.no-padding#navshop_menu_container
            a.section.item( href="http://www.returbutiken.com" exact ) Returbutiken
            i.right.angle.icon.divider#navshop_right_angle_divider
            a.section.active.item( href="/" exact ) {{ translate('shop.bidding') }}
            a.section.right.item( href="/ui" exact ) {{ translate('ui.staff') }}
            div.navbar-nav
                div.divider-thin                          
</template>

<script lang="coffee">
    module.exports = {
        name: 'NavShop'
    }
</script>

<style>
    #navshop_menu_container {
        width: 880px;
    }

    #navshop_right_angle_divider {
        margin-top: 10px;
    }
</style>
