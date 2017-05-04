<template lang="pug">
    div.ui.grid.padded
        div.ui.vertical.fluid.compact.menu.computer.tablet.only.row#section_menu
            router-link.item.header( to="/ui/sections" )
                | {{ translate('section.header') }}
                i.icon.external.square(
                    v-tooltip = ""
                    ":data-html" = "translate('goto.sections')" )
            a.item(
                v-for="section in sections"
                ":class"="section.selected ? 'active':''"
                @click="selectSection(section)" ) {{ getLanguage() == 'sv' ? section.name_sv : section.name }}
                div.ui.small.label(
                    ":class"="section.contacts.length>0 ? 'black' : 'grey'" )
                    | {{ section.contacts.length }}
            i.item( v-if="sections.length==0" ) {{ translate('empty') }}
        div.ui.row.selection.dropdown.mobile.only#sections(
            v-dropdown=""
            ":class"="sections.length > 0 ? '' : 'disabled'" )
            input(type="hidden" name="section_selection" )
            i.dropdown.icon
            div.default.text {{ translate('sections') }}
            div.class.menu
                div.item(
                    v-for="section in sections"
                    @click="selectSection(section)"
                    ":data-value"="section.id" ) {{section.name}}
</template>

<script lang="coffee">
    module.exports =
        name: 'Section'
        props: [ 'sections' ]
        methods:
            selectSection: (section) ->
                bus.$emit 'section_changed', section
</script>
