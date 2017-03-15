<template lang="pug">
    div.ui.grid.equal.width
        div.column.mobile.only
            div.ui.button.icon.item.dropdown#search( v-dropdown="" )
                i.search.icon
                div.menu
                    div.header {{ translate('tool.search') }}
                    div.ui.icon.input
                        i.search.icon
                        input(
                            ":value"="search"
                            ":placeholder"="translate('placeholder.search')"
                            @input="change_search($event.target.value)" )
                    div.item
                        div.ui.container.fluid(
                            v-if="search!=''"
                            @click="change_search('')" )
                            i.delete.icon
                            | {{ translate('tool.clear_search') }}
        div.column.tablet.only
            div.ui.button.icon.item.dropdown#search( v-dropdown="" )
                i.search.icon
                div.menu
                    div.header {{ translate('tool.search') }}
                    div.ui.icon.input
                        i.search.icon
                        input(
                            ":value"="search"
                            ":placeholder"="translate('placeholder.search')"
                            @input="change_search($event.target.value)" )
                    div.item
                        div.ui.container.fluid(
                            v-if="search!=''"
                            @click="change_search('')" )
                            i.delete.icon
                            | {{ translate('tool.clear_search') }}
        div.column.computer.only
            div.ui.icon.input
                i.search.icon
                input(
                    ":value"="search"
                    ":placeholder"="translate('placeholder.search')"
                    @input="change_search($event.target.value)" )
</template>

<script lang="coffee">
    module.exports =
        name: 'Search'
        props: ['search']
        methods:
            change_search: (value) ->
                bus.$emit 'search_changed', value
</script>
