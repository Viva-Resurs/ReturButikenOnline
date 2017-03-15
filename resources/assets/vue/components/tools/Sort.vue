<template lang="pug">
    div.item
        div.ui.grid.mobile.tablet.only
            div.ui.button.icon.item.dropdown#order( v-dropdown="" )
                i.sort.icon
                div.menu
                    div.item(
                        v-for="column in columns"
                        ":class"="(order==column)?'active':''"
                        @click="change_order(column)"
                        )
                        | {{ translate(column) }} &nbsp;
                        i.icon.label.sort(
                            v-if="order==column.key"
                            ":class" = "(desc==1)?'ascending':'descending'"
                        )
        div.ui.grid.computer.only
            div.row
                div.column
                    div.ui.button.item.dropdown#order( v-dropdown="" )
                        div.default.value(
                            v-for="column in columns"
                            v-if="order==column" ) {{ translate(column) }} &nbsp;
                            i.icon.label.sort.right.floated(
                                ":class" = "(desc==1)?'ascending':'descending'"
                            )
                        div.menu
                            div.item(
                                v-for="column in columns"
                                ":class"="(order==column)?'active':''"
                                @click="change_order(column)"
                                )
                                | {{ translate(column) }} &nbsp;
                                i.icon.label.sort(
                                    v-if="order==column.key"
                                    ":class" = "(desc==1)?'ascending':'descending'"
                                )
</template>

<script lang="coffee">
    module.exports =
        name: 'Sort'
        props: ['order', 'desc', 'columns']
        methods:
            change_order: (value) ->
                bus.$emit 'order_changed', value
</script>
