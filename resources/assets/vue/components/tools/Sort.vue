<template lang="pug">
    div.ui.grid.equal.width
        div.column.mobile.only
            div.fitted.item
                div.ui.buttons
                    div.ui.button.large.item.icon.top.right.pointing.dropdown#order( v-dropdown="" )
                        i.sort.icon.icon-style
                        div.menu
                            div.header {{ translate('tool.sort') }}
                            div.item(
                                v-for="column in columns"
                                ":class"="(order==column)?'active':''"
                                @click="change_order(column)"
                                )
                                | {{ translate(column) }} &nbsp;
                                i.icon.sort(
                                    v-if="order==column"
                                    ":class" = "(desc==1)?'ascending':'descending'"
                                )
        div.column.tablet.only
            div.ui.buttons
                div.ui.button.large.right.item.icon.top.right.pointing.dropdown#order( v-dropdown="" )
                    div.default.value(
                        v-for="column in columns"
                        v-if="order==column"
                        style="line-height: 12px;" )
                        | {{ translate(column) }} &nbsp;
                    i.icon.sort.right.floated(
                        ":class" = "(desc==1)?'ascending':'descending'"
                    )
                    div.menu
                        div.header {{ translate('tool.sort') }}
                        div.item(
                            v-for="column in columns"
                            ":class"="(order==column)?'active':''"
                            @click="change_order(column)"
                            )
                            | {{ translate(column) }} &nbsp;
                            i.icon.sort(
                                v-if="order==column" )
        //div.column.tablet.only
            div.fitted.item
                div.ui.buttons
                    div.ui.button.large.item.icon.top.right.pointing.dropdown#order( v-dropdown="" )
                        div.default.value(
                            v-for="column in columns"
                            v-if="order==column"
                            style="line-height: 12px;" )
                            | {{ translate(column) }} &nbsp;
                        i.icon.sort.right.floated(
                            ":class" = "(desc==1)?'ascending':'descending'"
                        )
                        div.menu
                            div.header {{ translate('tool.sort') }}
                            div.item(
                                v-for="column in columns"
                                ":class"="(order==column)?'active':''"
                                @click="change_order(column)"
                                )
                                | {{ translate(column) }} &nbsp;
                                i.icon.sort(
                                    v-if="order==column"
                                    ":class" = "(desc==1)?'ascending':'descending'"
                                )
        div.column.computer.only
            div.ui.basic.button.dropdown#order( v-dropdown="" )
                div.default.value(
                    v-for="column in columns"
                    v-if="order==column" ) {{ translate(column) }} &nbsp;
                    i.icon.sort.right.floated(
                        ":class" = "(desc==1)?'ascending':'descending'"
                    )
                div.menu
                    div.header {{ translate('tool.sort') }}
                    div.item(
                        v-for="column in columns"
                        ":class"="(order==column)?'active':''"
                        @click="change_order(column)"
                        )
                        | {{ translate(column) }} &nbsp;
                        i.icon.sort(
                            v-if="order==column"
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
