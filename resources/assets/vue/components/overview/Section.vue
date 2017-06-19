<template lang="pug">
    div.ui.grid.padded.start-item
        div.ui.vertical.fluid.compact.menu.computer.tablet.only.row
            div.ui.grid.item.header
                div.twelve.wide.column
                    | {{ translate('section.header') }}
                div.ui.container.right.aligned.four.wide.column
                    router-link( to="/ui/sections" )
                        i.icon.external.icon-style(
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
        
        div.ui.fluid.row.mobile.only.no-padding
            div.ui.fluid.label(style="border-radius: 0px; margin-right: 0px") {{ translate('sections') }}
            div.ui.fluid.selection.label.dropdown#sections(
                style="border-radius: 0px;background-color: white; margin-left: 0px"           
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
