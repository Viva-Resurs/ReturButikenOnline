<template lang="pug">
    div.ui.grid.equal.width
        div.column.mobile.only
            div.fitted.item
                div.ui.buttons( ":class"="search!=''?results==0?'red':'blue':''" )
                    div.ui.button.large.item.icon.top.left.pointing.dropdown#search( v-dropdown="" )
                        i.search.icon.icon-style
                        div.menu
                            div.header {{ translate('tool.search') }}
                            div.ui.icon.input(style="opacity: 1")
                                i.search.icon.icon-style
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
            div.fitted.item
                div.ui.buttons( ":class"="search!=''?results==0?'red':'blue':''" )
                    div.ui.button.large.item.icon.top.left.pointing.dropdown#search( v-dropdown="" style="opacity: 1" @click="focusMethod")
                        i.search.icon.icon-style
                        div.menu
                            div.header {{ translate('tool.search') }}
                            div.ui.icon.input(style="opacity: 1")
                                i.search.icon.icon-style
                                input(
                                    id="inputSearch"
                                    ":value"="search"
                                    ":placeholder"="translate('placeholder.search')"
                                    @input="change_search($event.target.value)")
                            div.item
                                div.ui.container.fluid(
                                    v-if="search!=''"
                                    @click="change_search('')")
                                    i.delete.icon
                                    | {{ translate('tool.clear_search') }}
        div.column.computer.only
            div.ui.icon.input(style="opacity: 1")
                i.large.search.icon.icon-style
                input(
                    style="margin-right: 10px; padding-top: 11.5px; padding-bottom: 11.5px; box-shadow: 0 1px 2px 0 rgba(34,36,38,.15); border: 1px solid rgba(34,36,38,.15)"
                    ":value"="search"
                    ":placeholder"="translate('placeholder.search')"
                    @input="change_search($event.target.value)" )
</template>

<script lang="coffee">
    module.exports =
        name: 'Search'
        props: ['search', 'results']
        methods:
            change_search: (value) ->
                bus.$emit 'search_changed', value

            focusMethod:() ->
                document.getElementById('inputSearch').focus()
                console.log "hello"

</script>
