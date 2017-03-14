<template lang="pug">
    div.item
        div.ui.grid.mobile.tablet.only
            div.ui.button.icon.item.dropdown#search( v-dropdown="" )
                i.search.icon
                div.menu
                    div.header Search
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
                            | Clear Search
        div.ui.grid.computer.only
            div.row
                div.column
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
