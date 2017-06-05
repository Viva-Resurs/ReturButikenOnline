<template lang="pug">
        div.ui.icon.button.hover-default(
            style="background-color: #FFF; border-top: 1px solid rgb(212,212,213); border-bottom: 1px solid rgb(212,212,213); border-left: 1px solid rgb(212,212,213)"
            v-tooltip = ""
            ":data-html" = `
                (item.is_active) ? translate('tool.active') : translate('tool.inactive')
            `
            @click    = "update(item)"
            ":class" = `
                (item.is_active) ? 'active-interval':''
            `
        )
            i.ui.icon.check.circle.icon-style(v-if="item.is_active == true")
            i.ui.icon.minus.circle.icon-style(v-if="item.is_active == false")
</template>

<script lang="coffee">
    module.exports =
        name: 'Active'
        props: [ 'item', 'from' ]
        methods:
            update: (item) ->
                if item.is_active == true ##om artikel är active ska man enkelt deaktivera den
                    item.is_active = false
                    bus.$emit @from + '_item_changed', item
                if item.is_active == false ##om artikel är inactive måste man sätta giltigt publishinterval
                    item.is_active = true
                    bus.$emit @from + '_item_changed', item
</script>
