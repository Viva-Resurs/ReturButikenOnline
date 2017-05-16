<template lang="pug">
    div
        div.ui.top.attached.menu
            div.right.menu.fitted.item
                paginate(
                    ":offset"="offset"
                    ":total"="countItems"
                    ":show-pagination"="true" )
                div.right.menu
                    div.right.menu
                        div.ui.buttons
                            add.right.item.large.icon( from="tokens" )
        div.ui.padded.grid
            div.row( v-if="items.length == 0" )
                div.ui.column.warning.message
                    p {{ (search!='') ? translate('no_results') : translate('empty') }}
            div.row( v-if="items.length > 0" )
                table.ui.very.basic.table.very.compact.unstackable
                    tbody( v-item="$route.hash.substr(1)" )
                        tr(
                            v-for="(item, index) in items"
                            ":id"="item.id" )
                            td.slim
                                h4.ui.sub.header {{ translate('token') }}
                                p {{ item.token }}
                            td.collapsing.bottom.aligned
                                div.ui.icon.buttons
                                    component( v-for="tool in toolsRow" ":is"="tool" ":item"="item" ":from"="from" )
        div.row( v-if="countItems > 0 && search!=''" )
            button.ui.button.searchresults_expander(
                v-if="limitOffBtn"
                @click="limitOff = true" )
                | {{ translate('show_all_results') }}
</template>

<script lang="coffee">
    module.exports =
        name: 'TokenMobileList'
        props: [
            'items'
            'toolsRow'
        ]
        components:
            Paginate: require '../tools/Paginate.vue'
            Add: require '../tools/Add.vue'
</script>
